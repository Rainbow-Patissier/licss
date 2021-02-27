<?php
include_once('../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0 && $data=SQL::fetch("SELECT grade,class,number FROM {$school_id}_account_class WHERE user_id=? AND cond=0",array($id))){
		$data2=SQL::fetch("SELECT sex,birthday FROM account_user WHERE user_id=? AND cond=0",array($id));
		$title="登録内容変更";
	}else{
		$title="エラーが発生";
		$id=0;
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
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content"><?php
	$secret_text='manage_user_edit';
	if(!isset($_POST['btn']) && $id!==0){
		?>
	<form method="post">
		<table>
			<tbody>
				<tr>
					<th>
						学年
					</th>
					<td>
						<input type="number" name="grade" placeholder="学年" title="学年を入力してください" min="0" value="<?=$data['grade'];?>" required>
					</td>
				</tr>
				<tr>
					<th>
						クラス
					</th>
					<td>
						<input type="text" name="class" placeholder="クラス" title="クラスを入力してください" value="<?=$data['class'];?>" required>
					</td>
				</tr>
				<tr>
					<th>
						番号
					</th>
					<td>
						<input type="number" name="number" placeholder="番号" title="番号を入力してください" min="0" value="<?=$data['number'];?>" required>
					</td>
				</tr>
				<tr>
					<th>
						名前
					</th>
					<td>
						<input type="text" name="name" placeholder="名前" title="名前を入力してください" value="<?=USER::name($id);?>" required>
					</td>
				</tr>
				<tr>
					<th>
						性別
					</th>
					<td>
						<?=$user_sex[$data2['sex']];?>
					</td>
				</tr>
				<tr>
					<th>
						生年月日
					</th>
					<td>
						<input type="date" name="birthday" placeholder="生年月日" title="生年月日を入力してください" value="<?=ALL::h($data2['birthday']);?>" required>
					</td>
				</tr>
				<tr>
					<th>
						利用者番号
					</th>
					<td>
						<?=sprintf('%08d',$id);?>
					</td>
				</tr>
			</tbody>
		</table>
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
		if(isset($_POST['grade']) && isset($_POST['class']) && isset($_POST['number']) && isset($_POST['name']) && isset($_POST['birthday'])){
			$row=SQL::fetch("SELECT MAX(class_id) as max FROM {$school_id}_account_class");
			$class_id=$row['max']+1;
			$row=SQL::fetch("SELECT user_right FROM {$school_id}_account_class WHERE user_id=? AND cond=0",array($id));
			$row2=SQL::fetch("SELECT user_id,birthday_year,birthday,sex,name,name_iv,email,email_iv,school_id FROM account_user WHERE user_id=? AND cond=0",array($id));
			$row2['birthday']=$_POST['birthday'];
			$name=SQL::encrypt($_POST['name']);
			$row2['name']=$name[0];
			$row2['name_iv']=$name[1];
			SQL::fetch("UPDATE {$school_id}_account_class SET cond=1,delete_date=NOW() WHERE cond=0 AND user_id=?;UPDATE account_user SET cond=1,delete_date=NOW() WHERE cond=0 AND user_id=?",array($id,$id));
			if(SQL::execute("INSERT INTO {$school_id}_account_class VALUES (?,?,?,?,?,?,NOW(),0,NULL);INSERT INTO account_user VALUES (?,?,?,?,?,?,?,?,?,NOW(),0,NULL)",array_merge(array($class_id,$id,$_POST['grade'],$_POST['class'],$_POST['number'],$row['user_right']),array_values($row2)))){
				?>
	<div>
		<h2>
			変更完了
		</h2>
		<p>
			利用者情報を変更しました。
		</p>
	</div><?php
			}else{
				?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			予期せぬエラーが発生したため，変更することができませんでした。
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