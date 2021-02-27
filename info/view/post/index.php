<?php
include_once('../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0 && $data=SQL::fetch("SELECT * FROM system_info WHERE info_id=?",array($id))){
		$title=ALL::h(SQL::decrypt($data['title'],$data['title_iv']));
	}else{
		$title="エラーが発生";
	}
}else{
	$id=0;
	$title="エラーが発生";
}
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>info/view/">
				運営からのお知らせ
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
	if($id!==0){
		?>
	<h2>
		<?=$title;?>
	</h2>
	<div>
		<?=ALL::echo_editor(SQL::decrypt($data['content'],$data['content_iv']));?>
	</div>
	<div>
		登録者/登録日時：<?=USER::name($data['user_id']);?>/<?=ALL::h($data['edit_date']);?>
	</div><?php
	}else{
		?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			無効なURLです。
		</p>
		<p>
			恐れ入りますが，はじめからやり直してください。
		</p>
	</div><?php
	}
	?>
</section><?php
include_once('../../../data/footer.php');
?>