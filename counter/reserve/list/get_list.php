<?php
include_once('../../../data/link.php');
$result=array();
if(isset($_GET['page'])){
	$page=(int)$_GET['page'];
	if($page===0){
		$result['res']=false;
		$result['reason']='無効な入力です';
	}else{
		$result['res']=true;
		$row=SQL::fetch("SELECT COUNT(reserve_id) as cnt FROM {$school_id}_counter_reserve");
		$result['all_pages']=ceil(((int)$row['cnt']-1)/100);
		$result['all_records']=$row['cnt'];
		$result['current_page']=$page;
		$result['first_record']=($page-1)*100+1;
		$last_record=$page*100;
		if($last_record>$row['cnt']){
			$last_record=$row['cnt'];
		}
		$result['last_record']=$last_record;
		$result['record']=array();
		$stmt=$pdo->prepare("SELECT book_id,user_id,reserve_date,cond FROM {$school_id}_counter_reserve ORDER BY edit_date DESC");
		$stmt->execute();
		foreach($stmt as $row){
			switch((int)$row['cond']){
				case 1:
					$condition='予約中';
					break;
				case 2:
					$condition='予約キャンセル済';
					break;
				case 3:
					$condition='貸出済';
			}
			$result['record'][]=array(
				sprintf('%09d',$row['book_id']),
				GET::book_name($row['book_id']),
				'['.USER::belong($row['user_id']).']'.USER::name($row['user_id']).'('.$row['user_id'].')',
				$row['reserve_date'],
				$condition,
				'condition_id'=>(int)$row['cond']
			);
		}
	}
}else{
	$result['res']=false;
	$result['reason']='無効な入力です';
}
header('Content-Type: application/json');
echo json_encode($result);