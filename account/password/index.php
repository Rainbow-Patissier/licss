<?php
include_once('../../data/link.php');
	$secret_text='account_password';
	if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		if(isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['check_password']) && $_POST['new_password']==$_POST['check_password']){
			if($row=SQL::fetch("SELECT user_id,account FROM account_info WHERE cond=0 AND user_id=?",array($user_id))){
				$hash=SQL::encrypt(password_hash($_POST['new_password'],PASSWORD_DEFAULT));
				if(SQL::execute("UPDATE account_info SET cond=1,delete_date=NOW() WHERE cond=0 AND user_id=?;INSERT INTO account_info VALUES (?,?,?,?,NOW(),0,NULL);",array_values(array_merge(array($user_id),$row,$hash)))){
					$_SESSION[$session_head.'form_result']=array(0,"登録しました");
					header("Location: {$request_url}");
				}else{
					$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生したため，変更できませんでした");
					header("Location: {$request_url}");
				}
			}else{
				$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生したため，変更できませんでした");
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
$title="パスワード変更";
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
<section id="main_content">
	<?php
	if(isset($_SESSION[$session_head.'form_result'][1]) && $_SESSION[$session_head.'form_result'][0]<2){
		?>
	<div>
		<p>
			<?=$_SESSION[$session_head.'form_result'][1];?>
		</p>
	</div><?php
	}else{
		?>
	<form method="post">
		<dl class="form_style">
			<dt>
				現在のパスワード
			</dt>
			<dd>
				<input type="password" name="current_password" placeholder="現在のパスワード" title="現在のパスワードを入力してください" required>
			</dd>
			<dt>
				新しいパスワード
			</dt>
			<dd>
				<input type="password" name="new_password" id="new_password" placeholder="新しいパスワード" title="12文字以上かつ1文字以上の数字・小文字アルファベット・大文字アルファベット・記号がそれぞれ含まれている新しいパスワードを入力してください" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[!-/:-@[-`{-~]).{12,}" autocomplete="new-password" required><br>
				※12文字以上かつ1文字以上の数字・小文字アルファベット・大文字アルファベット・記号がそれぞれ含まれていること
			</dd>
			<dt>
				確認用パスワード
			</dt>
			<dd>
				<input type="password" name="check_password" id="check_password" placeholder="確認用パスワード" title="確認用パスワードを入力してください" required>
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();return check_w_password();">
			登録
		</button>
	</form><?php
	}
	?>
</section><?php
include_once('../../data/footer.php');
?>