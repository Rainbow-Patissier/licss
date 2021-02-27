<?php
include_once('../../../../data/link.php');
$title="図書館だより登録";
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
			<a href="<?=$url;?>manage/other/letter/">
				図書館だより
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
	<form method="post" enctype="multipart/form-data">
		<dl class="form_style">
			<dt>
				タイトル
			</dt>
			<dd>
				<input type="text" name="title" placeholder="タイトル" title="タイトルを入力してください" required>
			</dd>
			<dt>
				図書館だより
			</dt>
			<dd>
				<div id="file_area"><input type="file" name="file" title="ファイルを選択してください" id="file" accept="application/pdf" required></div>
				※PDFのみ可
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form><?php
		ALL::remove_alert();
		ALL::drop_script();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['title']) && isset($_FILES['file'])){
			#print_r($_FILES['file']);
			if(isset($_FILES['file']) && is_array($_FILES['file']) && isset($_FILES['file']['error']) && is_int($_FILES['file']['error']) && $_FILES['file']['error']===0 && is_uploaded_file($_FILES['file']['tmp_name'])){
				$filepath=pathinfo($_FILES['file']['name']);
				if($filepath['extension']==='pdf'){
					$filename=$school_id.$date->format('Ymdhis.').$filepath['extension'];
					if(move_uploaded_file($_FILES['file']['tmp_name'],DATA_DIR.'letter/'.$filename)){
						//save DB
						$row=SQL::fetch("SELECT MAX(letter_id) as max FROM {$school_id}_manage_other_letter");
						$title=SQL::encrypt_school($_POST['title'].'.'.$filepath['extension']);
						$file=SQL::encrypt_school($filename);
						if(SQL::execute("INSERT INTO {$school_id}_manage_other_letter (letter_id,user_id,title,title_iv,file,file_iv,edit_date) VALUES (?,?,?,?,?,?,?)",array($row['max']+1,$user_id,$title[0],$title[1],$file[0],$file[1],$Ymdt))){
						?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			ファイルのアップロードに成功し，図書館だよりを登録しました。
		</p>
	</div><?php
						}else{
							?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			図書館だよりの登録に失敗しました。
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
			ファイルのアップロードに失敗しました。
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
			無効なファイルです。PDFのみアップロード可能です。
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
			ファイルのアップロードが確認できませんでした。
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