<?php
session_start();
require_once("./connect.php");
require_once("./function.php");
?>
<h2>BOSS HUNT!!</h2>   
<?php
$current = date("Y-m-d");
$user = $_SESSION['accounts_id'];
$queryx = "SELECT * FROM tbl_battle_boss WHERE p1user='$user' AND battledata LIKE '%$current%'";
$qx = mysql_query_md($queryx);
$countx = $battlecount = mysql_num_rows_md($qx);
?>


<?php

$loaduser = loadmember($_SESSION['accounts_id']);
$battlebonus = 0;
	//check for subscription
 $date_now = new DateTime();
 $date2    = new DateTime($loaduser['deadline']);

if ($date_now > $date2) {
	$battlebonus = 0;
}
else{
	$battlebonus = $loaduser['deadline_bonus'];
}	

?>	
<div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Note:</h5>
<p>
Once you defeated the boss. The current hero will be restricted to fight it again. But dont worry it will reset every 20 days also you can battle it with different warrior.
</p>
</div>

	
<div class='row'>
          <div class="col-lg-12 col-12">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $countx; ?> / <?php echo systemconfig("battlelimitboss"); ?></h3>

                <p>Battle Energy</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
          </div>
		  		  
		  
</div>
<div class="callout callout-success" style='border-left-color: #f012be!important;'>

<div class="info-box">


              <div class="info-box-content">
                <span class="info-box-text"></span>
                <span class="info-box-number">
                 		<div style='float:left;background-position: 1006px 145px!important;background: url(/sprites/npc/1.png) 0px 143px; width: 144px;height: 144px;border: 1px solid;border-radius: 64px;'></div>
						<p>Want more energy? purchase <a href='index.php?pages=activate' style='color:black!important;'>here!</a></p>
                </span>
              </div>
              <!-- /.info-box-content -->
</div>
</div>

<div id="poke-container" class="ui cards">
<style>
#overlayx {
  position: fixed;
  display: none;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5);
  z-index: 2;
  cursor: pointer;
}

#sellnowtoday {
    display: none;
}

#battlebody3 .card {
    width: 206px;
    margin: 0 auto;
}
</style>
<div id="overlayx">
</div>


<?php

$qpokes = mysql_query_md("SELECT * FROM tbl_bosses ORDER by hp ASC");		

	while($rowqpokes = mysql_fetch_md_assoc($qpokes)) {
?>	
		<div id='poke-<?php echo $rowqpokes['hash']; ?>' class="ui card">
		<div class='typedataholder'>
			<?php foreach(explode("|",$rowqpokes['pokeclass']) as $tt) { ?>
				
				<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
			<?php } ?>	
		</div>

		   <div class="image" style='margin:0 auto;'>  
				<img src='/sprites/boss/<?php echo $rowqpokes['main']; ?>' style='height:100px;'>	   
		   </div>
		   <h4><?php echo $rowqpokes['pokename']; ?></h4>
		   <p class='idsdata'>ID:#<?php echo $rowqpokes['hash']; ?></p>
		   <span>Attack:<?php echo $rowqpokes['attack']; ?></span>
		   <span>Defense:<?php echo $rowqpokes['defense']; ?></span>
		   <span>HP:<?php echo $rowqpokes['hp']; ?></span>
		   <span>Speed:<?php echo $rowqpokes['speed']; ?></span>
		   <span>Critical:<?php echo $rowqpokes['critical']; ?></span>
		   <span>Accuracy:<?php echo $rowqpokes['accuracy']; ?></span><br/>	

			<strong><span >Points Reward:<?php echo $rowqpokes['reward']; ?></span></strong><br/>

			<strong><span >Money Reward:<?php echo $rowqpokes['reward_money']; ?></span></strong><br/>
              <strong><span >Required Level:<?php echo $rowqpokes['level']; ?></span></strong><br/>			
		   <span>
		   
		   <?php 
 
		   if(empty($rowqpokes['emblem'])){
			   
			   $rowqpokes['emblem'] = 0;
			   
		   }
		   else{
			  $emb = loademblem($rowqpokes['emblem']); 
			   echo "<strong>Emblem: ".$emb['title_name']."</strong>";
		   }
			
		   ?>
		   
		   </span>
		   <br/>	
			<input class="btn btn-info btn-lg btnbattle" type="button" onclick="window.location='index.php?pages=viewboss&pokeid=<?php echo $rowqpokes['hash']; ?>'" name="battle" value="View">
		    <br><input class="btn btn-primary btn-lg btnbattle" type="button" onclick="battleme('<?php echo $rowqpokes['hash']; ?>')" name="battle" value="Challenge!">
		</div>
<?php
	}
