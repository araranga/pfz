<?php
function addeco($amount)
{
    mysql_query_md("UPDATE tbl_system SET value = value + $amount WHERE code='systemfund'");
}

function listweapon()
{

    $weapons = "sword,claw,punch,pike,gun,crossbow,bow,staff,whip,axe,mace,dagger";

    $array = array();

    foreach (explode(",", $weapons) as $d)
    {

        $array[$d] = $d;

    }

    return $array;
}

function checkbeta($betakey)
{
    $q = mysql_query_md("SELECT * FROM tbl_betakey WHERE betakey='$betakey' AND user IS NULL");
    $row = mysql_fetch_md_assoc($q);
    $count = mysql_num_rows_md($q);

    return $count;
}

function subeco($amount)
{
    mysql_query_md("UPDATE tbl_system SET value = value - $amount WHERE code='systemfund'");
}

function getcoin()
{
    $systemfund = systemconfig("systemfund");
    $query_dr = "SELECT SUM(balance) as c FROM `tbl_accounts`";
    $rowdr = mysql_fetch_md_array(mysql_query_md($query_dr));

    $rowdr2 = mysql_fetch_md_array(mysql_query_md("SELECT SUM(amount) as c FROM `tbl_withdraw_history`"));

    $conv = ($systemfund - ($rowdr["c"] + $rowdr2["c"])) * 0.0005;
	
	if($conv<0){
	
		$conv = 0.05;
		
	}
	

    return $conv;
}


function deductloser($loserpoke)
{
	$poke = loadpokev2($loserpoke);
    $member = loadmember($poke['user']);

    if ($member['balance'] > 50)
    {
		mysql_query_md("INSERT INTO tbl_income SET user='{$member['accounts_id']}', message='You Lose a battle: -0.05'");
        mysql_query_md("UPDATE tbl_accounts SET balance = balance - 0.5 WHERE accounts_id='{$member['accounts_id']}'");

    }
	
    if ($member['balance'] > 100)
    {
		mysql_query_md("INSERT INTO tbl_income SET user='{$member['accounts_id']}', message='You Lose a battle: -0.15'");
        mysql_query_md("UPDATE tbl_accounts SET balance = balance - 1 WHERE accounts_id='{$member['accounts_id']}'");

    }	

}

function checkpoke($winnerpoke)
{
    $poke = loadpokev2($winnerpoke);

    $userid = $poke["user"];
	
	$member = loadmember($poke['user']);
	
	
    $rewardwin = systemconfig("battlereward");
	if(empty($member['robot'])){
    mysql_query_md("UPDATE tbl_accounts SET balance = balance + $rewardwin WHERE accounts_id='$userid'");
	mysql_query_md("INSERT INTO tbl_income SET user='{$userid}', message='You Won a battle: {$rewardwin}'");
	}

    $req = $poke["level"] * 6;
	
	if($poke['level']==30){
		return;
	}
	
    if ($req <= $poke["exp"])
    {
        pokelevelup($winnerpoke, $poke["rate"]);
    }
}

function transgen()
{
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randstring = "";
    for ($i = 0;$i < 12;$i++)
    {
        $randstring .= $characters[rand(0, strlen($characters)) ];
    }
    return $randstring;
}

