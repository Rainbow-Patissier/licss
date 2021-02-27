<?php
include_once('../../data/link.php');
$result=array();
if(isset($_POST['i'])){
	$id=(int)$_POST['i'];
	if($id===0){
		$result['res']=false;
		$result['reason']='無効な図書番号が入力されました';
	}else{
		if($row=SQL::fetch("SELECT user_id,borrow_date FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($id))){
			$res=SQL::execute("UPDATE {$school_id}_counter_borrow SET reserve_date=? WHERE book_id=?",array($Ymdt,$id));
			if($res){
				$user=$row['user_id'];
				$book_info=GET::book_info($id);
				$result['res']=true;
				$result['user']=sprintf('%08d',$user).'&nbsp;&nbsp;'.USER::name($user);
				$result['bookname']=$book_info[0];
				$result['bookid']=$id;
				$result['seikyu']=GET::seikyu($id);
				$result['location']=$book_info[1];
				$result['borrowdate']=$row['borrow_date'];
				$row=SQL::fetch("SELECT COUNT(book_id) as cnt FROM {$school_id}_counter_reserve WHERE book_id=? AND cond=1",array($id));
				$result['reserve']=(int)$row['cnt'];
				$stmt=$pdo->prepare("SELECT book_id,borrow_date,reserve_scheduled FROM {$school_id}_counter_borrow WHERE user_id=? AND reserve_date IS NULL");
				$stmt->execute(array($user));
				$result['notreverse']=array();
				foreach($stmt as $row){
					$result['notreverse'][]=array('id'=>sprintf('%09d',$row['book_id']),'name'=>GET::book_name($row['book_id']),'borrow'=>$row['borrow_date'],'reverse'=>$row['reserve_scheduled']);
				}
			}else{
				$result['res']=false;
				$result['reason']='返却処理ができませんでした';
			}
		}else{
			$result['res']=false;
			$result['reason']='貸し出されていない本です';
		}
	}
}else{
	$result['res']=false;
	$result['reason']='無効な入力です';
}
header('Content-Type: application/json');
echo json_encode($result);