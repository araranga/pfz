<?php

//////////////BOSS BATTLE//////////////////

function generatebattleboss($id)
{
    global $EMBLEM_REGISTRY;

    $query = "SELECT * FROM tbl_battle_boss WHERE id ='$id' AND winner IS NULL";
    $q     = mysql_query_md($query);
    $row   = mysql_fetch_md_assoc($q);
    if (empty($row)) {
        echo "Please use correct battle id";
        exit(1);
    }

    $poke1 = loadpokev2($row["p1poke"]);
    $poke2 = loadbossv2($row["p2poke"]);

    // scale boss stats based on how many times this hero has beaten it
    $queryacv  = "SELECT * FROM tbl_achievement WHERE hero='{$row["p1poke"]}' AND boss='{$row["p2poke"]}'";
    $acvcount  = mysql_num_rows_md(mysql_query_md($queryacv));

    $poke2["hp"]       = addmore($poke2["hp"],       $acvcount, 0.20);
    $poke2["defense"]  = addmore($poke2["defense"],  $acvcount, 1);
    $poke2["attack"]   = addmore($poke2["attack"],   $acvcount, 0.35);
    $poke2["accuracy"] = addmore($poke2["accuracy"], $acvcount, 0.25);
    $poke2["speed"]    = addmore($poke2["speed"],    $acvcount, 0.15);

    // emblem slugs — must be loaded before pre-battle hooks
    $emblems = [
        1 => getEmblem($poke1['emblem']),
        2 => getEmblem($poke2['emblem']),
    ];

    // pre-battle emblem stat boosts (player only — bosses don't get pre-battle buffs)
    foreach ([1 => &$poke1] as $slot => &$poke) {
        $key = $emblems[$slot];
        if (!empty($key) && !empty($EMBLEM_REGISTRY[$key]['on_prebattle'])) {
            $EMBLEM_REGISTRY[$key]['on_prebattle']($poke);
        }
    }
    unset($poke);

    // weapon bonuses (player only)
    $stats_up   = ["hp", "speed", "critical", "accuracy", "attack", "defense"];
    $getweapon1 = mysql_fetch_md_assoc(mysql_query_md("SELECT * FROM tbl_items_users WHERE pokemon='{$poke1['id']}' LIMIT 1"));
    if (!empty($getweapon1['id'])) {
        foreach ($stats_up as $s) {
            if (!empty($getweapon1[$s])) { $poke1[$s] += $getweapon1[$s]; }
        }
    }

    // build skill lists
    $skill1    = loadpokeskill($poke1["hash"]);
    $skillboss = [
        ["title" => $poke2['skillname1'], "typebattle" => $poke2['element1'], "power" => $poke2['power1']],
        ["title" => $poke2['skillname2'], "typebattle" => $poke2['element2'], "power" => $poke2['power2']],
        ["title" => $poke2['skillname3'], "typebattle" => $poke2['element3'], "power" => $poke2['power3']],
    ];
    $skill2 = $skillboss;

    $roundp1     = 0;
    $roundp2     = 0;
    $regen_count = [1 => 0, 2 => 0];
    $pokeclassdata = [];
    $winner      = 0;
    $tira        = 0;
    $logs        = [];
    $turn        = 1;

    $fullhp1 = $hp1 = $poke1["hp"];
    $fullhp2 = $hp2 = $poke2["hp"];

    $winnerpoke     = 0;
    $loserpoke      = 0;
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
            $attacker     = &$poke1; $defender     = &$poke2;
            $hp_self      = &$hp1;   $hp_enemy     = &$hp2;
            $fullhp_self  = $fullhp1; $fullhp_enemy = $fullhp2;
            $skills       = $skill1;
            $slot_self    = 1; $slot_enemy = 2;
            $round        = $roundp1;
        } else {
            $roundp2++;
            $attacker     = &$poke2; $defender     = &$poke1;
            $hp_self      = &$hp2;   $hp_enemy     = &$hp1;
            $fullhp_self  = $fullhp2; $fullhp_enemy = $fullhp1;
            $skills       = $skill2;
            $slot_self    = 2; $slot_enemy = 1;
            $round        = $roundp2;
        }

        $tiraskill          = $skills[array_rand($skills)];
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
                $adddmg    = "Its super effective!";
                $curdamage += $curdamage * 0.65;
            }
            if (strpos($typedata["half_damage_to"], $v) !== false) {
                $adddmg    = "Its not effective!";
                $curdamage -= $curdamage * 0.35;
            }
            if (strpos($typedata["no_damage_to"], $v) !== false) {
                $adddmg    = "It cause super low damage!";
                $curdamage -= $curdamage * 0.75;
            }
        }

        $is_dodge  = getluck($defender["speed"], $attacker["accuracy"] + $tiraskill["accuracy"] + 45);
        $curdamage = $curdamage - ($defender["defense"] * 0.6);
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
        $curdamage               = $ctx['damage'];
        $notes                   = $ctx['notes'];
        $hp_self                 = $ctx['hp_self'];
        $regen_count[$slot_self] = $ctx['regen_count'];
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
        $ctx_after = [
            'damage'       => $curdamage,
            'notes'        => [],
            'attacker'     => $defender,
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

        foreach ([$ctx_after, $ctx_after2] as $c) {
            if ($c['winner'])     { $winner = 1; $winnerpoke = $c['winnerpoke']; $loserpoke = $c['loserpoke']; }
            if ($c['freeze_enemy'] && $turn == 1) { $enemy_freeze_1 = 1; }
            if ($c['freeze_enemy'] && $turn == 2) { $enemy_freeze_2 = 1; }
        }
        if ($turn == 1) { $hp2 = $ctx_after['hp_self'];  $hp1 = $ctx_after2['hp_self']; }
        else            { $hp1 = $ctx_after['hp_self'];  $hp2 = $ctx_after2['hp_self']; }

        if ($winner) { break; }

        $turn = ($turn == 1) ? 2 : 1;

        if ($tira == 100000) { $winner = 1; }
    }

    $mylogs = addslashes(json_encode($logs));
    mysql_query_md("UPDATE tbl_battle_boss SET winner='$winnerpoke', logs='$mylogs',fullhp1='$fullhp1',fullhp2='$fullhp2' WHERE id='$id'");

    if ($winnerpoke == $poke1['id']) {
        $reward  = addmore($poke2["reward"], $acvcount, 0.15);
        $reward2 = $poke2['reward_money'];
        $getuser = $poke1['user'];

        mysql_query_md("UPDATE tbl_accounts SET balance = balance + $reward WHERE accounts_id='$getuser'");
        mysql_query_md("UPDATE tbl_accounts SET balance_money = balance_money + $reward2 WHERE accounts_id='$getuser'");
        mysql_query_md("INSERT INTO tbl_income SET user='{$getuser}', message='You Won a boss battle: {$reward}'");
        mysql_query_md("INSERT INTO tbl_achievement SET hero='{$poke1['id']}',boss='{$poke2['id']}',victorytext='Slayer of the {$poke2['pokename']}',fightdate = CURRENT_DATE + INTERVAL 35 DAY");
    }

    if ($loserpoke == $poke1['id']) {
        deductloser($loserpoke);
    }
}