function pokelevelup($id, $rate)
{

    $rate_hp = $rate + rand(5, 25);
    mysql_query_md("UPDATE tbl_pokemon_users SET level = level + 1,attack = attack + $rate,defense = defense + $rate,
		hp = hp + $rate_hp, speed = speed + 1,critical = critical + 1,accuracy = accuracy + 1
		WHERE id='$id'");
}
function loadmovesfrontend($hash)
{
    $querys = "SELECT * FROM tbl_movesreindex WHERE pokehash='$hash'";
    $skillsq = mysql_query_md($querys);
    $skillarray = [];
    while ($skillsd = mysql_fetch_md_assoc($skillsq))
    {
        $skillarray[$skillsd["id"]] = $skillsd;
    }

    return $skillarray;
}

function randomskills($hash)
{
    mysql_query_md("UPDATE tbl_movesreindex SET activate=1 WHERE pokehash='$hash' ORDER BY RAND() limit 5");
}

function loadmoves($hash)
{
    $pokedata = loadpoke($hash);
    $pokeidd = $pokedata["id"];

    $querys = "SELECT * FROM `tbl_movesv2` as a LEFT JOIN tbl_pokemon_moves as b ON a.id=b.move_id WHERE b.pokemon_id = $pokeidd AND a.power !='' AND b.move_id IN (SELECT move_id FROM `tbl_pokemon_moves` WHERE `pokemon_id` LIKE '$pokeidd' GROUP by move_id ORDER BY `tbl_pokemon_moves`.`move_id` DESC) GROUP by a.identifier";
    $skillsq = mysql_query_md($querys);
    $skillarray = [];
    while ($skillsd = mysql_fetch_md_assoc($skillsq))
    {
        $skillsd["pokeid"] = $pokedata["id"];
        $skillsd["pokehash"] = $pokedata["hash"];
        $skillsd["pokemon"] = $pokedata["pokemon"];
        $skillsd["power"] = $skillsd["power"] + rand(1, 50);

        $skillarray[$skillsd["identifier"]] = $skillsd;
    }

    return $skillarray;
}

function generatemoves($hash)
{
    $skillarray = loadmoves($hash);

    foreach ($skillarray as $d)
    {
        $array = [];

        $array["typebattle"] = $d["typebattle"];
        $array["power"] = $d["power"];
        $array["title"] = $d["title"];
        $array["accuracy"] = $d["accuracy"];
        $array["move_id"] = $d["move_id"];
        $array["identifier"] = $d["identifier"];
        $array["pokemon"] = $d["pokemon"];
        $array["pokeid"] = $d["pokeid"];
        $array["pokehash"] = $d["pokehash"];

        $sql = [];

        foreach ($array as $a => $b)
        {
            $b = addslashes($b);
            $sql[] = "$a ='$b'";
        }

        $q = "INSERT INTO tbl_movesreindex SET " . implode(",", $sql);

        mysql_query_md($q);
    }
}

function loaditemuser($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_item_history WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loaditem($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_items WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadpoke($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE hash='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadweapon($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_items_users WHERE hash='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadweaponv2($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_items_users WHERE id='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}


function loadboss($hash)
{
    $q = mysql_query_md("SELECT * FROM tbl_bosses WHERE hash='$hash'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loadpokev2($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_pokemon_users WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}
function loadbossv2($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_bosses WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function loademblem($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_emblem WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}

function getEmblem($id)
{
    if (empty($id))
    {
    $q = mysql_query_md("SELECT * FROM tbl_emblem ORDER BY RAND() LIMIT 1");
    $row = mysql_fetch_md_assoc($q);
    $exp = explode(".", $row['image']);
    return $exp[0];
    }
    $q = mysql_query_md("SELECT * FROM tbl_emblem WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    $exp = explode(".", $row['image']);
    return $exp[0];
}

function getluck($chance, $odds)
{
    $luck = rand(1, $odds);
	
    if ($chance >= $luck)
    {
        return 1;
    }
    else
    {
        return 0;
    }
}

function loadpoketype($type)
{
    $q = mysql_query_md("SELECT * FROM tbl_damage WHERE type='$type'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}
function loadpokeskill($hash)
{
    $row = array();
    $q = mysql_query_md("SELECT * FROM tbl_movesreindex WHERE activate='1' AND pokehash='$hash'");
    while ($rowqpokes = mysql_fetch_md_assoc($q))
    {
        $row[] = $rowqpokes;
    }
    return $row;
}

function createcsv($list, $csvname)
{
    $file = fopen("uploads/{$csvname}.csv", "w");
    if (empty($list))
    {
        return;
    }
    foreach ($list as $line)
    {
        #var_dump($list);
        fputcsv($file, $line);
    }

    fclose($file);
}

function getbaseme()
{
    $q = mysql_query_md("SELECT * FROM tbl_core_config_data WHERE path='web/unsecure/base_url'");
    $row = mysql_fetch_md_array($q);
    return $row["value"];
}
function countfield($field, $value)
{
    $query = mysql_query_md("SELECT * FROM tbl_accounts WHERE $field='$value'");
    return mysql_num_rows_md($query);
}

function loadmember($id)
{
    $query = mysql_query_md("SELECT * FROM tbl_accounts WHERE accounts_id='$id'");
    return mysql_fetch_md_array($query);
}

function loadrow($table, $field, $id)
{
    $query = mysql_query_md("SELECT * FROM $table WHERE $field='$id'");
    return mysql_fetch_md_array($query);
}

function formquery($post)
{
    $return = [];
    foreach ($post as $key => $val)
    {
        $return[] = "$key='$val'";
    }
    return implode(",", $return);
}

function randid()
{
    return rand() . strtotime("now");
}
function totalaccount()
{
    $query = "SELECT username,accounts_id as aid,(SELECT COUNT(id) FROM tbl_cycle WHERE account_link = aid AND cycle_count=1 AND cycle_link = 0) as totalacct,account_count FROM tbl_accounts as acct
		JOIN tbl_package as pck WHERE pck.package_id = acct.package_id
		HAVING totalacct < account_count LIMIt 1";
    return mysql_query_md($query);
}

function autocreateaccount()
{
    return;
    while ($row = mysql_fetch_md_assoc(totalaccount()))
    {
        $limit = $row["account_count"] - $row["totalacct"];
        $aid = $row["aid"];
        for ($x = 1;$x <= $limit;$x++)
        {
            $username = $row["username"] . "-" . randid();
            mysql_query_md("INSERT INTO tbl_cycle SET username='$username',account_link='$aid',cycle_count='1',cycle_link='0'");
        }
        return;
    }
}
function autodetectparent()
{
    $query = "SELECT username,account_link,cycle_count,id as alink,(SELECT COUNT(id) FROM tbl_relation WHERE parent = alink) as checkparent FROM tbl_cycle HAVING checkparent < 2    ORDER by id ASC LIMIT 1 ";
    return mysql_query_md($query);
}
function autodetectchild($parentid)
{
    $query = "SELECT username,account_link,cycle_count,id as alink,(SELECT COUNT(id) FROM tbl_relation WHERE child = alink) as checkchild FROM tbl_cycle WHERE id!=$parentid AND id > $parentid HAVING checkchild = 0 ORDER by id ASC LIMIT 1";
    return mysql_query_md($query);
}
function loadcycle($id)
{
    $q = mysql_query_md("SELECT * FROM tbl_cycle WHERE id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row;
}
function getRate($id)
{
    $q = mysql_query_md("SELECT cycle_earn FROM tbl_package WHERE package_id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row["cycle_earn"];
}
function getUserPackage($id)
{
    $q = mysql_query_md("SELECT package_id FROM tbl_accounts WHERE accounts_id='$id'");
    $row = mysql_fetch_md_assoc($q);
    return $row["package_id"];
}
function addmoney($uid, $rate)
{
    mysql_query_md("UPDATE tbl_accounts SET balance = balance + $rate WHERE accounts_id=$uid");
}
function totalbalance($uid, $rate)
{
    mysql_query_md("UPDATE tbl_accounts SET total_earnings = total_earnings + $rate WHERE accounts_id=$uid");
}
function exitlabel($id)
{
    mysql_query_md("UPDATE tbl_cycle SET cycle_status = 1 WHERE id=$id");
}
function cycleinc($id)
{
    $user = loadcycle($id);
    $inc = $user["cycle_count"] + 1;
    exitlabel($id);

    $userpackage = getUserPackage($user["account_link"]);
    $rate = getRate($userpackage);
    totalbalance($user["account_link"], $rate);
    if ($inc == 4)
    {
        addmoney($account_link, $rate * 3);
        $username = "adminbonus-" . randid();
        $account_link = 1;
        $cycle_count = 1;
        $cycle_link = 0;
        mysql_query_md("INSERT INTO tbl_cycle SET username='$username',account_link='$account_link',cycle_count='$cycle_count',cycle_link='$cycle_link'");
    }
    else
    {
        $username = $user["username"] . "-" . $inc;
        $account_link = $user["account_link"];
        $cycle_count = $inc;
        if ($user["cycle_link"] == 0)
        {
            $cycle_link = $id;
        }
        else
        {
            $cycle_link = $user["cycle_link"];
        }

        $q = mysql_fetch_md_assoc(mysql_query_md("SELECT COUNT(id) as chet FROM tbl_cycle WHERE username='$username' AND account_link='$account_link' AND cycle_count='$cycle_count' AND cycle_link='$cycle_link'"));
        if ($q["chet"] == 0):
            mysql_query_md("INSERT INTO tbl_cycle SET username='$username',account_link='$account_link',cycle_count='$cycle_count',cycle_link='$cycle_link'");
        endif;
    }
}
function cycleevent($row)
{
    $rowx = mysql_fetch_md_assoc(autodetectchild($row["alink"]));
    $parent = $row["alink"];
    $child = $rowx["alink"];
    if ($child != "")
    {
        mysql_query_md("INSERT INTO tbl_relation SET parent='$parent',child='$child'");
        $q = mysql_fetch_md_assoc(mysql_query_md("SELECT COUNT(parent) as chet FROM tbl_relation WHERE parent='$parent'"));
        if ($q["chet"] == 2)
        {
            cycleinc($parent);
        }
    }
}
function mytimestamp()
{
    return date("Y-m-d H:i:s");
}

function getrow($var)
{
    $array["title"] = "Joint Lineage Microfinancing";
    $array["image"] = "logo.png";
    return $array;
}

function countquery($query)
{
    $q = mysql_query_md($query);
    return mysql_num_rows_md($q);
}

function getpackagelist()
{
    $packrowq = mysql_query_md("SELECT * FROM tbl_package");
    while ($packrow = mysql_fetch_md_assoc($packrowq))
    {
        $options[$packrow["package_id"]] = $packrow["package_name"];
    }
    return $options;
}

function getwheresearchv2($field)
{
    if ($_GET["pages"] == "report" || $_GET["pages"] == "withdraw" || $_GET["pages"] == "exchange" || $_GET["pages"] == "payments" || $_GET["pages"] == "bonuses")
    {
    }
    else
    {
        array_push($field, "stores");
    }

    $where = "WHERE ";
    $warray = [];
    $warray2 = [];

    if ($_GET["search"] != "")
    {
        $search = $_GET["search"];

        foreach ($field as $f)
        {
            $warray[] = "$f LIKE '%$search%'";
        }
    }

    $matic = 0;
    foreach ($field as $f)
    {
        if (!empty($_GET[$f]))
        {
            $warray2[] = "$f = '{$_GET[$f]}'";
        }
    }

    if ($_GET["pages"] == "report" || $_GET["pages"] == "withdraw" || $_GET["pages"] == "exchange" || $_GET["pages"] == "payments" || $_GET["pages"] == "bonuses")
    {
        //$warray2[]  = "tips.stores = '{$_SESSION['stores']}'";
        
    }
    else
    {
        $warray2[] = "stores = '{$_SESSION["stores"]}'";
    }

    if (count($warray))
    {
        $where .= "(" . implode(" OR ", $warray) . ")";
    }

    if (count($warray2))
    {
        if (count($warray))
        {
            $where .= " AND ";
        }
        $where .= implode(" AND ", $warray2) . " ";
    }

    if (trim($where) == "WHERE")
    {
        return;
    }

    return $where;
}

function getwheresearch($field)
{
    return getwheresearchv2($field);

    $where = "WHERE";
    if ($_GET["search"] != "")
    {
        $search = $_GET["search"];

        $where = "WHERE";

        foreach ($field as $f)
        {
            $where .= " $f LIKE '%$search%' OR";
        }
        $where .= " 1=1";
        $where = str_replace("OR 1=1", "", $where);
    }

    $matic = 0;
    foreach ($field as $f)
    {
        if (!empty($_GET[$f]))
        {
            $where .= " $f = '{$_GET[$f]}' OR";
        }
        $matic++;
    }
    if (!empty($matic))
    {
        $where .= " OR 1=1 ";
        $where = str_replace("OR 1=1", "", $where);
    }
    if (trim($where) == "WHERE")
    {
        return;
    }
    echo $where;
    return $where;
}

function getlimit($limit, $page)
{
    if ($page == "")
    {
        $page = 0;
    }
    else
    {
        $page--;
    }
    $limitx = $limit * $page;

    return "LIMIT $limitx,$limit";
}
function getpagecount($total, $limit)
{
    return ceil($total / $limit);
}

function csv()
{
    header("Content-Type: text/csv; charset=utf-8");

    header("Content-Disposition: attachment; filename=payout-" . $_GET["r"] . "-" . rand() . ".csv");

    // create a file pointer connected to the output stream
    $output = fopen("php://output", "w");

    if ($_GET["r"] == "bank")
    {
        $rows = mysql_query_md("SELECT b.accounts_id,b.username,transnum,email,amount,bank_name,bank_accountnumber,bank_accountname FROM  tbl_withdraw_history as a JOIN tbl_accounts as b WHERE claim_status=0 AND a.accounts_id=b.accounts_id AND claimtype='" . $_GET["r"] . "'

		");

        $array = explode(",", "accounts_id,username,transnum,email,amount,bank_name,bank_accountnumber,bank_accountname");
    }

    if ($_GET["r"] == "pickup")
    {
        $rows = mysql_query_md("SELECT b.accounts_id,b.username,transnum,email,amount FROM  tbl_withdraw_history as a JOIN tbl_accounts as b WHERE claim_status=0 AND a.accounts_id=b.accounts_id AND claimtype='" . $_GET["r"] . "'");

        $array = explode(",", "accounts_id,username,transnum,email,amount");
    }

    fputcsv($output, $array);

    // loop over the rows, outputting them
    while ($row = mysql_fetch_md_assoc($rows))
    {
        foreach ($row as $key => $val)
        {
            $row[$key] = "\"" . $val . "\"";
        }

        fputcsv($output, $row);
    }
}

function savebattlebot($hash, $user)
{
    $poke = loadpoke($hash);

    $id = $poke["id"];

    $user = $poke["user"];


   $rewardwin = systemconfig("battlelimitbot") + $_GET['ai'] + 100000;

    $current = date("Y-m-d");

    $queryx = "SELECT * FROM tbl_battlelog WHERE user='$user' AND battledata LIKE '%$current%'";
    $qx = mysql_query_md($queryx);
    $countx = mysql_num_rows_md($qx);

    if ($countx >= $rewardwin)
    {
        echo "Limited of $rewardwin Battle Per Day only";
        return;
    }

    $query = "SELECT * FROM tbl_battle WHERE (p1poke='$id' OR p2poke='$id') AND winner IS NULL";
    $q = mysql_query_md($query);
    $count = mysql_num_rows_md($q);

    $level = $poke['level'];

    $lessthan = $poke['level'] - systemconfig("gaplevel");
    $greaterthan = $poke['level'] + systemconfig("gaplevel");
    if ($lessthan < 0)
    {
        $lessthan = 0;
    }

    if (empty($count))
    {
        $queryp2 = "SELECT * FROM tbl_battle WHERE p2poke IS NULL AND p1poke!='$id' AND p1user!='$user' AND winner IS NULL AND (level >= $lessthan AND level <= $greaterthan) ORDER by level ASC";
        $qp2 = mysql_query_md($queryp2);
        $countp2 = mysql_num_rows_md($qp2);
        ///player 2
        if ($countp2 != 0)
        {
            $rowp2 = mysql_fetch_md_assoc($qp2);

            mysql_query_md("UPDATE tbl_battle SET p2user='$user',p2poke='$id' WHERE id='{$rowp2["id"]}'");
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
        }
        else
        {
            mysql_query_md("INSERT INTO tbl_battle SET p1user='$user',p1poke='$id',level='$level'");
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
        }

        //echo "Your Pokemon has already on queued. We will notify you on results. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
        
    }
    else
    {
        //echo "Your Pokemon already have pending battle. Please wait. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
        
    }
}

function savebattle($hash)
{
    $poke = loadpoke($hash);

    if (empty($poke["id"]))
    {
        echo "Warrior is not available. ID_MISSING";
        exit();
    }

    $id = $poke["id"];
    $user = $poke["user"];

    if ($user != $poke["user"])
    {
        echo "Warrior is not available. ACCT_INC";
        exit();
    }

    if (!empty($poke["is_market"]))
    {
        echo "Warrior is not able to battle since this is on sale on Market.";
        exit();
    }

    $level = $poke['level'];

    $lessthan = $poke['level'] - systemconfig("gaplevel");
    $greaterthan = $poke['level'] + systemconfig("gaplevel");
    if ($lessthan < 0)
    {
        $lessthan = 0;
    }
	
	if($poke['level']<=5){
		
		
		$lessthan = 1;
		$greaterthan = 6;
	}
	
	
	if($poke['level']<=10 && $poke['level']>=6){
		
		$lessthan = 6;
		
	}
	
	
	
	//echo "Debug::: $lessthan == $greaterthan";
	
	

    $loaduser = loadmember($poke['user']);
    $battlebonus = 0;
    //check for subscription
    $date_now = new DateTime();
    $date2 = new DateTime($loaduser['deadline']);

    if ($date_now > $date2)
    {
        $battlebonus = 0;
    }
    else
    {
        $battlebonus = $loaduser['deadline_bonus'];
    }
    //
    

    $rewardwin = systemconfig("battlelimit") + $battlebonus;

    $current = date("Y-m-d");

    $queryx = "SELECT * FROM tbl_battlelog WHERE user='$user' AND battledata LIKE '%$current%'";
    $qx = mysql_query_md($queryx);
    $countx = mysql_num_rows_md($qx);

    if ($countx >= $rewardwin)
    {
        echo "Limited of $rewardwin Battle Per Day only";
        exit();
    }

    $query = "SELECT * FROM tbl_battle WHERE (p1poke='$id' OR p2poke='$id') AND winner IS NULL";
    $q = mysql_query_md($query);
    $count = mysql_num_rows_md($q);

    if (empty($count))
    {
        $queryp2 = "SELECT * FROM tbl_battle WHERE p2poke IS NULL AND p1poke!='$id' AND p1user!='$user' AND winner IS NULL AND (level >= $lessthan AND level <= $greaterthan) ORDER by RAND() LIMIT 1";
        $qp2 = mysql_query_md($queryp2);
        $countp2 = mysql_num_rows_md($qp2);
        ///player 2
        if ($countp2 != 0)
        {
            $rowp2 = mysql_fetch_md_assoc($qp2);
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
            mysql_query_md("UPDATE tbl_battle SET p2user='$user',p2poke='$id' WHERE id='{$rowp2["id"]}'");
        }
        else
        {
            mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
            mysql_query_md("INSERT INTO tbl_battle SET p1user='$user',p1poke='$id',level='$level'");
        }

        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior has already on queued. Please wait..";
    }
    else
    {
        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior already have pending battle. Please wait. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
    }
}

function savebattleboss($hero, $boss)
{
    $herodata = loadpoke($hero);
    $bossdata = loadboss($boss);

    if (empty($herodata["id"]))
    {
        echo "Warrior is not available. ID_MISSING";
        exit();
    }
    if (empty($bossdata["id"]))
    {
        echo "Boss is not available. ID_MISSING";
        exit();
    }
    if (!empty($poke["is_market"]))
    {
        echo "Warrior is not able to battle since this is on sale on Market.";
        exit();
    }

    $id = $herodata["id"];
    $user = $herodata["user"];
    $boss_id = $bossdata["id"];

    if ($user != $herodata["user"])
    {
        echo "Warrior is not available. ACCT_INC";
        exit();
    }
    $loaduser = loadmember($herodata['user']);
    $battlebonus = 0;
    //check for subscription
    $date_now = new DateTime();
    $date2 = new DateTime($loaduser['deadline']);

    if ($date_now > $date2)
    {
        $battlebonus = 0;
    }
    else
    {
        $battlebonus = $loaduser['deadline_bonus'];
    }
    //
    

    $rewardwin = systemconfig("battlelimitboss");

    $current = date("Y-m-d");

    $queryx = "SELECT * FROM tbl_battle_boss WHERE p1user='$user' AND battledata LIKE '%$current%'";
    $qx = mysql_query_md($queryx);
    $countx = mysql_num_rows_md($qx);

    if ($countx >= $rewardwin)
    {
        echo "Limited of $rewardwin Battle Per Day only.";
        exit();
    }
    $query = "SELECT * FROM tbl_achievement WHERE hero='$id' AND boss='$boss_id' AND fightdate >= CURRENT_TIMESTAMP";
    $q = mysql_query_md($query);
    $rr = mysql_fetch_md_assoc($q);
    $count = mysql_num_rows_md($q);

    if (!empty($count))
    {

        echo "You already defeated this boss using ({$herodata['pokename']}). Unlock date for this will be on: " . date("M-d-Y h:i:s", strtotime($rr['fightdate']));
        exit();

    }

    $query = "SELECT * FROM tbl_battle_boss WHERE p1poke='$id' AND p2poke='$boss_id' AND winner IS NULL";
    $q = mysql_query_md($query);
    $count = mysql_num_rows_md($q);

    if (empty($count))
    {

        mysql_query_md("INSERT INTO tbl_battle_boss SET p1user='$user',p1poke='$id',p2user='0',p2poke='$boss_id'");

        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior has already on queued for boss battle. Please wait..";

        mysql_query_md("INSERT INTO tbl_battlelog SET user ='$user'");
        $query = "SELECT * FROM tbl_battle_boss WHERE p1poke='$id' AND p2poke='$boss_id' AND winner IS NULL";
        $qx = mysql_fetch_md_assoc(mysql_query_md($query));
        generatebattleboss($qx['id']);
    }
    else
    {
        echo "<i class=\"fas fa-spinner fa-spin\"></i>Your Warrior already have pending boss battle. Please wait. See here: <a href='index.php?pages=pokebattle'>Battles!</a>";
    }

    echo "<script>window.location='index.php?pages=pokebattleview-boss&id={$qx['id']}'; </script>";

}

function addmore($initial, $level, $percentage)
{

    $getadditonal = ($initial * $percentage) * $level;

    $final = $getadditonal + $initial;
    return $final;

}
?>
