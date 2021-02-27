<?php
include_once('../../../data/link.php');
$secret_text="user_edit";
$data=SQL::fetch("SELECT * FROM {$school_id}_account_class WHERE cond=0 AND user_id=?",array($user_id));
$data2=SQL::fetch("SELECT * FROM account_user WHERE cond=0 AND user_id=?",array($user_id));
$data3=SQL::fetch("SELECT grade,class,number,sex,name FROM {$school_id}_manage_setting_other WHERE cond=0 AND user_id=?",array($user_id));
if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
	if(($data3['grade'] || isset($_POST['grade'])) && ($data3['class'] || isset($_POST['class'])) && ($data3['number'] || isset($_POST['number'])) && ($data3['sex'] || isset($_POST['sex'])) && ($data3['name'] || isset($_POST['name']))){
		foreach(array('grade','class','number') as $val){
			if(isset($_POST[$val])){
				$data[$val]=$_POST[$val];
			}
		}
		if(isset($_POST['sex'])){
			$data2['sex']=SQL::decrypt_ks($_POST['sex']);
		}
		if(isset($_POST['name'])){
			$name=SQL::encrypt($_POST['name']);
			$data2['name']=$name[0];
			$data2['name_iv']=$name[1];
		}
		$data['edit_date']=$Ymdt;
		$data['cond']=0;
		$data['delete_date']=NULL;
		$data2['edit_date']=$Ymdt;
		$data2['cond']=0;
		$data2['delete_date']=NULL;
		SQL::execute("UPDATE account_user SET cond=1,delete_date=NOW() WHERE cond=0 AND user_id=?;UPDATE {$school_id}_account_class SET cond=1,delete_date=NOW() WHERE cond=0 AND user_id=?",array($user_id,$user_id));
		if(SQL::execute("INSERT INTO account_user VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",array_values($data2)) && SQL::execute("INSERT INTO {$school_id}_account_class VALUES (?,?,?,?,?,?,?,?,?)",array_values($data))){
			$_SESSION[$session_head.'form_result']=array(0,"登録しました");
			header("Location: {$request_url}");
		}else{
			$_SESSION[$session_head.'form_result']=array(0,"原因不明のエラーにより登録できませんでした");
			header("Location: {$request_url}");
		}
	}else{
		$_SESSION[$session_head.'form_result']=array(0,"未入力項目があるため，登録できませんでした");
		header("Location: {$request_url}");
	}
}else{
	if(isset($_SESSION[$session_head.'form_result'][0]) && $_SESSION[$session_head.'form_result'][0]<2){
		++$_SESSION[$session_head.'form_result'][0];
	}else{
		unset($_SESSION[$session_head.'form_result']);
	}
}
$title="登録内容変更";
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>foruser/">
				利用者機能
			</a>
		</li>
		<li>
			<a href="<?=$url;?>foruser/setting/">
				利用者情報
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
	if(isset($_SESSION[$session_head.'form_result'][1]) && $_SESSION[$session_head.'form_result'][0]<2){
		?>
	<div class="result">
		<?=ALL::h($_SESSION[$session_head.'form_result'][1]);?>
	</div><?php
	}
	?>
	<form method="post">
		<div class="scroll">
			<table>
				<tbody>
					<tr>
						<th>
							利用者番号
						</th>
						<td>
							<?=sprintf('%08d',$user_id);?>
						</td>
					</tr>
					<tr>
						<th>
							所属
						</th>
						<td>
							<span class="grade"><?php
								if($data3['grade']){
									echo $data['grade'];
								}else{
								?>
								<input type="number" name="grade" placeholder="学年" title="学年を数字で入力してください" value="<?=$data['grade'];?>" required><?php
								}
							?>
							</span>
							<span class="class"><?php
								if($data3['class']){
									echo $data['class'];
								}else{
									?>
								<input type="text" name="class" placeholder="クラス" title="クラスを入力してください" value="<?=$data['class'];?>" required><?php
								}
								?>
							</span>
							<span class="stno"><?php
								if($data3['number']){
									echo $data['number'];
								}else{
									?>
								<input type="number" name="number" placeholder="番号" title="出席番号を入力してください" value="<?=$data['number'];?>" required><?php
								}
								?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							性別
						</th>
						<td><?php
							if($data3['sex']){
								echo $user_sex[$data2['sex']];
							}else{
								?>
							<select name="sex" title="性別を選択してください" required>
								<option label="選択してください"></option><?php
								foreach($user_sex as $key=>$val){
									if($key==$data2['sex']){
										$selection=' selected';
									}else{
										$selection='';
									}
									?>
								<option value="<?=SQL::encrypt_ks($key);?>"<?=$selection;?>>
									<?=$val;?>
								</option><?php
								}
								?>
							</select><?php
							}
							?>
						</td>
					</tr>
					<tr>
						<th>
							名前
						</th>
						<td><?php
							if($data3['name']){
								echo SQL::decrypt($data2['name'],$data2['name_iv']);
							}else{
								?>
							<input type="text" name="name" placeholder="名前" title="名前を入力してください" value="<?=SQL::decrypt($data2['name'],$data2['name_iv']);?>" required><?php
							}
							?>
						</td>
					</tr>
					<tr>
						<th>
							権限
						</th>
						<td>
							<?=$user_right[$data['user_right']];?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form>
</section><?php
ALL::remove_alert();
include_once('../../../data/footer.php');
?>