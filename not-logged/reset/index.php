<?php
include_once('../../data/link.php');
if(isset($_GET['i']) && isset($_GET['d'])){
	$id=(int)SQL::decrypt_kf($_GET['i']);
	if($id!==0){
		$deadline=new DateTime(SQL::decrypt_kf($_GET['d']));
		if($Ymdt<=$deadline->format('Y-m-d H:i:s')){
			$title="パスワードリセット";
		}else{
			$id=-1;
			$title="期限切れURL";
		}
	}else{
		$title="エラーが発生";
	}
}else{
	$id=0;
	$title="エラーが発生";
}
include_once('../../data/not_logged_header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>">
				ホーム
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
	$secret_text='password_reset';
	if(!isset($_POST['btn']) && $id>0){
		?>
	<form method="post">
		<dl class="form_style">
			<dt>
				新しいパスワード
			</dt>
			<dd>
				<input type="password" name="new_password" id="new_password" placeholder="新しいパスワード" title="半角英数字記号それぞれ1文字以上含み，12文字以上32文字以下で入力してください" pattern="^(?=.*?[a-zA-Z])(?=.*?\d)(?=.*?[!-/:-@[-`{-~])[!-~]{12,32}$" required><br>
				※半角英数字記号それぞれ1文字以上含み，12文字以上32文字以下
			</dd>
			<dt>
				確認用パスワード
			</dt>
			<dd>
				<input type="password" name="check_password" id="check_password" placeholder="確認用パスワード" title="確認用パスワードを入力してください" required>
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="return check_w_password();">
			登録
		</button>
	</form><?php
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		if(isset($_POST['new_password']) && isset($_POST['check_password']) && $_POST['new_password']===$_POST['check_password']){
			if($row=SQL::fetch("SELECT * FROM account_info WHERE user_id=? AND cond=0",array($id))){
				SQL::execute("UPDATE account_info SET cond=1,delete_date=NOW() WHERE user_id=? AND cond=0",array($id));
				$pwd=SQL::encrypt(password_hash($_POST['new_password'],PASSWORD_DEFAULT));
				$row['pwd']=$pwd[0];
				$row['pwd_iv']=$pwd[1];
				$row['edit_date']=$Ymdt;
				if(SQL::execute("INSERT INTO account_info VALUES (?,?,?,?,?,?,?)",array_values($row))){
					?>
		<div>
			<h2>
				リセット完了
			</h2>
			<p>
				パスワードをリセットしました。
			</p>
			<p>
				<a href="<?=$url;?>not-logged/login/">ログイン画面</a>からログインしてください。
			</p>
		</div><?php
				}else{
					?>
		<div>
			<h2>
				エラーが発生
			</h2>
			<p>
				パスワードをリセットできませんでした。
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
	}elseif($id===-1){
		?>
	<div>
		<h2>
			期限切れURL
		</h2>
		<p>
			期限が切れたURLです。URL発行から10分間のみ有効です。
		</p>
		<p>
			恐れ入りますが，再度URLを発行してください。
		</p>
	</div><?php
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
include_once('../../data/footer.php');
?>