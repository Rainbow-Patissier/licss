<?php
include_once('../../../../data/link.php');
$title="図書館からのお知らせ登録";
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
	$secret_text='manage_other_info';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<dl class="form_style">
			<dt>
				タイトル
			</dt>
			<dd>
				<input type="text" name="title" placeholder="タイトル" title="タイトルを入力してください" required>
			</dd>
			<dt>
				本文
			</dt>
			<dd>
				<textarea name="content" id="manage_other_info_entry_content" class="editor_content"></textarea>
				<div id="manage_other_info_entry_editor" class="editor_area"></div>
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="editor_save(document.getElementById('manage_other_info_entry_content'));alert_cancel();">
			登録
		</button>
		<script src="<?=$url;?>data/js/editor/header.js"></script>
		<script src="<?=$url;?>data/js/editor/list.js"></script>
		<script src="<?=$url;?>data/js/editor/checklist.js"></script>
		<script src="<?=$url;?>data/js/editor/quote.js"></script>
		<script src="<?=$url;?>data/js/editor/code.js"></script>
		<script src="<?=$url;?>data/js/editor/table.js"></script>
		<script src="<?=$url;?>data/js/editor/raw.js"></script>
		<script src="<?=$url;?>data/js/editor/delimiter.js"></script>
		<script src="<?=$url;?>data/js/editor/image.js"></script>
		<script src="<?=$url;?>data/js/editor/link.js"></script>
		<script src="<?=$url;?>data/js/editor/warning.js"></script>
		<script src="<?=$url;?>data/js/editor/editor.js"></script>
		<script>
			const editor = new EditorJS({
				holder: 'manage_other_info_entry_editor',
				tools:{
					header: Header,        
					list: List,
					quote: Quote,
					code: CodeTool,
					table: Table,
					raw: RawTool,
					delimiter: Delimiter,
					warning: Warning
				},
				data: {}
			});
		</script>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['title']) && isset($_POST['content'])){
			$title=SQL::encrypt_school($_POST['title']);
			$content=SQL::encrypt_school($_POST['content']);
			$row=SQL::fetch("SELECT MAX(info_id) as max FROM {$school_id}_manage_other_info");
			if(SQL::execute("INSERT INTO {$school_id}_manage_other_info VALUES (?,?,?,?,?,?,?)",array($row['max']+1,$user_id,$title[0],$title[1],$content[0],$content[1],$Ymdt))){
				?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			図書館からのお知らせを登録しました。
		</p>
	</div><?php
			}else{
				?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			図書館からのお知らせを登録できませんでした。
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
			入力の確認ができなかったため，処理が完了できませんでした。
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
	?>
</section><?php
include_once('../../../../data/footer.php');
?>