<?php
include_once('../../../data/link.php');
$record=array();
if(isset($_POST['i'])){
	$record['res']=true;
	$record['number']='913';
	$record['hiragana']='a';
	$record['series']='1';
}else{
	$record['res']=false;
}
header("Content-Type: application/json");
echo json_encode($record);