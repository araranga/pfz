<?php
function randomcharuser(){

$dirname = "../actors/";
$images = glob($dirname."*.png");
$countavatar = 0;
foreach($images as $image) {
	$countavatar++;
	
    $finalimg = str_replace($dirname,"",$image);
	$character[$finalimg] = $finalimg;

}

return array_rand($character);	
	
}

function getrandoweaponuser(){

$q = mysql_query_md("SELECT * FROM tbl_weapons ORDER BY RAND() LIMIT 1");
$row=mysql_fetch_md_assoc($q);


return $row['slug'];
	
}	

function getrandoemblemuser(){

$q = mysql_query_md("SELECT * FROM tbl_emblem ORDER BY RAND() LIMIT 1");
$row=mysql_fetch_md_assoc($q);


return $row['id'];
	
}	



function getrandomelement(){

$q = mysql_query_md("SELECT * FROM tbl_damage");
$random = [];
while($row=mysql_fetch_md_assoc($q)){

	$random[$row['type']] = $row['type'];
	
}	

$return = [];
$return['armor'] = array_rand($random);


// Get 3 unique random keys from the array
$random_keys = array_rand($random, 3);

// Fetch the corresponding values (skills)
$random_skills = array_map(function($key) use ($random) {
    return $random[$key];
}, $random_keys);

$c = 0;
foreach($random_skills as $d){
	$c++;
	$return['skill'.$c] = randomskillsplayer($d);
	$return['type'.$c] = $d;
}

return $return;

}


