<?php
include_once('../../../data/link.php');
$title="利用者登録";
include_once('../../../data/header.php');
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
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content">
	<p>
		※オンラインでLiCSSを利用している場合<br>
		利用者登録は2通りの方法があります。<br>
		以前の学校や塾でオンラインのLiCSSを利用している場合，ユーザーデータがありますので利用者番号を登録してください。<br>
		※学校ネットワークのみであるローカルのLiCSSの場合，ユーザーデータはないので新規に登録を行ってください。
	</p>
	<ol>
		<li>
			<a href="<?=$request_url;?>old/">以前にLiCSSを利用していた児童生徒</a>
		</li>
		<li>
			<a href="<?=$request_url;?>new/">新規利用者登録</a>
		</li>
	</ol>
</section><?php
include_once('../../../data/footer.php');
?>