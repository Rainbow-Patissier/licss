<?php
include_once('../../../data/link.php');
$record=array();
$record['res']=true;
$record['info']=array();
$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY book_id");
$stmt->execute();
$stmt2=$pdo->prepare("SELECT ndc_code FROM {$school_id}_manage_setting_ndc WHERE ndc_id=? AND cond=0");
$stmt3=$pdo->prepare("SELECT name,name_iv FROM {$school_id}_manage_setting_location WHERE location_id=? AND cond=0");
foreach($stmt as $row){
	$author=array();
	foreach(array($row['author1'],$row['author2'],$row['author3'],$row['author4']) as $val){
		if(!empty($val)){
			$author[]=ALL::h($val);
		}
	}
	$stmt2->execute(array($row['ndc']));
	if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
		$ndc=$row2['ndc_code'];
	}else{
		$ndc='';
	}
	if(empty($row['drop_date'])){
		$stmt3->execute(array($row['location']));
		if($row3=$stmt3->fetch(PDO::FETCH_ASSOC)){
			$location=SQL::decrypt_school($row3['name'],$row3['name_iv']);
		}else{
			$location='';
		}
	}else{
		$location='廃棄済';
	}
	$record['info'][]=array(
		sprintf('%09d',$row['book_id']),
		$row['isbn'],
		ALL::h($row['entry_date']),
		ALL::h($row['series']),
		ALL::h($row['book_name']),
		ALL::h($row['sub_name']),
		implode('<br>',$author),
		ALL::h($row['publisher']),
		ALL::h($row['publish_date']),
		ALL::h($row['size']),
		ALL::h($row['page']),
		ALL::h($ndc),
		ALL::h(implode('-',array($row['seikyu1'],$row['seikyu2'],$row['seikyu3']))),
		ALL::h($location),
		'<a href="'.$url.'manage/books/entry/?i='.SQL::encrypt_k($row['book_id']).'">編集</a>'
	);
}
header("Content-Type: application/json");
echo json_encode($record);
