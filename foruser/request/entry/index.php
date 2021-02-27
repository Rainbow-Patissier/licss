<?php
include_once('../../../data/link.php');
$secret_text='';
	if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		if(isset($_POST['book_name']) && isset($_POST['isbn']) && isset($_POST['note'])){
			$row=SQL::fetch("SELECT MAX(request_id) as max FROM {$school_id}_manage_books_request");
			$request_id=$row['max']+1;
			if(SQL::execute("INSERT INTO {$school_id}_manage_books_request VALUES (?,?,?,?,?,CURDATE(),0,'',NOW(),0,NULL)",array($request_id,$user_id,$_POST['book_name'],$_POST['isbn'],$_POST['note']))){
				$_SESSION[$session_head.'form_result']=array(0,"図書購入依頼を申請していただき，有難うございました。<br>購入については検討をいたします。検討後は結果が登録されますので一覧ページからご確認ください。","申請完了");
				header("Location: {$request_url}");
			}else{
				$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生したため，登録できませんでした。","申請失敗");
				header("Location: {$request_url}");
			}
		}else{
			$_SESSION[$session_head.'form_result']=array(0,"未入力項目があるため，登録できませんでした。","申請不可");
			header("Location: {$request_url}");
		}
	}else{
		if(isset($_SESSION[$session_head.'form_result'][0]) && $_SESSION[$session_head.'form_result'][0]<2){
			++$_SESSION[$session_head.'form_result'][0];
		}else{
			unset($_SESSION[$session_head.'form_result']);
		}
	}
$title="図書購入依頼申請";
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
			<a href="<?=$url;?>foruser/request/">
				図書購入依頼
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
		<p>
			<?=$_SESSION[$session_head.'form_result'][1];?>
		</p>
	</div><?php
	}else{
	?>
	<form method="post">
		図書購入依頼を登録する前にもう一度蔵書にないか検索してみましょう！<br>
		<a href="<?=$url;?>foruser/books/search/" target="_blank">蔵書検索</a>
		<dl class="form_style">
			<dt>
				図書名
			</dt>
			<dd>
				<input type="text" name="book_name" placeholder="図書名" title="正確に図書名を入力してください" required>
			</dd>
			<dt>
				ISBN
			</dt>
			<dd>
				<input type="number" name="isbn" placeholder="ISBN" title="ISBNを入力してください">
				<br>
				※インターネットで本を検索し，ISBNを調べて入力してください
			</dd>
			<dt>
				備考
			</dt>
			<dd>
				<textarea name="note" placeholder="本に対する思いや備考等" title="本に対する思いや備考等を入力してください"></textarea>
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			依頼
		</button>
	</form><?php
		ALL::remove_alert();
	}
	?>
</section><?php
include_once('../../../data/footer.php');
?>