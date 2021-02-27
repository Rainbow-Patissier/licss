<?php
include_once('../../data/link.php');
$secret_text='account_edit';
	if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		if(isset($_POST['account']) && $row=SQL::fetch("SELECT user_id,account,pwd,pwd_iv FROM account_info WHERE cond=0 AND user_id=?",array($user_id))){
			$row['account']=$_POST['account'];
			if($row2=SQL::fetch("SELECT user_id as uid,birthday_year,birthday,sex,name,name_iv,email,email_iv,school_id FROM account_user WHERE cond=0 AND user_id=? ORDER BY edit_date DESC",array($user_id))){
				$email=SQL::encrypt($_POST['mail']);
				$row2['email']=$email[0];
				$row2['email_iv']=$email[1];
				if(SQL::execute("UPDATE account_info SET cond=1,delete_date=NOW() WHERE cond=0 AND user_id=?;UPDATE account_user SET cond=1,delete_date=NOW() WHERE cond=0 AND user_id=?;INSERT INTO account_info VALUES (?,?,?,?,NOW(),0,NULL);INSERT INTO account_user VALUES (?,?,?,?,?,?,?,?,?,NOW(),0,NULL)",array_values(array_merge(array($user_id,$user_id),$row,$row2)))){
					$_SESSION[$session_head.'form_result']=array(0,"登録しました");
					header("Location: {$request_url}");
				}else{
					$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生し，登録できませんでした");
					header("Location: {$request_url}");
				}
			}else{
				$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生し，登録できませんでした");
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
$title="アカウント情報変更";
$data=SQL::fetch("SELECT account FROM account_info WHERE cond=0 AND user_id=?",array($user_id));
$data2=SQL::fetch("SELECT email,email_iv FROM account_user WHERE cond=0 AND user_id=?",array($user_id));
include_once('../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>account/">
				アカウント
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
		<table>
			<tr>
				<th>
					アカウント名
				</th>
				<td>
					<input type="text" name="account" id="account" placeholder="アカウント名" title="アカウント名を入力してください" value="<?=$data['account'];?>" required><span class="red" id="application_account_message"></span>
				</td>
			</tr>
			<tr>
				<th>
					メールアドレス
				</th>
				<td>
					<input type="email" name="mail" placeholder="メールアドレス" title="メールアドレスを入力してください" value="<?=SQL::decrypt($data2['email'],$data2['email_iv']);?>">
				</td>
			</tr>
		</table>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form>
	<script>
		document.getElementById('account').oninput=function(){
			application_check_account(this.value);
		};
	</script><?php
		ALL::remove_alert();
	?>
</section><?php
include_once('../../data/footer.php');
?>