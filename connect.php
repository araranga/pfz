<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if(!empty($_GET['installnew']))
{


	$data = "tbl_accounts,tbl_expenses,tbl_files,tbl_loan,tbl_members,tbl_mutual,tbl_passbook,tbl_permission,tbl_schedule,tbl_schedule_mutual,tbl_sql,tbl_system";		

	foreach(explode(",",$data) as $v)
	{		
		mysql_query_md("ALTER TABLE `$v` ADD `stores` INT(255) NULL DEFAULT '1'");
	}



}



function moveredirect($url){
  echo "<script> window.location = '{$url}'; </script>";
}


function getconnection(){
  return new mysqli("localhost","root","root","pfz","8888");
}
function recordsql($q){

  return $q;
// if($_GET['debug']==1){

//   echo $q."<Br>";
// }
//   $querydata = addslashes($_SESSION['username']."==".$q);
//   $query = "INSERT INTO tbl_sql SET querydata='{$querydata}'";

$a = strtolower($q);
$t = 0;
if (strpos($a, 'insert') !== false) {
   $t = 1;
}
if (strpos($a, 'delete') !== false) {
   $t = 1;
}
if (strpos($a, 'update') !== false) {
   $t = 1;
}

if($t==0){
  return;
}

    $mysqli = getconnection();
    // Check connection
    if ($mysqli -> connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
      exit();
    }
    // Perform query
    if ($result = $mysqli -> query($query)) {
      // Free result set
      
    }
    $id = $mysqli->insert_id;
    $mysqli -> close();

}


mysql_query_md("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");


function mysql_query_md($q){

  recordsql($q);
  //if($_GET['debug']){
  //echo $q."<hr>";
  //}
		$mysqli = getconnection();

		// Check connection
		if ($mysqli -> connect_errno) {
		  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
		  exit();
		}

		// Perform query
		if ($result = $mysqli -> query($q)) {
		  // Free result set
		  
		}

		$mysqli -> close();

		return $result;
}



function mysql_query_md_insert($q){
    recordsql($q);
    $mysqli = getconnection();

    // Check connection
    if ($mysqli -> connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
      exit();
    }
    // Perform query
    if ($result = $mysqli -> query($q)) {
      // Free result set
      
    }

    $id = $mysqli->insert_id;

    $mysqli -> close();

    return $id;
}



function mysql_num_rows_md($result){
	return mysqli_num_rows($result);
}

function mysql_fetch_md_assoc($result){
	return mysqli_fetch_assoc($result);
}

function mysql_fetch_md_array($result){
	return mysqli_fetch_assoc($result);
}


function percentget($value){
	return ($value / 100);
 }

 function formatedateinput($date){
 	return date("Y-m-d",strtotime($date));
}




