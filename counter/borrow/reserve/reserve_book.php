<?php
include_once('../../../data/link.php');
$result=array();
if(isset($_POST['user']) && isset($_POST['book']) && isset($_POST['entry_user'])){
	$user=(int)$_POST['user'];
	$entry_user=(int)$_POST['entry_user'];
	$book=(int)$_POST['book'];
	if($user===0 || $book===0){
		$result['res']=false;
		$result['reason']='無効な図書番号です';
	}else{
		$row=SQL::fetch("SELECT MAX(reserve_id) as max FROM {$school_id}_counter_reserve");
		$reserve_id=$row['max']+1;
		if(SQL::execute("INSERT INTO {$school_id}_counter_reserve VALUES (?,?,?,?,?,?,?)",array($reserve_id,$entry_user,$book,$user,$Ymdt,1,$Ymdt))){
			$result['res']=true;
		}else{
			$result['res']=false;
			$result['reason']='登録に失敗';
		}
	}
}else{
	$result['res']=false;
	$result['reason']='無効な入力です';
}
header('Content-Type: application/json');
echo json_encode($result);