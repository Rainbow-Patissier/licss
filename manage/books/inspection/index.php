<?php
include_once('../../../data/link.php');
$title="蔵書点検";
include_once('../../../data/header.php');
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
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content"><?php
	$secret_text='manage_books_inspection';
	if(!isset($_POST['btn'])){
		?>
	<form method="post" enctype="multipart/form-data">
		<h3>
			蔵書点検方法
		</h3>
		<ol>
			<li>
				蔵書の図書バーコードを読み込むcsvで保存する
			</li>
			<li>
				1.のcsvをアップロードする
			</li>
		</ol>
		※csvは1列目に1行ごとに図書番号(半角数字)を入力してください。
		<h3>
			csvデータアップロード
		</h3>
		<div id="file_area"><input type="file" name="file" title="ファイルを選択してください" id="file" accept=".csv" required></div>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form><?php
		#ALL::remove_alert();
		ALL::drop_script();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_FILES['file']) && is_array($_FILES['file']) && isset($_FILES['file']['error']) && is_int($_FILES['file']['error']) && $_FILES['file']['error']===0 && is_uploaded_file($_FILES['file']['tmp_name'])){
			$row=SQL::fetch("SELECT MAX(inspection_id) as inspection FROM {$school_id}_manage_books_inspection");
			$inspection_id=$row['inspection']+1;
			$row=SQL::fetch("SELECT MAX(group_id) as max FROM {$school_id}_manage_books_inspection");
			$group_id=$row['max'];
			$row=SQL::fetch_all("SELECT book_number,edit_date FROM {$school_id}_manage_books_inspection WHERE group_id=?",array($group_id));
			$previous_book_id=array_column($row,'book_number');
			$previous_edit_date=$row[0]['edit_date'];
			++$group_id;
			$row=SQL::fetch_all("SELECT book_id FROM {$school_id}_manage_books_info WHERE cond=0");
			$all_book_id=array_column($row,'book_id');
			$stmt=$pdo->prepare("INSERT INTO {$school_id}_manage_books_inspection VALUES (?,?,?,?,?)");
			$inspection=array();
			$handle=fopen($_FILES['file']['tmp_name'],"r");
			while(($row=fgetcsv($handle))){
				$inspection[]=$row[0];
				$stmt->execute(array($inspection_id,$group_id,$user_id,$row[0],$Ymdt));
				++$inspection_id;
			}
			?>
	<div>
		<h2>
			実行結果
		</h2>
		<h3>
			蔵書に登録されているが，蔵書点検ではなかった図書番号
		</h3>
		<div class="scroll" style="height: 300px;">
			<table><?php
			foreach(array_diff($all_book_id,$inspection) as $val){
				?>
				<tr>
					<td><?=sprintf('%09d',$val);?></td>
				</tr><?php
			}
			?>
			</table>
		</div>
		<h3>
			蔵書に登録されていない図書番号(蔵書登録がされていない可能性)
		</h3>
		<div class="scroll" style="height: 300px;">
			<table><?php
			foreach(array_diff($inspection,$all_book_id) as $val){
				?>
				<tr>
					<td><?=sprintf('%09d',$val);?></td>
				</tr><?php
			}
			?>
			</table>
		</div>
		<h3>
			前回(<?=$previous_edit_date;?>)の蔵書点検ではあったが，今回の蔵書点検ではなかった図書番号
		</h3>
		<div class="scroll" style="height: 300px;">
			<table><?php
			foreach(array_diff($previous_book_id,$inspection) as $val){
				?>
				<tr>
					<td><?=sprintf('%09d',$val);?></td>
				</tr><?php
			}
			?>
			</table>
		</div>
		<h3>
			前回・今回の蔵書点検でともになかった図書番号(紛失の可能性大)
		</h3>
		<div class="scroll" style="height: 300px;">
			<table><?php
			$previous=array_diff($all_book_id,$previous_book_id);
			$thistime=array_diff($all_book_id,$inspection);
			foreach(array_intersect($thistime,$previous) as $val){
				?>
				<tr>
					<td><?=sprintf('%09d',$val);?></td>
				</tr><?php
			}
			?>
			</table>
		</div>
	</div><?php
		}else{
			?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			ファイルのアップロードを確認できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
		}
	}else{
		?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			予期せぬエラーが発生したため，処理が完了できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
	}
	?>
</section><?php
include_once('../../../data/footer.php');
?>