function randomskillsplayer($type){
	
$skills = [];

$skills['normal'] = [
    "Power Strike", "Quick Jab", "Slam Attack", "Focus Punch", "Heavy Blow", 
    "Rapid Strike", "Basic Thrust", "Precision Slash", "Crushing Chop", 
    "Ground Pound", "Blade Dance", "Flurry Attack", "Swift Kick", 
    "Twin Strike", "Shield Bash", "Counter Punch", "Arm Breaker", 
    "Uppercut", "Roundhouse", "Shattering Smash", "Frenzied Assault", 
    "Piercing Blow", "Breaker Strike", "Meteor Punch", "Gale Strike", 
    "Battering Ram", "Guard Breaker", "Crescent Slash", "Savage Slash", 
    "Overhead Strike", "Chain Strike", "Master’s Guard", "Supreme Focus", 
    "Blade Wave", "Momentum Surge", "Spiral Strike", "Critical Barrage", 
    "Spinning Kick", "Fist Barrage", "Power Reversal", "Warrior’s Pride", 
    "Savage Roar", "Shield Crush", "Energy Transfer", "Ultimate Smash", 
    "Berserker Break", "Perfect Parry", "Deadly Counter", "Supreme Willpower", 
    "Limit Breaker", "Staggering Shout", "Stunning Fist", "Thunderous Clap", 
    "Ground Slam", "Momentum Shock", "Blinding Sweep", "Grapple Throw", 
    "Crowd Push", "Push Back", "Whirlwind Strike", "Wide Slash", 
    "Disorienting Strike", "Shatter Defense", "Group Parry", "Bind Grip", 
    "Neutral Blade", "Unarmed Mastery", "Adaptive Fighter", "Vanguard Tactics", 
    "Martial Spirit", "Essence of Strength", "Heroic Charge", "Fundamental Fury", 
    "Origin Strike", "Normalized Power"
];
$skills['fighting'] = [
    "Iron Fist", "Roundhouse Kick", "Dragon Punch", "Tiger Strike", "Rapid Jabs", 
    "Flying Knee", "Elbow Smash", "Spinning Uppercut", "Haymaker", 
    "Sweeping Kick", "Bone Breaker", "Shattering Fist", "Crane Kick", 
    "Focus Strike", "Charging Tackle", "Leaping Smash", "Martial Barrage", 
    "Cross Counter", "Tornado Kick", "Hammer Fist", "Exploding Palm", 
    "Lion’s Pounce", "Wolf Fang Strike", "Eagle Dive", "Double Elbow", 
    "Savage Grapple", "Seismic Slam", "Iron Palm", "Blazing Knuckle", 
    "Thunder Strike", "Shockwave Fist", "Rolling Kick", "Brutal Takedown", 
    "Impact Strike", "Crushing Grip", "Whirlwind Punch", "Overpowering Blow", 
    "Flurry of Blows", "Comet Strike", "Crescent Kick", "Unstoppable Lunge", 
    "Momentum Smash", "Reckless Charge", "Guardian Strike", "Skyward Fist", 
    "Tectonic Smash", "Phoenix Strike", "Dragon Claw", "Battle Fury", 
    "Warrior’s Roar", "Rampaging Fist", "Final Blow", "Heavenly Strike", 
    "Doomfist Slam", "Gravity Punch", "Ultimate Grapple", "Power Throw", 
    "Submission Hold", "Takedown Strike", "Bear Claw Swipe", "Diving Tackle", 
    "Rising Uppercut", "Pressure Point Strike", "Deadly Jab", "Crippling Smash", 
    "Blade-Hand Strike", "Combo Barrage", "Wolf’s Fang Slash", "Spiral Smash", 
    "Breaker’s Impact", "Twin Dragon Fists", "Feral Assault", "Relentless Pummel", 
    "Hurricane Kick", "Chained Strikes", "Blitz Punch", "Thousand Fists", 
    "Earthbreaker", "Meteor Crash", "Frenzy Grapple", "War Dance", 
    "Shadow Step Strike", "Unyielding Charge", "Dragon Spirit", "Phoenix Dive", 
    "Skybreaker Kick", "Ironclad Rush", "Bloodied Knuckle", "Savage Blitz", 
    "Lionheart Strike", "Unrelenting Fury", "Shockwave Kick", "Landslide Tackle", 
    "Echoing Strike", "Shattering Palm", "Burning Fury", "Momentum Crush", 
    "Ferocious Roar", "Stonewall Slam", "Berserker Kick", "Dragon’s Fang", 
    "Beast Crusher", "Infinite Barrage", "Worldbreaker Punch"
];
$skills['flying'] = [
    "Sky Cutter", "Gale Strike", "Wind Slash", "Feather Barrage", "Hawk Dive", 
    "Aerial Sweep", "Wing Slash", "Storm Kick", "Soaring Strike", 
    "Tailwind Slice", "Wind Claw", "Gust Flurry", "Tornado Spin", 
    "Cyclone Wing", "Air Cutter", "Skyward Slash", "Cloudbreaker", 
    "Aerial Ace", "Eagle Dive", "Vortex Blade", "Updraft Kick", 
    "Hurricane Spin", "Falcon Punch", "Feather Storm", "Spiral Gust", 
    "Gale Rush", "Storm Breaker", "Diving Strike", "Sky Fury", 
    "Aerial Rampage", "Thunderous Wings", "Gust Barrage", "Razor Wind", 
    "Whirlwind Crash", "Tempest Strike", "Phoenix Ascent", "Wings of Fury", 
    "Soaring Claw", "Skyburst", "Cyclone Barrage", "Tornado Dive", 
    "Storm Dive", "Falcon Strike", "Hawk Talon Slash", "Highwind Blade", 
    "Skyward Barrage", "Crosswind Cutter", "Gale Force", "Wind Spiral", 
    "Updraft Slash", "Stormblade", "Aether Dive", "Windshear Claw", 
    "Jetstream Rush", "Bladed Feathers", "Heaven’s Descent", "Rising Gale", 
    "Whirlwind Kick", "Sky Splitter", "Cloud Dancer", "Aerial Tempest", 
    "Feather Slash", "Cyclone Thrust", "Stormfang Slash", "Tailwind Strike", 
    "Tornado Crush", "Skybreaker", "Aerial Blitz", "Windstorm Crash", 
    "Ascension Strike", "Swift Wings", "Windrider Slash", "Zephyr Dash", 
    "Hurricane Dash", "Updraft Fury", "Wingblade Fury", "Thunder Gale", 
    "Sonic Wing", "Vortex Dive", "Jetstream Cutter", "Gale Surge", 
    "Sky Piercer", "Razor Feathers", "Gliding Talons", "Spiral Wing", 
    "Typhoon Strike", "Sky Surge", "Aerial Onslaught", "Twisting Gust", 
    "Cyclone Edge", "Tempest Claw", "Feather Barrage", "Horizon Slash", 
    "Wind Reaver", "Cloudpiercer", "Hurricane Claw", "Aether Wings", 
    "Skyfall Impact", "Heavenly Gust", "Soaring Tempest", "Gale Spear", 
    "Stormflight", "Swift Gale Barrage", "Zephyr Strike", "Eagle's Edge", 
    "Upwind Surge", "Wings of Vortex", "Aether's Descent", "Skyborn Barrage"
];	
	

$skills['poison'] = [
    "Venomous Bite", "Toxic Spit", "Poison Cloud", "Noxious Blast", 
    "Paralytic Venom", "Corrosive Toxin", "Acid Spray", "Venom Slash", 
    "Neurotoxin", "Deathly Spores", "Snake Strike", "Contagion", 
    "Epidemic Burst", "Miasma Wave", "Virulent Strike", "Poison Dart", 
    "Toxic Barrage", "Blight Strike", "Venom Spray", "Crippling Toxin", 
    "Plague Touch", "Deadly Sting", "Shadow Venom", "Toxic Explosion", 
    "Bane Slash", "Toxic Eruption", "Corruption Wave", "Serrated Fang", 
    "Poison Rain", "Lingering Miasma", "Fetid Slash", "Pestilent Strike", 
    "Plague Arrow", "Venomous Claw", "Leeching Venom", "Spreading Blight", 
    "Tainted Blade", "Rotting Touch", "Viral Sting", "Decay Lash", 
    "Putrid Claw", "Toxic Wave", "Poisonous Fang", "Blighted Arrow", 
    "Sludge Strike", "Venom Surge", "Rotten Bite", "Septic Strike", 
    "Plague Fang", "Noxious Spikes", "Lethal Venom", "Toxic Spray", 
    "Oozing Blade", "Venom Barrage", "Bane Lash", "Dark Toxin", 
    "Poison Fang", "Decay Wave", "Toxic Scythe", "Blight Claws", 
    "Corrupting Venom", "Epidemic Slash", "Poison Nova", "Infectious Strike", 
    "Pustulent Bite", "Fetid Blade", "Toxic Strike", "Seeping Poison", 
    "Blight Eruption", "Deadly Miasma", "Poison Lance", "Venom Arc", 
    "Putrid Wave", "Contaminated Slash", "Festering Wound", "Miasmic Slash", 
    "Poison Thorn", "Rotting Fang", "Plague Strike", "Dark Corruption", 
    "Lingering Decay", "Pestilence Wave", "Corrosive Bite", "Poison Lash", 
    "Toxic Reaver", "Venom Fang", "Blight Burst", "Rot Arrow", 
    "Corrupt Scythe", "Poison Barrage", "Decay Stab", "Viral Fang", 
    "Septic Spit", "Venomous Wave", "Foul Toxin", "Putrid Strike", 
    "Poison Surge", "Festering Claw", "Noxious Spike", "Contaminated Bite"
];	

$skills['ground'] = [
    "Earthquake", "Sand Tomb", "Mud Slap", "Magnitude", "Earth Power", 
    "Stone Edge", "Mud Bomb", "Sandstorm", "Dig", "Fissure", 
    "Mud Shot", "Rock Slide", "Quicksand", "Land Crush", "Sand Blast", 
    "Terra Force", "Rock Smash", "Ground Crush", "Mudslide", "Seismic Toss", 
    "Earth Slash", "Tectonic Rift", "Ground Barrage", "Rock Barrage", "Earth Quake Barrage", 
    "Boulder Barrage", "Fissure Barrage", "Mudstrike", "Stone Barrage", "Mudslide Barrage", 
    "Earth Barrage", "Rock Wave", "Tectonic Barrage", "Rock Pummel", "Land Barrage", 
    "Earth Spike", "Tectonic Surge", "Seismic Surge", "Stone Barrage", "Mud Quake", 
    "Boulder Strike", "Groundwave", "Earth Barrage Barrage", "Mud Crash", "Stone Strike", 
    "Ground Pound", "Mud Storm", "Landquake", "Seismic Barrage", "Boulder Smash", 
    "Stone Claw", "Rockfall", "Seismic Barrage", "Rockbreaker", "Mudstream", 
    "Earth Spin", "Earthquake Barrage", "Tectonic Pulse", "Stone Crumble", "Rock Barrage", 
    "Seismic Smash", "Earth Barrage", "Mudstrike Barrage", "Sand Barrage", "Rock Slicer", 
    "Ground Vortex", "Earthstorm", "Mud Slam", "Boulder Smash", "Earthshatter", 
    "Seismic Surge", "Rockquake", "Ground Fissure", "Boulder Crash", "Quicksand Barrage", 
    "Ground Fissure", "Stone Crunch", "Earth Crash", "Mudstrike", "Earth Barrage", 
    "Rockshatter", "Mudquake", "Stone Force", "Earth Geyser", "Rock Eruption", 
    "Tectonic Barrage", "Mud Barrage", "Stone Pummel", "Earthquake Crash", "Tectonic Wave", 
    "Seismic Slam", "Groundquakes", "Earthquake Slash", "Ground Spike", "Mud Burst", 
    "Sandstorm Barrage", "Mudstrike Barrage", "Seismic Attack", "Stone Barrage Barrage", "Tectonic Claw"
];

$skills['rock'] = [
    "Stone Edge", "Rock Slide", "Stealth Rock", "Rock Throw", "Rock Smash", 
    "Rock Blast", "Head Smash", "Ancient Power", "Stone Barrage", "Smack Down", 
    "Power Gem", "Rock Tomb", "Stone Hammer", "Rock Punch", "Stone Rush", 
    "Rolling Rock", "Rockquake", "Boulder Bash", "Rock Barrage", "Rock Storm", 
    "Granite Crush", "Rock Barrage Barrage", "Stone Barrage", "Shale Barrage", "Petrify", 
    "Stone Pummel", "Rock Crusher", "Crag Barrage", "Stony Barrage", "Stone Slice", 
    "Quarry Barrage", "Rolling Boulder", "Shattering Smash", "Cave Barrage", "Rocktide", 
    "Crush Stone", "Pebble Barrage", "Crag Rush", "Rock Digger", "Shatter Stone", 
    "Stalagmite Barrage", "Rock Throw Barrage", "Stone Stab", "Granite Barrage", "Boulder Toss", 
    "Stone Barrage Barrage", "Pebble Crush", "Rock Eruption", "Boulder Shield", "Granite Break", 
    "Stone Shield", "Shale Smash", "Earth Crush", "Rock Slicer", "Granite Bash", 
    "Stonebreak", "Petrified Barrage", "Boulder Bludgeon", "Stone Piercer", "Rock Barrage Smash", 
    "Eruption Barrage", "Stone Strike", "Rubble Strike", "Rockwave", "Boulder Barrage Barrage", 
    "Rock Dash", "Stony Crash", "Stone Smite", "Granite Throw", "Boulder Barrage Barrage", 
    "Pebble Barrage", "Rock Toss Barrage", "Granite Slash", "Boulder Strike", "Stone Wall", 
    "Rock Barrage", "Gravel Barrage", "Boulder Barrage Barrage", "Rock Tumult", "Stone Slash", 
    "Granite Storm", "Shale Barrage", "Petrified Barrage", "Rock Slash", "Stoneflame", 
    "Boulder Rush", "Shale Rush", "Rock Barrage Smash", "Stone Barrage Barrage", "Stone Maul", 
    "Crag Quake", "Granite Burst", "Rockstorm", "Earthquake Smash", "Boulder Storm", 
    "Shale Crash", "Stone Barrage Barrage", "Shatter Barrage", "Quarry Crumble", "Stony Smash"
];

$skills['bug'] = [
    "X-Scissor", "Bug Buzz", "Leech Life", "Toxic Thread", "Silver Wind", 
    "Pin Missile", "Fell Stinger", "Bug Bite", "String Shot", "Sticky Web", 
    "Infestation", "Bug Barrage", "Toxic Sting", "Swarm Strike", "Bug Slap", 
    "Pin Barrage", "Beetle Bash", "Caterpillar Barrage", "Bugspray", "Lacerate", 
    "Insect Barrage", "Thread Lash", "Sting Barrage", "Swarm Fury", "Bug Slash", 
    "Pin Whip", "Stinger Strike", "Web Barrage", "Exoskeleton Smash", "Bug Crush", 
    "Cocoon Barrage", "Spore Barrage", "Infestation Barrage", "Pincer Barrage", "Insect Barrage", 
    "Vine Strike", "Bug Squash", "Lash Barrage", "Thread Barrage", "Swarm Attack", 
    "Pollen Barrage", "Caterpillar Crush", "Spiderweb Barrage", "Flying Barrage", "Bug Slam", 
    "Honey Barrage", "Insect Swarm", "Bug Barrage Barrage", "Swarm Barrage", "Beetle Charge", 
    "Venom Sting", "Web Lash", "Caterpillar Barrage", "Beetle Barrage", "Stinger Barrage", 
    "Exoskeleton Barrage", "Silk Barrage", "Bug Burn", "Spine Barrage", "Sticky Barrage", 
    "Bug Punch", "Spider Strike", "Scorpion Sting", "Toxic Barrage", "Silk Spin", 
    "Insect Slash", "Bug Smash", "Caterpillar Lash", "Beetle Punch", "Pincer Strike", 
    "Venomous Strike", "Thread Barrage", "Bug Impact", "Web Barrage", "Bug Wave", 
    "Sticky Barrage", "Venom Slash", "Exoskeleton Barrage", "Swarm Slash", "Caterpillar Bite", 
    "Spider Barrage", "Insect Barrage Barrage", "Bugweb Barrage", "Exoskeleton Claw", "Venom Barrage", 
    "Bee Strike", "Insect Slash", "Beetle Charge", "Scorpion Barrage", "Swarm Barrage", 
    "Sticky Thread", "Thread Barrage Barrage", "Swarm Barrage", "Bug Barrage Barrage", "Insect Venom", 
    "Bug Maul", "Spider Web", "Pollen Strike", "Scorpion Barrage", "Toxic Web"
];

$skills['ghost'] = [
    "Phantom Slash", "Shadow Grasp", "Ethereal Blade", "Soul Drain", "Haunting Cry", 
    "Spectral Claw", "Wraith Strike", "Poltergeist Smash", "Spirit Rush", "Ghostly Howl", 
    "Corpse Touch", "Phantom Burst", "Deathly Claw", "Soul Ripper", "Shadow Pulse", 
    "Eerie Bite", "Dark Whisper", "Haunting Echo", "Phantom Stab", "Grave Slash", 
    "Specter Surge", "Wraith Barrage", "Ghostfire", "Cursed Claw", "Soul Shatter", 
    "Phantasmal Spike", "Spirit Nova", "Nether Blade", "Nightmare Touch", "Soul Spike", 
    "Death Grasp", "Ethereal Wave", "Shadow Swipe", "Poltergeist Spin", "Wailing Slash", 
    "Spirit Barrage", "Dark Mist", "Haunted Flame", "Nether Stab", "Cursed Bite", 
    "Soulbound Strike", "Grim Claw", "Spectral Edge", "Wraithstorm", "Nightshade Slash", 
    "Nether Burst", "Death Pulse", "Soul Lash", "Shadowfang Slash", "Haunting Wave", 
    "Eerie Stab", "Phantom Crush", "Dark Flurry", "Spirit Fang", "Shadow Rift", 
    "Poltergeist Barrage", "Soul Harvester", "Nether Nova", "Ethereal Surge", 
    "Wailing Claw", "Spectral Fang", "Grim Fang", "Phantasmal Barrage", "Dark Grip", 
    "Shadowfire", "Haunted Blade", "Cursed Pulse", "Soulshard Spike", "Ghost Fang", 
    "Spirit Wave", "Wraith Claw", "Phantom Edge", "Nether Grip", "Dark Slash", 
    "Haunting Stab", "Eerie Strike", "Spectral Fire", "Shadowblaze", "Nether Flare", 
    "Deathly Pulse", "Poltergeist Wave", "Ethereal Lash", "Gravefire", "Soulburst", 
    "Wraithblade", "Spirit Swipe", "Phantom Flurry", "Cursed Edge", "Nether Slash", 
    "Ghostfire Barrage", "Haunted Lash", "Specter Lash", "Dark Nova", "Soulwave", 
    "Nightmare Claw", "Wraithfire", "Shadowflare", "Ethereal Stab", "Grimwave", 
    "Shadow Fang", "Spiritflame", "Dark Howl", "Ghostfire Pulse", "Soulbind Slash", 
    "Netherbite", "Ethereal Barrage", "Darkfang Claw", "Spiritfire", "Wraithstrike", 
    "Haunting Fang", "Spectral Burst", "Grave Spike", "Soul Lash", "Nether Spin", 
    "Death Rift", "Haunted Pulse", "Dark Mist Slash", "Shadow Rift Slash", "Phantom Blaze", 
    "Cursed Flurry", "Specter Blaze", "Eerie Pulse", "Spiritfire Slash", "Nightmare Edge", 
    "Soulbound Claw", "Wraithblaze", "Netherstorm", "Phantomstorm", "Shadowfire Edge", 
    "Grave Lash", "Haunting Flame", "Ethereal Howl", "Poltergeist Surge", "Specter Flare"
];

	
$skills['steel'] = [
    "Iron Slash", "Steel Blade", "Metal Burst", "Armor Crush", "Plated Jab", 
    "Iron Spike", "Steel Barrage", "Metal Storm", "Chrome Claw", "Plated Stomp", 
    "Titanium Smash", "Heavy Slam", "Steel Impact", "Iron Fist", "Metal Grip", 
    "Forged Strike", "Steel Rain", "Iron Claw", "Metal Wave", "Steel Jab", 
    "Razor Edge", "Titanium Fang", "Metallic Surge", "Plated Strike", "Iron Barrage", 
    "Steel Shard", "Iron Impact", "Metal Fang", "Steelstorm", "Plated Flurry", 
    "Chrome Fang", "Heavy Claw", "Titanium Surge", "Metal Slam", "Iron Grip", 
    "Razor Strike", "Steel Scythe", "Armor Crush", "Forged Barrage", "Iron Spin", 
    "Steel Fang", "Titanium Edge", "Plated Barrage", "Metal Burst", "Ironstorm", 
    "Steel Lash", "Heavy Slash", "Chrome Blade", "Metal Slice", "Steel Shatter", 
    "Iron Crush", "Metal Flare", "Steel Crush", "Plated Impact", "Titanium Swipe", 
    "Steel Storm", "Heavy Shard", "Iron Fist", "Plated Slash", "Metal Shard", 
    "Forged Spin", "Steel Spike", "Titanium Blade", "Metal Shatter", "Plated Slice", 
    "Chrome Scythe", "Heavy Smash", "Metal Barrage", "Steel Rift", "Plated Surge", 
    "Iron Strike", "Razor Slash", "Titanium Crush", "Steel Spin", "Forged Fang", 
    "Plated Stab", "Steel Shard", "Metal Claw", "Titanium Fang", "Steel Jab", 
    "Heavy Edge", "Iron Shatter", "Metal Lash", "Razor Fang", "Plated Fang", 
    "Titanium Claw", "Steel Blaze", "Iron Barrage", "Chrome Storm", "Forged Spike", 
    "Heavy Lash", "Plated Smash", "Steelblade Flurry", "Titanium Barrage", 
    "Razor Spin", "Metal Fang", "Steel Stab", "Plated Edge", "Iron Slam", 
    "Heavy Wave", "Chrome Spin", "Steelstorm Crush", "Titanium Claw", "Iron Spike", 
    "Forged Grip", "Plated Lash", "Heavy Slash", "Steel Flare", "Titanium Slice", 
    "Metal Edge", "Plated Flurry", "Forged Crush", "Steel Spike", "Titanium Spin"
];	
	
$skills['fire'] = [
    "Fireball", "Flame Wave", "Inferno Blast", "Blazing Slash", "Heat Wave", 
    "Molten Strike", "Flame Barrage", "Lava Burst", "Burning Stab", "Fire Spin", 
    "Phoenix Wing", "Incinerate", "Flame Spike", "Blaze Kick", "Pyro Burst", 
    "Ember Rain", "Infernal Slash", "Flare Strike", "Molten Lash", "Firestorm", 
    "Lava Bomb", "Flame Edge", "Firelash", "Burning Slash", "Ignite", "Flame Blade", 
    "Blazing Impact", "Lava Stomp", "Scorch Wave", "Fire Nova", "Molten Flare", 
    "Heat Strike", "Infernal Flare", "Pyro Strike", "Phoenix Dive", "Blaze Slash", 
    "Molten Barrage", "Lava Wave", "Inferno Slash", "Fire Claw", "Ember Flurry", 
    "Flare Nova", "Flame Spin", "Lava Slash", "Blazing Wave", "Molten Spike", 
    "Infernal Grip", "Heat Lash", "Blazing Spike", "Flare Crush", "Burning Fang", 
    "Molten Edge", "Inferno Burst", "Flame Shard", "Phoenix Flame", "Scorch Lash", 
    "Ignition Slash", "Heat Claw", "Lava Fang", "Fire Burst", "Flame Barrage", 
    "Blaze Fang", "Molten Scythe", "Infernal Stab", "Scorch Flare", "Ember Edge", 
    "Burning Grip", "Molten Flame", "Flame Barrage", "Firestorm Spike", "Blaze Barrage", 
    "Inferno Wave", "Lava Crush", "Flameburst", "Phoenix Strike", "Scorch Slash"
];	
	

$skills['water'] = [
    "Water Gun", "Aqua Jet", "Hydro Pump", "Surf", "Water Pulse", 
    "Bubble Beam", "Aqua Tail", "Tidal Wave", "Water Spout", "Hydro Cannon", 
    "Rain Dance", "Waterfall", "Water Slash", "Ice Beam", "Liquid Rush", 
    "Tsunami", "Water Blast", "Vortex Wave", "Splash Bomb", "Aquatic Surge", 
    "Frost Surge", "Aqua Burst", "Bubble Shield", "Hydration Burst", "Water Strike", 
    "Frozen Stream", "Splash Strike", "Water Cutter", "Stream Cutter", "Ocean Rush", 
    "Tidal Blast", "Flood Torrent", "Aqua Wave", "Rainstorm", "Hydro Blast", 
    "Frozen Torrent", "Vapor Burst", "Mist Wave", "Aqua Vortex", "Flood Strike", 
    "Surging Torrent", "Water Blast", "Tidal Surge", "Hydro Sweep", "Wave Cutter", 
    "Water Lash", "Shower Beam", "Rainfall", "Hydro Slash", "Frozen Surge", 
    "Aqua Hammer", "Flood Wave", "Oceanic Claw", "Storm Break", "Mist Slash", 
    "Aqua Geyser", "Surge Strike", "Torrent Barrage", "Water Storm", "Frostbite", 
    "Icy Surge", "Mist Bomb", "Bubble Barrage", "Ocean Slash", "Aqua Pulse", 
    "Water Cut", "Tempest Wave", "Frozen Claw", "Raging Wave", "Aquatic Shield", 
    "Tsunami Strike", "Vapor Wave", "Hydro Smash", "Rain Wave", "Frozen Vortex", 
    "Water Eruption", "Tidal Pulse", "Bubble Crash", "Tsunami Barrage", "Icy Barrage", 
    "Shower Surge", "Splash Wave", "Tidal Torrent", "Hydro Break", "Aqua Cutter", 
    "Water Barrage", "Splash Barrage", "Aquatic Pulse", "Frost Barrage", "Vapor Strike", 
    "Raging Waters", "Water Trident", "Tidal Burst", "Mist Strike", "Aqua Beam", 
    "Hydroquake", "Waterflare", "Frozen Wave", "Mist Bomb", "Splash Storm", 
    "Whirlpool Barrage", "Aqua Barrage", "Hydration Strike", "Frozen Jet", "Tidal Fury", 
    "Aqua Storm", "Water Barrage", "Hydro Grenade", "Flood Cutter", "Aqua Blast", 
    "Water Bomb", "Mist Barrage", "Ice Rush", "Frozen Flow", "Wave Strike"
];
$skills['grass'] = [
    "Vine Whip", "Leaf Storm", "Solar Beam", "Energy Ball", "Razor Leaf", 
    "Petal Dance", "Grass Knot", "Synthesis", "Leech Seed", "Grassy Terrain", 
    "Seed Bomb", "Magical Leaf", "Nature's Wrath", "Wood Hammer", "Grass Pummel", 
    "Leaf Blade", "Flower Fury", "Bramble Surge", "Wood Slash", "Spore Burst", 
    "Grass Cutter", "Thorn Barrage", "Leaf Barrage", "Razor Vine", "Bloom Burst", 
    "Poison Ivy", "Nature's Fury", "Leaf Shower", "Green Gale", "Herb Barrage", 
    "Petal Barrage", "Thorn Strike", "Bramble Lash", "Lumber Strike", "Seed Barrage", 
    "Foliage Barrage", "Leaf Surge", "Creeping Vines", "Sap Strike", "Wooden Punch", 
    "Branch Barrage", "Forest Fire", "Vine Crush", "Sprout Smash", "Tangle Lash", 
    "Bloom Blast", "Tropical Torrent", "Thorn Claw", "Spreading Vines", "Rogue Vine", 
    "Thornstrike", "Tropical Fury", "Petal Stab", "Wild Bloom", "Flower Crush", 
    "Bramble Bash", "Nature's Smite", "Floral Barrage", "Sap Barrage", "Tree Slam", 
    "Vine Lash", "Flower Slam", "Creeper's Barrage", "Leaf Sickle", "Pollen Barrage", 
    "Wooden Smash", "Vine Lash", "Thorn Whip", "Sap Barrage", "Tangle Barrage", 
    "Branch Bash", "Leaf Grenade", "Forest Quake", "Wooden Barrage", "Blossom Strike", 
    "Thornstrike", "Petal Blast", "Vine Tackle", "Bloom Surge", "Nature's Vengeance", 
    "Grassy Barrage", "Spore Bomb", "Thorn Beam", "Wild Nature", "Leaf Crash", 
    "Floral Smash", "Vine Whip Barrage", "Herb Tackle", "Tropical Burst", "Plant Barrage", 
    "Bramble Barrage", "Floral Punch", "Vine Root", "Nature's Fury", "Tangle Slash", 
    "Branch Fist", "Sap Surge", "Flower Slash", "Vine Grip", "Wooden Barrage"
];
	
$skills['electric'] = [
    "Thunderbolt", "Spark", "Thunder Strike", "Volt Tackle", "Thunder Wave", 
    "Discharge", "Electro Ball", "Shockwave", "Thunder Punch", "Wild Charge", 
    "Charge Beam", "Zap Cannon", "Electro Shock", "Lightning Strike", "Spark Shock", 
    "Thunder Crash", "Volt Storm", "Spark Surge", "Electro Surge", "Static Charge", 
    "Lightning Bolt", "Volt Pulse", "Thunderstorm", "Electric Claw", "Zap Surge", 
    "Charged Slam", "Energy Discharge", "Static Shock", "Electric Barrage", "Lightning Barrage", 
    "Thunder Shock", "Volt Charge", "Power Surge", "Thunder Ball", "Electromagnetic Pulse", 
    "Shock Strike", "Flash Bolt", "Voltage Slash", "Electric Spike", "Electro Burst", 
    "Electric Strike", "Thunder Smash", "Volt Slam", "Zapped Strike", "Electric Surge", 
    "Spark Barrage", "Static Blast", "Charge Pulse", "Electric Burst", "Thunder Slash", 
    "Volt Surge", "Electro Cut", "Voltage Kick", "Lightning Barrage", "Power Burst", 
    "Zapping Strike", "Thunderous Strike", "Shock Barrage", "Electro Spin", "Lightning Barrage", 
    "Spark Barrage", "Charged Beam", "Lightning Bolt Barrage", "Volt Slash", "Electro Blast", 
    "Thunderstrike Barrage", "Electric Blade", "Volt Nova", "Electric Wave", "Flash Strike", 
    "Shocking Surge", "Thunderstrike Smash", "Volt Vortex", "Electro Smash", "Electric Storm", 
    "Static Barrage", "Thunderstorm Barrage", "Electric Pulse", "Volt Rush", "Lightning Eruption", 
    "Power Beam", "Shocking Strike", "Volt Barrage", "Zap Wave", "Electric Lash", 
    "Thunder Impact", "Lightning Pulse", "Zap Claw", "Volt Burst", "Thunder Strike Barrage", 
    "Electric Vortex", "Thunder Slash", "Shocking Lash", "Power Pulse", "Voltage Strike", 
    "Lightning Surge", "Zap Barrage", "Electro Shockwave", "Volt Cut", "Shock Charge"
];	
	
$skills['psychic'] = [
    "Psychic Strike", "Confusion", "Psybeam", "Psychic Blast", "Mind Crush", 
    "Telekinesis", "Hypnosis", "Future Sight", "Psychic Wave", "Mind Bend", 
    "Telepathic Strike", "Psychic Punch", "Levitate", "Mental Shock", "Psychic Crush", 
    "Mind Control", "Telekinetic Force", "Telepathic Barrage", "Mind Strike", "Aura Blast", 
    "Psychic Lash", "Telekinetic Burst", "Mental Storm", "Psychic Barrage", "Psychic Shield", 
    "Cerebral Blast", "Mind Slash", "Psychic Barrage", "Psychic Surge", "Mental Wave", 
    "Telekinetic Wave", "Mind Slicer", "Psychic Lock", "Psychic Sword", "Telekinetic Claw", 
    "Thought Surge", "Psychic Beam", "Mind Burst", "Psy Shock", "Mental Strike", 
    "Psychic Push", "Aura Slash", "Mind Shield", "Psychic Lash Barrage", "Mental Assault", 
    "Psychic Fang", "Telepathic Force", "Mind Whip", "Thought Pulse", "Psychic Blade", 
    "Telekinetic Impact", "Mind Claw", "Psycho Cut", "Telepathic Barrage", "Cerebral Pulse", 
    "Mental Slash", "Psychic Blast Barrage", "Mind Cradle", "Psychic Reach", "Telekinetic Slicer", 
    "Psychic Wave Barrage", "Mental Fury", "Psychic Tear", "Telepathic Jab", "Mental Grasp", 
    "Psychic Strike Barrage", "Telekinetic Whip", "Psychic Storm", "Mind Lash", "Aura Whip", 
    "Psychic Nova", "Telekinetic Strike", "Mind Shockwave", "Psybeam Barrage", "Mental Focus", 
    "Aura Surge", "Psychic Flare", "Mind Spear", "Telekinetic Push", "Psychic Claw", 
    "Cerebral Strike", "Thought Barrage", "Mind Grasp", "Psychic Charge", "Psychic Storm Barrage"
];


$skills['ice'] = [
    "Ice Beam", "Blizzard", "Frost Breath", "Icy Wind", "Ice Shard", 
    "Glacial Strike", "Freeze", "Frostbite", "Hailstorm", "Avalanche", 
    "Ice Fang", "Frozen Claw", "Frost Slash", "Ice Storm", "Glacial Barrage", 
    "Icicle Spear", "Snowfall", "Chill Barrage", "Freeze Pulse", "Frostwave", 
    "Ice Claw", "Frost Surge", "Winter Gale", "Blizzard Barrage", "Snowball", 
    "Icy Strike", "Chill Surge", "Frozen Edge", "Glacial Barrage", "Ice Slice", 
    "Frost Cut", "Chilling Wave", "Frost Slash Barrage", "Glacial Edge", "Cold Wave", 
    "Hail Barrage", "Chill Strike", "Frozen Rage", "Icequake", "Blizzard Slash", 
    "Frost Barrage", "Chill Slash", "Ice Breaker", "Glacial Barrage", "Icy Cut", 
    "Snowstorm", "Freeze Strike", "Frost Flame", "Cold Slash", "Icy Barrage", 
    "Frozen Barrage", "Chill Bomb", "Glacial Storm", "Icy Burst", "Snowflame", 
    "Chilling Surge", "Glacial Punch", "Ice Nova", "Frostquake", "Blizzard Fury", 
    "Icy Wave", "Frozen Barrage", "Ice Spike", "Hail Spike", "Snow Crush", 
    "Chill Pulse", "Frost Barrage", "Icy Smash", "Frozen Burst", "Snow Spout", 
    "Frostpulse", "Icy Barrage", "Chillwave", "Snow Strike", "Iced Slash", 
    "Glacial Slash", "Frost Strike", "Blizzard Strike", "Snow Barrage", "Chill Nova", 
    "Cold Slash", "Frozen Strike", "Icy Swipe", "Hail Barrage", "Frost Burst", 
    "Ice Bite", "Glacial Surge", "Winter Strike", "Chill Barrage", "Froststorm", 
    "Icy Claw", "Snow Sweep", "Glacial Shard", "Frozen Barrage", "Snow Hammer", 
    "Icequake Barrage", "Chill Blade", "Frozen Wave", "Blizzard Surge", "Hailstrike", 
    "Frostfire", "Icy Smash", "Cold Burst", "Glacial Fist", "Icy Barrage"
];
	
	
$skills['dragon'] = [
    "Dragon Claw", "Dragon Pulse", "Draco Meteor", "Dragon Breath", "Roar of the Dragon", 
    "Dragon Rage", "Dragon Tail", "Dragon Slash", "Dragon Strike", "Dragon Flame", 
    "Draco Charge", "Dragon Roar", "Dragon Crash", "Draco Burst", "Dragon Beam", 
    "Dragon Wave", "Dragon Fury", "Dragon Wrath", "Dragonfire", "Dragon Smash", 
    "Drake Surge", "Dragon Dance", "Ancient Flame", "Dragon Barrage", "Draconic Charge", 
    "Dragon Strike Barrage", "Draconic Storm", "Fire Dragon", "Dragon Barrage", "Ancient Rage", 
    "Draco Slash", "Dragon Spire", "Dragon Blaster", "Dragon Blast", "Fiery Tail", 
    "Dragon Ball", "Drake Strike", "Dragon Gale", "Dragon Stomp", "Dragonflame", 
    "Dragonblast", "Dragon Thunder", "Draconic Flame", "Tail Swipe", "Dragon Tail Barrage", 
    "Drake Barrage", "Dragon Roar Barrage", "Dragon Charge", "Dragonstrike Barrage", "Ancient Wrath", 
    "Dragonflame Barrage", "Dragonlash", "Dragon Spiral", "Dragon Smite", "Dragon Wrath Barrage", 
    "Draco Beam", "Dragonfire Barrage", "Drake Charge", "Dragon Rage Barrage", "Dragon Claw Barrage", 
    "Fiery Breath", "Draconic Barrage", "Tail Barrage", "Ancient Fury", "Dragon Fury Barrage", 
    "Drake Fire", "Dragon's Fire", "Drake Blast", "Dragon Charge Barrage", "Dragonfall", 
    "Fiery Roar", "Draconic Barrage", "Dragon Rush", "Dragon Tail Swipe", "Dragon Strike Barrage", 
    "Dragon Fury Barrage", "Dragon Slash Barrage", "Draco Strike", "Dragon Surge", "Ancient Barrage", 
    "Dragon Breath Barrage", "Dragon Roar Fury", "Drake Breath", "Dragon Barrage Fury", "Draconic Pulse", 
    "Dragon Slash", "Tail Slash", "Dragon Claw Strike", "Ancient Roar", "Dragon Roar Smash", 
    "Dragon Wave Barrage", "Draco Roar", "Dragon Nova", "Draconic Fire", "Dragonstorm"
];	

$skills['dark'] = [
    "Shadow Ball", "Night Slash", "Dark Pulse", "Shadow Claw", "Nightmare", 
    "Dark Void", "Shadow Strike", "Evil Eye", "Dark Fire", "Shadow Barrage", 
    "Dark Bolt", "Shadow Storm", "Nightshade", "Cursed Beam", "Dark Beam", 
    "Dark Charge", "Shadow Blast", "Dread Surge", "Shadow Fang", "Cursed Fang", 
    "Nightflare", "Dark Slash", "Phantom Strike", "Cursed Barrage", "Shadow Flame", 
    "Night Barrage", "Shadow Fury", "Cursed Strike", "Dark Shadow", "Eclipse Strike", 
    "Night Fury", "Shadow Impact", "Grim Barrage", "Dread Strike", "Dark Wave", 
    "Night Storm", "Blackout", "Eclipse Barrage", "Shadow Surge", "Void Pulse", 
    "Nightstrike", "Dark Pulse Barrage", "Dread Wave", "Night Slash Barrage", "Void Strike", 
    "Shadow Slice", "Dark Smite", "Cursed Surge", "Night Claw", "Void Barrage", 
    "Dark Cut", "Dread Barrage", "Shadowflame", "Night Claw Barrage", "Cursed Slash", 
    "Void Strike Barrage", "Night Fury Barrage", "Shadow Flame Barrage", "Eclipse Barrage", 
    "Dread Smite", "Night Tornado", "Shadow Crush", "Cursed Whip", "Dark Touch", 
    "Grim Touch", "Shadow Orb", "Dread Barrage", "Dark Strike Barrage", "Night Doom", 
    "Shadowquake", "Nightmare Barrage", "Cursed Charge", "Dark Storm", "Void Slash", 
    "Nightfire", "Shadow Flame Strike", "Dread Claw", "Cursed Flame", "Void Blast", 
    "Grim Barrage", "Shadow Punch", "Night Barrage", "Cursed Pulse", "Dark Vortex", 
    "Shadow Whirlwind", "Night Mist", "Void Barrage", "Dark Claw", "Night Orb"
];

	
$skills['fairy'] = [
    "Moonblast", "Dazzling Gleam", "Fairy Wind", "Play Rough", "Draining Kiss", 
    "Misty Terrain", "Fairy Lock", "Moonlight", "Pixilate", "Wish", 
    "Lucky Chant", "Fairy Aura", "Fairy Pulse", "Magical Leaf", "Enchanted Strike", 
    "Healing Wish", "Charmed Kiss", "Starlight Surge", "Magical Barrage", "Sweet Kiss", 
    "Fairy Barrier", "Twirling Strike", "Healing Touch", "Magical Mist", "Glimmering Spark", 
    "Lullaby", "Aerial Flash", "Flower Shield", "Fairy Stomp", "Pixie Dust", 
    "Serene Strike", "Pixie Barrage", "Moonbeam", "Fey Barrage", "Sparkling Strike", 
    "Fairylight Barrage", "Magic Bomb", "Fairyforce", "Starfall", "Dazzle Burst", 
    "Moonbeam Barrage", "Whimsical Wind", "Fairy Flash", "Lullaby Barrage", "Pixie Mist", 
    "Enchanting Barrage", "Glitter Strike", "Wish Barrage", "Fairy Kiss", "Prism Barrage", 
    "Floral Whip", "Fairy Spark", "Love Barrage", "Lunar Kiss", "Mystic Barrage", 
    "Twilight Barrage", "Dreamy Pulse", "Flower Barrage", "Shining Force", "Fairy Shine", 
    "Magical Whirlwind", "Stardust Barrage", "Fey Strike", "Lunar Burst", "Moon Strike", 
    "Sparkling Burst", "Fey Surge", "Wish Strike", "Twilight Strike", "Lullaby Wave", 
    "Gleam Barrage", "Fairy Eruption", "Tinkle Barrage", "Shimmer Barrage", "Moonstrike Barrage", 
    "Fairy Vibe", "Fey Shield", "Lightstrike Barrage", "Pixie Barrage", "Magical Shield", 
    "Twilight Surge", "Dream Barrage", "Fairy Delight", "Pixie Strike", "Enchanted Barrage"
];	
	
// Get a random key from the array
$random_key = array_rand($skills[$type]);

// Use the key to get the value
$random_skill = $skills[$type][$random_key];


return $random_skill;	
	
}

