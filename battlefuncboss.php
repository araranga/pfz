<?php


//////////////BOSS BATTTLE//////////////////
//////////////BOSS BATTTLE//////////////////
//////////////BOSS BATTTLE//////////////////
//////////////BOSS BATTTLE//////////////////
//////////////BOSS BATTTLE//////////////////
//////////////BOSS BATTTLE//////////////////


function generatebattleboss($id)
{
    $query = "SELECT * FROM tbl_battle_boss WHERE id ='$id' AND winner IS NULL";
    $q = mysql_query_md($query);
    $row = mysql_fetch_md_assoc($q);
    if (empty($row))
    {
        echo "Please use correct battle id";
        exit(1);
    }
    //loading pokemon
    $poke1 = loadpokev2($row["p1poke"]);
    $poke2 = loadbossv2($row["p2poke"]);

    $queryacv = "SELECT * FROM tbl_achievement WHERE hero='{$row["p1poke"]}' AND boss='{$row["p2poke"]}'";
    $queryacvq = mysql_query_md($queryacv);
    $acvcount = mysql_num_rows_md($queryacvq);

    $poke2["hp"] = addmore($poke2["hp"], $acvcount, 0.20);
    $poke2["defense"] = addmore($poke2["defense"], $acvcount, 1);
    $poke2["attack"] = addmore($poke2["attack"], $acvcount, 0.35);
    $poke2["accuracy"] = addmore($poke2["accuracy"], $acvcount, 0.25);
    $poke2["speed"] = addmore($poke2["speed"], $acvcount, 0.15);
	
	//6DC*wC822R@j
	
	
	if($emblem1=='focus'){
		

		$poke1["attack"] = addmore($poke1["attack"], 1, 0.15);
		$poke1["accuracy"] = addmore($poke1["accuracy"], 1, 0.10);
		$poke1["speed"] = addmore($poke1["speed"], 1, 0.10);
		
		
	}
	if($emblem2=='focus'){
		

		$poke2["attack"] = addmore($poke2["attack"], 1, 0.15);
		$poke2["accuracy"] = addmore($poke2["accuracy"], 1, 0.10);
		$poke2["speed"] = addmore($poke2["speed"], 1, 0.10);
		
		
	}	
	
	
	
	$stats_up = array("hp","speed","critical","accuracy","attack","defense");


    $getweapon1 = mysql_fetch_md_assoc(mysql_query_md("SELECT * FROM tbl_items_users WHERE pokemon='{$poke1['id']}' LIMIT 1"));		
	if(!empty($getweapon1['id'])){
	
		foreach($stats_up as $sup){
			
			if(!empty($getweapon1[$sup]))
			{
				$poke1[$sup] = $poke1[$sup] + $getweapon1[$sup];
				
			}
			
		}
	
		
	}
		
	
	
	

    //var_dump($poke1);
    //var_dump($poke2);
    //preload skills
    $skill1 = loadpokeskill($poke1["hash"]);
    //$skill2 = loadpokeskill($poke2["hash"]);
    $skillboss = array();

    $skillboss[] = array(
        "title" => $poke2['skillname1'],
        "typebattle" => $poke2['element1'],
        "power" => $poke2['power1']
    );
    $skillboss[] = array(
        "title" => $poke2['skillname2'],
        "typebattle" => $poke2['element2'],
        "power" => $poke2['power2']
    );
    $skillboss[] = array(
        "title" => $poke2['skillname3'],
        "typebattle" => $poke2['element3'],
        "power" => $poke2['power3']
    );

    $skill2 = $skillboss;

    //preload emblem
    $emblem1 = getEmblem($poke1['emblem']);
    $emblem2 = getEmblem($poke2['emblem']);

    $roundp1 = 0;
    $roundp2 = 0;

    $dps_regen2 = 0;
    $dps_regen1 = 0;

    //preload class
    $pokeclass = [];
    //var_dump($pokeclass);
    $pokeclassdata = [];

    foreach ($pokeclass as $pc)
    {
        $pokeclassdata[$pc] = loadpoketype($pc);
    }

    $winner = 0;
    $tira = 0;

    $turn = rand(1, 2);
    $logs = [];

    $fullhp1 = $hp1 = $poke1["hp"];
    $fullhp2 = $hp2 = $poke2["hp"];

    $winnerpoke = 0;

    $turn = 1;

    while ($winner != 1)
    {
        $tira++;

        //freeze effective
        if ($enemy_freeze_2 == 1)
        {
            $enemy_freeze_2 = 0;
            $roundp1++;

            $turn = 2;

        }

        if ($enemy_freeze_1 == 1)
        {
            $enemy_freeze_1 = 0;
            $roundp2++;

            $turn = 1;
        }
        //
        

        if ($turn == 1)
        {

            $roundp1++;
            $random_skill = array_rand($skill1);

            $tiraskill = $skill1[array_rand($skill1) ];

			$is_crit_chance = 100 + ($poke2["defense"] * 0.25);
            $is_crit = getluck($poke1["critical"], $is_crit_chance);

            $notes = [];

            if (empty($pokeclassdata[$tiraskill["typebattle"]]))
            {
                $pokeclassdata[$tiraskill["typebattle"]] = loadpoketype($tiraskill["typebattle"]);
            }

            $initial_msg = $notes[] = "{$poke1["pokename"]} Uses {$tiraskill["title"]}({$tiraskill["typebattle"]}) to {$poke2["pokename"]}({$poke2["pokeclass"]})";
			
			$tiraskill["power"] = rand(180,270);

            $initialdmg = $curdamage = $tiraskill["power"] + $poke1["attack"];

            $enemy = explode("|", $poke2["pokeclass"]);

            $adddmg = "";
            foreach ($enemy as $v)
            {
                if (strpos($pokeclassdata[$tiraskill["typebattle"]]["double_damage_to"], $v) !== false)
                {
                    $adddmg = "Its super effective!";
                    $curdamage = $curdamage + ($curdamage * 0.65);
                }
                if (strpos($pokeclassdata[$tiraskill["typebattle"]]["half_damage_to"], $v) !== false)
                {
                    $adddmg = "Its not effective!";
                    $curdamage = $curdamage - ($curdamage * 0.35);
                }
                if (strpos($pokeclassdata[$tiraskill["typebattle"]]["no_damage_to"], $v) !== false)
                {
                    $adddmg = "It cause super low damage!";
                    $curdamage = $curdamage - ($curdamage * 0.75);
                }
            }

            $acc = $poke1["accuracy"] + $tiraskill["accuracy"] + 45;

            $accdodge = $poke2["speed"];

            $is_dodge = getluck($poke2["speed"], $acc);

            $curdamage = $curdamage - ($poke2["defense"] * 0.6);

            if ($curdamage < 0)
            {
                $curdamage = 1;
            }

            $curdamage = ceil($curdamage);

            if ($is_dodge)
            {
                $notes[] = "Enemy Dodge! Takes no damage!";
                $curdamage = 0;
            }
            else
            {
                if ($is_crit)
                {
                    $curdamage = $curdamage * 2;
                    if (!empty($curdamage))
                    {
                        $notes[] = "Its a critical hit!";
                    }
                }
                else
                {
                }

                $notes[] = "Deals a $curdamage!";
                $notes[] = $adddmg;
            }
			if($emblem1== 'desire'){
				
					
				if($is_crit){
					$dpsatk = ($curdamage * 0.40);
					$curdamage = $dpsatk + $curdamage;
					$notes[] = "Desires attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";
				}
				
				
			}
            //BEFORE ATK
			
			
			if($emblem1=='thunderstrike'){
		

                if ($roundp1 == 4)
                {

                $dpsatk = round(($fullhp1 - $hp1) * 0.85);
                $curdamage = $curdamage + $dpsatk;
                $notes[] = "ThunderStrike attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";

				}					
				
				
			}				
			
			
			
			
			
			
			
            if ($emblem1 == 'dpsatk')
            {

                $dpsatk = round($poke1['attack'] * 0.75);
                $curdamage = $curdamage + $dpsatk;
                $notes[] = "Fire attacks give +($dpsatk) additonal dmg! Total of ($curdamage";

                if ($roundp1 == 6)
                {
                    $emblem1 = '';
                }
            }

            if ($emblem1 == 'dpshp')
            {

				$dpshpadd = 0.01 * $roundp1;

                $dpsatk = round($fullhp1 * (0.06 + $dpshpadd));
                $curdamage = $curdamage + $dpsatk;
                $notes[] = "Poison attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";

                if ($roundp1 == 5)
                {
                    $emblem1 = '';
                }
            }

            if ($emblem1 == 'lastwill')
            {
                $lastwill_comp = ($hp1 / $fullhp1) * 100;
                if ($lastwill_comp <= 21)
                {
                    $dpsatk = ($curdamage * 1.50);
                    $curdamage = $curdamage + $dpsatk;
                    $notes[] = "Last Will attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";

                    $emblem1 = '';
                }

            }

            if ($emblem1 == 'regen')
            {
                $lastwill_comp = ($hp1 / $fullhp1) * 100;
                if ($lastwill_comp <= 26)
                {
                    $dpsatk = round($fullhp1 * 0.35);
                    $hp1 = $hp1 + $dpsatk;
                    $datalogs["damage"] = 0;
                    $datalogs["notes"] = array(
                        "REGEN use by: {$poke1["pokename"]}: +($dpsatk)!"
                    );
                    $datalogs["enemyhp"] = $hp2;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke1["id"];
                    $datalogs["pokename"] = $poke1["pokename"];
                    $datalogs["skillname"] = "REGEN(heal)";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;
                    $emblem1 = '';
                }
            }

            if ($emblem1 == 'puredamage')
            {

                $is_double_attack = getluck(15, 100);

                if ($is_double_attack == 1)
                {
                    $curdamage = $initialdmg + 250;

                    if ($is_crit)
                    {
                        $curdamage = $curdamage * 2;
                    }
                    $notes = array();
                    $notes[] = $initial_msg;
                    $notes[] = "Armor Break Activated use by: {$poke1["pokename"]}!! Deals ($curdamage)";

                }

            }

            if ($emblem1 == 'dpsregen' && $hp1 < $fullhp1)
            {
                $dps_regen1++;
                $dpsatk = round($poke1['defense'] * 1.5);
                $hp1 = $hp1 + $dpsatk;
                $datalogs["damage"] = 0;
                $datalogs["notes"] = array(
                    "Forest Buff heals use by: {$poke1["pokename"]}: +($dpsatk)!"
                );
                $datalogs["enemyhp"] = $hp1;
                $datalogs["hp1"] = $hp1;
                $datalogs["hp2"] = $hp2;
                $datalogs["dealer"] = $poke1["id"];
                $datalogs["pokename"] = $poke1["pokename"];
                $datalogs["skillname"] = "Forest Buff(heal)";
                $datalogs["element"] = "normal";
                $logs[] = $datalogs;

                if ($dps_regen1 == 4)
                {
                    $emblem1 = '';
                }
            }

            //
            

            $hp2 = $hp2 - $curdamage;
            $datalogs = [];
            $datalogs["damage"] = $curdamage;
            $datalogs["notes"] = $notes;
            $datalogs["enemyhp"] = $hp2;
            $datalogs["hp1"] = $hp1;
            $datalogs["hp2"] = $hp2;
            $datalogs["dealer"] = $poke1["id"];
            $datalogs["pokename"] = $poke1["pokename"];
            $datalogs["skillname"] = $tiraskill["title"];
            $datalogs["element"] = $tiraskill["typebattle"];
            $logs[] = $datalogs;
            if ($hp2 <= 0)
            {
                $winner = 1;
                $winnerpoke = $poke1["id"];
                $loserpoke = $poke2["id"];
                break;
            }

            //AFTER ATK
            

            if ($emblem2 == 'reflect')
            {

                $is_double_attack = getluck(20, 100);

                if ($is_double_attack == 1)
                {
                    $notes = array();
					$curdamage = $curdamage + ($curdamage * 0.20);
				    $notes[] = "Reflect Shield Used by {$poke2['pokename']}!! Deals ($curdamage)";

                    $hp1 = $hp1 - $curdamage;
                    $datalogs = [];
                    $datalogs["damage"] = $curdamage;
                    $datalogs["notes"] = $notes;
                    $datalogs["enemyhp"] = $hp1;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke2["id"];
                    $datalogs["pokename"] = $poke2["pokename"];
                    $datalogs["skillname"] = "Reflect Shield!";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;
                    if ($hp1 <= 0)
                    {
                        $winner = 1;
                        $winnerpoke = $poke2["id"];
                        $loserpoke = $poke1["id"];
                        break;
                    }

                }

            }

            if ($emblem1 == 'freezeturn_cd')
            {

                if ($roundp1 == 3)
                {
                    $emblem1 = 'freezeturn';
                }
            }
            if ($emblem1 == 'freezeturn')
            {

                $is_double_attack = getluck(10, 100);

                if ($is_double_attack == 1)
                {

                    $enemy_freeze_1 = 1;
                    $emblem1 = 'freezeturn_cd';
                    $datalogs["damage"] = 0;
                    $datalogs["notes"] = array(
                        "Freeze Turn activated!"
                    );
                    $datalogs["enemyhp"] = $hp1;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke1["id"];
                    $datalogs["pokename"] = $poke1["pokename"];
                    $datalogs["skillname"] = "FREEZE!!!";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;

                }

            }

            if ($emblem1 == 'doubleattack')
            {

                $is_double_attack = getluck(12, 100);

                if ($is_double_attack == 1)
                {
                    $notes = array();
                    $notes[] = "Double Attack activated! Additional($curdamage)!";

                    $hp2 = $hp2 - $curdamage;
                    $datalogs = [];
                    $datalogs["damage"] = $curdamage;
                    $datalogs["notes"] = $notes;
                    $datalogs["enemyhp"] = $hp2;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke1["id"];
                    $datalogs["pokename"] = $poke1["pokename"];
                    $datalogs["skillname"] = "Double Attack!";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;
                    if ($hp2 <= 0)
                    {
                        $winner = 1;
                        $winnerpoke = $poke1["id"];
                        $loserpoke = $poke2["id"];

                        break;
                    }

                }

            }
            //
            

            $turn = 2;
        }

        if ($turn == 2)
        {
            $roundp2++;

            $random_skill = array_rand($skill2);

            $tiraskill = $skill2[array_rand($skill2) ];

			$is_crit_chance = 100 + ($poke1["defense"] * 0.25);
            $is_crit = getluck($poke2["critical"], $is_crit_chance);	

            $notes = [];

            if (empty($pokeclassdata[$tiraskill["typebattle"]]))
            {
                $pokeclassdata[$tiraskill["typebattle"]] = loadpoketype($tiraskill["typebattle"]);
            }

            $initial_msg = $notes[] = "{$poke2["pokename"]} Uses {$tiraskill["title"]}({$tiraskill["typebattle"]}) to {$poke1["pokename"]}({$poke1["pokeclass"]})";

            $initialdmg = $curdamage = $tiraskill["power"] + $poke2["attack"];

            if ($is_crit)
            {
                $curdamage = $curdamage * 2;
                if (!empty($curdamage))
                {
                    $notes[] = "Its a critical hit!";
                }
            }
            else
            {
            }

            $enemy = explode("|", $poke1["pokeclass"]);

            $adddmg = "";
            foreach ($enemy as $v)
            {
                if (strpos($pokeclassdata[$tiraskill["typebattle"]]["double_damage_to"], $v) !== false)
                {
                    $adddmg = "Its super effective!";
                    $curdamage = $curdamage + ($curdamage * 0.65);
                }
                if (strpos($pokeclassdata[$tiraskill["typebattle"]]["half_damage_to"], $v) !== false)
                {
                    $adddmg = "Its not effective!";
                    $curdamage = $curdamage - ($curdamage * 0.35);
                }
                if (strpos($pokeclassdata[$tiraskill["typebattle"]]["no_damage_to"], $v) !== false)
                {
                    $adddmg = "It cause super low damage!";
                    $curdamage = $curdamage - ($curdamage * 0.75);
                }
            }

            $acc = $poke2["accuracy"] + $tiraskill["accuracy"] + 45;

            $accdodge = $poke1["speed"];

            $is_dodge = getluck($poke1["speed"], $acc);

            $curdamage = $curdamage - ($poke1["defense"] / 2);

            if ($curdamage < 0)
            {
                $curdamage = 1;
            }

            $curdamage = ceil($curdamage);

            if ($is_dodge)
            {
                $notes[] = "Enemy Dodge! Takes no damage!";
                $curdamage = 0;
            }
            else
            {
                $notes[] = "Deals a $curdamage!";
                $notes[] = $adddmg;
            }
			if($emblem2== 'desire'){
				
					
				if($is_crit){
					$dpsatk = ($curdamage * 0.40);
					$curdamage = $dpsatk + $curdamage;
					$notes[] = "Desires attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";
				}
				
				
			}
			
			
			if($emblem2=='thunderstrike'){
		

                if ($roundp2 == 4)
                {

                $dpsatk = round(($fullhp2 - $hp2) * 0.85);
                $curdamage = $curdamage + $dpsatk;
                $notes[] = "ThunderStrike attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";

				}					
				
				
			}				
			
			
			
			
			
            //BEFORE ATK
            if ($emblem2 == 'dpsatk')
            {

                $dpsatk = round($poke2['attack'] * 0.75);
                $curdamage = $curdamage + $dpsatk;
                $notes[] = "Fire attacks give +($dpsatk) additonal dmg! Total of ($curdamage";

                if ($roundp2 == 6)
                {
                    $emblem2 = '';
                }
            }

            if ($emblem2 == 'dpshp')
            {
				$dpshpadd = 0.01 * $roundp2;
                $dpsatk = round($fullhp2 * (0.06 + $dpshpadd));
                $curdamage = $curdamage + $dpsatk;
                $notes[] = "Poison attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";

                if ($roundp2 == 5)
                {
                    $emblem2 = '';
                }
            }

            if ($emblem2 == 'lastwill')
            {
                $lastwill_comp = ($hp2 / $fullhp2) * 100;
                if ($lastwill_comp <= 21)
                {
                    $dpsatk = ($curdamage * 1.50);
                    $curdamage = $curdamage + $dpsatk;
                    $notes[] = "Last Will attacks give +($dpsatk) additonal dmg! Total of ($curdamage)";

                    $emblem2 = '';
                }

            }

            if ($emblem2 == 'regen')
            {
                $lastwill_comp = ($hp2 / $fullhp2) * 100;
                if ($lastwill_comp <= 26)
                {
                    $dpsatk = round($fullhp2 * 0.35);
                    $hp2 = $hp2 + $dpsatk;
                    $datalogs["damage"] = 0;
                    $datalogs["notes"] = array(
                        "REGEN use by: {$poke2["pokename"]} +($dpsatk)!"
                    );
                    $datalogs["enemyhp"] = $hp1;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke2["id"];
                    $datalogs["pokename"] = $poke2["pokename"];
                    $datalogs["skillname"] = "REGEN(heal)";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;
                    $emblem2 = '';
                }
            }

            if ($emblem2 == 'puredamage')
            {

                $is_double_attack = getluck(15, 100);

                if ($is_double_attack == 1)
                {
                    $curdamage = $initialdmg + 250;
                    if ($is_crit)
                    {
                        $curdamage = $curdamage * 2;
                    }

                    $notes = array();
                    $notes[] = $initial_msg;
                    $notes[] = "Armor Break Activated!! Deals ($curdamage)";

                }

            }

            if ($emblem2 == 'dpsregen' && $hp2 < $fullhp2)
            {
                $dps_regen2++;
                $dpsatk = round($poke2['defense'] * 1.5);
                $hp2 = $hp2 + $dpsatk;
                $datalogs["damage"] = 0;
                $datalogs["notes"] = array(
                    "Forest Buff heals use by: {$poke2["pokename"]} : +($dpsatk)!"
                );
                $datalogs["enemyhp"] = $hp1;
                $datalogs["hp1"] = $hp1;
                $datalogs["hp2"] = $hp2;
                $datalogs["dealer"] = $poke2["id"];
                $datalogs["pokename"] = $poke2["pokename"];
                $datalogs["skillname"] = "Forest Buff(heal)";
                $datalogs["element"] = "normal";
                $logs[] = $datalogs;

                if ($dps_regen2 == 4)
                {
                    $emblem2 = '';
                }
            }

            //
            

            $hp1 = $hp1 - $curdamage;
            $datalogs = [];
            $datalogs["damage"] = $curdamage;
            $datalogs["notes"] = $notes;
            $datalogs["enemyhp"] = $hp1;
            $datalogs["hp1"] = $hp1;
            $datalogs["hp2"] = $hp2;
            $datalogs["dealer"] = $poke2["id"];
            $datalogs["pokename"] = $poke2["pokename"];
            $datalogs["skillname"] = $tiraskill["title"];
            $datalogs["element"] = $tiraskill["typebattle"];
            $logs[] = $datalogs;
            if ($hp1 <= 0)
            {
                $winner = 1;
                $winnerpoke = $poke2["id"];
                $loserpoke = $poke1["id"];

                break;
            }

            //AFTER ATK
            

            if ($emblem1 == 'reflect')
            {

                $is_double_attack = getluck(20, 100);

                if ($is_double_attack == 1)
                {
                    $notes = array();
					$curdamage = $curdamage + ($curdamage * 0.20);
				    $notes[] = "Reflect Shield Used by {$poke1['pokename']}!! Deals ($curdamage)";

                    $hp2 = $hp2 - $curdamage;
                    $datalogs = [];
                    $datalogs["damage"] = $curdamage;
                    $datalogs["notes"] = $notes;
                    $datalogs["enemyhp"] = $hp2;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke1["id"];
                    $datalogs["pokename"] = $poke1["pokename"];
                    $datalogs["skillname"] = "Reflect Shield!";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;
                    if ($hp2 <= 0)
                    {
                        $winner = 1;
                        $winnerpoke = $poke1["id"];
                        $loserpoke = $poke2["id"];
                        break;
                    }

                }

            }

            if ($emblem2 == 'freezeturn_cd')
            {

                if ($roundp2 == 3)
                {
                    $emblem2 = 'freezeturn';
                }
            }
            if ($emblem2 == 'freezeturn')
            {

                $is_double_attack = getluck(10, 100);

                if ($is_double_attack == 1)
                {

                    $enemy_freeze_2 = 1;
                    $emblem2 = 'freezeturn_cd';
                    $datalogs["damage"] = 0;
                    $datalogs["notes"] = array(
                        "Freeze Turn activated!"
                    );
                    $datalogs["enemyhp"] = $hp1;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke2["id"];
                    $datalogs["pokename"] = $poke2["pokename"];
                    $datalogs["skillname"] = "FREEZE!!!";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;

                }

            }

            if ($emblem2 == 'doubleattack')
            {

                $is_double_attack = getluck(12, 100);

                if ($is_double_attack == 1)
                {
                    $notes = array();
                    $notes[] = "Double Attack activated! Additional($curdamage)!";

                    $hp1 = $hp1 - $curdamage;
                    $datalogs = [];
                    $datalogs["damage"] = $curdamage;
                    $datalogs["notes"] = $notes;
                    $datalogs["enemyhp"] = $hp1;
                    $datalogs["hp1"] = $hp1;
                    $datalogs["hp2"] = $hp2;
                    $datalogs["dealer"] = $poke2["id"];
                    $datalogs["pokename"] = $poke2["pokename"];
                    $datalogs["skillname"] = "Double Attack!";
                    $datalogs["element"] = "normal";
                    $logs[] = $datalogs;
                    if ($hp1 <= 0)
                    {
                        $winner = 1;
                        $winnerpoke = $poke2["id"];
                        $loserpoke = $poke1["id"];

                        break;
                    }

                }

            }
            //
            $turn = 1;
        }

        if ($tira == 100000)
        {
            $winner = 1;
        }
    }

    $mylogs = addslashes(json_encode($logs));
    mysql_query_md("UPDATE tbl_battle_boss SET winner='$winnerpoke', logs='$mylogs',fullhp1='$fullhp1',fullhp2='$fullhp2' WHERE id='$id'");

    if ($winnerpoke == $poke1['id'])
    {
        $reward = $poke2['reward'];
		$reward = addmore($poke2["reward"], $acvcount, 0.15);
		
		
		$reward2 = $poke2['reward_money'];
		
        $getuser = $poke1['user'];
        mysql_query_md("UPDATE tbl_accounts SET balance = balance + $reward WHERE accounts_id='$getuser'");
		mysql_query_md("UPDATE tbl_accounts SET balance_money = balance_money + $reward2 WHERE accounts_id='$getuser'");

        $vt = "Slayer of the {$poke2['pokename']}";

        $NewDate = Date('y:m:d h:i:s', strtotime('+30 days'));



		mysql_query_md("INSERT INTO tbl_income SET user='{$getuser}', message='You Won a boss battle: {$reward}'");
        mysql_query_md("INSERT INTO tbl_achievement SET hero='{$poke1['id']}',boss='{$poke2['id']}',victorytext='$vt',fightdate = CURRENT_DATE 	+ INTERVAL 35 DAY");

    }
	
	if($loserpoke == $poke1['id']){
		deductloser($loserpoke);
	}
}




?>