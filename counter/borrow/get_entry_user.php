<?php
include_once('../../data/link.php');
$result=array();
if(isset($_POST['i'])){
	$id=(int)$_POST['i'];
	if($id===0){
		$result['res']=false;
		$result['reason']='無効な利用者番号です';
	}else{
		$name=USER::name($id);
		if($name==='No Name'){
			$result['res']=false;
			$result['reason']='存在しないユーザーです';
		}else{
			$result['res']=true;
			$result['name']=$name;
		}
	}
}else{
	$result['res']=false;
	$result['reason']='無効な入力です';
}
header('Content-Type: application/json');
echo json_encode($result);