function addeco($amount)
{
    mysql_query_md("UPDATE tbl_system SET value = value + $amount WHERE code='systemfund'");
}

function listweapon()
{

    $weapons = "sword,claw,punch,pike,gun,crossbow,bow,staff,whip,axe,mace,dagger";

    $array = array();

    foreach (explode(",", $weapons) as $d)
    {

        $array[$d] = $d;

    }

    return $array;
}

function checkbeta($betakey)
{
    $q = mysql_query_md("SELECT * FROM tbl_betakey WHERE betakey='$betakey' AND user IS NULL");
    $row = mysql_fetch_md_assoc($q);
    $count = mysql_num_rows_md($q);

    return $count;
}

function subeco($amount)
{
    mysql_query_md("UPDATE tbl_system SET value = value - $amount WHERE code='systemfund'");
}

function getcoin()
{
    $systemfund = systemconfig("systemfund");
    $query_dr = "SELECT SUM(balance) as c FROM `tbl_accounts`";
    $rowdr = mysql_fetch_md_array(mysql_query_md($query_dr));

    $rowdr2 = mysql_fetch_md_array(mysql_query_md("SELECT SUM(amount) as c FROM `tbl_withdraw_history`"));

    $conv = ($systemfund - ($rowdr["c"] + $rowdr2["c"])) * 0.0005;
	
	if($conv<0){
	
		$conv = 0.05;
		
	}
	

    return $conv;
}


