<?php
include_once('../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0){
		$title="個別連絡詳細";
		$secret_text='foruser_info_view';
		$data=SQL::fetch("SELECT edit_user_id,teacher_user_id,student_user_id,title,title_iv,content,content_iv,edit_date,read_condition,reply FROM {$school_id}_manage_user_info WHERE chat_id=? ORDER BY edit_date",array($id));
		SQL::execute("UPDATE {$school_id}_manage_user_info SET read_condition=1 WHERE chat_id=? AND read_condition=0 AND student_user_id=? AND teacher_user_id=0",array($id,$user_id));
		if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
			if(isset($_POST['message'])){
				$row=SQL::fetch("SELECT MAX(userinfo_id) as max FROM {$school_id}_manage_user_info");
				$values=array();
				$values[]=$row['max']+1;
				$values[]=$id;
				$values[]=$data['edit_user_id'];
				$values[]=$data['edit_user_id'];
				$values[]=0;
				$values[]='';
				$values[]='';
				$content=SQL::encrypt_school($_POST['message']);
				$values[]=$content[0];
				$values[]=$content[1];
				if(SQL::execute("INSERT INTO {$school_id}_manage_user_info VALUES (?,?,?,?,?,?,?,?,?,0,'',NOW())",$values)){
					$message="<h2>個別連絡のお知らせ</h2>".USER::name($data['teacher_user_id'])."&nbsp;様<p>".USER::name($user_id)."&nbsp;様から個別連絡への返信が届いております。<br><a href=".'"'.$url.'">LiCSS</a>にログインしてから個別連絡を確認してください。';
					ALL::send_html_mail(USER::email($data['teacher_user_id']),"個別連絡のお知らせ",$message);
					$_SESSION[$session_head.'form_result']=array(0,"送信しました");
					header("Location: {$request_url}");
				}else{
					$_SESSION[$session_head.'form_result']=array(0,"送信できませんでした");
					header("Location: {$request_url}");
				}
			}else{
				$_SESSION[$session_head.'form_result']=array(0,"未入力項目があるため，登録できませんでした");
				header("Location: {$request_url}");
			}
		}else{
			if(isset($_SESSION[$session_head.'form_result'][0]) && $_SESSION[$session_head.'form_result'][0]<2){
				++$_SESSION[$session_head.'form_result'][0];
			}else{
				unset($_SESSION[$session_head.'form_result']);
			}
		}
		$data=SQL::fetch("SELECT student_user_id,title,title_iv,content,content_iv,edit_date,read_condition,reply FROM {$school_id}_manage_user_info WHERE chat_id=? ORDER BY edit_date",array($id));
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
			<a href="<?=$url;?>foruser/">
				利用者機能
			</a>
		</li>
		<li>
			<a href="<?=$url;?>foruser/contact/">
				個別連絡
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
	<div class="result">
		<?=ALL::h($_SESSION[$session_head.'form_result'][1]);?>
	</div><?php
	}
	if((int)$data['read_condition']===0){
		$read='未読';
	}else{
		$read='既読';
	}
		?>
	<form method="post">
		<div id="manage_user_info_view_wrapper">
			<div class="manage_user_info_view_teacher_wrapper">
				<div class="manage_user_info_view_title">
					<?=ALL::h(SQL::decrypt_school($data['title'],$data['title_iv']));?>
				</div>
				<div class="manage_user_info_view_message">
					<?=ALL::h(SQL::decrypt_school($data['content'],$data['content_iv']));?>
				</div>
				<div>
					<?=$data['edit_date'];?>(<?=$read;?>)
				</div>
			</div><?php
		$stmt=$pdo->prepare("SELECT teacher_user_id,title,title_iv,content,content_iv,edit_date,read_condition FROM {$school_id}_manage_user_info WHERE chat_id=? ORDER BY edit_date LIMIT 10000 OFFSET 1");
		$stmt->execute(array($id));
		foreach($stmt as $row){
			if($row['teacher_user_id']!=0){
				?>
			<div class="manage_user_info_view_student_wrapper"><?php
				$read='';
			}else{
				?>
			<div class="manage_user_info_view_teacher_wrapper"><?php
				if((int)$row['read_condition']===0){
					$read='(未読)';
				}else{
					$read='(既読)';
				}
			}
			?>
				<div class="manage_user_info_view_message">
					<?=ALL::h(SQL::decrypt_school($row['content'],$row['content_iv']));?>
				</div>
				<div>
					<?=$row['edit_date'].$read;?>
				</div>
			</div><?php
		}
		?>
		</div><?php
		if((int)$data['reply']===0){
			?>
		<div id="manage_user_info_view_send">
			<textarea name="message" placeholder="返信内容" title="返信内容を入力してください" required></textarea><br>
			<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
			<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
				送信
			</button>
		</div><?php
			ALL::remove_alert();
		}
		?>
	</form>
</section><?php
include_once('../../../data/footer.php');
?>