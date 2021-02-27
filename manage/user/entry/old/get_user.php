<?php
include_once('../../../../data/link.php');
$result=array();
if(isset($_POST['i'])){
	$id=(int)$_POST['i'];
	if($id===0){
		$result['res']=false;
	}else{
		$row=SQL::fetch("SELECT COUNT(user_id) as cnt FROM {$school_id}_account_class WHERE user_id=? AND cond=0",array($id));
		if((int)$row['cnt']>0){
			$result['res']=true;
			$result['name']='Exist';
		}else{
			$name=USER::name($id);
			if($name==='No Name'){
				$result['res']=false;
			}else{
				$result['res']=true;
				$result['name']=$name;
			}
		}
	}
}else{
	$result['res']=false;
}
header("Content-Type:application/json");
echo json_encode($result);