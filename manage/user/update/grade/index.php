<?php
include_once('../../../../data/link.php');
$title="在校生所属情報更新";
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
			<a href="<?=$url;?>manage/user/">
				利用者管理
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/user/update/">
				年度更新
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
	$secret_text='manage_user_update';
	if(!isset($_POST['btn'])){
		?>
	<form method="post" enctype="multipart/form-data">
		<ol>
			<li>
				<a href="<?=$url;?>data/download/?f=<?=SQL::encrypt_k('user_update.csv');?>&n=<?=SQL::encrypt_k('年度更新.csv');?>&c=<?=SQL::encrypt_k(1);?>" download="年度更新.csv">
					CSVデータ
				</a>
				をダウンロードし，利用者番号(任意)，名前，新しい学年，クラス，番号，生年月日(任意)を入力してください。<br>
				※名前はシステムに登録されているものと全く同じ漢字・スペースで入力してください。
			</li>
			<li>
				1.のCSVデータをアップロードしてください。
				<div id="file_area"><input type="file" name="file" title="ファイルを選択してください" id="file" required></div>
			</li>
		</ol>
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
			/*csvデータ扱い*/
			$key=0;
			$handle=fopen($_FILES['file']['tmp_name'],"r");
			$failed=array();
			$success=array();
			$row=SQL::fetch("SELECT MAX(class_id) as max FROM {$school_id}_account_class");
			$class_id=$row['max']+1;
			$stmt_entry=$pdo->prepare("INSERT INTO {$school_id}_account_class VALUES (?,?,?,?,?,?,?,?,?)");
			$stmt_select=$pdo->prepare("SELECT * FROM {$school_id}_account_class WHERE cond=0 AND user_id=? ORDER BY class_id DESC");
			$stmt_update=$pdo->prepare("UPDATE {$school_id}_account_class SET cond=1,delete_date=? WHERE user_id=? AND cond=0");
			$stmt_name=$pdo->prepare("SELECT user_id,name,name_iv FROM account_user WHERE cond=0 AND school_id=?");
			$stmt_birthday=$pdo->prepare("SELECT user_id,birthday FROM account_user WHERE cond=0 AND school_id=?");
			while($row=fgetcsv($handle)){
				if($key!=0){
					$row2=array();
					foreach($row as $row3){
						foreach(explode(',',$row3) as $val){
							$row2[]=$val;
						}
					}
					if(empty($row2[0]) && empty($row2[4])){
						//名前判定
						$stmt_name->execute(array($school_id));
						foreach($stmt_name as $i=>$row_name){
							if(str_replace(' ','',$row2[5])===str_replace(' ','',SQL::decrypt($row_name['name'],$row_name['name_iv']))){
								$stmt_select->execute(array($row_name['user_id']));
								if($row_entry=$stmt_select->fetch(PDO::FETCH_ASSOC)){
									$row_entry['class_id']=$class_id;
									$row_entry['grade']=$row2[1];
									$row_entry['class']=$row2[2];
									$row_entry['number']=$row2[3];
									$row_entry['edit_date']=$Ymdt;
									$stmt_update->execute(array($Ymdt,$row_name['user_id']));
									$row_entry=array_values($row_entry);
									if($stmt_entry->execute($row_entry)){
										$row2[0]=$row_name['user_id'];
										$success[]=$row2;
										++$class_id;
									}else{
										$row2[0]=$row_name['user_id'];
										$failed[]=$row2;
									}
									break;
								}else{
									$failed[]=$row2;
									break;
								}
							}
						}
					}elseif(!empty($row2[0])){
						//利用者番号
						$stmt_select->execute(array($row2[0]));
						if($row_entry=$stmt_select->fetch(PDO::FETCH_ASSOC)){
							$row_entry['class_id']=$class_id;
							$row_entry['grade']=$row2[1];
							$row_entry['class']=$row2[2];
							$row_entry['number']=$row2[3];
							$row_entry['edit_date']=$Ymdt;
							$stmt_update->execute(array($Ymdt,$row2[0]));
							$row_entry=array_values($row_entry);
							if($stmt_entry->execute($row_entry)){
								$success[]=$row2;
								++$class_id;
							}else{
								$failed[]=$row2;
							}
						}else{
							$failed[]=$row2;
						}
					}elseif(!empty($row2[4])){
						//生年月日
						$stmt_birthday->execute(array($school_id));
						foreach($stmt_birthday as $row_name){
							$row_birthday=new DateTime($row2[4]);
							$user_birthday=new DateTime($row_name['birthday']);
							if($row_birthday->format('Y-m-d')===$user_birthday->format('Y-m-d')){
								$stmt_select->execute(array($row_name['user_id']));
								if($row_entry=$stmt_select->fetch(PDO::FETCH_ASSOC)){
									$row_entry['class_id']=$class_id;
									$row_entry['grade']=$row2[1];
									$row_entry['class']=$row2[2];
									$row_entry['number']=$row2[3];
									$row_entry['edit_date']=$Ymdt;
									$stmt_update->execute(array($Ymdt,$row_name['user_id']));
									$row_entry=array_values($row_entry);
									if($stmt_entry->execute($row_entry)){
										$row2[0]=$row_name['user_id'];
										$success[]=$row2;
										++$class_id;
									}else{
										$row2[0]=$row_name['user_id'];
										$failed[]=$row2;
									}
									break;
								}else{
									$failed[]=$row2;
									break;
								}
							}
						}
					}else{
						//判定不可
						$failed[]=$row2;
					}
				}
				++$key;
			}
			?>
	<div>
		<h2>
			登録完了
		</h2>
		<div>
			<a href="#manage_user_update_grade_success">登録成功</a>
			<a href="#manage_user_update_grade_failed">登録失敗</a>
		</div>
		<div id="manage_user_update_grade_success" class="scroll">
			<table>
				<caption>
					登録成功
				</caption>
				<thead>
					<tr>
						<th>
							利用者番号
						</th>
						<th>
							所属
						</th>
						<th>
							誕生日
						</th>
						<th>
							名前
						</th>
					</tr>
				</thead>
				<tbody><?php
			foreach($success as $row){
				?>
					<tr>
						<td>
							<?=ALL::h(sprintf('%08d',$row[0]));?>
						</td>
						<td>
							<?=ALL::h($row[1].'年'.$row[2].'組'.$row[3].'番');?>
						</td>
						<td>
							<?=ALL::h($row[4]);?>
						</td>
						<td>
							<?=ALL::h($row[5]);?>
						</td>
					</tr><?php
			}
			?>
				</tbody>
			</table>
		</div>
		<div id="manage_user_update_grade_failed" class="scroll">
			<table>
				<caption>
					登録失敗
				</caption>
				<thead>
					<tr>
						<th>
							利用者番号
						</th>
						<th>
							所属
						</th>
						<th>
							誕生日
						</th>
						<th>
							名前
						</th>
					</tr>
				</thead>
				<tbody><?php
			foreach($failed as $row){
				?>
					<tr>
						<td>
							<?=ALL::h(sprintf('%08d',$row[0]));?>
						</td>
						<td>
							<?=ALL::h($row[1].'年'.$row[2].'組'.$row[3].'番');?>
						</td>
						<td>
							<?=ALL::h($row[4]);?>
						</td>
						<td>
							<?=ALL::h($row[5]);?>
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
include_once('../../../../data/footer.php');
?>