function generatedate($postarray){



if($postarray['payment_type']=='cutoff'){

	$given_date = strtotime($postarray['loan_start']);
	$given_date_nearcutoff = strtotime(date("Y-m-15",strtotime($postarray['loan_start'])));
	$current_date =strtotime(date("Y-m-d"));
	$given_num = $postarray['loop_number'];



	if($given_date>=$given_date_nearcutoff)
	{
		$payment[] = date("Y-m-t",$given_date);
	}
	else
	{	
		$payment[] = date("Y-m-15",$given_date);
		$payment[] = date("Y-m-t",$given_date);
		//$given_date = strtotime('+1 month', $given_date);
	}
	$counter = 0;


	$given_date = strtotime('+1 month', $given_date);


	for ($i = count($payment) + 1; $i <= $given_num; $i++) {
	$counter++;
	if($counter==1){
		$for_end = ($given_date);
	}else{
		$for_end = strtotime('+'.($counter - 1).' month', $given_date);
	}
	

    $payment[] = date('Y-m-15', $for_end);
	$i++;
	if($i<=$given_num){
	$payment[] = date('Y-m-t', $for_end); 		
	}




	}




}

if($postarray['payment_type']=='monthly'){

$given_date = strtotime($postarray['loan_start']);
$is_end = 0;
if($given_date == strtotime(date("Y-m-t",$given_date)))
{
	$given_date = strtotime(date("Y-m",$given_date));
	$is_end = 1;
}
$current_date =strtotime(date("Y-m-d"));






$payment[] = date('Y-m-d', strtotime($postarray['loan_start']));

$given_num = $postarray['loop_number'];


// $given_date = strtotime('now');
$given_day = $postarray['helper'];

// $for_start = strtotime('Friday', $given_date);
$for_start = strtotime($given_date);
$for_end = strtotime('+'.$given_num.' month', $given_date);
// $for_end = strtotime($postarray['edate']);
for ($i = 2; $i <= $given_num; $i++) {

	$for_end = strtotime('+'.($i - 1).' month', $given_date);
	if($is_end){
		$payment[] = date('Y-m-t', $for_end);
	}else{
		$payment[] = date('Y-m-d', $for_end);
	}
    
}


}
if($postarray['payment_type']=='weekly'){

$given_date = strtotime($postarray['loan_start']);


// $given_date = strtotime('now');
$given_day = $postarray['helper'];
$given_num = $postarray['loop_number'];
// $for_start = strtotime('Friday', $given_date);
$for_start = strtotime($given_day, $given_date);
$for_end = strtotime('+'.$given_num.' week', $given_date);
// $for_end = strtotime($postarray['edate']);


$counter = 0;
for ($i = $for_start; $i <= $for_end; $i = strtotime('+1 week', $i)) {
    $counter++;
    $payment[] = date('Y-m-d', $i);
    if($counter==$given_num){
        break;
    }
}

}

	return $payment;
}

















            function loadform($field,$sdata = array(),$is_editable_field = 1)
            {
				

               $return = '<div class="card card-primary"><div class="card-body">';
               foreach($field as $inputs)
               {



    
                      $addedattr = array();
               					if($inputs['attributes'])
               					{

	               					

	               					foreach($inputs['attributes'] as $keya=>$vala){

	               						$addedattr[] = "$keya=\"$vala\"";


	               					}

	               					
               		

               					}


                          if($_GET['task']=='delete' || $_GET['task']=='view'){

                              $addedattr[] = "disabled=\"disabled\"";
                          }


                        $inputs['attr'] = implode(" ", $addedattr);

                       if($inputs['label']!='')
                       {
                       $label = $inputs['label'];
                       }
                       else
                       {
                       $label = ucwords($inputs['value']);
                       }

                  
                  $return .= "<div class=\"form-group\">";     
                  $return .= "<label for=\"exampleInputEmail1\">{$label}{$req_fld}</label>";
     				 if ( $is_editable_field ) { 

                     if ($inputs['type']=='select')
                     {                                                                                               
                      
                  $return .= "<select class='form-control' name=\"{$inputs['value']}\" id=\"{$inputs['value']}\" required {$inputs['attr']}>";

                        foreach($inputs['option'] as $key=>$val)
                        {
                        	$tselect = '';
                        	if($sdata[$inputs['value']]==$key){ $tselect = "selected='selected'"; }
                        	$return .= "<option {$tselect} value='{$key}'>{$val}</option>";

                        }

                        $return .= "</select><span class=\"validation-status\"></span>";

       
                     }

                     else if($inputs['type']=='textarea'){
                      $sdata[$inputs['value']] = htmlentities($sdata[$inputs['value']]);
                     	$return .= "<textarea class='form-control' {$inputs['attr']} required name=\"{$inputs['value']}\" id=\"{$inputs['value']}\" name=''>{$sdata[$inputs['value']]}</textarea>";

                     }

                     else if($inputs['type']=='editor'){
                      $sdata[$inputs['value']] = htmlentities($sdata[$inputs['value']]);
                      $return .= "<textarea style='height:300px'  class='editor form-control' {$inputs['attr']} name=\"{$inputs['value']}\" id=\"{$inputs['value']}\" name=''>{$sdata[$inputs['value']]}</textarea>";

                     }


                     else
                     {
					           $stepany = '';
                      if($inputs['type']=='number'){
                        $stepany = "step='any'";
                      }
						$return .= "<input class='form-control' $stepany required {$inputs['attr']} type=\"{$inputs['type']}\" name=\"{$inputs['value']}\" id=\"{$inputs['value']}\" size=\"40\" maxlength=\"255\" value=\"{$sdata[$inputs['value']]}\" />";

						            $return .= "<span class=\"validation-status\"></span>";
                        $return .= "</div>";
            

                     }
               
                  
           		} else {  
           			//$return .= "<td>{$sdata[$inputs['value']]}</td>";
           		} 
                
               }


           		$return .= "</div></div>";

           		return $return;
           		}




              function multiformconfig($code,$name = NULL,$array = array()){


                $arrayconvert = json_decode($array,true);

                $return = "";

                if(empty($name)){
                  $name = ucwords($code);
                }

         $return.= "<hr>

         <h3>$name</h3>
         <table id='multi-{$code}' class='table table-striped table-bordered table-hover dataTable no-footer'>
            <thead><tr role='row'>
                  <th>Label</th>
                  <th>Value</th>
                  <th>Action</th>
            </tr>
          </thead> <tbody class='tbodyconfig{$code}'>



          ";


          if(empty($arrayconvert)){


              for($i=1;$i<=5;$i++){
                $return .= "<tr class='tr-{$code}-$i'>
              <td><input type='text' name='{$code}[$i][label]'></td>
              <td><input type='text' name='{$code}[$i][value]'></td>
              <td><a onclick=\"removeme('tr-{$code}-$i')\" href='javascript:void(0)'>Remove</a></td>
            </tr>";
              }

          }else{


            foreach($arrayconvert as $k=>$v){

              $labela = addslashes($arrayconvert[$k]['label']);
              $valuea = addslashes($arrayconvert[$k]['value']);
                $return .= "<tr class='tr-{$code}-$k'>
              <td><input type='text' name='{$code}[$k][label]' value='{$labela}'></td>
              <td><input type='text' name='{$code}[$k][value]' value='{$valuea}'></td>
              <td><a onclick=\"removeme('tr-{$code}-$k')\" href='javascript:void(0)'>Remove</a></td>
            </tr>";



            }



          }
         

         


          $return .= " </tbody>
          <tfoot>
 <tr><td colspan='3'><a onclick=\"addtable('{$code}')\" href='javascript:void(0)'>Add more field</a></td></tr>         
          </tfoot>
          </table>
          <br><br><hr>";

          return $return;


              }


              
              function ratedata($id){

                $q = mysql_query_md("SELECT * FROM `tbl_rate` WHERE rate_id='$id'");

                $a = mysql_fetch_md_array($q);


                return $a;
              }

              function userdata($id){

                $q = mysql_query_md("SELECT * FROM `tbl_accounts` WHERE accounts_id='$id'");

                $a = mysql_fetch_md_array($q);

                return $a;
              }


              function cmsdata($id){

                $q = mysql_query_md("SELECT * FROM `tbl_cms` WHERE id='$id'");

                $a = mysql_fetch_md_array($q);

                return $a;
              }


              function systemconfig($code){

                $q = mysql_query_md("SELECT value FROM `tbl_system` WHERE code='$code'");

                $a = mysql_fetch_md_array($q);


                return $a['value'];
              }



              function getarrayconfig($code){

                $q = mysql_query_md("SELECT value FROM `tbl_system` WHERE code='$code'");

                $a = mysql_fetch_md_array($q);
                $return = array();


                if(!empty($a['value'])){

                $data =  json_decode($a['value'],true);

                foreach($data as $k=>$v){

                    $return[$data[$k]['value']] = $data[$k]['label'];


                }


                }
                

                return $return;
              }


?>