<?php
session_start();
include("connect.php");
include("function.php");
if($_SESSION['accounts_id']=='')
{
exit("<script> window.location='/web/index.php' </script>");
}
$main = getrow("tbl_logo");
$tablerowxxx = "tbl_accounts";
$queryrowxxx = "SELECT * FROM $tablerowxxx WHERE accounts_id='".$_SESSION['accounts_id']."'";
$qrowxxx = mysql_query_md($queryrowxxx);
$rowxxx = mysql_fetch_md_assoc($qrowxxx);
foreach($rowxxx as $key=>$val)
{
  if($key!='stores'){
    $_SESSION[$key] = $val;
  }
  
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include("inc/head.php"); ?>
<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
<body class="hold-transition sidebar-mini layout-fixed">
  <style>
div#modal-primary-emblem td {
    width: 50%;
}  
  
.battle .itemweapon {
    display: block!important;
}  
.itemweapon {
    width: 60px;
    height: 49px;
    position: absolute;
    z-index: 999;
    margin-left: -23px;
    margin-top: 12px;
	display:none;
}

.itemweapondemo {
    width: 60px;
    height: 49px;	
}


<?php
$qweapons = mysql_query_md("SELECT * FROM tbl_weapons");
while($qweaponsr=mysql_fetch_md_array($qweapons))
{
	
	?>
.itemweapon-<?php echo $qweaponsr['slug']; ?>{

background:url('/sprites/<?php echo $qweaponsr['image']; ?>') <?php echo $qweaponsr['description']; ?>;
}  	
	<?php
}

?>


 
    
  
.user-panel .info {
    display: inline-block;
    padding: 5px 5px 5px 10px;
    white-space: normal;
}

.tableborderrpg {
    width: 100%;
    overflow: scroll;
}

aside.main-sidebar.sidebar-dark-primary.elevation-4 {
   
}
.noti {
    padding: 11px;
    font-size: 19px;
    color: green;
}

.card.card-primary {
    padding: 20px;
}
a.goback {
    font-size: 16px;
    text-decoration: underline;
}

li.paginate_button {
    margin-left: 6px;
}

a.brand-link {
    text-align: center;
}

.p1user{
  -webkit-transform: scaleX(-1);
  transform: scaleX(-1);	
}
  </style>
  <style>
.flipme{

 transform:scaleX(-1);
	
}
.mainchar {
  width: 64px;
  height: 64px;
}

.battle{
	background-position: 263px 384px!important;
}


.death{
	background-position: 64px 64px!important;
}

.pain{
	
background-position: 570px 127px!important;	
}
.win{
	background-position: 256px 63px!important;
}

.cast{
	
    background-position:-124px -128px!important;	
}

.dodge{
	
    background-position: 191px 0px!important;
	
}

.showchar {
	
	margin: 84px;
	
}
</style>

<style>
.ui.cards>.card {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: .875em .5em;
    float: none;
}
.ui.card, .ui.cards>.card {
    max-width: 100%;
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    width: 290px;
    min-height: 0;
    background: #fff;
    padding: 0;
    border: none;
    border-radius: .28571429rem;
    -webkit-box-shadow: 0 1px 3px 0 #d4d4d5, 0 0 0 1px #d4d4d5;
    box-shadow: 0 1px 3px 0 #d4d4d5, 0 0 0 1px #d4d4d5;
    -webkit-transition: -webkit-box-shadow .1s ease,-webkit-transform .1s ease;
    transition: -webkit-box-shadow .1s ease,-webkit-transform .1s ease;
    transition: box-shadow .1s ease,transform .1s ease;
    transition: box-shadow .1s ease,transform .1s ease,-webkit-box-shadow .1s ease,-webkit-transform .1s ease;
    z-index: '';
}

.ui.cards {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: -.875em -.5em;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.uipokeimg {
	width:100%;
}
.ui.card {
    padding: 16px!important;
}

.idsdata{
	
}
.typedataholder {
    position: absolute;
}
.typesdata {
    background: #212121;
    color: white;
    padding: 4px;
    font-size: 12px;
    float: left;
	margin-left:1px;

	text-transform: uppercase;
}
/*
.typesdata.water {
    background-color: skyblue;
}
.typesdata.rock {
    background-color: brown;
}
.typesdata.ground {
    background-color: #969191;
}
.typesdata.steel {
    background-color: silver;
}
.typesdata.bug {
    background-color: #3fdc3f;
}
.typesdata.grass {
    background-color: green;
}
*/
#battlenow {
	display:none;
}
#battlebody2 .btnbattle {
	display:none;
}

#battlebody2 .card {
    width: 206px;
    margin: 0 auto;
}

.typedataholder {
    z-index: 50;
}
</style>  
<div class="wrapper">

  <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
