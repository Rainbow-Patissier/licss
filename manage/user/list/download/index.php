<?php
include_once('../../../../data/link.php');
if(isset($_GET['c']) && SQL::decrypt_k($_GET['c'])===$Ymd){
	$csv="利用者番号,学年,クラス,番号,性別,誕生日,名前,権限\n";
	$stmt=$pdo->prepare("SELECT user_id,grade,class,number,user_right FROM {$school_id}_account_class WHERE cond=0 ORDER BY grade,class,number");
	$stmt->execute();
	$stmt2=$pdo->prepare("SELECT name,name_iv,sex,birthday FROM account_user WHERE cond=0 AND user_id=?");
	$rights=array('admin','admin','teacher','officer','general');
	$sex=array('秘密','男性','女性','中性');
	foreach($stmt as $row){
		$stmt2->execute(array($row['user_id']));
		if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
			$temp_csv=array();
			$temp_csv[]=$row['user_id'];
			$temp_csv[]=$row['grade'];
			$temp_csv[]=$row['class'];
			$temp_csv[]=$row['number'];
			$temp_csv[]=$sex[(int)$row2['sex']];
			$temp_csv[]=$row2['birthday'];
			$temp_csv[]=ALL::h(SQL::decrypt($row2['name'],$row2['name_iv']));
			$temp_csv[]=$user_right[$row['user_right']];
			$csv.=implode(',',$temp_csv)."\n";
		}
	}
	header("Content-Type: text/csv");
	header('Content-Disposition: attachment; filename="利用者情報'.$Ymdt.'.csv"'); 
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
				<a href="<?=$url;?>manage/">
					利用者管理
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
				有効なURLではありません。
			</p>
			<p>
				恐れ入りますが，もう一度始めからやり直してください。
			</p>
		</div>
	</section><?php
	include_once('../../../../data/footer.php');
}
?>