<?php
session_start();
require_once("./connect.php");
require_once("./function.php");
$accounts_id = $_SESSION['accounts_id'];
$q = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
$row = mysql_fetch_md_assoc($q);

$conv = 1;
function trans()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 12; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

	if($_POST['submit']!='')
	{
		if($_POST['password']!=$row['password'])
		{
			$error .= "<i class=\"fa fa-warning\"></i>Password do not match.<br>";
		}
		if($_POST['withdraw']==0 || $_POST['withdraw']<0)
		{
						$error .= "<i class=\"fa fa-warning\"></i>Please input valid and not empty amount to withdraw.<br>";
		}
		
		if($_POST['withdraw']<300)
		{
						//$error .= "<i class=\"fa fa-warning\"></i>Minimum 300 coins is required.<br>";
		}		
		
		if($_POST['withdraw']>$row['balance_money']) 
		{
			$error .= "<i class=\"fa fa-warning\"></i>Amount to withdraw(".$_POST['withdraw'].") is insufficient on current balance(".$row['balance_money']."). Please input valid amount.<br>";
		}
		
		
		
		$countchar = mysql_num_rows_md(mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE user='$accounts_id' AND level>=15"));
		if(empty($countchar)){
			
			$error .= "<i class=\"fa fa-warning\"></i>You must have atleast 1 level 15 hero.<br>";
			
		}
		
		$totalwithdraw = $conv * $_POST['withdraw'];
		
		if($totalwithdraw<100){
			
			$error .= "<i class=\"fa fa-warning\"></i>Amount to withdraw $totalwithdraw must be atleast equal or greater than 100 pesos.<br>";
		}
		
		if($error=='')
		{
		$sum  = $row['balance_money'] - $_POST['withdraw'];
		$convtotal = $conv * $_POST['withdraw'];
		
		mysql_query_md("UPDATE tbl_accounts SET balance_money='".$sum."' WHERE accounts_id='$accounts_id'");
		$success = 1;
		$trans = trans();
		$convtotal_deduct = ($conv * $_POST['withdraw']) - (($conv * $_POST['withdraw']) * 0.08);
		
		mysql_query_md("INSERT INTO tbl_withdraw_history SET conv='$convtotal_deduct',transnum='$trans',claimtype='".$_POST['claimtype']."',address='".$_POST['address']."',accounts_id='$accounts_id',new_balance='".$sum."',amount='".$_POST['withdraw']."',current_balance='".$row['balance']."'");
		$q = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$accounts_id'");
		
		subeco($convtotal);
		
		
		$msg = "Withdrawal request from {$_SESSION['email']} / {$_SESSION['fullname']} total of {$convtotal_deduct} to GCash:{$_POST['address']}";
		
		mail("ardeenathanraranga@gmail.com","Withdrawal $trans",$msg);
		mail("satchieee@gmail.com","Withdrawal $trans",$msg);
		$row = mysql_fetch_md_assoc($q);		
		}
	}
	
$field[] = array("type"=>"text","value"=>"bank_name","label"=>"Bank Name");
$field[] = array("type"=>"text","value"=>"bank_accountname","label"=>"Account Name");
$field[] = array("type"=>"text","value"=>"bank_accountnumber","label"=>"Account Number");
//
$field[] = array("type"=>"text","value"=>"name","label"=>"Fullname");
$field[] = array("type"=>"text","value"=>"address","label"=>"Address");
$field[] = array("type"=>"text","value"=>"phone","label"=>"Phone Number");
//
$field[] = array("type"=>"number","value"=>"withdraw","label"=>"Amount to withraw");
$field[] = array("type"=>"password","value"=>"password","label"=>"Please enter password");
//
?>
<h2>Withdrawal Request - Balance(<?php echo number_format($row['balance_money'],2);?>)</h2>   
<?php
if($error!='')
{
?>
<div class="warning"><ul class="fa-ul"><li><?php echo $error;?></li></ul></div>
<?php
}
?>



<div class="alert alert-info alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
*Additional 8% deduction for withdrawal fee. Which help us pay server and stuff
<br/>
*You must have 1 item purchase in item shop
<br/>
*You must have 1 Level 15 hero.
</div>




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
$field = array();

$field[] = array("type"=>"select","value"=>"claimtype","label"=>"Select Mode of Withdrawal","option"=>array("btc"=>"Gcash"));
$field[] = array("type"=>"text","value"=>"address","label"=>"GCASH Number:");
$field[] = array("type"=>"password","value"=>"password","label"=>"Please enter password:");
$field[] = array("type"=>"number","value"=>"withdraw","label"=>"Amount to withdraw:","attributes"=>array('onkeyup'=>'eta()'));
//$field[] = array("type"=>"select","value"=>"stores","label"=>"Branch","option"=>getarrayconfig('stores'));
?>

<div class="panel panel-default">
   <div class="panel-body">
   

   
      <form method='POST' action='?pages=<?php echo $_GET['pages'];?>'>
         <?php echo loadform($field,$sdata); ?>
		 
<div class="callout callout-success">
<p>Estimated In Pesos:<span id='eta'></span></p>
</div>  		 
		 
         <center><input class='btn btn-primary btn-lg' type='submit' name='submit' value='Submit'></center>
      </form>
   </div>
</div> 


<script>
function eta(){
	
	var withdraw = parseFloat(jQuery('#withdraw').val());
	var conv = parseFloat("<?php echo $conv; ?>");
	var total = (withdraw * conv) - ((withdraw * conv) * 0.08);
	
	jQuery('#eta').text(total.toFixed(2));
}
</script>