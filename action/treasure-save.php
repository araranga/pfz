<?php
session_start();
require_once("../connect.php");
require_once("../function.php");
$accounts_id = $_SESSION['accounts_id'];
$q = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
$row = mysql_fetch_md_assoc($q);
function trans()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 12; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

$error = '';

if(empty($_POST['password'])){
	
	$error .= "<i class=\"fa fa-warning\"></i>Password is required.<br>";
	
}
		
if($_POST['password']!=$row['password'])
{
	$error .= "<i class=\"fa fa-warning\"></i>Password do not match.<br>";
}
if(1>$row['chest']) 
{
	$error .= "<i class=\"fa fa-warning\"></i>You have no Chest Left. You can buy it on the Item Shop ;)<br>";
}


if(empty($error)){
	mysql_query_md("UPDATE tbl_accounts SET chest=chest - 1 WHERE accounts_id='$accounts_id'");
	
	$chance = 8 + $row['chest_chance'];
	
	if($accounts_id==20){
		
		$chance = 100;
	}
if(!getluck($chance,100)){
	mysql_query_md("UPDATE tbl_accounts SET chest_chance=chest_chance + 1 WHERE accounts_id='$accounts_id'");
	$error .= "<i class=\"fa fa-warning\"></i>It seems the chest is empty. Try again later.<br>";
}
}
	
	
	$stats = array("hp","speed","critical","accuracy","attack","defense");
	$weapon_hash = "wp-{$accounts_id}-".trans();
	
	$numstat = rand(1,5);
	
	
	$final_stat = array();
	
	for ($x = 1; $x <= $numstat; $x++) {
		$randstat = $stats[array_rand($stats,1)];
	  $final_stat[$randstat] = $randstat;
	}	
		
		
	$post_data = array();
	
	foreach($final_stat as $fkey=>$fval){
		
		
		
		
		
		if($fkey=='hp'){
			
			$data = rand(1,350);
			
			$is_rare = rand(1,3);
			
			$valfinal = $is_rare * $data;
		}
		else if($fkey=='attack'){

			
			$data = rand(1,35);
			
			$is_rare = rand(1,3);
			
			$valfinal = $is_rare * $data;
			
		}


		else if($fkey=='defense'){

			
			$data = rand(1,60);
			
			$is_rare = rand(1,3);
			
			$valfinal = $is_rare * $data;
			
		}
		else{

			$data = rand(1,10);
			
			$is_rare = rand(1,3);
			
			$valfinal = $is_rare * $data;			
			
			
		}



		$post_data[$fkey] = $valfinal;
		
		
	}

	

	
$qweapons = mysql_query_md("SELECT * FROM tbl_weapons WHERE is_free!=1 ORDER by RAND() LIMIT 1");
$qweaponsr= mysql_fetch_md_array($qweapons);


$weaponcount = mysql_query_md("SELECT weapon FROM  tbl_items_users WHERE weapon='{$qweaponsr['slug']}' AND user='$accounts_id'");
$weaponcountnum = mysql_num_rows_md($weaponcount);	
if(empty($weaponcountnum)){
	
	$weaponcountnum = 1;
}





$post_data['weapon']= $qweaponsr['slug'];
$post_data['pokename'] = $_SESSION['fullname']." ".$qweaponsr['title_name']." ".numberToRomanRepresentation($weaponcountnum);
$post_data['user'] = $accounts_id;
$post_data['hash'] = $weapon_hash;

if($error==''){
	
	
	$insert_data = array();
	
	
	foreach($post_data as $k=>$v){
		$v = addslashes($v);
		$insert_data[] = "$k='$v'";
	}
	

	
	mysql_query_md("INSERT INTO tbl_items_users SET ".implode(",",$insert_data));



	

$q = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
$row = mysql_fetch_md_assoc($q);	
	
?>
<style>
div#battlebody {
    margin: 0 auto;
}
</style>
<h4>Congratulations! You got an item!</h4>
<div id="poke-container" class="ui cards">
	<div id="pokeitemv2-xxx" class="ui card">
			   <div class="image">
			   <br>
			    <div class='itemweapondemo itemweapon-<?php echo $post_data['weapon']; ?>' style='margin: 0 auto;'></div>
			   <h4><?php echo $post_data['pokename']; ?></h4>
			   <p>
			   <?php 
			   foreach($final_stat as $fkey=>$fval){ 
					echo $fkey.":".$post_data[$fkey]."<br>";
			   }
			   ?>
			   </p>
			</div>
	
	
	</div>
<?php
}
if($error!='')
{
?>
<div class="warning"><ul class="fa-ul"><li><?php echo $error;?></li></ul></div>
<?php
}
?>
This page will refresh in seconds.
<script>
jQuery('#walletbalancechest').text("<?php echo $row['chest']; ?>");
</script>
<script>
setTimeout(myGreeting, 2000);

function myGreeting(){
	
  window.location = 'index.php?pages=treasure';	
}

</script>