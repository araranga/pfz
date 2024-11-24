<?php
$sdata = array();
$field[] = array("type"=>"text","value"=>"pokename","label"=>"Boss Name");


$q = mysql_query_md("SELECT * FROM tbl_damage");
while($rowxxx = mysql_fetch_md_assoc($q)) {
	
	$damages[$rowxxx['type']] = ucwords($rowxxx['type']);
}





$field[] = array("type"=>"select","value"=>"pokeclass","label"=>"Armor Element:","option"=>$damages);
$field[] = array("type"=>"number","value"=>"reward","label"=>"Reward");
$field[] = array("type"=>"number","value"=>"reward_money","label"=>"Reward Money");
$field[] = array("type"=>"number","value"=>"level","label"=>"Level");


$field[] = array("type"=>"text","value"=>"attack","label"=>"Attack");
$field[] = array("type"=>"text","value"=>"defense","label"=>"Defense");
$field[] = array("type"=>"text","value"=>"hp","label"=>"HP");
$field[] = array("type"=>"text","value"=>"speed","label"=>"Speed");
$field[] = array("type"=>"text","value"=>"critical","label"=>"Critical");
$field[] = array("type"=>"text","value"=>"accuracy","label"=>"Accuracy");
$field[] = array("type"=>"text","value"=>"main","label"=>"Image");


$field[] = array("type"=>"text","value"=>"skillname1","label"=>"Skill 1 Name");
$field[] = array("type"=>"select","value"=>"element1","label"=>"Skill 1 Element:","option"=>$damages);
$field[] = array("type"=>"text","value"=>"power1","label"=>"Skill 1 Power");

$field[] = array("type"=>"text","value"=>"skillname2","label"=>"Skill 2 Name");
$field[] = array("type"=>"select","value"=>"element2","label"=>"Skill 2 Element:","option"=>$damages);
$field[] = array("type"=>"text","value"=>"power2","label"=>"Skill 2 Power");


$field[] = array("type"=>"text","value"=>"skillname3","label"=>"Skill 3 Name");
$field[] = array("type"=>"select","value"=>"element3","label"=>"Skill 3 Element:","option"=>$damages);
$field[] = array("type"=>"text","value"=>"power3","label"=>"Skill 3 Power");



$q = mysql_query_md("SELECT * FROM tbl_emblem");
while($rowxxx = mysql_fetch_md_assoc($q)) {
	
	$emblemrow[$rowxxx['id']] = ucwords($rowxxx['title_name']);
}

$field[] = array("type"=>"select","value"=>"emblem","label"=>"Emblem:","option"=>$emblemrow);

?>
<h2>Boss Add</h2>


<div class="panel panel-default">
   <div class="panel-body">
      <form method='POST' action='?pages=<?php echo $_GET['pages'];?>'>
	  <input type='hidden' name='task' value='<?php echo $_GET['task'];?>'>
        <?php echo loadform($field,$sdata); ?>
         <center><input class='btn btn-primary btn-lg' type='submit' name='submit' value='<?php echo ucwords($_GET['task']);?>'></center>
      </form>
   </div>
</div> 


