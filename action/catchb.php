<?php
session_start();
require_once("./connect.php");
require_once("./function.php");
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
//
?>
<audio controls autoplays loop hidden>
    <source src="https://vgmsite.com/soundtracks/pokemon-gameboy-sound-collection/vdrfhwxr/104-oak%20research%20lab.mp3" type="audio/mpeg">
</audio>
<h2>Acquire Warrior - Quest Scroll #:(<span id='pokeremain'><?php echo $row['pokeballs'];?></span>)</h2>   
<?php
if($error!='')
{
?>
<div class="warning"><ul class="fa-ul"><li><?php echo $error;?></li></ul></div>
<?php
}
?>

<style>
.bank,.remit,.remitmain,.smartpadala,.antibug
{
	display:none;
}
</style>
<?php
if($success!='')
{
?>
<div class="noti"><ul class="fa-ul"><li><i class="fa fa-check fa-li"></i>Done requesting for withdrawal please see status <a href='?pages=withdrawhistory'>here</a> </li></ul></div>
<?php
}
?>
<?php

//echo rand(1,898);

$field = array();
$field[] = array("type"=>"text","value"=>"pokename","label"=>"Warrior Name");
//$field[] = array("type"=>"select","value"=>"claimtype","label"=>"Select Mode of Withdrawal","option"=>array("btc"=>"Bitcoin","SLP"=>"SLP"));
//$field[] = array("type"=>"text","value"=>"address","label"=>"BTC Address:");
$field[] = array("type"=>"password","value"=>"password","label"=>"Please enter password:");
//$field[] = array("type"=>"number","value"=>"withdraw","label"=>"Number of Draw:");

//$field[] = array("type"=>"select","value"=>"weapon","label"=>"Weapon (this is for aethestic):","option"=>listweapon());



$q = mysql_query_md("SELECT * FROM tbl_damage");
while($rowxxx = mysql_fetch_md_assoc($q)) {
	
	$damages[$rowxxx['type']] = ucwords($rowxxx['type']);
}
$field[] = array("type"=>"select","value"=>"avatar_class","label"=>"Armor Element:","option"=>$damages);
//$field[] = array("type"=>"select","value"=>"stores","label"=>"Branch","option"=>getarrayconfig('stores'));


$field[] = array("type"=>"text","value"=>"skill1","label"=>"Skill 1 Name");
$field[] = array("type"=>"select","value"=>"element1","label"=>"Skill 1 Element:","option"=>$damages);


$field[] = array("type"=>"text","value"=>"skill2","label"=>"Skill 2 Name");
$field[] = array("type"=>"select","value"=>"element2","label"=>"Skill 2 Element:","option"=>$damages);


$field[] = array("type"=>"text","value"=>"skill3","label"=>"Skill 3 Name");
$field[] = array("type"=>"select","value"=>"element3","label"=>"Skill 3 Element:","option"=>$damages);






?>

<div class="panel panel-default">
   <div class="panel-body">
      <form id='catchpoke' method='POST' action='#'>
         <?php echo loadform($field,$sdata); ?>
		 <input type='hidden' name='withdraw' value='1'>
<H2>SELECT WEAPON (Design Only)</h2>			 
<div>	 
<?php
$countavatar = 0;
$qweapons = mysql_query_md("SELECT * FROM tbl_weapons WHERE is_free = 1");
while($qweaponsr=mysql_fetch_md_array($qweapons))
{
	$countavatar++;
?>		 
		 
	   <div class="imageselection">  
			<label for="countavatarimg<?php echo $countavatar; ?>" >
			<div class='itemweapondemo itemweapon-<?php echo $qweaponsr['slug'];?>'></div>
			</label>
			<input <?php if($countavatar==1) { echo "checked='checked'"; } ?>required id='countavatarimg<?php echo $countavatar; ?>' class='selectchar' type='radio' name='weapon' value='<?php echo $qweaponsr['slug'];?>'>			
	   </div>     
<?php
}
?>
<br style='clear:both;'/>
</div>		 
<H2>SELECT AVATAR</h2>		 
<div>	 
<?php
$dirname = "actors/";
$images = glob($dirname."*.png");
$countavatar = 0;
foreach($images as $image) {
	$countavatar++;
	
    $finalimg = str_replace($dirname,"",$image);
	$character[$finalimg] = $finalimg;
?>		 
		 
	   <div class="imageselection">  
			<label for="countavatar<?php echo $countavatar; ?>" >
			<div class='mainchar flipme' style='background: url(/actors/<?php echo $finalimg; ?>) 0px 0px;'></div>
			</label>
			<input <?php if($countavatar==1) { echo "checked='checked'"; } ?>required id='countavatar<?php echo $countavatar; ?>' class='selectchar' type='radio' name='avatar' value='<?php echo $finalimg; ?>'>			
	   </div>     
<?php
}
?>
<br style='clear:both;'/>
</div>

