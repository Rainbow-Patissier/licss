<?php
include_once('../../data/link.php');
$result=array();
if(isset($_POST['i'])){
	$id=(int)$_POST['i'];
	if($id===0){
		$result['res']=false;
	}else{
		$name=USER::name($id);
		if($name==='No Name'){
			$result['res']=false;
		}else{
			$result['res']=true;
			$result['name']=$name;
			$result['belong']=USER::belong($id);
			if($row=SQL::fetch_all("SELECT book_id FROM {$school_id}_counter_borrow WHERE user_id=? AND reserve_date IS NULL",array($id))){
				$borrow_book=array_column($row,'book_id');
				$result['borrowing']=array();
				foreach($borrow_book as $val){
					$result['borrowing'][]=array('id'=>sprintf('%09d',$val),'name'=>GET::book_name($val));
				}
			}else{
				$result['borrowing']=array();
			}
			$borrow_info=USER::borrow_info($id);
			$result['reverse_date']=$borrow_info[1];
			$result['limit']=$borrow_info[0];
		}
	}
}else{
	$result['res']=false;
}
header('Content-Type: application/json');
echo json_encode($result);