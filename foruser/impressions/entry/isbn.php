<?php
include_once('../../../data/link.php');
$result=array();
if(isset($_GET['book_id'])){
	if($row=SQL::fetch("SELECT isbn FROM {$school_id}_manage_books_info WHERE cond=0 AND book_id=?",array((int)$_GET['book_id']))){
		$result['res']=true;
		$result['isbn']=(int)$row['isbn'];
	}else{
		$result['res']=true;
		$result['isbn']='';
	}
}else{
	$result['res']=false;
	$result['reason']='無効なURLです';
}
header('Content-Type:application:json');
echo json_encode($result);