function deductloser($loserpoke)
{
	$poke = loadpokev2($loserpoke);
    $member = loadmember($poke['user']);

    if ($member['balance'] > 50)
    {
		mysql_query_md("INSERT INTO tbl_income SET user='{$member['accounts_id']}', message='You Lose a battle: -0.05'");
        mysql_query_md("UPDATE tbl_accounts SET balance = balance - 0.5 WHERE accounts_id='{$member['accounts_id']}'");

    }
	
    if ($member['balance'] > 100)
    {
		mysql_query_md("INSERT INTO tbl_income SET user='{$member['accounts_id']}', message='You Lose a battle: -0.15'");
        mysql_query_md("UPDATE tbl_accounts SET balance = balance - 1 WHERE accounts_id='{$member['accounts_id']}'");

    }	

}

function checkpoke($winnerpoke)
{
    $poke = loadpokev2($winnerpoke);

    $userid = $poke["user"];
	
	$member = loadmember($poke['user']);
	
	
    $rewardwin = systemconfig("battlereward");
	if(empty($member['robot'])){
    mysql_query_md("UPDATE tbl_accounts SET balance = balance + $rewardwin WHERE accounts_id='$userid'");
	mysql_query_md("INSERT INTO tbl_income SET user='{$userid}', message='You Won a battle: {$rewardwin}'");
	}

    $req = $poke["level"] * 6;
	
	if($poke['level']==30){
		return;
	}
	
    if ($req <= $poke["exp"])
    {
        pokelevelup($winnerpoke, $poke["rate"]);
    }
}

