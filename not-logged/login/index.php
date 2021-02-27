<?php
include_once('../../data/link.php');
$secret_text='login';
$message='';
if(isset($_POST['btn']) && isset($_POST['account']) && isset($_POST['password']) &&SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
	if($row=SQL::fetch("SELECT user_id,pwd,pwd_iv FROM account_info WHERE account=? AND cond=0",array($_POST['account']))){
		if(password_verify($_POST['password'],SQL::decrypt($row['pwd'],$row['pwd_iv'])) && $row2=SQL::fetch("SELECT name,name_iv,school_id FROM account_user WHERE user_id=? AND cond=0 ORDER BY edit_date DESC",array($row['user_id']))){
			if($row3=SQL::fetch("SELECT school_name,school FROM manage_school WHERE school_id=? AND cond=0",array($row2['school_id']))){
				if(!empty($_POST['url'])){
					$res_url=SQL::decrypt_ks($_POST['url']);
				}else{
					$res_url='';
				}
				$_SESSION=array();
				session_regenerate_id(true);
				$row4=SQL::fetch("SELECT MAX(log_id) as max FROM account_log",array());
				SQL::execute("INSERT INTO account_log VALUES (?,?,?,NULL)",array($row4['max']+1,$row['user_id'],$Ymdt));
				$_SESSION[$session_head.'user_log']=(int)$row4['max']+1;
				$_SESSION[$session_head.'school_id']=(int)$row2['school_id'];
				$_SESSION[$session_head.'school_name']=ALL::h($row3['school_name'].$school_kind[$row3['school']]);
				$_SESSION[$session_head.'user_id']=(int)$row['user_id'];
				$_SESSION[$session_head.'user_name']=ALL::h(SQL::decrypt($row2['name'],$row2['name_iv']));
				$_SESSION[$session_head.'user_right']=USER::right();
				if(!empty($res_url) && $url!=$res_url){
					header('Location: '.$res_url);
					exit();
				}
				switch($row2['user_right']){
					case 0:
						header('Location: '.$url.'manage/');
						break;
					case 1:
						header('Location: '.$url.'manage/');
						break;
					case 2:
						header('Location: '.$url.'manage/');
						break;
					case 3:
						header('Location: '.$url.'manage/');
						break;
					case 4:
						header('Location: '.$url.'manage/');
						break;
				}
				exit();
			}
		}
	}
	$message='<div class="red">アカウント名もしくはパスワードが違います</div>';
}
$title="ログイン";
include_once('../../data/not_logged_header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content">
	<div id="login_wrapper">
		<form method="post">
			<dl class="form_style">
				<dt>
					アカウント名
				</dt>
				<dd>
					<input type="text" name="account" placeholder="アカウント名" title="アカウント名を入力してください" required>
				</dd>
				<dt>
					パスワード
				</dt>
				<dd>
					<input type="password" name="password" placeholder="パスワード" title="パスワードを入力してください" required>
				</dd>
			</dl>
			<?=$message;?>
			<input type="hidden" name="url" value="<?=SQL::encrypt_ks(SQL::decrypt_k($_GET['url']));?>">
			<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
			<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
				ログイン
			</button><br>
			<a href="<?=$url;?>not-logged/forget-password/">アカウント名・パスワードを忘れた場合</a>
		</form>
	</div>
</section><?php
include_once('../../data/footer.php');
?>