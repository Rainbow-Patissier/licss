<?php
include_once('../../../data/link.php');
$title="年度更新";
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
	<ol>
		<li>
			卒業生を登録してください。<br>
			<button type="button" onClick="window.location.href='<?=$request_url;?>graduate/'">
				卒業生登録
			</button>
		</li>
		<li>
			在校生の所属情報(学年・組・番号)を更新します。<br>
			<button type="button" onClick="window.location.href='<?=$request_url;?>grade/'">
				在校生所属情報更新
			</button>
		</li>
	</ol>
</section><?php
include_once('../../../data/footer.php');
?>