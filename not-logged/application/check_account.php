<?php
include_once('../../data/link.php');
$result=array();
if(isset($_POST['a'])){
	$result['res']=true;
	$row=SQL::fetch("SELECT COUNT(account) as cnt FROM account_info WHERE account=?",array($_POST['a']));
	if((int)$row['cnt']===0){
		$result['usable']=true;
	}else{
		$result['usable']=false;
	}
}else{
	$result['res']=false;
}
header("Content-Type: application/json");
echo json_encode($result);