function transgen()
{
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randstring = "";
    for ($i = 0;$i < 12;$i++)
    {
        $randstring .= $characters[rand(0, strlen($characters)) ];
    }
    return $randstring;
}

function pokelevelup($id, $rate)
{

    $rate_hp = $rate + rand(5, 25);
    mysql_query_md("UPDATE tbl_pokemon_users SET level = level + 1,attack = attack + $rate,defense = defense + $rate,
		hp = hp + $rate_hp, speed = speed + 1,critical = critical + 1,accuracy = accuracy + 1
		WHERE id='$id'");
}
function loadmovesfrontend($hash)
{
    $querys = "SELECT * FROM tbl_movesreindex WHERE pokehash='$hash'";
    $skillsq = mysql_query_md($querys);
    $skillarray = [];
    while ($skillsd = mysql_fetch_md_assoc($skillsq))
    {
        $skillarray[$skillsd["id"]] = $skillsd;
    }

    return $skillarray;
}

function randomskills($hash)
{
    mysql_query_md("UPDATE tbl_movesreindex SET activate=1 WHERE pokehash='$hash' ORDER BY RAND() limit 5");
}

function loadmoves($hash)
{
    $pokedata = loadpoke($hash);
    $pokeidd = $pokedata["id"];

    $querys = "SELECT * FROM `tbl_movesv2` as a LEFT JOIN tbl_pokemon_moves as b ON a.id=b.move_id WHERE b.pokemon_id = $pokeidd AND a.power !='' AND b.move_id IN (SELECT move_id FROM `tbl_pokemon_moves` WHERE `pokemon_id` LIKE '$pokeidd' GROUP by move_id ORDER BY `tbl_pokemon_moves`.`move_id` DESC) GROUP by a.identifier";
    $skillsq = mysql_query_md($querys);
    $skillarray = [];
    while ($skillsd = mysql_fetch_md_assoc($skillsq))
    {
        $skillsd["pokeid"] = $pokedata["id"];
        $skillsd["pokehash"] = $pokedata["hash"];
        $skillsd["pokemon"] = $pokedata["pokemon"];
        $skillsd["power"] = $skillsd["power"] + rand(1, 50);

        $skillarray[$skillsd["identifier"]] = $skillsd;
    }

    return $skillarray;
}

function generatemoves($hash)
{
    $skillarray = loadmoves($hash);

    foreach ($skillarray as $d)
    {
        $array = [];

        $array["typebattle"] = $d["typebattle"];
        $array["power"] = $d["power"];
        $array["title"] = $d["title"];
        $array["accuracy"] = $d["accuracy"];
        $array["move_id"] = $d["move_id"];
        $array["identifier"] = $d["identifier"];
        $array["pokemon"] = $d["pokemon"];
        $array["pokeid"] = $d["pokeid"];
        $array["pokehash"] = $d["pokehash"];

        $sql = [];

        foreach ($array as $a => $b)
        {
            $b = addslashes($b);
            $sql[] = "$a ='$b'";
        }

        $q = "INSERT INTO tbl_movesreindex SET " . implode(",", $sql);

        mysql_query_md($q);
    }
}

function loaditemuser($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_item_history WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loaditem($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_items WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadpoke($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE hash='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadweapon($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_items_users WHERE hash='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadweaponv2($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_items_users WHERE id='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}


function loadboss($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_bosses WHERE hash='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadpokev2($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}
function loadbossv2($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_bosses WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loademblem($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_emblem WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function getEmblem($id)
{
    if (empty($id))
    {
    $q = mysql_query_md("SELECT * FROM tbl_emblem ORDER BY RAND() LIMIT 1");
    $row = mysql_fetch_md_assoc($q);
    $exp = explode(".", $row['image']);
    return $exp[0];
    }
    $q = mysql_query_md("SELECT * FROM tbl_emblem WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    $exp = explode(".", $row['image']);
    return $exp[0];
}

function getluck($chance, $odds)
{
    $luck = rand(1, intval($odds));
	
    if ($chance >= $luck)
    {
        return 1;
    }
    else
    {
        return 0;
    }
}

function loadpoketype($type)
{
    $q = mysql_query_md("SELECT * FROM tbl_damage WHERE type='$type'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}
function loadpokeskill($hash)
{
    $row = array();
    $q = mysql_query_md("SELECT * FROM tbl_movesreindex WHERE activate='1' AND pokehash='$hash'");
    while ($rowqpokes = mysql_fetch_md_assoc($q))
    {
        $row[] = $rowqpokes;
    }
    return $row;
}

function createcsv($list, $csvname)
{
    $file = fopen("uploads/{$csvname}.csv", "w");
    if (empty($list))
    {
        return;
    }
    foreach ($list as $line)
    {
        #var_dump($list);
        fputcsv($file, $line);
    }

    fclose($file);
}

function getbaseme()
{
    $q = mysql_query_md("SELECT * FROM tbl_core_config_data WHERE path='web/unsecure/base_url'");
    $row = mysql_fetch_md_array($q);
    return $row["value"];
}
function countfield($field, $value)
{
    $query = mysql_query_md("SELECT * FROM tbl_accounts WHERE $field='$value'");
    return mysql_num_rows_md($query);
}

function loadmember($id)
{
    $query = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$id'");
    return mysql_fetch_md_array($query);
}

function loadrow($table, $field, $id)
{
    $query = mysql_query_md("SELECT * FROM $table WHERE $field='$id'");
    return mysql_fetch_md_array($query);
}

function formquery($post)
{
    $return = [];
    foreach ($post as $key => $val)
    {
        $return[] = "$key='$val'";
    }
    return implode(",", $return);
}

function randid()
{
    return rand() . strtotime("now");
}
function totalaccount()
{
    $query = "SELECT username,accounts_id as aid,(SELECT COUNT(id) FROM tbl_cycle WHERE account_link = aid AND cycle_count=1 AND cycle_link = 0) as totalacct,account_count FROM tbl_accounts as acct
		JOIN tbl_package as pck WHERE pck.package_id = acct.package_id
		HAVING totalacct < account_count LIMIt 1";
    return mysql_query_md($query);
}

function autocreateaccount()
{
    return;
    while ($row = mysql_fetch_md_assoc(totalaccount()))
    {
        $limit = $row["account_count"] - $row["totalacct"];
        $aid = $row["aid"];
        for ($x = 1;$x <= $limit;$x++)
        {
            $username = $row["username"] . "-" . randid();
            mysql_query_md("INSERT INTO tbl_cycle SET username='$username',account_link='$aid',cycle_count='1',cycle_link='0'");
        }
        return;
    }
}
function autodetectparent()
{
    $query = "SELECT username,account_link,cycle_count,id as alink,(SELECT COUNT(id) FROM tbl_relation WHERE parent = alink) as checkparent FROM tbl_cycle HAVING checkparent < 2    ORDER by id ASC LIMIT 1 ";
    return mysql_query_md($query);
}
function autodetectchild($parentid)
{
    $query = "SELECT username,account_link,cycle_count,id as alink,(SELECT COUNT(id) FROM tbl_relation WHERE child = alink) as checkchild FROM tbl_cycle WHERE id!=$parentid AND id > $parentid HAVING checkchild = 0 ORDER by id ASC LIMIT 1";
    return mysql_query_md($query);
}
function loadcycle($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_cycle WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}
function getRate($id)
{
    $q = mysql_query_md("SELECT cycle_earn FROM tbl_package WHERE package_id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row["cycle_earn"];
}
function getUserPackage($id)
{
    $q = mysql_query_md("SELECT package_id FROM tbl_accounts WHERE accounts_id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row["package_id"];
}
function addmoney($uid, $rate)
{
    mysql_query_md("UPDATE tbl_accounts SET balance = balance + $rate WHERE accounts_id=$uid");
}
function totalbalance($uid, $rate)
{
    mysql_query_md("UPDATE tbl_accounts SET total_earnings = total_earnings + $rate WHERE accounts_id=$uid");
}
function exitlabel($id)
{
    mysql_query_md("UPDATE tbl_cycle SET cycle_status = 1 WHERE id=$id");
}
function cycleinc($id)
{
    $user = loadcycle($id);
    $inc = $user["cycle_count"] + 1;
    exitlabel($id);

    $userpackage = getUserPackage($user["account_link"]);
    $rate = getRate($userpackage);
    totalbalance($user["account_link"], $rate);
    if ($inc == 4)
    {
        addmoney($account_link, $rate * 3);
        $username = "adminbonus-" . randid();
        $account_link = 1;
        $cycle_count = 1;
        $cycle_link = 0;
        mysql_query_md("INSERT INTO tbl_cycle SET username='$username',account_link='$account_link',cycle_count='$cycle_count',cycle_link='$cycle_link'");
    }
    else
    {
        $username = $user["username"] . "-" . $inc;
        $account_link = $user["account_link"];
        $cycle_count = $inc;
        if ($user["cycle_link"] == 0)
        {
            $cycle_link = $id;
        }
        else
        {
            $cycle_link = $user["cycle_link"];
        }

        $q = mysql_fetch_md_assoc(mysql_query_md("SELECT COUNT(id) as chet FROM tbl_cycle WHERE username='$username' AND account_link='$account_link' AND cycle_count='$cycle_count' AND cycle_link='$cycle_link'"));
        if ($q["chet"] == 0):
            mysql_query_md("INSERT INTO tbl_cycle SET username='$username',account_link='$account_link',cycle_count='$cycle_count',cycle_link='$cycle_link'");
        endif;
    }
}
function cycleevent($row)
{
    $rowx = mysql_fetch_md_assoc(autodetectchild($row["alink"]));
    $parent = $row["alink"];
    $child = $rowx["alink"];
    if ($child != "")
    {
        mysql_query_md("INSERT INTO tbl_relation SET parent='$parent',child='$child'");
        $q = mysql_fetch_md_assoc(mysql_query_md("SELECT COUNT(parent) as chet FROM tbl_relation WHERE parent='$parent'"));
        if ($q["chet"] == 2)
        {
            cycleinc($parent);
        }
    }
}
function mytimestamp()
{
    return date("Y-m-d H:i:s");
}

function getrow($var)
{
    $array["title"] = "Joint Lineage Microfinancing";
    $array["image"] = "logo.png";
    return $array;
}

function countquery($query)
{
    $q = mysql_query_md($query);
    return mysql_num_rows_md($q);
}

function getpackagelist()
{
    $packrowq = mysql_query_md("SELECT * FROM tbl_package");
    while ($packrow = mysql_fetch_md_assoc($packrowq))
    {
        $options[$packrow["package_id"]] = $packrow["package_name"];
    }
    return $options;
}

function getwheresearchv2($field)
{
    if ($_GET["pages"] == "report" || $_GET["pages"] == "withdraw" || $_GET["pages"] == "exchange" || $_GET["pages"] == "payments" || $_GET["pages"] == "bonuses")
    {
    }
    else
    {
        array_push($field, "stores");
    }

    $where = "WHERE ";
    $warray = [];
    $warray2 = [];

    if ($_GET["search"] != "")
    {
        $search = $_GET["search"];

        foreach ($field as $f)
        {
            $warray[] = "$f LIKE '%$search%'";
        }
    }

    $matic = 0;
    foreach ($field as $f)
    {
        if (!empty($_GET[$f]))
        {
            $warray2[] = "$f = '{$_GET[$f]}'";
        }
    }

    if ($_GET["pages"] == "report" || $_GET["pages"] == "withdraw" || $_GET["pages"] == "exchange" || $_GET["pages"] == "payments" || $_GET["pages"] == "bonuses")
    {
        //$warray2[]  = "tips.stores = '{$_SESSION['stores']}'";
        
    }
    else
    {
        $warray2[] = "stores = '{$_SESSION["stores"]}'";
    }

    if (count($warray))
    {
        $where .= "(" . implode(" OR ", $warray) . ")";
    }

    if (count($warray2))
    {
        if (count($warray))
        {
            $where .= " AND ";
        }
        $where .= implode(" AND ", $warray2) . " ";
    }

    if (trim($where) == "WHERE")
    {
        return;
    }

    return $where;
}

function getwheresearch($field)
{
    return getwheresearchv2($field);

    $where = "WHERE";
    if ($_GET["search"] != "")
    {
        $search = $_GET["search"];

        $where = "WHERE";

        foreach ($field as $f)
        {
            $where .= " $f LIKE '%$search%' OR";
        }
        $where .= " 1=1";
        $where = str_replace("OR 1=1", "", $where);
    }

    $matic = 0;
    foreach ($field as $f)
    {
        if (!empty($_GET[$f]))
        {
            $where .= " $f = '{$_GET[$f]}' OR";
        }
        $matic++;
    }
    if (!empty($matic))
    {
        $where .= " OR 1=1 ";
        $where = str_replace("OR 1=1", "", $where);
    }
    if (trim($where) == "WHERE")
    {
        return;
    }
    echo $where;
    return $where;
}

function getlimit($limit, $page)
{
    if ($page == "")
    {
        $page = 0;
    }
    else
    {
        $page--;
    }
    $limitx = $limit * $page;

    return "LIMIT $limitx,$limit";
}
function getpagecount($total, $limit)
{
    return ceil($total / $limit);
}

function csv()
{
    header("Content-Type: text/csv; charset=utf-8");

    header("Content-Disposition: attachment; filename=payout-" . $_GET["r"] . "-" . rand() . ".csv");

    // create a file pointer connected to the output stream
    $output = fopen("php://output", "w");

    if ($_GET["r"] == "bank")
    {
        $rows = mysql_query_md("SELECT b.accounts_id,b.username,transnum,email,amount,bank_name,bank_accountnumber,bank_accountname FROM  tbl_withdraw_history as a JOIN tbl_accounts as b WHERE claim_status=0 AND a.accounts_id=b.accounts_id AND claimtype='" . $_GET["r"] . "'

		");

        $array = explode(",", "accounts_id,username,transnum,email,amount,bank_name,bank_accountnumber,bank_accountname");
    }

    if ($_GET["r"] == "pickup")
    {
        $rows = mysql_query_md("SELECT b.accounts_id,b.username,transnum,email,amount FROM  tbl_withdraw_history as a JOIN tbl_accounts as b WHERE claim_status=0 AND a.accounts_id=b.accounts_id AND claimtype='" . $_GET["r"] . "'");

        $array = explode(",", "accounts_id,username,transnum,email,amount");
    }

    fputcsv($output, $array);

    // loop over the rows, outputting them
    while ($row = mysql_fetch_md_assoc($rows))
    {
        foreach ($row as $key => $val)
        {
            $row[$key] = "\"" . $val . "\"";
        }

        fputcsv($output, $row);
    }
}

function savebattlebot($hash, $user)
{
    $poke = loadpoke($hash);

    $id = $poke["id"];

    $user = $poke["user"];


   $rewardwin = systemconfig("battlelimitbot") + $_GET['ai'] + 100000;

    $current = date("Y-m-d");

    $queryx = "SELECT * FROM tbl_battlelog WHERE user='$user' AND battledata LIKE '%$current%'";
    $qx = mysql_query_md($queryx);
    $countx = mysql_num_rows_md($qx);

    if ($countx >= $rewardwin)
    {
        echo "Limited of $rewardwin Battle Per Day only";
        return;
    }

    $query = "SELECT * FROM tbl_battle WHERE (p1poke='$id' OR p2poke='$id') AND winner IS NULL";
    $q = mysql_query_md($query);
    $count = mysql_num_rows_md($q);

    $level = $poke['level'];

    $lessthan = $poke['level'] - systemconfig("gaplevel");
    $greaterthan = $poke['level'] + systemconfig("gaplevel");
    if ($lessthan < 0)
    {
        $lessthan = 0;
    }

    if (empty($count))
    {
        $queryp2 = "SELECT * FROM tbl_battle WHERE p2poke IS NULL AND p1poke!='$id' AND p1user!='$user' AND winner IS NULL AND (level >= $lessthan AND level <= $greaterthan) ORDER by level ASC";
        $qp2 = mysql_query_md($queryp2);
        $countp2 = mysql_num_rows_md($qp2);
        ///player 2
        if ($countp2 != 0)
        {
            $rowp2 = mysql_fetch_md_assoc($qp2);

            mysql_query_md("UPDATE tbl_battle SET p2user='$user',p2poke='$id' WHERE id='{$rowp2["id"]}'");
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
        }
        else
        {
            mysql_query_md("INSERT INTO tbl_battle SET p1user='$user',p1poke='$id',level='$level'");
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
        }

        //echo "Your Pokemon has already on queued. We will notify you on results. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
        
    }
    else
    {
        //echo "Your Pokemon already have pending battle. Please wait. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
        
    }
}

function savebattle($hash)
{
    $poke = loadpoke($hash);

    if (empty($poke["id"]))
    {
        echo "Warrior is not available. ID_MISSING";
        exit();
    }

    $id = $poke["id"];
    $user = $poke["user"];

    if ($user != $poke["user"])
    {
        echo "Warrior is not available. ACCT_INC";
        exit();
    }

    if (!empty($poke["is_market"]))
    {
        echo "Warrior is not able to battle since this is on sale on Market.";
        exit();
    }

    $level = $poke['level'];

    $lessthan = $poke['level'] - systemconfig("gaplevel");
    $greaterthan = $poke['level'] + systemconfig("gaplevel");
    if ($lessthan < 0)
    {
        $lessthan = 0;
    }
	
	if($poke['level']<=5){
		
		
		$lessthan = 1;
		$greaterthan = 6;
	}
	
	
	if($poke['level']<=10 && $poke['level']>=6){
		
		$lessthan = 6;
		
	}
	
	
	
	//echo "Debug::: $lessthan == $greaterthan";
	
	

    $loaduser = loadmember($poke['user']);
    $battlebonus = 0;
    if(!empty($loaduser['deadline'])){
    //check for subscription
    $date_now = new DateTime();
    $date2 = new DateTime($loaduser['deadline']);

    if ($date_now > $date2)
    {
        $battlebonus = 0;
    }
    else
    {
        $battlebonus = $loaduser['deadline_bonus'];
    }
    }
    //
    

    $rewardwin = systemconfig("battlelimit") + $battlebonus;

    $current = date("Y-m-d");

    $queryx = "SELECT * FROM tbl_battlelog WHERE user='$user' AND battledata LIKE '%$current%'";
    $qx = mysql_query_md($queryx);
    $countx = mysql_num_rows_md($qx);

    if ($countx >= $rewardwin)
    {
        echo "Limited of $rewardwin Battle Per Day only";
        exit();
    }

    $query = "SELECT * FROM tbl_battle WHERE (p1poke='$id' OR p2poke='$id') AND winner IS NULL";
    $q = mysql_query_md($query);
    $count = mysql_num_rows_md($q);

    if (empty($count))
    {
        $queryp2 = "SELECT * FROM tbl_battle WHERE p2poke IS NULL AND p1poke!='$id' AND p1user!='$user' AND winner IS NULL AND (level >= $lessthan AND level <= $greaterthan) ORDER by RAND() LIMIT 1";
        $qp2 = mysql_query_md($queryp2);
        $countp2 = mysql_num_rows_md($qp2);
        ///player 2
        if ($countp2 != 0)
        {
            $rowp2 = mysql_fetch_md_assoc($qp2);
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
            mysql_query_md("UPDATE tbl_battle SET p2user='$user',p2poke='$id' WHERE id='{$rowp2["id"]}'");
        }
        else
        {
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
            mysql_query_md("INSERT INTO tbl_battle SET p1user='$user',p1poke='$id',level='$level'");
        }

        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior has already on queued. Please wait..";
    }
    else
    {
        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior already have pending battle. Please wait. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
    }
}

function savebattleboss($hero, $boss)
{
    $herodata = loadpoke($hero);
    $bossdata = loadboss($boss);

    if (empty($herodata["id"]))
    {
        echo "Warrior is not available. ID_MISSING";
        exit();
    }
    if (empty($bossdata["id"]))
    {
        echo "Boss is not available. ID_MISSING";
        exit();
    }
    if (!empty($poke["is_market"]))
    {
        echo "Warrior is not able to battle since this is on sale on Market.";
        exit();
    }

    $id = $herodata["id"];
    $user = $herodata["user"];
    $boss_id = $bossdata["id"];

    if ($user != $herodata["user"])
    {
        echo "Warrior is not available. ACCT_INC";
        exit();
    }
    $loaduser = loadmember($herodata['user']);
    $battlebonus = 0;
    //check for subscription
    $date_now = new DateTime();
    $date2 = new DateTime($loaduser['deadline']);

    if ($date_now > $date2)
    {
        $battlebonus = 0;
    }
    else
    {
        $battlebonus = $loaduser['deadline_bonus'];
    }
    //
    

    $rewardwin = systemconfig("battlelimitboss");

    $current = date("Y-m-d");

    $queryx = "SELECT * FROM tbl_battle_boss WHERE p1user='$user' AND battledata LIKE '%$current%'";
    $qx = mysql_query_md($queryx);
    $countx = mysql_num_rows_md($qx);

    if ($countx >= $rewardwin)
    {
        echo "Limited of $rewardwin Battle Per Day only.";
        exit();
    }
    $query = "SELECT * FROM tbl_achievement WHERE hero='$id' AND boss='$boss_id' AND fightdate >= CURRENT_TIMESTAMP";
    $q = mysql_query_md($query);
    $rr = mysql_fetch_md_assoc($q);
    $count = mysql_num_rows_md($q);

    if (!empty($count))
    {

        echo "You already defeated this boss using ({$herodata['pokename']}). Unlock date for this will be on: " . date("M-d-Y h:i:s", strtotime($rr['fightdate']));
        exit();

    }

    $query = "SELECT * FROM tbl_battle_boss WHERE p1poke='$id' AND p2poke='$boss_id' AND winner IS NULL";
    $q = mysql_query_md($query);
    $count = mysql_num_rows_md($q);

    if (empty($count))
    {

        mysql_query_md("INSERT INTO tbl_battle_boss SET p1user='$user',p1poke='$id',p2user='0',p2poke='$boss_id'");

        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior has already on queued for boss battle. Please wait..";

        mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
        $query = "SELECT * FROM tbl_battle_boss WHERE p1poke='$id' AND p2poke='$boss_id' AND winner IS NULL";
        $qx = mysql_fetch_md_assoc(mysql_query_md($query));
        generatebattleboss($qx['id']);
    }
    else
    {
        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior already have pending boss battle. Please wait. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
    }

    echo "<script>window.location='index.php?pages=pokebattleview-boss&id={$qx['id']}'; </script>";

}

function addmore($initial, $level, $percentage)
{

    $getadditonal = ($initial * $percentage) * $level;

    $final = $getadditonal + $initial;
    return $final;

}
?>
