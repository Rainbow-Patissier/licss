<?php
include_once('../../../data/link.php');
$record=array();
if(isset($_POST['k'])){
	$k=(int)SQL::decrypt_ks($_POST['k']);
	if($k===0){
		$record['res']=false;
	}else{
		$record['res']=true;
		switch($k){
			case 1:
				//図書台帳
				$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY book_id");
				break;
			case 2:
				//NDC
				$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY ndc");
				break;
			case 3:
				//保管場所
				$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY location");
				break;
			case 4:
				//財源
				$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY financial");
				break;
			case 5:
				//廃棄図書
				$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 AND (drop_date!='' OR drop_date!='0000-00-00') ORDER BY book_id");
				break;
			case 6:
				//新着図書
				$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY entry_date DESC LIMIT 100");
				break;
		}
		$stmt->execute();
		$stmt5=$pdo->prepare("SELECT ndc_code FROM {$school_id}_manage_setting_ndc WHERE cond=0 AND ndc_id=?");
		foreach($stmt as $row){
			$stmt5->execute(array($row['ndc']));
			$row5=$stmt5->fetch(PDO::FETCH_ASSOC);
			$record['result'][]=array(
				'book_id'=>ALL::h(sprintf('%09d',$row['book_id'])),
				'date'=>ALL::h($row['entry_date']),
				'book_name'=>ALL::h($row['book_name']),
				'author'=>array_unique(array(ALL::h($row['author1']),ALL::h($row['author2']),ALL::h($row['author3']),ALL::h($row['author4']))),
				'ndc'=>ALL::h((int)$row5['ndc_code']),
				/*'location'=>ALL::h(SQL::decrypt_school($row2['name'],$row2['name_iv'])),
				'financial'=>ALL::h(SQL::decrypt_school($row3['name'],$row3['name_iv'])),
				'store'=>ALL::h(SQL::decrypt_school($row4['name'],$row4['name_iv'])),*/
				'location'=>GET::location($row['location']),
				'financial'=>GET::financial($row['financial']),
				'store'=>GET::store($row['store']),
				'price'=>ALL::h($row['price']),
				'publisher'=>ALL::h($row['publisher']),
				'publish_year'=>ALL::h($row['publish_date']),
				'size'=>ALL::h($row['size']),
				'page'=>ALL::h($row['page']),
				'seikyu'=>ALL::h($row['seikyu1'].'-'.$row['seikyu2'].'-'.$row['seikyu3']),
				'delete'=>ALL::h($row['drop_date'])
									 );
		}
		/*$record['result'][0]['book_id']='0000001';
		$record['result'][0]['date']='2020.10.05';
		$record['result'][0]['book_name']='本の名前';
		$record['result'][0]['author']=array('著者1','著者2');
		$record['result'][0]['ndc']=913;
		$record['result'][0]['location']='開架';
		$record['result'][0]['financial']='PTA';
		$record['result'][0]['store']='本や';
		$record['result'][0]['price']=1000;
		$record['result'][0]['publisher']='Company';
		$record['result'][0]['publish_year']=2019;
		$record['result'][0]['size']='210x297mm';
		$record['result'][0]['page']=50;
		$record['result'][0]['seikyu']='913-チ-1';
		$record['result'][0]['delete']='2020.10.10';
		$record['result'][1]['book_id']='0000001';
		$record['result'][1]['date']='2020.10.05';
		$record['result'][1]['book_name']='本の名前';
		$record['result'][1]['author']=array('著者1','著者2');
		$record['result'][1]['ndc']=913;
		$record['result'][1]['location']='開架';
		$record['result'][1]['financial']='PTA';
		$record['result'][1]['store']='本や';
		$record['result'][1]['price']=1000;
		$record['result'][1]['publisher']='Company';
		$record['result'][1]['publish_year']=2019;
		$record['result'][1]['size']='210x297mm';
		$record['result'][1]['page']=50;
		$record['result'][1]['seikyu']='913-チ-1';
		$record['result'][1]['delete']='2020.10.10';*/
	}
}else{
	$record['res']=false;
}
header("Content-Type: application/json");
echo json_encode($record);