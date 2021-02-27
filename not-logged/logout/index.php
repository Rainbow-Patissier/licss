<?php
include_once('../../data/link.php');
SQL::execute("UPDATE account_log SET logout=? WHERE log_id=?",array($Ymdt,$_SESSION[$session_head.'user_log']));
$_SESSION=array();
$title="ログアウト";
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
	ログアウトしました。
</section><?php
include_once('../../data/footer.php');
?>