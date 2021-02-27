<?php
include_once('../../data/link.php');
$title="システムインフォメーション管理";
include_once('../../data/header.php');
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
<section id="main_content"><?php
	if(USER::right()===0){
		
	}else{
		?>
	<div>
		<h2>
			アクセス不可
		</h2>
		<p>
			このページにアクセスする権限がありません。
		</p>
	</div><?php
	}
	?>
</section><?php
include_once('../../data/footer.php');
?>