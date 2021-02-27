<?php
include_once('../../data/link.php');
$title="アカウント名・パスワードを忘れた場合";
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
<section id="main_content">
	<form>
		登録しているアカウント名もしくはメールアドレスを入力してください
		<br>
		<input type="text" id="account_or_mail" placeholder="アカウント名もしくはメールアドレス" title="登録しているアカウント名もしくはメールアドレスを入力してください" required>
		<div id="check_account_display" class="red"></div>
		<button type="button" id="check_account" onClick="forget_check_account();">
			送信
		</button>
	</form>
</section><?php
include_once('../../data/footer.php');
?>