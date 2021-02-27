<?php
include_once('../../../../data/link.php');
$title="利用者追加";
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
			<a href="<?=$url;?>manage/user/entry/">
				利用者追加
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
	$secret_text='manage_use_entry_old';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		利用者番号を入力してください。(バーコードの読み取りでも可) <br>
		<input type="number" id="manage_user_entry_old_add_user_id" placeholder="利用者番号" title="利用者番号を入力してください" autofocus>
		<button type="button" onClick="add_user();">
			追加
		</button>
		<table>
			<thead>
				<tr>
					<th>
						利用者番号
					</th>
					<th>
						名前
					</th>
					<th>
						所属
					</th>
				</tr>
			</thead>
			<tbody id="manage_user_entry_old_add_table"></tbody>
		</table>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
		<div id="announce_back" class="no_print"></div>
		<div id="announce_wrapper" class="no_print">
			<div id="announce_msg">
				 <div class="balls-guruguru">
					<span class="ball ball-1"></span>
					<span class="ball ball-2"></span>
					<span class="ball ball-3"></span>
					<span class="ball ball-4"></span>
					<span class="ball ball-5"></span>
					<span class="ball ball-6"></span>
					<span class="ball ball-7"></span>
					<span class="ball ball-8"></span>
				</div>
			</div>
		</div>
		<script>
			document.getElementById('manage_user_entry_old_add_user_id').onkeypress=function(e){
				if(e.keyCode===13){
					add_user();
					return false;
				}
			}
			function add_user(){
				document.getElementById('announce_back').style.display='block';
				document.getElementById('announce_wrapper').style.display='block';
				var id=document.getElementById('manage_user_entry_old_add_user_id');
				var body=document.getElementById('manage_user_entry_old_add_table');
				var cnt=body.getElementsByTagName('tr').length;
				if(cnt>245){
					alert('一度に追加できる利用者の上限に達しました。一度登録してください。')
				}else{
					var tr=document.createElement('tr');
					body.appendChild(tr);
					var td=document.createElement('td');
					td.innerHTML=id.value;
					tr.appendChild(td);
					var td2=document.createElement('td');
					manage_user_entry_get_name(id.value,td2);
					tr.appendChild(td2);
					td=document.createElement('td');
					td.innerHTML='<input type="number" name="grade['+cnt+']" placeholder="学年" title="学年を入力してください" required>年<input type="text" name="class['+cnt+']" placeholder="組" title="組を入力してください" required>組<input type="number" name="number['+cnt+']" placeholder="番号" title="番号を入力してください" required>番<input type="hidden" name="user['+cnt+']" value="'+id.value+'">';
					tr.appendChild(td);
					setTimeout(function(){
						console.log(td2);
						if(td2.dataset.accept=='none'){
							tr.remove();
						}
						document.getElementById('announce_back').style.display='none';
						document.getElementById('announce_wrapper').style.display='none';
					},50);
					id.value='';
					id.focus();
				}
			}
		</script>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && is_array($_POST['grade']) && is_array($_POST['class']) && is_array($_POST['number']) && is_array($_POST['user']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}'][\$key]) &&";
		}*/
		if(isset($_POST['grade']) && isset($_POST['class']) && isset($_POST['number']) && isset($_POST['user'])){
			$success=array();
			$failed=array();
			$row=SQL::fetch("SELECT MAX(class_id) as max FROM {$school_id}_account_class");
			$class_id=$row['max']+1;
			$stmt=$pdo->prepare("INSERT INTO {$school_id}_account_class VALUES (?,?,?,?,?,4,?,0,NULL)");
			$stmt2=$pdo->prepare("SELECT user_id,birthday_year,birthday,sex,name,name_iv,email,email_iv FROM account_user WHERE cond=0 AND user_id=?");
			$stmt3=$pdo->prepare("INSERT INTO account_user VALUES (?,?,?,?,?,?,?,?,?,NOW(),0,NULL)");
			foreach($_POST['user'] as $key=>$val){
				$stmt2->execute(array($val));
				if(!empty($val) && isset($_POST['grade'][$key]) && isset($_POST['class'][$key]) && isset($_POST['number'][$key]) && $row=$stmt2->fetch(PDO::FETCH_ASSOC)){
					$row=array_values($row);
					$row[]=$school_id;
					if($stmt->execute(array($class_id,$val,$_POST['grade'][$key],$_POST['class'][$key],$_POST['number'][$key],$Ymdt)) && $stmt3->execute($row)){
						++$class_id;
						$success[]=array($val,$_POST['grade'][$key],$_POST['class'][$key],$_POST['number'][$key]);
					}else{
						$failed[]=array($val,$_POST['grade'][$key],$_POST['class'][$key],$_POST['number'][$key]);
					}
				}else{
					$failed[]=array($val);
				}
			}
			?>
	<div>
		<h2>
			登録完了
		</h2>
		<div>
			<a href="#manage_user_entry_old_success">登録成功</a>
			<a href="#manage_user_entry_old_failed">登録失敗</a>
		</div>
		<div id="manage_user_entry_old_success">
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
							名前
						</th>
						<th>
							所属
						</th>
					</tr>
				</thead>
				<tbody><?php
			foreach($success as $row){
				?>
					<tr>
						<td>
							<?=ALL::h($row[0]);?>
						</td>
						<td>
							<?=USER::name($row[0]);?>
						</td>
						<td>
							<?=ALL::h($row[1].'年'.$row[2].'組'.$row[3].'番');?>
						</td>
					</tr><?php
			}
			?>
				</tbody>
			</table>
		</div>
		<div id="manage_user_entry_old_failed">
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
							名前
						</th>
						<th>
							所属
						</th>
						<th>
							理由
						</th>
					</tr>
				</thead>
				<tbody><?php
			foreach($failed as $row){
				if(isset($row[1])){
					?>
					<tr>
						<td>
							<?=ALL::h($row[0]);?>
						</td>
						<td>
							<?=USER::name($row[0]);?>
						</td>
						<td>
							<?=ALL::h($row[1].'年'.$row[2].'組'.$row[3].'番');?>
						</td>
						<td>
							登録不可(原因不明)
						</td>
					</tr><?php
				}else{
					?>
					<tr>
						<td>
							<?=ALL::h($row[0]);?>
						</td>
						<td>
							<?=USER::name($row[0]);?>
						</td>
						<td>
							
						</td>
						<td>
							入力内容に不備
						</td>
					</tr><?php
				}
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
			必要事項すべての入力が確認できませんでした。
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