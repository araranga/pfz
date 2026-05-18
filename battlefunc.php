<?php
include("battlefuncboss.php");

$EMBLEM_REGISTRY = require(__DIR__ . "/emblems.php");

// ---------------------------------------------------------------------------
// fire_emblem_hook — invoke a single hook on an emblem, push any returned log
// entry, and write mutated ctx values back to the local variables.
// ---------------------------------------------------------------------------
function fire_emblem_hook($hook, &$emblems, $slot, array &$ctx, array &$logs)
{
    global $EMBLEM_REGISTRY;
    $key = $emblems[$slot];
    if (empty($key) || empty($EMBLEM_REGISTRY[$key][$hook])) {
        return;
    }
    $entry = $EMBLEM_REGISTRY[$key][$hook]($ctx);
    if (!empty($entry)) {
        $logs[] = $entry;
    }
    $emblems[$slot] = $ctx['emblem'];
}

function generatebattle($id)
{
    global $EMBLEM_REGISTRY;

    $query = "SELECT * FROM tbl_battle WHERE id ='$id' AND winner IS NULL";
    $q     = mysql_query_md($query);
    $row   = mysql_fetch_md_assoc($q);
    if (empty($row)) {
        echo "Please use correct battle id";
        exit(1);
    }

    $poke1  = loadpokev2($row["p1poke"]);
    $poke2  = loadpokev2($row["p2poke"]);
    $skill1 = loadpokeskill($poke1["hash"]);
    $skill2 = loadpokeskill($poke2["hash"]);

    // emblem slugs — stored in array so fire_emblem_hook can mutate them by ref
    $emblems = [
        1 => getEmblem($poke1['emblem']),
        2 => getEmblem($poke2['emblem']),
    ];

    // level-gap handicap
    $p1level = $poke1['level'] - $poke2['level'];
    $p2level = $poke2['level'] - $poke1['level'];
    $p1gap   = 0;
    $p2gap   = 0;
    if ($p1level >= 0 && $p1level >= 8) { $p2gap = 1; }
    if ($p2level >= 0 && $p2level >= 8) { $p1gap = 1; }

    if ($p1gap) {
        $poke1["attack"]   = addmore($poke1["attack"],   1, 0.20);
        $poke1["accuracy"] = addmore($poke1["accuracy"], 1, 0.15);
        $poke1["speed"]    = addmore($poke1["speed"],    1, 0.15);
    }
    if ($p2gap) {
        $poke2["attack"]   = addmore($poke2["attack"],   1, 0.20);
        $poke2["accuracy"] = addmore($poke2["accuracy"], 1, 0.15);
        $poke2["speed"]    = addmore($poke2["speed"],    1, 0.15);
    }

    // pre-battle emblem stat boosts
    foreach ([1 => &$poke1, 2 => &$poke2] as $slot => &$poke) {
        $key = $emblems[$slot];
        if (!empty($key) && !empty($EMBLEM_REGISTRY[$key]['on_prebattle'])) {
            $EMBLEM_REGISTRY[$key]['on_prebattle']($poke);
        }
    }
    unset($poke);

    // is_god random stat boosts
    $stats_upv2 = ["speed", "critical", "accuracy", "attack", "defense"];
    foreach ([&$poke1, &$poke2] as &$poke) {
        if (!empty($poke['is_god'])) {
            foreach ($stats_upv2 as $s) {
                $poke[$s] = addmore($poke[$s], 1, (rand(15, 80) / 100));
            }
        }
    }
    unset($poke);

    // weapon bonuses
    $stats_up = ["hp", "speed", "critical", "accuracy", "attack", "defense"];
    $getweapon1 = mysql_fetch_md_assoc(mysql_query_md("SELECT * FROM tbl_items_users WHERE pokemon='{$poke1['id']}' LIMIT 1"));
    $getweapon2 = mysql_fetch_md_assoc(mysql_query_md("SELECT * FROM tbl_items_users WHERE pokemon='{$poke2['id']}' LIMIT 1"));
    foreach ([1 => [$getweapon1, &$poke1], 2 => [$getweapon2, &$poke2]] as [$weapon, &$poke]) {
        if (!empty($weapon['id'])) {
            foreach ($stats_up as $s) {
                if (!empty($weapon[$s])) { $poke[$s] += $weapon[$s]; }
            }
        }
    }
    unset($poke);

    $roundp1     = 0;
    $roundp2     = 0;
    $regen_count = [1 => 0, 2 => 0];
    $pokeclassdata = [];
    $winner      = 0;
    $tira        = 0;
    $logs        = [];
    $turn        = 2;

    $fullhp1 = $hp1 = $poke1["hp"] + 2000;
    $fullhp2 = $hp2 = $poke2["hp"] + 2000;

    $winnerpoke  = 0;
    $loserpoke   = 0;
    $enemy_freeze_1 = 0;
    $enemy_freeze_2 = 0;

    while ($winner != 1) {
        $tira++;

        if ($enemy_freeze_2 == 1) { $enemy_freeze_2 = 0; $roundp1++; $turn = 2; }
        if ($enemy_freeze_1 == 1) { $enemy_freeze_1 = 0; $roundp2++; $turn = 1; }

        // ------------------------------------------------------------------ //
        // Determine attacker / defender for this turn                         //
        // ------------------------------------------------------------------ //
        if ($turn == 1) {
            $roundp1++;
            $attacker  = &$poke1; $defender  = &$poke2;
            $hp_self   = &$hp1;   $hp_enemy  = &$hp2;
            $fullhp_self = $fullhp1; $fullhp_enemy = $fullhp2;
            $skills    = $skill1;
            $slot_self = 1; $slot_enemy = 2;
            $round     = $roundp1;
        } else {
            $roundp2++;
            $attacker  = &$poke2; $defender  = &$poke1;
            $hp_self   = &$hp2;   $hp_enemy  = &$hp1;
            $fullhp_self = $fullhp2; $fullhp_enemy = $fullhp1;
            $skills    = $skill2;
            $slot_self = 2; $slot_enemy = 1;
            $round     = $roundp2;
        }

        $tiraskill = $skills[array_rand($skills)];
        $tiraskill["power"] = rand(180, 270);

        $is_crit_chance = 100 + ($defender["defense"] * 0.25);
        $is_crit  = getluck($attacker["critical"], $is_crit_chance);

        $notes = [];

        if (empty($pokeclassdata[$tiraskill["typebattle"]])) {
            $pokeclassdata[$tiraskill["typebattle"]] = loadpoketype($tiraskill["typebattle"]);
        }

        $initial_msg = $notes[] = "{$attacker['pokename']} Uses {$tiraskill['title']}({$tiraskill['typebattle']}) to {$defender['pokename']}({$defender['pokeclass']})";
        $initialdmg  = $curdamage = $tiraskill["power"] + $attacker["attack"];

        // type effectiveness
        $adddmg = "";
        foreach (explode("|", $defender["pokeclass"]) as $v) {
            $typedata = $pokeclassdata[$tiraskill["typebattle"]];
            if (strpos($typedata["double_damage_to"], $v) !== false) {
                $adddmg = "Its super effective!";
                $curdamage += $curdamage * 0.75;
            }
            if (strpos($typedata["half_damage_to"], $v) !== false) {
                $adddmg = "Its not effective!";
                $curdamage -= $curdamage * 0.35;
            }
            if (strpos($typedata["no_damage_to"], $v) !== false) {
                $adddmg = "It cause super low damage!";
                $curdamage -= $curdamage * 0.55;
            }
        }

        $is_dodge  = getluck($defender["speed"], $attacker["accuracy"] + $tiraskill["accuracy"] + 45);
        $curdamage = $curdamage - ($defender["defense"] / 2);
        if ($curdamage < 0) { $curdamage = 1; }
        $curdamage = ceil($curdamage);

        if ($is_dodge) {
            $notes[]   = "Enemy Dodge! Takes no damage!";
            $curdamage = 0;
        } else {
            if ($is_crit) {
                $curdamage *= 2;
                if (!empty($curdamage)) { $notes[] = "Its a critical hit!"; }
            }
            $notes[] = "Deals a $curdamage!";
            $notes[] = $adddmg;
        }

        // ---- on_before_attack emblem hook --------------------------------- //
        $ctx = [
            'damage'       => $curdamage,
            'notes'        => $notes,
            'attacker'     => $attacker,
            'defender'     => $defender,
            'hp_self'      => $hp_self,
            'hp_enemy'     => $hp_enemy,
            'fullhp_self'  => $fullhp_self,
            'fullhp_enemy' => $fullhp_enemy,
            'round'        => $round,
            'emblem'       => $emblems[$slot_self],
            'regen_count'  => $regen_count[$slot_self],
            'is_crit'      => $is_crit,
            'initialdmg'   => $initialdmg,
            'initial_msg'  => $initial_msg,
            'winner'       => $winner,
            'winnerpoke'   => $winnerpoke,
            'loserpoke'    => $loserpoke,
            'freeze_enemy' => 0,
        ];
        fire_emblem_hook('on_before_attack', $emblems, $slot_self, $ctx, $logs);
        $curdamage              = $ctx['damage'];
        $notes                  = $ctx['notes'];
        $hp_self                = $ctx['hp_self'];
        $regen_count[$slot_self]= $ctx['regen_count'];
        // write hp back to the aliased variable
        if ($turn == 1) { $hp1 = $hp_self; } else { $hp2 = $hp_self; }

        // ---- apply damage ------------------------------------------------- //
        $hp_enemy -= $curdamage;
        if ($turn == 1) { $hp2 = $hp_enemy; } else { $hp1 = $hp_enemy; }

        $logs[] = [
            'damage'   => $curdamage,
            'notes'    => $notes,
            'enemyhp'  => $hp_enemy,
            'hp1'      => $hp1,
            'hp2'      => $hp2,
            'dealer'   => $attacker["id"],
            'pokename' => $attacker["pokename"],
            'skillname'=> $tiraskill["title"],
            'element'  => $tiraskill["typebattle"],
        ];

        if ($hp_enemy <= 0) {
            $winner     = 1;
            $winnerpoke = $attacker["id"];
            $loserpoke  = $defender["id"];
            break;
        }

        // ---- on_after_attack emblem hooks --------------------------------- //
        // defender's reactive emblems (reflect) fire first
        $ctx_after = [
            'damage'       => $curdamage,
            'notes'        => [],
            'attacker'     => $defender,   // roles flip for reactive emblems
            'defender'     => $attacker,
            'hp_self'      => $hp_enemy,
            'hp_enemy'     => $hp_self,
            'fullhp_self'  => $fullhp_enemy,
            'fullhp_enemy' => $fullhp_self,
            'round'        => $round,
            'emblem'       => $emblems[$slot_enemy],
            'regen_count'  => $regen_count[$slot_enemy],
            'is_crit'      => $is_crit,
            'initialdmg'   => $initialdmg,
            'initial_msg'  => $initial_msg,
            'winner'       => $winner,
            'winnerpoke'   => $winnerpoke,
            'loserpoke'    => $loserpoke,
            'freeze_enemy' => 0,
        ];
        fire_emblem_hook('on_after_attack', $emblems, $slot_enemy, $ctx_after, $logs);
        // attacker's post-hit emblems (doubleattack, freezeturn)
        $ctx_after2 = [
            'damage'       => $curdamage,
            'notes'        => [],
            'attacker'     => $attacker,
            'defender'     => $defender,
            'hp_self'      => $hp_self,
            'hp_enemy'     => $hp_enemy,
            'fullhp_self'  => $fullhp_self,
            'fullhp_enemy' => $fullhp_enemy,
            'round'        => $round,
            'emblem'       => $emblems[$slot_self],
            'regen_count'  => $regen_count[$slot_self],
            'is_crit'      => $is_crit,
            'initialdmg'   => $initialdmg,
            'initial_msg'  => $initial_msg,
            'winner'       => $winner,
            'winnerpoke'   => $winnerpoke,
            'loserpoke'    => $loserpoke,
            'freeze_enemy' => 0,
        ];
        fire_emblem_hook('on_after_attack', $emblems, $slot_self, $ctx_after2, $logs);

        // merge results from after-attack contexts
        foreach ([$ctx_after, $ctx_after2] as $c) {
            if ($c['winner'])     { $winner = 1; $winnerpoke = $c['winnerpoke']; $loserpoke = $c['loserpoke']; }
            if ($c['freeze_enemy'] && $turn == 1) { $enemy_freeze_1 = 1; }
            if ($c['freeze_enemy'] && $turn == 2) { $enemy_freeze_2 = 1; }
        }
        // write hp mutations from after-attack back
        // ctx_after holds the defender's hp (reflect may have changed it)
        // ctx_after2 only covers attacker-side emblems (doubleattack, freeze) which don't change hp_self
        if ($turn == 1) { $hp2 = $ctx_after['hp_self']; }
        else            { $hp1 = $ctx_after['hp_self']; }

        if ($winner) { break; }

        $turn = ($turn == 1) ? 2 : 1;

        if ($tira == 10000) { $winner = 1; }
    }

    $debug  = $p1level . "====" . $p2level . "=====" . $p1gap . "===" . $p2gap;
    $mylogs = json_encode($logs, JSON_HEX_APOS);

    mysql_query_md("UPDATE tbl_battle SET winner='$winnerpoke', logs='$mylogs',fullhp1='$fullhp1',fullhp2='$fullhp2',hash='$debug' WHERE id='$id'");

    if (empty($p1gap) && empty($p2gap)) {
        mysql_query_md("UPDATE tbl_pokemon_users SET win = win + 1, exp = exp + 1 WHERE id='$winnerpoke'");
        mysql_query_md("UPDATE tbl_pokemon_users SET lose = lose + 1 WHERE id='$loserpoke'");
    }

    checkpoke($winnerpoke);
    deductloser($loserpoke);
}
