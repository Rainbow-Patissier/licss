<?php
include_once('../../../../data/link.php');
if(isset($_GET['c']) && SQL::decrypt_k($_GET['c'])===$Ymd){
	$csv=implode(',',array('図書番号','ISBN','受入日','シリーズ名','書名','書名フリガナ','副書名','著作者１','著作者２','著作者３','著作者４','著作者１フリガナ','著作者２フリガナ','著作者３フリガナ','著作者４フリガナ','出版社','出版日','サイズ','ページ数','価格','NDC','請求記号','保管場所','財源','禁帯出','廃棄日'))."\n";
	$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY book_id");
	$stmt2=$pdo->prepare("SELECT ndc_code FROM {$school_id}_manage_setting_ndc WHERE cond=0 AND ndc_id=?");
	$stmt3=$pdo->prepare("SELECT name,name_iv FROM {$school_id}_manage_setting_location WHERE cond=0 AND location_id=?");
	$stmt4=$pdo->prepare("SELECT name,name_iv FROM {$school_id}_manage_setting_financial WHERE cond=0 AND financial_id=?");
	$stmt->execute();
	foreach($stmt as $row){
		$temp_csv=array();
		$temp_csv[]=sprintf('%09d',$row['book_id']);
		$temp_csv[]=$row['isbn'];
		$temp_csv[]=$row['entry_date'];
		$temp_csv[]=$row['series'];
		$temp_csv[]=$row['book_name'];
		$temp_csv[]=$row['book_name_ruby'];
		$temp_csv[]=$row['sub_name'];
		$temp_csv[]=$row['author1'];
		$temp_csv[]=$row['author2'];
		$temp_csv[]=$row['author3'];
		$temp_csv[]=$row['author4'];
		$temp_csv[]=$row['author1_ruby'];
		$temp_csv[]=$row['author2_ruby'];
		$temp_csv[]=$row['author3_ruby'];
		$temp_csv[]=$row['author4_ruby'];
		$temp_csv[]=$row['publisher'];
		if($row['publish_date']=='0000-00-00'){
			$temp_csv[]='';
		}else{
			$temp_csv[]=$row['publish_date'];
		}
		$temp_csv[]=$row['size'];
		$temp_csv[]=$row['page'];
		$temp_csv[]=$row['price'];
		$stmt2->execute(array($row['ndc']));
		if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
			$temp_csv[]=$row2['ndc_code'];
		}else{
			$temp_csv[]='';
		}
		$temp_csv[]=implode('-',array($row['seikyu1'],$row['seikyu2'],$row['seikyu3']));
		$stmt3->execute(array($row['location']));
		if($row3=$stmt3->fetch(PDO::FETCH_ASSOC)){
			$temp_csv[]=SQL::decrypt_school($row3['name'],$row3['name_iv']);
		}else{
			$temp_csv[]='';
		}
		$stmt4->execute(array($row['financial']));
		if($row4=$stmt4->fetch(PDO::FETCH_ASSOC)){
			$temp_csv[]=SQL::decrypt_school($row4['name'],$row4['name_iv']);
		}else{
			$temp_csv[]='';
		}
		if((int)$row['not_reserve']===1){
			$temp_csv[]='禁帯出';
		}else{
			$temp_csv[]='';
		}
		if($row['drop_date']=='0000-00-00'){
			$temp_csv[]='';
		}else{
			$temp_csv[]=$row['drop_date'];
		}
		$csv.=implode(',',$temp_csv)."\n";
	}
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="書誌情報'.$Ymdt.'.csv"'); 
	echo mb_convert_encoding($csv,"SJIS");
}else{
	$title="エラーが発生";
	include_once('../../../../data/header.php');
	?>
	<nav id="bread_crumb">
		<ul id="bread_crumb_list">
			<li>
				<a href="<?=$url;?>manage/">
					管理業務
				</a>
			</li>
			<li>
				<a href="<?=$url;?>manage/books/">
					蔵書管理
				</a>
			</li>
			<li>
				<a href="<?=$url;?>manage/books/bookinfo/">
					書誌情報
				</a>
			</li>
			<li>
				<a href="<?=$request_url;?>">
					<?=$title;?>
				</a>
			</li>
		</ul>
	</nav>
	<section id="main_content">
		<div>
			<h2>
				エラーが発生
			</h2>
			<p>
				予期せぬエラーが発生したため，処理が完了できませんでした。
			</p>
		</div>
	</section><?php
	include_once('../../../../data/footer.php');
}
?>