<?php
include_once('../../../data/link.php');
$result=array();
if(isset($_POST['i'])){
	$id=(int)$_POST['i'];
	if($id===0){
		$result['res']=false;
		$result['reason']='無効な図書番号です';
	}else{
		$name=GET::book_name($id);
		if($name==='No Book'){
			$result['res']=false;
			$result['reason']='存在しない図書番号です';
		}else{
			$row=SQL::fetch("SELECT COUNT(book_id) as cnt FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($id));
			if($row['cnt']==0){
				$result['res']=false;
				$result['reason']='貸出されていません';
			}else{
				$result['res']=true;
				$book_info=GET::book_info($id);
				$result['name']=$name;
				$result['seikyu']=GET::seikyu($id);
				$result['location']=$book_info[1];
			}
			
		}
	}
}else{
	$result['res']=false;
	$result['reason']='無効な入力です';
}
header('Content-Type: application/json');
echo json_encode($result);