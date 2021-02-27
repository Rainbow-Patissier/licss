<?php
include_once('../../data/link.php');
$result=array();
if(isset($_POST['book']) && isset($_POST['user']) && isset($_POST['borrow_date']) && isset($_POST['reverse_date']) && isset($_POST['entry_user'])){
	$user=(int)$_POST['user'];
	$entry_user=(int)$_POST['entry_user'];
	$book_id=explode('-',$_POST['book']);
	$borrow_date=new DateTime($_POST['borrow_date']);
	$borrow_date=$borrow_date->format('Y-m-d H:i:s');
	$reverse_date=new DateTime($_POST['reverse_date']);
	$reverse_date=$reverse_date->format('Y-m-d H:i:s');
	$row=SQL::fetch("SELECT MAX(borrow_id) as max FROM {$school_id}_counter_borrow");
	$borrow_id=(int)$row['max']+1;
	$stmt=$pdo->prepare("INSERT INTO {$school_id}_counter_borrow VALUES (?,?,?,?,?,?,NULL)");
	foreach($book_id as $val){
		if($stmt->execute(array($borrow_id,$entry_user,$val,$user,$borrow_date,$reverse_date))){
			$result['res']=true;
			//予約処理
			SQL::execute("UPDATE {$school_id}_counter_reserve SET cond=3 WHERE book_id=? AND user_id=?",array($book_id,$user));
			++$borrow_id;
		}else{
			$result['res']=false;
			$result['reason']='登録に失敗';
		}
	}
}else{
	$result['res']=false;
	$result['reason']='予想外のエラー';
}
header('Content-Type: application/json');
echo json_encode($result);