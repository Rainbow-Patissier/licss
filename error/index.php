<?php
include_once('../data/link.php');
if(isset($_GET['e'])){
	switch((int)$_GET['e']){
		case 401:
			$title="認証に失敗しました";
			break;
		case 403:
			$title="アクセス権がありません";
			break;
		case 404:
			$title="ページが存在しません";
			break;
		case 500:
			$title="サーバ内でエラーが発生しています";
			break;
		case 501:
			$title="リクエストの処理ができません";
			break;
		case 503:
			$title="サービスが利用できません";
			break;
		default:
			$title="エラーが発生しています";
	}
}else{
	$title="エラーが発生しています";
}
include_once('../data/header.php');
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
	<h2>
		<?=$title;?>
	</h2>
	<div>
		<p>
			表題の通りエラーが発生しています。
		</p>
		<p>
			恐れ入りますが，<a href="https://rainbow-patissier.i-yhp.com/%e3%81%8a%e5%95%8f%e3%81%84%e5%90%88%e3%82%8f%e3%81%9b/" target="_blank">管理者</a>までご連絡ください。
		</p>
	</div>
</section><?php
include_once('../data/footer.php');
?>