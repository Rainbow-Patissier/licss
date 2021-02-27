<?php
include_once('../../../data/link.php');
$title="利用者登録";
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
			<a href="<?=$url;?>manage/user/">
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
<section id="main_content"><?php
	$secret_text='manage_user_entry';
	if(!isset($_POST['btn'])){
		?>
	<form method="post" enctype="multipart/form-data">
		<ol>
			<li>
				<a href="<?=$url;?>data/download/?f=<?=SQL::encrypt_k('user_entry.csv');?>&n=<?=SQL::encrypt_k('ユーザー登録.csv');?>&c=<?=SQL::encrypt_k(1);?>" download="ユーザー登録.csv">
					CSVデータ
				</a>
				をダウンロードし，必要事項を入力してください。<br>
				※性別については，男性は1を女性は2を入力してください。
			</li>
			<li>
				1.のCSVデータをアップロードしてください。
				<div id="file_area"><input type="file" name="file" title="ファイルを選択してください" id="file" accept=".csv" required></div>
			</li>
		</ol>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text.'_upload');?>" onClick="alert_cancel();">
			登録
		</button>
	</form><?php
		#ALL::remove_alert();
		ALL::drop_script();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text.'_upload' && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		if(isset($_FILES['file']) && is_array($_FILES['file']) && isset($_FILES['file']['error']) && is_int($_FILES['file']['error']) && $_FILES['file']['error']===0 && is_uploaded_file($_FILES['file']['tmp_name'])){
			$handle=fopen($_FILES['file']['tmp_name'],"r");
			$key=0;
			$import=array();
			$failed=array();
			$sex=array('秘密','男性','女性','中性');
			$row=SQL::fetch("SELECT MAX(class_id) as max FROM {$school_id}_account_class");
			$class_id=$row['max']+1;
			$stmt=$pdo->prepare("INSERT INTO account_user VALUES (?,?,?,?,?,?,?,?,?,?,0,NULL)");
			$stmt3=$pdo->prepare("INSERT INTO {$school_id}_account_class VALUES (?,?,?,?,?,?,?,0,NULL)");
			$stmt4=$pdo->prepare("INSERT INTO account_info VALUES (?,?,?,?,?,0,NULL)");
			$stmt2=$pdo->prepare("SELECT COUNT(user_id) as cnt FROM account_user WHERE birthday_year=?");
			while(($data=fgetcsv($handle))){
				if($key!==0){
					$row=array();
					foreach($data as $val){
						foreach(explode(',',$val) as $value){
							$row[]=$value;
						}
					}
					$values=array();
					$birthday=new DateTime($row[4]);
					$stmt2->execute(array($birthday->format('Y')));
					if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						//アカウント情報entry
						$values[]=$birthday->format('y').sprintf('%06d',$row2['cnt']);
						$values[]=$birthday->format('Y');
						$values[]=$birthday->format('Y-m-d');
						$values[]=$row[3];
						$name=SQL::encrypt($row[5]);
						$values[]=$name[0];
						$values[]=$name[1];
						$values[]='';
						$values[]='';
						$values[]=$school_id;
						$values[]=$Ymdt;
						//アカウントログイン情報
						$pwd=str_shuffle(mb_substr(str_shuffle('0123456789'),0,4).mb_substr(str_shuffle('qazxswedcvfrtgbnhyujmkiolp'),0,6).mb_substr(str_shuffle(',+-./;()@:\][]'),0,2));
						$hash=SQL::encrypt(password_hash($pwd,PASSWORD_DEFAULT));
						$stmt4->execute(array($values[0],$values[0],$hash[0],$hash[1],$Ymdt));
						if($stmt->execute($values)){
							$import[]=array($values[0],$row[0].'年'.$row[1].'組'.$row[2].'番',$row[4],$row[5],$values[0],$pwd);
						}else{
							$failed[]=array($row[0].'-'.$row[1].'-'.$row[2],$sex[$row[3]],$row[4],$row[5]);
						}
						//所属entry
						$stmt3->execute(array($class_id,$values[0],$row[0],$row[1],$row[2],4,$Ymdt));
						++$class_id;
					}
				}
				++$key;
			}
			?>
	<div>
		<h2 class="no_print">
			登録完了
		</h2>
		<div class="no_print">
			<a href="#manage_user_entry_import">登録完了ユーザー</a>
			<a href="#manage_user_entry_failed">登録失敗ユーザー</a><br>
			<button type="button" onClick="window.print();">印刷</button>
		</div>
		<div><?php
			$n=1;
			foreach($import as $row){
				if($n===1){
					?>
			<div class="manage_user_entry_account_wrapper"><?php
				}
				?>
			<div class="manage_user_entry_account_info">
				<span><strong><?=$row[1];?>&nbsp;&nbsp;<?=$row[3];?>様</strong></span>
				<div>下記の内容で図書館利用者情報が登録されました。システムにアクセスし，アカウント名およびパスワードを変更してください。</div>
				<div class="manage_user_entry_account_info_wrapper">
					<table>
						<tbody>
							<tr>
								<th>
									利用者番号
								</th>
								<td>
									<?=$row[0];?>
								</td>
							</tr>
							<tr>
								<th>
									名前
								</th>
								<td>
									<?=$row[3];?>
								</td>
							</tr>
							<tr>
								<th>
									生年月日
								</th>
								<td>
									<?=$row[2];?>
								</td>
							</tr>
							<tr>
								<th>
									アカウント名
								</th>
								<td>
									<?=$row[4];?>
								</td>
							</tr>
							<tr>
								<th>
									パスワード
								</th>
								<td>
									<?=$row[5];?>
								</td>
							</tr>
						</tbody>
					</table>
					<div>
						<img src="<?=$url;?>img/qr.png" class="manage_user_entry_qr">
						<?=$url;?>
					</div>
				</div>
			</div><?php
				if($n===10){
					?>
			</div><?php
					$n=0;
				}
				++$n;
			}
			?>
		</div>
		<div id="manage_user_entry_import"><?php
			$n=0;
			foreach($import as $row){
				if($n===10){
					$n=0;
					?>
			<div class="manage_user_entry_margin"></div><?php
				}
				?>
			<div class="manage_user_entry_account_info">
				<span><strong><?=$row[1];?>&nbsp;&nbsp;<?=$row[3];?>様</strong></span>
				<div>下記の内容で図書館利用者情報が登録されました。システムにアクセスし，アカウント名およびパスワードを変更してください。</div>
				<div class="manage_user_entry_account_info_wrapper">
					<table>
						<tbody>
							<tr>
								<th>
									利用者番号
								</th>
								<td>
									<?=$row[0];?>
								</td>
							</tr>
							<tr>
								<th>
									名前
								</th>
								<td>
									<?=$row[3];?>
								</td>
							</tr>
							<tr>
								<th>
									生年月日
								</th>
								<td>
									<?=$row[2];?>
								</td>
							</tr>
							<tr>
								<th>
									アカウント名
								</th>
								<td>
									<?=$row[4];?>
								</td>
							</tr>
							<tr>
								<th>
									パスワード
								</th>
								<td>
									<?=$row[5];?>
								</td>
							</tr>
						</tbody>
					</table>
					<div>
						<img src="<?=$url;?>img/qr.png" class="manage_user_entry_qr">
						<?=$url;?>
					</div>
				</div>
			</div><?php
				++$n;
			}
			?>
		</div>
		<div class="scroll no_print" id="manage_user_entry_failed">
			<table>
				<caption>
					登録失敗
				</caption>
				<thead>
					<tr>
						<th>
							所属(学年-クラス-番号)
						</th>
						<th>
							性別
						</th>
						<th>
							生年月日
						</th>
						<th>
							名前
						</th>
					</tr>
				</thead>
				<tbody><?php
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
					</tr><?php
			}
			?>
				</tbody>
			</table>
		</div>
		<style>
			@page{
				size: A4;
			}
			@media print{
				html{
					width: 210mm;
					height: 297mm;
				}
				body{
					margin: 0;
				}
				/*.manage_user_entry_account_info:nth-child(10n+1),
				.manage_user_entry_account_info:nth-child(10n+2){
					break-before: page;
					margin-top: 10mm;
				}*/
			}
		</style>
		<script>
			window.print();
		</script>
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