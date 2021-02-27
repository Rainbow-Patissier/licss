<?php
include_once('../../data/link.php');
$result=array();
if(isset($_GET['p']) && $_GET['c']){
	$page=$_GET['p'];
	$current=$_GET['c'];
	$row=SQL::fetch("SELECT COUNT(*) as cnt FROM {$school_id}_counter_borrow WHERE user_id=?",array($user_id));
	$all_page=(int)ceil(((int)$row['cnt']/10));
	$result['res']=true;
	$result['all_page']=$all_page;
	$result['cnt']=$row['cnt'];
	if($page==='top'){
		$display=1;
	}elseif($page==='last'){
		$display=$all_page;
	}else{
		$display=(int)$current+(int)$page;
	}
	$result['current_page']=(int)$display;
	$result['record']=array();
	$offset=($display-1)*10;
	$stmt=$pdo->prepare("SELECT book_id,borrow_date,reserve_scheduled,reserve_date FROM {$school_id}_counter_borrow WHERE user_id=? ORDER BY borrow_id DESC LIMIT 10 OFFSET {$offset}");
	$stmt->execute(array($user_id));
	foreach($stmt as $row){
		$result['record'][]=array(sprintf('%09d',$row['book_id']),GET::book_name($row['book_id']),$row['borrow_date'],$row['reserve_scheduled'],$row['reserve_date']);
	}
}else{
	$result['res']=false;
	$result['reason']='無効なアクセスです';
}
#print_r($result);
header("Content-Type:application/json");
echo json_encode($result);