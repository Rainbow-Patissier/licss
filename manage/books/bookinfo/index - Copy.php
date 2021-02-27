<?php
include_once('../../../data/link.php');
$title='書誌情報';
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
	$secret_text='manage_books_bookinfo';
	if(!isset($_POST['btn'])){
		?>
	<form method="post" enctype="multipart/form-data">
		<h2>
			書誌情報エクスポート
		</h2>
		<div>
			<a href="<?=$request_url;?>download/?c=<?=SQL::encrypt_k($Ymd);?>">CSVデータ出力</a>
		</div>
		<h2>
			書誌情報インポート
		</h2>
		<div>
			<ol>
				<li>
					<a href="<?=$url;?>data/download/?f=<?=SQL::encrypt_k('bookinfo_import.csv');?>&n=<?=SQL::encrypt_k('書誌情報インポート.csv');?>&c=<?=SQL::encrypt_k(1);?>">
						CSVデータ
					</a>
					をダウンロードし，必要事項を入力してください。
				</li>
				<li>
					1.のCSVデータをアップロードしてください。
					<div id="file_area"><input type="file" name="file" title="ファイルを選択してください" id="file" accept=".csv" required></div>
					※図書番号がない場合は空欄にしてください。<br>
					<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text.'_upload');?>" onClick="alert_cancel();">
						次へ
					</button>
				</li>
			</ol>
		</div>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
	</form><?php
		ALL::drop_script();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text.'_upload' && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		if(isset($_FILES['file']) && is_array($_FILES['file']) && isset($_FILES['file']['error']) && is_int($_FILES['file']['error']) && $_FILES['file']['error']===0 && is_uploaded_file($_FILES['file']['tmp_name'])){
			?>
	<form method="post">
		<div class="scroll">
			<table>
				<thead>
					<tr>
						<th>図書番号</th>	<th>ISBN</th>	<th>受入日</th>	<th>シリーズ名</th>	<th>書名</th>	<th>書名フリガナ</th>	<th>副書名</th>	<th>著作者１</th>	<th>著作者２</th>	<th>著作者３</th>	<th>著作者４</th>	<th>著作者１フリガナ</th>	<th>著作者２フリガナ</th>	<th>著作者３フリガナ</th>	<th>著作者４フリガナ</th>	<th>出版社</th>	<th>出版日</th>	<th>サイズ</th>	<th>ページ数</th>	<th>価格</th>	<th>NDC</th>	<th>請求記号</th>	<th>保管場所</th>	<th>財源</th>	<th>禁貸</th>	<th>廃棄日</th>
					</tr>
				</thead>
				<tbody><?php
			$handle=fopen($_FILES['file']['tmp_name'],"r");
			$key=0;
			while(($row=fgetcsv($handle))){
				$n=0;
				if($key!==0){
				?>
					<tr><?php
				foreach($row as $row2){
					foreach(explode(',',$row2) as $val){
				?>
						<td>
							<input type="text" name="import[<?=$key;?>][<?=$n;?>]" value="<?=$val;?>">
						</td><?php
						++$n;
					}
				}
				?>
					</tr><?php
				}
				++$key;
			}
			?>
				</tbody>
			</table>
		</div>
		※図書番号が空欄の場合，自動的に図書番号を割り振ります。<br>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text.'_import');?>" onClick="alert_cancel();">
			インポート
		</button>
	</form><?php
		}else{
			?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			入力の確認ができなかったため，処理が完了できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
		}
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text.'_import' && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['import']) && is_array($_POST['import'])){
			$import_data=array();
			$handle=fopen($_FILES['file']['tmp_name'],"r");
			$key=0;
			while(($row=fgetcsv($handle))){
				if($key!==0){
					foreach($row as $row2){
						$import[$key]=array();
						foreach(explode(',',$row2) as $val){
							$import[$key]=$val;
						}
					}
				}
				++$key;
			}
			$import=array();
			$cannot_entry=array();
			$failed=array();
			$stmt=$pdo->prepare("SELECT COUNT(book_id) as cnt FROM {$school_id}_manage_books_info WHERE book_id=?");
			$stmt2=$pdo->prepare("INSERT INTO {$school_id}_manage_books_info VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,NULL)");
			$stmt3=$pdo->prepare("SELECT ndc_id FROM {$school_id}_manage_setting_ndc WHERE ndc_code=? AND cond=0");
			foreach($import as $row){
				#print_r($row);
				$stmt->execute(array($row[0]));
				$row2=$stmt->fetch(PDO::FETCH_ASSOC);
				if((int)$row2['cnt']===0){
					$values=array();
					$values[]=$row[0];
					$values[]=$user_id;
					$values[]=$row[1];
					$values[]=$row[2];
					$values[]=$row[3];
					$values[]=$row[4];
					$values[]=$row[5];
					$values[]=$row[6];
					$values[]=$row[7];
					$values[]=$row[11];
					$values[]='';
					$values[]=$row[8];
					$values[]=$row[12];
					$values[]='';
					$values[]=$row[9];
					$values[]=$row[13];
					$values[]='';
					$values[]=$row[10];
					$values[]=$row[14];
					$values[]='';
					$values[]=$row[15];
					$values[]=$row[16];
					$values[]=$row[17];
					$values[]=$row[18];
					$values[]=$row[19];
					$seikyu=explode('-',$row[21]);
					for($i=0;$i<4;++$i){
						if(isset($seikyu[$i])){
							$values[]=$seikyu[$i];
						}else{
							$values[]='';
						}
					}
					$values[]='';
					$stmt3->execute(array($row[20]));
					if($row3=$stmt3->fetch(PDO::FETCH_ASSOC)){
						$values[]=$row3['ndc_id'];
					}else{
						$values[]='';
					}
					$values[]='';
					$values[]='';
					$values[]='';
					$values[]=$row[24];
					$values[]=$row[25];
					$values[]='';
					$values[]='';
					$values[]=$Ymdt;
					if(!$stmt2->execute($values)){
						$failed[]=$row;
					}else{
						$import[]=$row;
					}
				}else{
					$cannot_entry[]=$row;
				}
			}
			?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			下記以外の蔵書は登録しました。
		</p>
		<div class="scroll">
			<table style="font-size: 9pt;">
				<caption>
					登録不可リスト
				</caption>
				<thead>
					<tr>
						<th>図書番号</th><th>ISBN</th><th>受入日</th><th>シリーズ名</th><th>書名</th><th>書名フリガナ</th><th>副書名</th><th>著作者１</th><th>著作者２</th><th>著作者３</th><th>著作者４</th><th>著作者１フリガナ</th><th>著作者２フリガナ</th><th>著作者３フリガナ</th><th>著作者４フリガナ</th><th>出版社</th><th>出版日</th><th>サイズ</th><th>ページ数</th><th>価格</th><th>NDC</th><th>請求記号</th><th>保管場所</th><th>財源</th><th>禁貸</th><th>廃棄日</th><th>理由</th>
					</tr>
				</thead>
				<tbody><?php
				foreach($cannot_entry as $row){
					?>
					<tr><?php
					foreach($row as $val){
						?>
						<td>
							<?=ALL::h($val);?>
						</td><?php
					}
					?>
						<td>
							図書番号に被りがあるため
						</td>
					</tr><?php
				}
				foreach($failed as $row){
					?>
					<tr><?php
					foreach($row as $val){
						?>
						<td>
							<?=ALL::h($val);?>
						</td><?php
					}
					?>
						<td>
							登録失敗
						</td>
					</tr><?php
				}
				?>
				</tbody>
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
			入力の確認ができなかったため，処理が完了できませんでした。
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