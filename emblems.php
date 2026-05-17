<?php

/*
 * Emblem definitions.
 *
 * Each key is the emblem slug stored in the database.
 * Available hooks:
 *
 *   on_prebattle(&$poke)
 *     - Called once before the battle loop starts.
 *     - Modify $poke stats directly.
 *
 *   on_before_attack(&$ctx)
 *     - Called at the start of the attacker's turn, after crit/dodge/type
 *       modifiers are applied.
 *     - $ctx keys: damage, notes, attacker, defender, hp_self, hp_enemy,
 *       fullhp_self, fullhp_enemy, round, emblem, is_crit, logs
 *     - Modify $ctx['damage'], $ctx['notes'], $ctx['emblem'], $ctx['hp_self'],
 *       $ctx['logs'] as needed.
 *     - Return BATTLE_LOG_ENTRY array to push an extra log entry, or null.
 *
 *   on_after_attack(&$ctx)
 *     - Called after the attacker's hit has been applied to the defender's HP.
 *     - Same $ctx shape. Additionally has $ctx['winner'] and
 *       $ctx['winnerpoke'] / $ctx['loserpoke'] which you can set to end the
 *       battle immediately.
 *     - Return BATTLE_LOG_ENTRY array to push an extra log entry, or null.
 */

return [

    // -------------------------------------------------------------------------
    // focus — pre-battle stat boost
    // -------------------------------------------------------------------------
    'focus' => [
        'on_prebattle' => function (&$poke) {
            $poke['attack']   = addmore($poke['attack'],   1, 0.15);
            $poke['accuracy'] = addmore($poke['accuracy'], 1, 0.10);
            $poke['speed']    = addmore($poke['speed'],    1, 0.10);
        },
    ],

    // -------------------------------------------------------------------------
    // desire — crits deal +40% bonus damage
    // -------------------------------------------------------------------------
    'desire' => [
        'on_before_attack' => function (&$ctx) {
            if ($ctx['is_crit']) {
                $bonus = $ctx['damage'] * 0.40;
                $ctx['damage'] += $bonus;
                $ctx['notes'][] = "Desires attacks give +(". round($bonus) .") additional dmg! Total of ({$ctx['damage']})";
            }
        },
    ],

    // -------------------------------------------------------------------------
    // thunderstrike — on round 4, convert missing HP into bonus damage (×0.85)
    // -------------------------------------------------------------------------
    'thunderstrike' => [
        'on_before_attack' => function (&$ctx) {
            if ($ctx['round'] == 4) {
                $bonus = round(($ctx['fullhp_self'] - $ctx['hp_self']) * 0.85);
                $ctx['damage'] += $bonus;
                $ctx['notes'][] = "ThunderStrike attacks give +($bonus) additional dmg! Total of ({$ctx['damage']})";
            }
        },
    ],

    // -------------------------------------------------------------------------
    // dpsatk (Fire) — +75% of attack as bonus damage; expires after round 6
    // -------------------------------------------------------------------------
    'dpsatk' => [
        'on_before_attack' => function (&$ctx) {
            $bonus = round($ctx['attacker']['attack'] * 0.75);
            $ctx['damage'] += $bonus;
            $ctx['notes'][] = "Fire attacks give +($bonus) additional dmg! Total of ({$ctx['damage']})";
            if ($ctx['round'] == 6) {
                $ctx['emblem'] = '';
            }
        },
    ],

    // -------------------------------------------------------------------------
    // dpshp (Poison) — % of max HP as bonus; scaling per round; expires after round 5
    // -------------------------------------------------------------------------
    'dpshp' => [
        'on_before_attack' => function (&$ctx) {
            $scale = 0.06 + (0.01 * $ctx['round']);
            $bonus = round($ctx['fullhp_self'] * $scale);
            $ctx['damage'] += $bonus;
            $ctx['notes'][] = "Poison attacks give +($bonus) additional dmg! Total of ({$ctx['damage']})";
            if ($ctx['round'] == 5) {
                $ctx['emblem'] = '';
            }
        },
    ],

    // -------------------------------------------------------------------------
    // lastwill — when HP ≤ 25%, deal +150% bonus damage (one time)
    // -------------------------------------------------------------------------
    'lastwill' => [
        'on_before_attack' => function (&$ctx) {
            $hp_pct = ($ctx['hp_self'] / $ctx['fullhp_self']) * 100;
            if ($hp_pct <= 25) {
                $bonus = $ctx['damage'] * 1.50;
                $ctx['damage'] += $bonus;
                $ctx['notes'][] = "Last Will attacks give +(". round($bonus) .") additional dmg! Total of ({$ctx['damage']})";
                $ctx['emblem'] = '';
            }
        },
    ],

    // -------------------------------------------------------------------------
    // regen — when HP ≤ 26%, heal 35% of max HP (one time)
    // -------------------------------------------------------------------------
    'regen' => [
        'on_before_attack' => function (&$ctx) {
            $hp_pct = ($ctx['hp_self'] / $ctx['fullhp_self']) * 100;
            if ($hp_pct <= 26) {
                $heal = round($ctx['fullhp_self'] * 0.35);
                $ctx['hp_self'] = min($ctx['hp_self'] + $heal, $ctx['fullhp_self']);
                $ctx['emblem'] = '';
                return [
                    'damage'   => 0,
                    'notes'    => ["REGEN use by: {$ctx['attacker']['pokename']}: +($heal)!"],
                    'enemyhp'  => $ctx['hp_enemy'],
                    'hp1'      => $ctx['hp_self'],
                    'hp2'      => $ctx['hp_enemy'],
                    'dealer'   => $ctx['attacker']['id'],
                    'pokename' => $ctx['attacker']['pokename'],
                    'skillname'=> 'REGEN(heal)',
                    'element'  => 'normal',
                ];
            }
        },
    ],

    // -------------------------------------------------------------------------
    // puredamage (Armor Break) — 15% chance to reset damage to base+250
    // -------------------------------------------------------------------------
    'puredamage' => [
        'on_before_attack' => function (&$ctx) {
            if (getluck(15, 100)) {
                $ctx['damage'] = $ctx['initialdmg'] + 250;
                if ($ctx['is_crit']) {
                    $ctx['damage'] *= 2;
                }
                $ctx['notes'] = [
                    $ctx['initial_msg'],
                    "Armor Break Activated use by {$ctx['attacker']['pokename']} !! Deals ({$ctx['damage']})",
                ];
            }
        },
    ],

    // -------------------------------------------------------------------------
    // dpsregen (Forest Buff) — heal defense×1.5 each turn; expires after 4 procs
    // -------------------------------------------------------------------------
    'dpsregen' => [
        'on_before_attack' => function (&$ctx) {
            if ($ctx['hp_self'] < $ctx['fullhp_self']) {
                $ctx['regen_count']++;
                $heal = round($ctx['attacker']['defense'] * 1.5);
                $ctx['hp_self'] = min($ctx['hp_self'] + $heal, $ctx['fullhp_self']);
                if ($ctx['regen_count'] == 4) {
                    $ctx['emblem'] = '';
                }
                return [
                    'damage'   => 0,
                    'notes'    => ["Forest Buff heals use by: {$ctx['attacker']['pokename']}: +($heal)!"],
                    'enemyhp'  => $ctx['hp_self'],
                    'hp1'      => $ctx['hp_self'],
                    'hp2'      => $ctx['hp_enemy'],
                    'dealer'   => $ctx['attacker']['id'],
                    'pokename' => $ctx['attacker']['pokename'],
                    'skillname'=> 'Forest Buff(heal)',
                    'element'  => 'normal',
                ];
            }
        },
    ],

    // -------------------------------------------------------------------------
    // reflect — 20% chance to reflect incoming damage back +20% to the attacker
    // -------------------------------------------------------------------------
    'reflect' => [
        'on_after_attack' => function (&$ctx) {
            if (getluck(20, 100)) {
                $reflected = $ctx['damage'] + ($ctx['damage'] * 0.20);
                $ctx['hp_enemy'] -= $reflected;
                $entry = [
                    'damage'   => $reflected,
                    'notes'    => ["Reflect Shield Used by {$ctx['attacker']['pokename']}!! Deals ($reflected)"],
                    'enemyhp'  => $ctx['hp_enemy'],
                    'hp1'      => $ctx['hp_self'],
                    'hp2'      => $ctx['hp_enemy'],
                    'dealer'   => $ctx['attacker']['id'],
                    'pokename' => $ctx['attacker']['pokename'],
                    'skillname'=> 'Reflect Shield!',
                    'element'  => 'normal',
                ];
                if ($ctx['hp_enemy'] <= 0) {
                    $ctx['winner']     = 1;
                    $ctx['winnerpoke'] = $ctx['attacker']['id'];
                    $ctx['loserpoke']  = $ctx['defender']['id'];
                }
                return $entry;
            }
        },
    ],

    // -------------------------------------------------------------------------
    // freezeturn — 10% chance to skip enemy's next turn; 3-round cooldown
    // -------------------------------------------------------------------------
    'freezeturn' => [
        'on_after_attack' => function (&$ctx) {
            if (getluck(10, 100)) {
                $ctx['freeze_enemy'] = 1;
                $ctx['emblem']       = 'freezeturn_cd';
                return [
                    'damage'   => 0,
                    'notes'    => ['Freeze Turn activated!'],
                    'enemyhp'  => $ctx['hp_self'],
                    'hp1'      => $ctx['hp_self'],
                    'hp2'      => $ctx['hp_enemy'],
                    'dealer'   => $ctx['attacker']['id'],
                    'pokename' => $ctx['attacker']['pokename'],
                    'skillname'=> 'FREEZE!!!',
                    'element'  => 'normal',
                ];
            }
        },
    ],

    'freezeturn_cd' => [
        'on_after_attack' => function (&$ctx) {
            if ($ctx['round'] == 3) {
                $ctx['emblem'] = 'freezeturn';
            }
        },
    ],

    // -------------------------------------------------------------------------
    // doubleattack — 12% chance to hit again for the same damage
    // -------------------------------------------------------------------------
    'doubleattack' => [
        'on_after_attack' => function (&$ctx) {
            if (getluck(12, 100)) {
                $ctx['hp_enemy'] -= $ctx['damage'];
                $entry = [
                    'damage'   => $ctx['damage'],
                    'notes'    => ["Double Attack activated! Additional({$ctx['damage']})!"],
                    'enemyhp'  => $ctx['hp_enemy'],
                    'hp1'      => $ctx['hp_self'],
                    'hp2'      => $ctx['hp_enemy'],
                    'dealer'   => $ctx['attacker']['id'],
                    'pokename' => $ctx['attacker']['pokename'],
                    'skillname'=> 'Double Attack!',
                    'element'  => 'normal',
                ];
                if ($ctx['hp_enemy'] <= 0) {
                    $ctx['winner']     = 1;
                    $ctx['winnerpoke'] = $ctx['attacker']['id'];
                    $ctx['loserpoke']  = $ctx['defender']['id'];
                }
                return $entry;
            }
        },
    ],

];
