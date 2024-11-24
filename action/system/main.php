<?php
   $primary = "id";
   $tbl = "tbl_system";
   $query  = mysql_query_md("SELECT * FROM $tbl");
   while($row=mysql_fetch_md_assoc($query))
   {

          $sdata[$row['code']] = $row['value'];
      
   }

$field = array();
$field2 = array();


$field2[] = array("type"=>"editor","value"=>"terms");
// $field[] = array("type"=>"editor","value"=>"vision");
//$field2[] = array("type"=>"text","value"=>"merchant_id","label"=>"Merchant ID.");
#var_dump(generatedate($sdata));
$field2[] = array("type"=>"text","value"=>"battlereward","label"=>"Reward Per Battle.");
$field2[] = array("type"=>"text","value"=>"battlelimit","label"=>"Battle Limit.");
$field2[] = array("type"=>"text","value"=>"battlelimitboss","label"=>"Battle Limit Boss.");
$field2[] = array("type"=>"text","value"=>"battlelimitbot","label"=>"Battle Limit Bot.");

$field2[] = array("type"=>"text","value"=>"gaplevel","label"=>"Battle Bracket Gap Level.");

$field2[] = array("type"=>"select","value"=>"ai","label"=>"AI Auto Battle",option=>array("1"=>"Yes","0"=>"No"));
$field2[] = array("type"=>"text","value"=>"systemfund","label"=>"System Fund.");


$field2[] = array("type"=>"editor","value"=>"announcement");
//
?>
<h2>System Configuration</h2>
<div class="panel panel-default">
   <div class="panel-body">
      <form method='POST' action='?pages=<?php echo $_GET['pages'];?>'>
    <input type='hidden' name='task' value='configsave'>
      <input type='hidden' name='user' value='<?php echo $_GET['uid'];?>'>
      <input type='hidden' name='id' value='<?php echo $_GET['id'];?>'>
         


         <?php echo loadform($field2,$sdata); ?>

         <?php //echo multiformconfig("loanclass","Loan Class",$sdata['loanclass']); ?>



         <center><input class='btn btn-primary btn-lg' type='submit' name='submit' value='Save Configuration'></center>
      </form>
   </div>
</div> 
