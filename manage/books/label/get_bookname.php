<?php
include_once('../../../data/link.php');
$record=array();
if(isset($_POST['i'])){
	$record['res']=true;
	$record['book_name']='本の名前'.$_POST['i'];
}else{
	$record['res']=false;
}
header("Content-Type: application/json");
echo json_encode($record);