<?php
session_start();
require_once("../connect.php");
require_once("../function.php");
$accounts_id = $_SESSION['accounts_id'];
$q = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
$row = mysql_fetch_md_assoc($q);

if(empty($accounts_id)){
	exit();
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


	if($_POST['withdraw']!='')
	{
		
		$pokemons = mysql_num_rows_md(mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE user='$accounts_id' AND (is_market IS NULL OR is_market != 1)"));
		
		if($pokemons>=6){
			
			$error .= "<i class=\"fa fa-warning\"></i>Maximum of 6 Warrior per account only (You can sell your other Warrior to get space).<br>";
		}
		
		if($_POST['password']!=$row['password'])
		{
			$error .= "<i class=\"fa fa-warning\"></i>Password do not match.<br>";
		}
		if($_POST['withdraw']==0 || $_POST['withdraw']<0)
		{
						$error .= "<i class=\"fa fa-warning\"></i>Please input valid and not empty amount to Draw.<br>";
		}
		if($_POST['withdraw']>$row['pokeballs']) 
		{
			$error .= "<i class=\"fa fa-warning\"></i>You have no Quest Scroll Left. You can buy it on the Item Shop ;)<br>";
		}


$elements_user = getrandomelement();

$_POST['skill1'] = $elements_user['skill1'];
$_POST['skill2'] = $elements_user['skill2'];
$_POST['skill3'] = $elements_user['skill3'];

$_POST['element1'] = $elements_user['type1'];
$_POST['element2'] = $elements_user['type2'];
$_POST['element3'] = $elements_user['type3'];

$_POST['avatar_class'] = $elements_user['armor'];


$_POST['emblem'] = getrandoemblemuser();

$_POST['weapon'] = getrandoweaponuser();

$_POST['avatar'] = randomcharuser();
  
	
		
		
		
		if(empty(trim($_POST['skill1']))){
			
			$error .= "<i class=\"fa fa-warning\"></i>Skill 1 empty.<br>";
		}
		if(empty(trim($_POST['skill2']))){
			
			$error .= "<i class=\"fa fa-warning\"></i>Skill 2 empty.<br>";
		}
		if(empty(trim($_POST['skill3']))){
			
			$error .= "<i class=\"fa fa-warning\"></i>Skill 3 empty.<br>";
		}		
		
		if($error=='')
		{
		$sum  = $row['pokeballs'] - $_POST['withdraw'];
		mysql_query_md("UPDATE tbl_accounts SET pokeballs='".$sum."' WHERE accounts_id='$accounts_id'");
		$success = 1;
		$trans = trans();
		mysql_query_md("INSERT INTO tbl_pokebuy_history SET transnum='$trans',claimtype='".$_POST['claimtype']."',address='".$_POST['address']."',accounts_id='$accounts_id',new_balance='".$sum."',amount='".$_POST['withdraw']."',current_balance='".$row['balance']."'");
		$q = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");




///pokemon

$x = 1;
while($x<=ceil($_POST['withdraw']))
{
		$pokeid = rand(1);
		

		
		$attack = rand(130,150);
		$defense = rand(15,35);
		$hp = rand(600,800) + 1000;
		$user = $_SESSION['accounts_id'];
		$speed = rand(10,40);
		$critical = rand(5,30);
		$accuracy = rand(5,30);
		$rate = rand(5,10);
		
		$level = 1;
		
		$hash = $_SESSION['accounts_id']."-".trans();
		$pokeclass = array();
		
		
		

	$pokename = $_POST['pokename'];
	$front = ($_POST['avatar']);
	$back = ($_POST['avatar']);
	$main = ($_POST['avatar']);
	$emblem = ($_POST['emblem']);
	$weapon = ($_POST['weapon']);

		
		$pokeclassfin = $_POST['avatar_class'];
		


$pokeadd = "INSERT INTO tbl_pokemon_users SET weapon='$weapon',emblem='$emblem',user='$user',pokemon='$pokeid',attack='$attack',defense='$defense',hp='$hp',speed='$speed',critical='$critical',accuracy='$accuracy',level=1,rate='$rate',front='$front',back='$back',main='$main',pokename='$pokename',pokeclass='$pokeclassfin',hash='$hash'";
mysql_query_md($pokeadd);
	

$dmg1 = rand(200,300);
$dmg2 = rand(100,120);
$dmg3 = rand(80,90);

$title1 = addslashes($_POST['skill1']);
$title2 = addslashes($_POST['skill2']);
$title3 = addslashes($_POST['skill3']);

$element1 = addslashes($_POST['element1']);
$element2 = addslashes($_POST['element2']);
$element3 = addslashes($_POST['element3']);

$acc1 = rand(50,90);
$acc2 = rand(50,90);
$acc3 = rand(50,90);


$ident1 = slugifychar($title1.rand());
$ident2 = slugifychar($title2.rand());
$ident3 = slugifychar($title3.rand());

mysql_query_md("INSERT INTO tbl_movesreindex SET typebattle='$element1',power='$dmg1',title='$title1',accuracy='$acc1',pokehash='$hash',activate=1,identifier='$ident1'");
mysql_query_md("INSERT INTO tbl_movesreindex SET typebattle='$element2',power='$dmg2',title='$title2',accuracy='$acc2',pokehash='$hash',activate=1,identifier='$ident2'");
mysql_query_md("INSERT INTO tbl_movesreindex SET typebattle='$element3',power='$dmg3',title='$title3',accuracy='$acc3',pokehash='$hash',activate=1,identifier='$ident3'");
	
//generatemoves($hash);		
//randomskills($hash);		
		
		
		
		$x++;
}

///



		$row = mysql_fetch_md_assoc($q);		
		}
	}
?>
<?php
if($success!='')
{
	
	
?>

												<div id="poke-container" class="ui cards">

												<?php
												$qpokes = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE hash='{$hash}'");		

													while($rowqpokes = mysql_fetch_md_assoc($qpokes)) {
												?>	
														<div id='poke-<?php echo $rowqpokes['hash']; ?>' class="ui card" style='width: 100%;'>
														<div class='typedataholder'>
															<?php foreach(explode("|",$rowqpokes['pokeclass']) as $tt) { ?>
																<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
															<?php } ?>	
														</div>
														
													   <div class="image">  
															<div class='mainchar flipme showchar' style='background: url(/actors/<?php echo $rowqpokes['front']; ?>) 0px 0px;'></div>		   
													   </div>
														   <h4><?php echo $rowqpokes['pokename']; ?></h4>
														   <p class='idsdata'>ID:#<?php echo $rowqpokes['hash']; ?></p>
														   <span>Level:<?php echo $rowqpokes['level']; ?></span>
														   <span>Attack:<?php echo $rowqpokes['attack']; ?></span>
														   <span>Defense:<?php echo $rowqpokes['defense']; ?></span>
														   <span>HP:<?php echo $rowqpokes['hp']; ?></span>
														   <span>Speed:<?php echo $rowqpokes['speed']; ?></span>
														   <span>Critical:<?php echo $rowqpokes['critical']; ?></span>
														   <span>Accuracy:<?php echo $rowqpokes['accuracy']; ?></span><br/>	

														   <span>
														   
														   <?php 
														   $games = $rowqpokes['win'] + $rowqpokes['lose'];
														   
															if(!empty($games)) {
																echo "Win Rate:".number_format(($rowqpokes['win'] / $games) * 100,2)."%"; 
																echo "<br>"."W/L:".$rowqpokes['win']."/".$games;
															}
														   echo "<br>";
														   
														   
														   ?>
														   
														   </span><br/>	



															<span><input class="btn btn-info btn-lg btnbattle" type="button" onclick="window.location='index.php?pages=viewpoke&pokeid=<?php echo $rowqpokes['hash']; ?>'" name="battle" value="View"></span>														   
														
														</div>
												<?php
													}
												?>	
														
														
												</div>									
											

<script>
jQuery('#pokeremain').text("<?php echo $row['pokeballs']; ?>");
window.location = 'index.php?pages=pokemon';
</script>

<?php
}
?>
<?php
if($error!='')
{
?>
<div class="warning"><ul class="fa-ul"><li><?php echo $error;?></li></ul></div>
<?php
}
?>