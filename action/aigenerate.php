<?php
session_start();
require_once("../connect.php");
require_once("../function.php");

$ai_user = 360;

$qpoke = mysql_query_md("SELECT * FROM tbl_battle WHERE TIMESTAMPDIFF(SECOND, battledata, NOW()) >= 30 AND p2user IS NULL");
	
	
	
	
	
while($rowqpoke = mysql_fetch_md_assoc($qpoke)){

		$check_level = $rowqpoke['level'];
		
		$count_levels = mysql_num_rows_md(mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE user = $ai_user AND level= $check_level"));
		
		$remaining = 15 - $count_levels;
		var_dump($remaining);
		if($remaining>0){
			echo 1;
			for ($x = 0; $x <= $remaining; $x++) {
			  createbot($check_level);
			}				
			
		}
			$qs = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE user = $ai_user AND level = $check_level ORDER by RAND() LIMIT 1");
			while($as = mysql_fetch_md_assoc($qs)){
				echo $as['hash']."<br>";
				try{
					savebattlebot($as['hash'],$as['user']);
				}
				catch(Exception $e) {
					echo '<br>Message: ' .$e->getMessage();
				}
				
				$poke = $as['id'];
				
				var_dump($poke);
				mysql_query_md("UPDATE tbl_battle WHERE p1poke='$poke' SET v1 ='1'");
				mysql_query_md("UPDATE tbl_battle WHERE p2poke='$poke' SET v2 ='1'");
			}			

}


$qpoke = mysql_query_md("SELECT * FROM tbl_accounts WHERE robot = 1 ORDER by RAND()");



function generateAttack(){
	
$attackNames = [
    "Fireball",
    "Ice Shard",
    "Thunderstrike",
    "Poison Fang",
    "Wind Slash",
    "Earthquake",
    "Shadow Bolt",
    "Holy Smite",
    "Arcane Blast",
    "Acid Rain",
    "Lava Wave",
    "Frost Nova",
    "Lightning Surge",
    "Venom Strike",
    "Cyclone Kick",
    "Stone Gaze",
    "Dark Vortex",
    "Divine Wrath",
    "Magic Missile",
    "Toxic Breath",
    "Magma Surge",
    "Blizzard",
    "Shockwave",
    "Stinging Barb",
    "Tornado Slash",
    "Quicksand Grasp",
    "Necrotic Beam",
    "Sacred Light",
    "Energy Surge",
    "Corrosive Spit",
    "Inferno Burst",
    "Frozen Prison",
    "Thunderclap",
    "Searing Gaze",
    "Poison Cloud",
    "Gale Force",
    "Earth's Fury",
    "Shadow Strike",
    "Holy Nova",
    "Arcane Torrent",
    "Acid Spray",
    "Lava Burst",
    "Frostbite",
    "Chain Lightning",
    "Venomous Bite",
    "Hurricane Fist",
    "Petrifying Gaze",
    "Abyssal Bolt",
    "Divine Judgment",
    "Astral Ray",
    "Virulent Eruption",
    "Frost Armor",
    "Static Shock",
    "Viper's Kiss",
    "Blazing Comet",
    "Icicle Spear",
    "Venomous Cloud",
    "Soul Drain",
    "Cyclone Tempest",
    "Earthshaker",
    "Shadowmeld",
    "Celestial Smite",
    "Ethereal Surge",
    "Acidic Spray",
    "Molten Torrent",
    "Frostbite",
    "Electric Surge",
    "Poisoned Blade",
    "Tornado Strike",
    "Stoneform",
    "Dark Embrace",
    "Divine Retribution",
    "Magic Surge",
    "Corrosive Wave",
    "Infernal Blaze",
    "Frost Barrier",
    "Stormcaller",
    "Petrification",
    "Abyssal Grip",
    "Radiant Beam",
    "Arcane Storm",
    "Venomous Spores",
    "Solar Flare",
    "Glacial Spike",
    "Toxic Mist",
    "Thunderous Roar",
    "Quicksand Torrent",
    "Soul Reaver",
    "Shadow Veil",
    "Elemental Fury",
    "Holy Aegis",
    "Arcane Whirlwind",
    "Acidic Eruption",
    "Lava Fissure",
    "Frost Nova",
    "Viper Strike",
    "Tornado Fury",
    "Stone Skin",
    "Shadow Dance",
    "Celestial Wrath",
    "Pyroclasm",
    "Crystalize",
    "Blade Dance",
    "Solar Beam",
    "Dark Pulse",
    "Eternal Frost",
    "Thunderous Impact",
    "Chaos Strike",
    "Vorpal Slash",
    "Abyssal Surge",
    "Nova Burst",
    "Life Drain",
    "Phantom Strike",
    "Elemental Quake",
    "Rune of Destruction",
    "Mystic Wave",
    "Frostbite",
    "Venomous Vortex",
    "Crimson Strike",
    "Celestial Storm",
    "Rift Bolt",
    "Void Nova",
    "Radiant Fury",
    "Tempest Blade",
    "Aegis of Light",
    "Divine Retaliation",
    "Cursed Bolt",
    "Toxic Eruption",
    "Sonic Wave",
    "Shadowstep",
    "Earthen Fury",
    "Soul Shatter",
    "Molten Rain",
    "Whirlwind Assault",
    "Arcane Annihilation",
    "Icy Veil",
    "Plaguebearer's Grasp",
    "Cinderstorm",
    "Frozen Blade",
    "Venomous Web",
    "Quakebreaker",
    "Shadowsong",
    "Celestial Blessing",
    "Raging Tempest",
    "Ebonflame Strike",
    "Rune of Resilience",
    "Searing Ember",
    "Nether Implosion",
    "Luminescent Wave",
    "Crystal Lance",
    "Void Torrent",
    "Eldritch Blast",
    "Hallowed Ground",
    "Eclipse",
    "Solar Flare",
    "Blizzard's Embrace",
    "Thunderclap",
    "Purifying Light",
    "Gravity Well",
    "Temporal Warp",
    "Blighted Reckoning",
    "Sandstorm",
    "Chaos Devastation",
    "Abyssal Chains",
    "Radiant Incandescence",
    "Venomous Barrage",
    "Ethereal Blades",
    "Aetheric Storm",
    "Void Eruption",
    "Frostburn",
    "Stonebreaker",
    "Nova Singularity",
    "Inferno Whirl",
    "Aurora Borealis",
    "Moonlit Blade",
    "Serpent's Venom",
    "Divine Intervention",
    "Tempest Torrent",
    "Hellfire",
    "Flarestrike",
    "Acid Rain",
    "Rapid Thunder",
    "Quakeblast",
    "Blade of the Damned",
    "Chaos Storm",
    "Voidfire",
    "Starfall",
    "Frostbite",
    "Venomstrike",
    "Tsunami",
    "Dark Eclipse",
    "Astral Barrage",
    "Magnetic Pulse",
    "Abyssal Surge",
    "Radiant Fury",
    "Eternal Torment",
    "Necromantic Rebirth",
    "Thornstrike",
    "Plague of Shadows",
    "Eclipsed Soul",
    "Inferno's Fury",
    "Glacial Wrath",
    "Ethereal Cascade",
    "Elemental Quake",
    "Frost Nova",
    "Venomous Vortex",
    "Crimson Strike",
    "Celestial Storm",
    "Rift Bolt",
    "Void Nova",
    "Radiant Beam",
    "Tempest Blade",
    "Aegis of Light",
    "Divine Retribution",
    "Cursed Bolt",
    "Toxic Eruption",
    "Sonic Wave",
    "Shadow Dance",
    "Earthen Fury",
    "Soul Shatter",
    "Molten Rain",
    "Whirlwind Assault",
    "Arcane Annihilation",
    "Icy Veil",
    "Plaguebearer's Grasp",
    "Cinderstorm",
    "Frozen Blade",
    "Venomous Web",
    "Quakebreaker",
    "Shadowsong",
    "Celestial Blessing",
    "Raging Tempest",
    "Ebonflame Strike",
    "Rune of Resilience",
    "Searing Ember",
    "Nether Implosion",
    "Luminescent Wave",
    "Crystal Lance",
    "Void Torrent",
    "Eldritch Blast",
    "Hallowed Ground",
    "Eclipse",
    "Solar Flare",
    "Blizzard's Embrace",
    "Thunderclap",
    "Purifying Light",
    "Gravity Well",
    "Temporal Warp",
    "Blighted Reckoning",
    "Sandstorm",
    "Chaos Devastation",
    "Abyssal Chains",
    "Radiant Incandescence",
    "Venomous Barrage",
    "Ethereal Blades",
    "Aetheric Storm",
    "Void Eruption",
    "Frostburn",
    "Stonebreaker",
    "Nova Singularity",
    "Inferno Whirl",
    "Aurora Borealis",
    "Moonlit Blade",
    "Serpent's Venom",
    "Divine Intervention",
    "Tempest Torrent",
    "Hellfire",
    "Flarestrike",
    "Acid Rain",
    "Rapid Thunder",
    "Quakeblast",
    "Blade of the Damned",
    "Chaos Storm",
    "Voidfire",
    "Starfall",
    "Frostbite",
    "Venomstrike",
    "Tsunami",
    "Dark Eclipse",
    "Astral Barrage",
    "Magnetic Pulse",
    "Abyssal Surge",
    "Radiant Fury",
    "Eternal Torment",
    "Necromantic Rebirth",
    "Thornstrike",
    "Plague of Shadows",
    "Eclipsed Soul",
    "Inferno's Fury",
    "Glacial Wrath",
    "Ethereal Cascade",
    "Elemental Quake",
    "Frost Nova",
    "Venomous Vortex",
    "Crimson Strike",
    "Celestial Storm",
    "Rift Bolt",
    "Void Nova",
    "Radiant Beam",
    "Tempest Blade",
    "Aegis of Light",
    "Divine Retribution",
    "Cursed Bolt",
    "Toxic Eruption",
    "Sonic Wave",
    "Shadow Dance",
    "Earthen Fury",
    "Soul Shatter",
    "Molten Rain",
    "Whirlwind Assault",
    "Arcane Annihilation",
    "Icy Veil",
    "Plaguebearer's Grasp",
    "Cinderstorm",
    "Frozen Blade",
    "Venomous Web",
    "Quakebreaker",
    "Shadowsong",
    "Celestial Blessing",
    "Raging Tempest",
    "Ebonflame Strike",
    "Rune of Resilience",
    "Searing Ember",
    "Nether Implosion",
    "Luminescent Wave",
    "Crystal Lance",
    "Void Torrent",
    "Eldritch Blast",
    "Hallowed Ground",
    "Eclipse",
    "Solar Flare",
    "Blizzard's Embrace",
    "Thunderclap",
    "Purifying Light",
    "Gravity Well",
    "Temporal Warp",
    "Blighted Reckoning",
    "Sandstorm",
    "Chaos Devastation",
    "Abyssal Chains",
    "Radiant Incandescence",
    "Venomous Barrage",
    "Ethereal Blades",
    "Aetheric Storm",
    "Void Eruption",
    "Frostburn",
    "Stonebreaker",
    "Nova Singularity",
    "Inferno Whirl",
    "Aurora Borealis",
    "Moonlit Blade",
    "Serpent's Venom",
    "Divine Intervention",
    "Tempest Torrent",
    "Hellfire",
    "Flarestrike",
    "Acid Rain",
    "Rapid Thunder",
    "Quakeblast",
    "Blade of the Damned",
    "Chaos Storm",
    "Voidfire",
    "Starfall",
    "Frostbite",
    "Venomstrike",
    "Tsunami",
    "Dark Eclipse",
    "Astral Barrage",
    "Magnetic Pulse",
    "Abyssal Surge",
    "Radiant Fury",
    "Eternal Torment",
    "Necromantic Rebirth",
    "Thornstrike",
    "Plague of Shadows",
    "Eclipsed Soul",
    "Inferno's Fury",
    "Glacial Wrath",
    "Ethereal Cascade",
    "Elemental Quake",
    "Frost Nova",
    "Venomous Vortex",
    "Crimson Strike",
    "Celestial Storm",
    "Rift Bolt",
    "Void Nova",
    "Radiant Beam",
    "Tempest Blade",
    "Aegis of Light",
    "Divine Retribution",
    "Cursed Bolt",
    "Toxic Eruption",
    "Sonic Wave",
    "Shadow Dance",
    "Earthen Fury",
    "Soul Shatter",
    "Molten Rain",
    "Whirlwind Assault",
    "Arcane Annihilation",
    "Icy Veil",
    "Plaguebearer's Grasp",
    "Cinderstorm",
    "Frozen Blade",
    "Venomous Web",
    "Quakebreaker",
    "Shadowsong",
    "Celestial Blessing",
    "Raging Tempest",
    "Ebonflame Strike",
    "Rune of Resilience",
    "Searing Ember",
    "Nether Implosion",
    "Luminescent Wave",
    "Crystal Lance",
    "Void Torrent",
    "Eldritch Blast",
    "Hallowed Ground",
    "Eclipse",
    "Solar Flare",
    "Blizzard's Embrace",
    "Thunderclap",
    "Purifying Light",
    "Gravity Well",
    "Temporal Warp",
    "Blighted Reckoning",
    "Sandstorm",
    "Chaos Devastation",
    "Abyssal Chains",
    "Radiant Incandescence",
    "Venomous Barrage",
    "Ethereal Blades",
    "Aetheric Storm",
    "Void Eruption",
    "Frostburn",
    "Stonebreaker",
    "Nova Singularity",
    "Inferno Whirl",
    "Aurora Borealis",
    "Moonlit Blade",
    "Serpent's Venom",
    "Divine Intervention",
    "Tempest Torrent",
    "Hellfire",
    "Flarestrike",
    "Acid Rain",
    "Rapid Thunder",
    "Quakeblast",
    "Blade of the Damned",
    "Chaos Storm",
    "Voidfire",
    "Starfall",
    "Frostbite",
    "Venomstrike",
    "Tsunami",
    "Dark Eclipse",
    "Astral Barrage",
    "Magnetic Pulse",
    "Abyssal Surge",
    "Radiant Fury",
    "Eternal Torment",
    "Necromantic Rebirth",
    "Thornstrike",
    "Plague of Shadows",
    "Eclipsed Soul",
    "Inferno's Fury",
    "Glacial Wrath",
    "Ethereal Cascade",
    "Elemental Quake"];
	
	return $random_key = $attackNames[array_rand($attackNames)];
}


function generateName()
{
$animals = [
    "Aeliana",
    "Thrain",
    "Lyria",
    "Kaelin",
    "Elowen",
    "Darian",
    "Auric",
    "Eldric",
    "Sylas",
    "Cassia",
    "Gavric",
    "Isolde",
    "Branwen",
    "Eldarion",
    "Thalassa",
    "Valandor",
    "Elara",
    "Eirik",
    "Nariel",
    "Eowyn",
    "Gavric",
    "Thalindra",
    "Raelin",
    "Caelia",
    "Aurelia",
    "Aelar",
    "Thrain",
    "Lyria",
    "Kaelin",
    "Elowen",
    "Darian",
    "Auric",
    "Eldric",
    "Sylas",
    "Cassia",
    "Gavric",
    "Isolde",
    "Branwen",
    "Eldarion",
    "Thalassa",
    "Valandor",
    "Elara",
    "Eirik",
    "Nariel",
    "Eowyn",
    "Gavric",
    "Thalindra",
    "Raelin",
    "Caelia",
    "Aurelia",
    "Aelar",
    "Thrain",
    "Lyria",
    "Kaelin",
    "Elowen",
    "Darian",
    "Auric",
    "Eldric",
    "Sylas",
    "Cassia",
    "Gavric",
    "Isolde",
    "Branwen",
    "Eldarion",
    "Thalassa",
    "Valandor",
    "Elara",
    "Eirik",
    "Nariel",
    "Eowyn",
    "Gavric",
    "Thalindra",
    "Raelin",
    "Caelia",
    "Aurelia",
    "Aelar",
    "Thrain",
    "Lyria",
    "Kaelin",
    "Elowen",
    "Darian",
    "Auric",
    "Eldric",
    "Sylas",
    "Cassia",
    "Gavric",
    "Isolde",
    "Branwen",
    "Eldarion",
    "Thalassa",
    "Valandor",
    "Elara",
    "Eirik",
    "Nariel",
    "Eowyn",
    "Gavric",
    "Thalindra",
    "Raelin",
    "Caelia",
    "Aurelia",
    "Aelar",
    "Thrain",
    "Lyria",
    "Kaelin",
    "Elowen",
    "Darian",
    "Auric",
    "Eldric",
    "Sylas",
    "Cassia",
    "Gavric",
    "Isolde",
    "Branwen",
    "Eldarion",
    "Thalassa",
    "Valandor",
    "Elara",
    "Eirik",
    "Nariel",
    "Eowyn",
    "Gavric",
    "Thalindra",
    "Raelin",
    "Caelia",
    "Aurelia",
    "Aelar",
    "Thrain",
    "Lyria",
    "Kaelin",
    "Elowen",
    "Darian",
    "Auric",
    "Eldric",
    "Sylas",
    "Cassia",
    "Gavric",
    "Isolde",
    "Branwen",
    "Eldarion",
    "Thalassa",
    "Valandor",
    "Elara",
    "Eirik",
    "Nariel",
    "Eowyn",
    "Gavric",
    "Thalindra",
    "Raelin",
    "Caelia",
    "Aurelia",
    "Aelar",
];

$surnames = [
    "Ironforge",
    "Stormrider",
    "Shadowthorn",
    "Frostwind",
    "Silverbrook",
    "Fireforge",
    "Ravenshadow",
    "Thornblade",
    "Starcaller",
    "Stonebreaker",
    "Bloodfang",
    "Dragonheart",
    "Nightshade",
    "Swiftarrow",
    "Grimhammer",
    "Lightbringer",
    "Wildmoon",
    "Thunderstrike",
    "Ironfist",
    "Stormweaver",
    "Flameheart",
    "Blackthorn",
    "Frostfall",
    "Moonshadow",
    "Winterfell",
    "Emberflame",
    "Darkrider",
    "Silverthorn",
    "Firewalker",
    "Shadowbane",
    "Thunderforge",
    "Grimshadow",
    "Nightfall",
    "Starcaster",
    "Ironclaw",
    "Stoneheart",
    "Bloodmoon",
    "Frostbeard",
    "Emberblade",
    "Windrider",
    "Goldenshield",
    "Deathweaver",
    "Ashenrider",
    "Stormcaller",
    "Thornhammer",
    "Dragonbane",
    "Shadowfist",
    "Frostwhisper",
    "Flameforge",
    "Thunderclaw",
    "Darkmoon",
    "Lightbringer",
    "Bloodfang",
    "Silverbrook",
    "Starfall",
    "Ironforge",
    "Fireheart",
    "Ravenshadow",
    "Swiftarrow",
    "Stonebreaker",
    "Emberflame",
    "Winterfell",
    "Stormrider",
    "Shadowthorn",
    "Moonshadow",
    "Nightshade",
    "Frostwind",
    "Blackthorn",
    "Wildmoon",
    "Frostfall",
    "Flameheart",
    "Thunderstrike",
    "Ironfist",
    "Firewalker",
    "Silverthorn",
    "Thunderforge",
    "Grimshadow",
    "Starcaster",
    "Darkrider",
    "Stoneheart",
    "Frostbeard",
    "Emberblade",
    "Windrider",
    "Goldenshield",
    "Deathweaver",
    "Ashenrider",
    "Stormcaller",
    "Thornhammer",
    "Dragonbane",
    "Shadowfist",
    "Frostwhisper",
    "Flameforge",
    "Thunderclaw",
    "Darkmoon",
    "Lightbringer",
    "Bloodfang",
    "Silverbrook",
    "Starfall",
    "Ironforge",
    "Fireheart",
];

	$random_key = array_rand($animals);

	$picked_name = $animals[$random_key];
	
	$random_key2 = array_rand($surnames);

	$picked_name2 = $surnames[$random_key2];	
	

	$random_number = rand(1000, 9999);

	$name = $picked_name." ".$picked_name2. '-' . $random_number;

	return $name;
}

function slugifychar($text,$divider = '-')
{
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}
function trans()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 12; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

function createbot($level)
{
	


$damages = array();
$emblems = array();
$avatar = array();
$weapons = array();



$q1 = mysql_query_md("SELECT * FROM tbl_damage");
while($rowxxx1 = mysql_fetch_md_assoc($q1)) 
{
	$damages[$rowxxx1['type']] = ($rowxxx1['type']);
}

$q2 = mysql_query_md("SELECT * FROM tbl_weapons WHERE is_free = 1");
while($rowxxx2 = mysql_fetch_md_assoc($q2)) {
	$weapons[$rowxxx2['slug']] = ($rowxxx2['slug']);
}

$q3 = mysql_query_md("SELECT * FROM tbl_emblem");
while($rowxxx3 = mysql_fetch_md_assoc($q3)) {
	$emblems[$rowxxx3['id']] = ($rowxxx3['id']);
}


$dirname = $_SERVER['DOCUMENT_ROOT']."/actors/";
$images = glob($dirname."*.png");
$countavatar = 0;
foreach($images as $image) {
	$countavatar++;
	
    $finalimg = str_replace($dirname,"",$image);
	$avatar[$finalimg] = $avatar;
	
}


$_POST['pokename'] = ucfirst(generateName());

$_POST['avatar'] = array_rand($avatar);
$_POST['weapon'] = array_rand($weapons);

		$pokeid = rand(1,1);
		
		$rate = rand(7,10);
		
		$added = $level * $rate;
		
		$rate_hp = ($rate + rand(5, 25)) * $level;
		
		
		$attack = rand(130,150) + $added;
		$defense = rand(15,35) + $added;
		$hp = rand(600,800) + 1000 + $rate_hp;
		$user = 360;
		$speed = rand(10,40) + $level;
		$critical = rand(5,30) + $level;
		$accuracy = rand(5,30) + $level;
		
		
		
		
		$hash = "000"."-".trans();
		$pokeclass = array();
		
		
		

	$pokename = $_POST['pokename'];
	$front = ($_POST['avatar']);
	$back = ($_POST['avatar']);
	$main = ($_POST['avatar']);
	$emblem = array_rand($emblems);
	$weapon = ($_POST['weapon']);

		
	$pokeclassfin = array_rand($damages);
		


echo $pokeadd = "INSERT INTO tbl_pokemon_users SET weapon='$weapon',emblem='$emblem',user='$user',pokemon='$pokeid',attack='$attack',defense='$defense',hp='$hp',speed='$speed',critical='$critical',accuracy='$accuracy',level='$level',rate='$rate',front='$front',back='$back',main='$main',pokename='$pokename',pokeclass='$pokeclassfin',hash='$hash'";

	

$dmg1 = rand(200,300);
$dmg2 = rand(100,120);
$dmg3 = rand(80,90);


$element1 = array_rand($damages);
$element2 = array_rand($damages);
$element3 = array_rand($damages);


$title1 = ucwords(addslashes($element1)." ".generateAttack());
$title2 = ucwords(addslashes($element2)." ".generateAttack());
$title3 = ucwords(addslashes($element3)." ".generateAttack());


$acc1 = rand(50,90);
$acc2 = rand(50,90);
$acc3 = rand(50,90);


$ident1 = slugifychar($title1.rand());
$ident2 = slugifychar($title2.rand());
$ident3 = slugifychar($title3.rand());

echo "$pokename <br>";
echo "$element1 $element2 $element3 <br>";
echo "$title1 // $title2 // $title3";


mysql_query_md("INSERT INTO tbl_movesreindex SET typebattle='$element1',power='$dmg1',title='$title1',accuracy='$acc1',pokehash='$hash',activate=1,identifier='$ident1'");
mysql_query_md("INSERT INTO tbl_movesreindex SET typebattle='$element2',power='$dmg2',title='$title2',accuracy='$acc2',pokehash='$hash',activate=1,identifier='$ident2'");
mysql_query_md("INSERT INTO tbl_movesreindex SET typebattle='$element3',power='$dmg3',title='$title3',accuracy='$acc3',pokehash='$hash',activate=1,identifier='$ident3'");
mysql_query_md($pokeadd);	
	
}



?>
