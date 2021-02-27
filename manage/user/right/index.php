<?php
include_once('../../../data/link.php');
$secret_text='manage_user_right';
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!=0){
		if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
			if(isset($_POST['right'])){
				$row=SQL::fetch("SELECT * FROM {$school_id}_account_class WHERE cond=0 AND user_id=?",array($id));
				SQL::execute("UPDATE {$school_id}_account_class SET cond=1,delete_date=? WHERE user_id=? AND cond=0",array($Ymdt,$id));
				$row['edit_date']=$Ymdt;
				$row['user_right']=(int)SQL::decrypt_ks($_POST['right']);
				$row=array_values($row);
				$row[8]=NULL;
				if(SQL::execute("INSERT INTO {$school_id}_account_class VALUES (?,?,?,?,?,?,?,?,?)",$row)){
					$_SESSION[$session_head.'form_result']=array(0,"権限を変更しました");
					header("Location: {$request_url}");
				}else{
					$_SESSION[$session_head.'form_result']=array(0,"権限の変更ができませんでした");
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
		$name=USER::name($id);
		$title=$name."-権限変更";
		$right=USER::right($id);
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
			<a href="<?=$url;?>manage/user/list/">
				一覧
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
	#if(!isset($_POST['btn'])){
	if(true){
		if(isset($_SESSION[$session_head.'form_result'][1]) && $_SESSION[$session_head.'form_result'][0]<2){
		?>
	<div class="result">
		<?=ALL::h($_SESSION[$session_head.'form_result'][1]);?>
	</div><?php
		}
		?>
	<form method="post">
		<?=USER::belong($id).$name;?>さんの権限を<?=$user_right[$right];?>から
		<select name="right" title="権限を選択してください" required>
			<option label="選択してください"></option><?php
		foreach($user_right as $key=>$val){
			if((int)$key!==0){
				if((int)$key===$right){
					$select=' selected';
				}else{
					$select='';
				}
				?>
			<option value="<?=SQL::encrypt_ks($key);?>" title="<?=$val;?>"<?=$select;?>><?=$val;?></option><?php
			}
		}
		?>
		</select>
		に変更する。<br>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			変更
		</button>
	</form><?php
		#ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['right'])){
			$row=SQL::fetch("SELECT * FROM {$school_id}_account_class WHERE cond=0 AND user_id=?",array($id));
			SQL::execute("UPDATE {$school_id}_account_class SET cond=1,delete_date=? WHERE user_id=? AND cond=0",array($Ymdt,$id));
			$row['edit_date']=$Ymdt;
			$row['user_right']=(int)SQL::decrypt_ks($_POST['right']);
			$row=array_values($row);
			$row[8]=NULL;
			if(SQL::execute("INSERT INTO {$school_id}_account_class VALUES (?,?,?,?,?,?,?,?,?)",$row)){
				?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			権限を変更しました。
		</p>
	</div><?php
			}else{
				?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			権限を変更できませんでした。
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
include_once('../../../data/footer.php');
?>