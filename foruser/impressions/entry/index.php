<?php
include_once('../../../data/link.php');
$secret_text='impression_entry';
if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
	if(isset($_POST['title']) && isset($_POST['book_no']) && isset($_POST['isbn']) && isset($_POST['content']) && isset($_POST['public'])){
		$row=SQL::fetch("SELECT MAX(impressions_id) as max FROM {$school_id}_foruser_impressions");
		$impressions_id=$row['max']+1;
		if(SQL::execute("INSERT INTO {$school_id}_foruser_impressions VALUES (?,?,?,?,?,?,?,?,?,NOW(),0,NULL)",array($impressions_id,$user_id,$user_id,$_POST['title'],$_POST['book_no'],$_POST['isbn'],$_POST['content'],(int)SQL::decrypt_ks($_POST['public']),(int)mb_strlen(str_replace("\n","",$_POST['content']))+1-(int)count(explode("\n",$_POST['content']))))){
			$_SESSION[$session_head.'form_result']=array(0,"<p>感想文の登録が完了しました。</p><p>下書きの場合，感想文一覧から編集ができます。</p>","登録完了");
			header("Location: {$request_url}");
		}else{
			$_SESSION[$session_head.'form_result']=array(0,"<p>感想文の登録に失敗しました。</p>","登録失敗");
			header("Location: {$request_url}");
		}
	}else{
		$_SESSION[$session_head.'form_result']=array(0,"<p>未入力項目があるため，登録できませんでした</p>","登録失敗");
		header("Location: {$request_url}");
	}
}else{
	if(isset($_SESSION[$session_head.'form_result'][0]) && $_SESSION[$session_head.'form_result'][0]<2){
		++$_SESSION[$session_head.'form_result'][0];
	}else{
		unset($_SESSION[$session_head.'form_result']);
	}
}
$title="感想文登録";
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>foruser/">
				利用者機能
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
	if(isset($_SESSION[$session_head.'form_result'][1]) && $_SESSION[$session_head.'form_result'][0]<2){
		?>
	<div>
		<h2>
			<?=$_SESSION[$session_head.'form_result'][2];?>
		</h2>
		<?=$_SESSION[$session_head.'form_result'][1];?>
	</div><?php
	}else{
	?>
	<form method="post">
		<dl class="form_style">
			<dt>
				感想文タイトル
			</dt>
			<dd>
				<input type="text" name="title" placeholder="感想文タイトル" title="感想文タイトルを入力してください" required>
			</dd>
			<dt>
				図書番号
			</dt>
			<dd>
				<input type="number" name="book_no" placeholder="図書番号" title="図書番号がある場合，図書番号を入力してください" onChange="foruser_get_isbn(this.value);">
			</dd>
			<dt>
				ISBN
			</dt>
			<dd>
				<input type="number" name="isbn" id="isbn" placeholder="ISBN" title="ISBNを入力してください">
			</dd>
			<dt>
				感想文
			</dt>
			<dd>
				<textarea name="content" id="impression" placeholder="感想文" title="感想文の本文を入力してください" required></textarea>
			</dd>
			<dt>
				公開方法
			</dt>
			<dd>
				<label>
					<input type="radio" name="public" value="<?=SQL::encrypt_ks(0);?>" checked>下書き
				</label>
				<label>
					<input type="radio" name="public" value="<?=SQL::encrypt_ks(1);?>">非公開
				</label>
				<label>
					<input type="radio" name="public" value="<?=SQL::encrypt_ks(2);?>">学内公開
				</label>
				<label>
					<input type="radio" name="public" value="<?=SQL::encrypt_ks(3);?>">公開
				</label>
				<br>
				※下書きにすると，あとから編集できますが，公開しません。<br>
				※下書き以外を選択すると，あとから編集できません。
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form>
	<div id="announce_back" class="no_print"></div>
	<div id="announce_wrapper" class="no_print">
		<div id="announce_msg">
			 <div class="balls-guruguru">
				<span class="ball ball-1"></span>
				<span class="ball ball-2"></span>
				<span class="ball ball-3"></span>
				<span class="ball ball-4"></span>
				<span class="ball ball-5"></span>
				<span class="ball ball-6"></span>
				<span class="ball ball-7"></span>
				<span class="ball ball-8"></span>
			</div>
		</div>
	</div><?php
		ALL::remove_alert();
	}
	?>
</section><?php
include_once('../../../data/footer.php');
?>