<?php
include_once('../../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0 && $data=SQL::fetch("SELECT user_id,title,title_iv,content,content_iv,edit_date FROM {$school_id}_manage_other_info WHERE info_id=?",array($id))){
		$title=ALL::h(SQL::decrypt_school($data['title'],$data['title_iv']));
	}else{
		$title="エラーが発生";
	}
}else{
	$id=0;
	$title="エラーが発生";
}
include_once('../../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>manage/">
				管理業務
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/other/">
				その他
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/other/info/">
				図書館からのお知らせ
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
	<div>
		<h2>
			<?=$title;?>
		</h2>
		<div>
			<?=ALL::echo_editor(SQL::decrypt_school($data['content'],$data['content_iv']));?>
		</div>
		<div>
			登録者/登録日時：<?=USER::name($data['user_id']);?>/<?=$data['edit_date'];?>
		</div>
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
include_once('../../../../data/footer.php');
?>