<!--     <a href="index.php" class="brand-link">
      <span class="brand-text font-weight-light">Kringle Cash</span>
    </a> -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <i class="fas fa-search fa-user" style='font-size: 33px;color: white;'></i>
        </div>
        <div class="info">
          <a href="index.php" class="d-block"><?php echo $_SESSION['fullname']; ?>
		  <br/>
		  <span><?php echo date("M d, Y h:i A"); ?></span>
		  </a>
		  
        </div>
	
      </div>


      <!-- Sidebar Menu -->
      <?php include("inc/menu.php"); ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  
<style>
.annc a {
	color:black!important;
}
</style>
<div class="callout callout-success annc" style="border-left-color: black!important;">

<div class="info-box">


              <div class="info-box-content">
                <span class="info-box-text"></span>
                <span class="info-box-number">
                 		<div style="float:left;background-position: 871px 145px!important;background: url(/sprites/npc/2.png) 0px 143px; width: 144px;height: 144px;border: 1px solid;border-radius: 64px;padding:30px;"></div>
						<div style='    padding: 13px;float: left;width: 83%;word-break: break-all;'><?php echo (systemconfig("announcement")); ?></div>
						<br style='clear:both;'/>
                </span>
              </div>
              <!-- /.info-box-content -->
</div>
</div>	  
	  
	  
            <?php

    $currpage = $_GET['pages'];
    if($currpage=='')
    {
      $currpage = 'pokemon';
    }


    if($currpage=='exchangerequest' && $_SESSION['activated']==0){
      $currpage = "activate";
    }
    include("action/".$currpage.".php");
            ?>
      </div>
            
    </section>


<br>
<br>
<hr>	
<style>
.ads {
    width: 100%;
}

.ads2 {
    margin: 0 auto;
    width: 70%;
    padding: 10px;
}
ul.pagination {

    list-style: none;
    display: flex;
    flex-wrap: wrap;
}
ul.pagination a.active {
    border-radius: 5px;
}

ul.pagination a {
    border-radius: 5px;
}
</style>

<div class='ads'>
<div class='ads2'>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4346027402657123"
     crossorigin="anonymous"></script>
<!-- footer main login -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-4346027402657123"
     data-ad-slot="3301355061"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-4346027402657123"
     data-ad-slot="4422865049"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>	
</div>
</div>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	
	
	

	
	
	
	
	
	
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2025 Pocket Fighters</strong><br/>
	<span>All original characters, artwork and other media remain the property of their respective authors.</span>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>

<script src="plugins/toastr/toastr.min.js"></script>


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->


<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>


<script type="text/javascript">
  
 jQuery( document ).ready(function() {
      


      jQuery('.editor').summernote({
        height: 250
      });
      <?php if ($_GET['pages']=='subscription' || $_GET['pages']=='exchangerequest' || ($_GET['pages']=='users' && $_GET['task']=='edit')) { ?>
      start();
      <?php } ?>


      jQuery( ".nav-link" ).each(function( index ) {
              if(jQuery(this).attr('href')=='index.php?pages=<?php echo $_GET['pages']; ?>'){

                 jQuery(this).addClass('active');
              }
      });
	  
	  



setInterval(function() { 

		jQuery.post("action/aigenerate.php", {battlehash: Math.random()}, function(result){

		});	
		jQuery.post("action/generatebattle.php", {battlehash: Math.random()}, function(result){

		});	
		
		<?php if($_SESSION['robot']!=1) { ?>
		jQuery.post("action/generatebattlenoti.php", {battlehash: Math.random()}, function(result){
			if(result!=''){
				//toastr.success(result);
				
				<?php if($_GET['pages']!='pokebattleview') { ?>
				if(jQuery('#modal-battleload:visible').length)
				{
					
				}else{
					jQuery('#battlenowload').trigger('click');
					jQuery('#battleloadbody').html(result);								
				}
				<?php } ?>
				
				<?php if($_GET['pages']=='pokebattleview') { ?>
				//toastr.success(result);	
				<?php } ?>

			}
			
		});	
		<?php } ?>
}, 5000);




});
</script>

<script type="text/javascript">
  
  jQuery( document ).ready(function() {
	 
	 jQuery('#noemblem').trigger('click');
	 
 });
 
 
 jQuery( document ).ready(function() {
	 
	 jQuery('#nohero').trigger('click');
	 
 });
 
 
</script>


<button id='battlenowload' type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-battleload" style='display:none;'>
                  Launch Primary Modal
                </button>
<div class="modal fade" id="modal-battleload" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Your Battle Is Ready!!</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>
			
            <div id='battleloadbody' class="modal-body">
            </div>			
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>		

</body>
</html>
