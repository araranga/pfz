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



?>

<div class="panel panel-default">
   <div class="panel-body">
      <form id='catchpoke' method='POST' action='#'>
         <?php echo loadform($field,$sdata); ?>
		 <input type='hidden' name='withdraw' value='1'>
	 



	   
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






</div>