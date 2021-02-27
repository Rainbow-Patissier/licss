<?php
include_once('../../../data/link.php');
/*
post['t']=type
保管場所-save_place
財源-financial_resources
書店-book_store
*/
$result=array();
if(isset($_POST['t'])){
	$result['res']=true;
	$result['list']=array();
	switch((string)$_POST['t']){
		case 'save_place':
			$stmt=$pdo->prepare("SELECT location_id,name,name_iv FROM {$school_id}_manage_setting_location WHERE cond=0 ORDER BY location_id");
			$stmt->execute();
			foreach($stmt as $row){
				$result['list'][]=array(
					'id'=>SQL::encrypt_ks($row['location_id']),
					'name'=>ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']))
				);	
			}
			break;
		case 'financial_resources':
			$stmt=$pdo->prepare("SELECT financial_id,name,name_iv FROM {$school_id}_manage_setting_financial WHERE cond=0 ORDER BY financial_id");
			$stmt->execute();
			foreach($stmt as $row){
				$result['list'][]=array(
					'id'=>SQL::encrypt_ks($row['financial_id']),
					'name'=>ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']))
				);	
			}
			break;
		case 'book_store':
			$stmt=$pdo->prepare("SELECT store_id,name,name_iv,store_code FROM {$school_id}_manage_setting_store WHERE cond=0 ORDER BY store_id");
			$stmt->execute();
			foreach($stmt as $row){
				$result['list'][]=array(
					'id'=>SQL::encrypt_ks($row['store_id']),
					'name'=>ALL::h(SQL::decrypt_school($row['name'],$row['name_iv'])),
					'code'=>$row['store_code']
				);	
			}
			break;
		case 'ndc':
			$stmt=$pdo->prepare("SELECT ndc_id,name,name_iv,ndc_code FROM {$school_id}_manage_setting_ndc WHERE cond=0 ORDER BY ndc_code");
			$stmt->execute();
			foreach($stmt as $row){
				$result['list'][]=array(
					'id'=>SQL::encrypt_ks($row['ndc_id']),
					'name'=>ALL::h(SQL::decrypt_school($row['name'],$row['name_iv'])),
					'ndc'=>$row['ndc_code']
				);	
			}
			break;
	}
}else{
	$result['res']=false;
}
header("Content-Type:application/json");
echo json_encode($result);