<?php
$countavatar = 0;
$q = mysql_query_md("SELECT * FROM tbl_emblem");
?>
<hr>
<H2>Select EMBLEMS</h2>
<div class='tableborderrpg'>
<table class='table table-striped table-bordered table-hover'>
	<tr>
		<td>Emblem Name</td>
		<td>Effects</td>
	</tr>
	
	<?php 
	while($row = mysql_fetch_md_assoc($q)) { 
			$countavatar++;
	?>
	<tr>
		<td>
		<input <?php if($countavatar==1) { echo "checked='checked'"; } ?>required id='emblem<?php echo $countavatar; ?>' class='selectchar' type='radio' name='emblem' value='<?php echo ($row['id']); ?>'>
		<label for="emblem<?php echo $countavatar; ?>" >
		<img src='/sprites/passive/<?php echo ($row['image']); ?>'><?php echo ucfirst($row['title_name']); ?>
		</label>
		</td>
		<td><?php echo ucfirst($row['description']); ?></td>
	</tr>		
	<?php } ?>
</table>
</div>
	   
		 <center>
		 
		 <input class='btn btn-primary btn-lg' onclick="catchpokemon()"type='button' name='submit' value='Hire!'></center>
		 
      </form>
   </div>
</div> 

<hr>

<style>

@media screen and (max-width: 600px) {
	.imageselection {
			width:100% !important;
	}
	input.selectchar {

		float: left;
	}	
	
}


.imageselection {
    float: left;
    width: 90px;
    margin: 20px;
}
input.selectchar {
    margin-left: 24px;
}
</style>
<script>
	function catchpokemon(){
		
		jQuery('#battlenow').trigger('click');
		jQuery('#battlebody').html("");
		jQuery('#battlebody').html("<p>Loading..</p>");
		jQuery.post("action/catchsave.php", jQuery( "#catchpoke" ).serialize(), function(result){
			jQuery('#battlebody').html(result);
		});				
	}
</script>

<button id='battlenow' type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary1">
                  Launch Primary Modal
                </button>
				
<div class="modal fade" id="modal-primary1" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Catching a Pokemon</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>		
            <div id='battlebody' class="modal-body">
              <p>Loading…</p>
            </div>

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>	




<H2>DAMAGES COMPUTATION</h2>
<div class="alert alert-info alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h5><i class="icon fas fa-info"></i>Hey!</h5>
Check here on your warrior elements it shows whos type it is effectiveness.
</div>
<?php
$q = mysql_query_md("SELECT * FROM tbl_damage");
/*
type
double_damage_from
double_damage_to
half_damage_from
half_damage_to
no_damage_from
no_damage_to
*/
?>
<div class='tableborderrpg'>
<table class='table table-striped table-bordered table-hover'>
	<tr>
		<td>Type</td>
		<td colspan='3'>Attack (Outgoing)</td>
		<td colspan='3'>Defense (Ingoing)</td>
	</tr>
	
	<tr style='text-align:center;font-weight:700;'>
		<td>-</td>
		<td>+75%</td>
		<td>-35%</td>
		<td>-65%</td>
		<td>+75%</td>
		<td>-35%</td>
		<td>-65%</td>
	</tr>
	<?php while($row = mysql_fetch_md_assoc($q)) { ?>
	<tr>
		<td><?php echo ucfirst($row['type']); ?></td>
		
	


		<td>
		<?php foreach(explode("|",$row['double_damage_to']) as $tt) { 
			
			if(!empty($tt)) { 
		
		?>
		<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
		<?php } } ?>
		</td>
	
		<td>
		<?php foreach(explode("|",$row['half_damage_to']) as $tt) { 
			
			if(!empty($tt)) { 
		
		?>
		<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
		<?php } } ?>
		</td>



		<td>
		<?php foreach(explode("|",$row['no_damage_to']) as $tt) { 
			
			if(!empty($tt)) { 
		
		?>
		<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
		<?php } } ?>
		</td>



	
		
		<td>
		<?php foreach(explode("|",$row['double_damage_from']) as $tt) { 
			
			if(!empty($tt)) { 
		
		?>
		<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
		<?php } } ?>
		</td>
		<td>
		<?php foreach(explode("|",$row['half_damage_from']) as $tt) { 
			
			if(!empty($tt)) { 
		
		?>
		<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
		<?php } } ?>
		</td>
		

		<td>
		<?php foreach(explode("|",$row['no_damage_from']) as $tt) { 
			
			if(!empty($tt)) { 
		
		?>
		<div class='typesdata <?php echo $tt; ?>'><img src='sprites/type/<?php echo strtolower($tt); ?>.png' style='width:25px;margin-right:1px;'><?php echo ucfirst($tt); ?></div>
		<?php } } ?>
		</td>	
		
		







	</tr>	
	<?php } ?>
</table>



</div>