?>	
		
		
</div>
<?php
$totalimit = systemconfig("battlelimit") + $battlebonus;
?>
<script>
	function savebattleboss(id){
		
		
		<?php if($battlecount==$totalimit) { ?>
			alert("Limit of <?php echo systemconfig("battlelimit"); ?>  battles a days only");
			return;
		<?php } ?>
		
		var battlehash = jQuery(id).attr('battlehash');
		var heroselect = jQuery('#heroselect').val();
		
		if(heroselect==''){
			alert("please select hero");
			return
		}
		if(battlehash==''){
			alert("please select boss");
			return
		}		
		jQuery('#battlebody').html("<p>Loading..</p>");
		jQuery.post("action/savebattleboss.php", {boss: battlehash,hero:heroselect}, function(result){
			jQuery('#battlebody').html(result);
		});				
	}
	
	function battleme(battlehash){

		<?php if($battlecount==$totalimit) { ?>
			alert("Limit of <?php echo systemconfig("battlelimit"); ?>  battles a days only");
			return;
		<?php } ?>
		
		jQuery('.hashbattle').text(battlehash);
		jQuery('#battlenow').trigger('click');
		jQuery('#battlebody').html("");
		
		var htmlpoke = jQuery('#poke-'+battlehash).html();
		jQuery('#battlebody2').html("<div class=\"ui card\">"+htmlpoke+"</div>");
		
		jQuery('#savebattle').attr('battlehash',battlehash);

	
		
		
		//toastr.success("Your battle has done watch here <a href=''>Watch</a>");
		
	}
	
	function showheroboss(heroid){
		
		jQuery('.heroselects').hide();
		jQuery('#pokehero-'+heroid).show();

		
	}
</script>
<button id='battlenow' type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary1">
                  Launch Primary Modal
                </button>
<div class="modal fade" id="modal-primary1" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Queue for Boss Battle - <span class='hashbattle'></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>
			
		

			<div id='battlebody3' class='modal-body'>

			<h4>HERO SELECT:</h4>
			<select id='heroselect' onchange='showheroboss(this.value)'>
			<option value=''>Select hero</option>
<?php
$myuser = $_SESSION['accounts_id'];
$qpokes = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE user='$myuser' AND (is_market IS NULL OR is_market!=1)");		

while($rowqpokes = mysql_fetch_md_assoc($qpokes)) {
?>
	<option value='<?php echo $rowqpokes['hash']; ?>'><?php echo $rowqpokes['pokename']; ?></option>
<?php
	}
?>	
			</select>
<?php
$myuser = $_SESSION['accounts_id'];
$qpokes = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE user='$myuser' AND (is_market IS NULL OR is_market!=1)");		

	while($rowqpokes = mysql_fetch_md_assoc($qpokes)) {
?>	
		<div id='pokehero-<?php echo $rowqpokes['hash']; ?>' class="ui card heroselects" style='display:none;'>
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
		   
		   
		   
		   if(empty($rowqpokes['emblem'])){
			   
			   $rowqpokes['emblem'] = 0;
			   
		   }
		   else{
			  $emb = loademblem($rowqpokes['emblem']); 
			   echo "<strong>Emblem: ".$emb['title_name']."</strong>";
		   }
			$getweapon = mysql_fetch_md_assoc(mysql_query_md("SELECT * FROM tbl_items_users WHERE pokemon='{$rowqpokes['id']}' LIMIT 1"));		
			$rowqpokes['weapon_name'] = $getweapon['pokename'];		   
			if(!empty($rowqpokes['weapon_name'])){
				 echo "<br>";
				echo "<strong>Weapon : ".$rowqpokes['weapon_name']."</strong>"; 
			}				
		   ?>
		   
		   </span>
		</div>
<?php
	}
?>	
						<hr>	
			</div>
			
            <div id='battlebody2' class="modal-body">
              <p>Loading…</p>
            </div>				
            <div id='battlebody' class="modal-body">
              <p>Loading…</p>
            </div>			
            <div class="modal-footer justify-content-between">
              <button id='savebattle' type="button" onclick="savebattleboss(this)" class="btn btn-primary">Lets Go!</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>				