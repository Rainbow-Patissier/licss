<?php
include_once('../../../../data/link.php');
$title="個別連絡作成";
include_once('../../../../data/header.php');
if(isset($_GET['i'])){
	$person=(int)SQL::decrypt_k($_GET['i']);
}else{
	$person=0;
}
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
			<a href="<?=$url;?>manage/user/info/">
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
	$secret_text='manage_user_info';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<dl class="form_style">
			<dt>
				連絡相手
			</dt>
			<dd>
				<select name="person" title="連絡相手を選択してください" required>
					<option label="選択してください"></option><?php
		$row=SQL::fetch_all("SELECT grade,class FROM {$school_id}_account_class WHERE cond=0 ORDER BY grade,class");
		$stmt=$pdo->prepare("SELECT user_id FROM {$school_id}_account_class WHERE cond=0 AND grade=? AND class=? AND user_id!=? ORDER BY number");
		$all_grade=array_unique(array_column($row,'grade'));
		$all_class=array_unique(array_column($row,'class'));
		foreach($all_grade as $grade){
			foreach($all_class as $class){
				?>
					<optgroup label="<?=ALL::h($grade);?>年<?=ALL::h($class);?>組"><?php
				$stmt->execute(array($grade,$class,$user_id));
				foreach($stmt as $row){
					if((int)$row['user_id']===$person){
						$select=' selected';
					}else{
						$select='';
					}
					$name=USER::name($row['user_id']);
					?>
						<option value="<?=SQL::encrypt_ks($row['user_id']);?>" title="<?=$name;?>"<?=$select;?>>
							<?=$name;?>
						</option><?php
				}
				?>
					</optgroup><?php
			}
		}
		?>
				</select>
			</dd>
			<dt>
				タイトル
			</dt>
			<dd>
				<input type="text" name="title" placeholder="タイトル" title="タイトルを入力してください" required>
			</dd>
			<dt>
				内容
			</dt>
			<dd>
				<textarea name="message" placeholder="内容" title="内容を入力してください" required></textarea>
			</dd>
			<dt>
				返信の許可
			</dt>
			<dd>
				<label>
					<input type="radio" name="reply" title="返信を許可する" value="true">許可する
				</label>
				<label>
					<input type="radio" name="reply" title="返信を許可しない" value="false" checked>許可しない
				</label>
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['person']) && isset($_POST['title']) && isset($_POST['message']) && isset($_POST['reply'])){
			$title=SQL::encrypt_school($_POST['title']);
			$content=SQL::encrypt_school($_POST['message']);
			$row=SQL::fetch("SELECT MAX(userinfo_id) as max FROM {$school_id}_manage_user_info");
			$userinfo_id=$row['max']+1;
			$row=SQL::fetch("SELECT MAX(chat_id) as max FROM {$school_id}_manage_user_info");
			$chat_id=$row['max']+1;
			$student_user_id=(int)SQL::decrypt_ks($_POST['person']);
			if($_POST['reply']==='true'){
				$reply=0;
			}else{
				$reply=1;
			}
			$sql="INSERT INTO {$school_id}_manage_user_info VALUES (?,?,?,?,?,?,?,?,?,0,?,?)";
			$values=array($userinfo_id,$chat_id,$user_id,0,$student_user_id,$title[0],$title[1],$content[0],$content[1],$reply,$Ymdt);
			if(SQL::execute($sql,$values)){
				$message="<h2>個別連絡のお知らせ</h2>".USER::name($student_user_id)."&nbsp;様<p>".USER::name($user_id)."&nbsp;様から個別連絡が届いております。<br><a href=".'"'.$url.'">LiCSS</a>にログインしてから個別連絡を確認してください。';
				ALL::send_html_mail(USER::email($student_user_id),"個別連絡のお知らせ",$message);
				?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			個別連絡を登録しました。
		</p>
	</div><?php
			}else{
				?>
	<div>
		<h2>
			登録失敗
		</h2>
		<p>
			個別連絡を登録できませんでした。
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