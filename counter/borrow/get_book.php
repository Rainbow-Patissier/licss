<?php
include_once('../../data/link.php');
$result=array();
if(isset($_POST['i'])){
	$id=(int)$_POST['i'];
	if($id===0){
		$result['res']=false;
	}else{
		$name=GET::book_name($id);
		if($name==='No Book'){
			$result['res']=false;
			$result['reason']='存在しない本です';
		}else{
			$row=SQL::fetch("SELECT COUNT(book_id) as cnt FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($id));
			if($row['cnt']==0){
				$result['res']=true;
				$result['id']=sprintf('%09d',$id);
				$result['name']=$name;
			}else{
				$result['res']=false;
				$result['reason']='貸出中の本です';
			}
		}
	}
}else{
	$result['res']=false;
	$result['reason']='無効な送信です';
}
header('Content-Type: application/json');
echo json_encode($result);