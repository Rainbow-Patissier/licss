<?php
include_once('../../../data/link.php');
$title="貸出冊数・返却期限設定";
include_once('../../../data/header.php');
if($data=SQL::fetch("SELECT * FROM {$school_id}_manage_setting_borrow WHERE cond=0")){
	$number=array($data['admin_number'],$data['teacher_number'],$data['officer_number'],$data['student_number']);
	$deadline=array($data['admin_deadline'],$data['teacher_deadline'],$data['officer_deadline'],$data['student_deadline']);
}else{
	$number=array('','','','');
	$deadline=array('','','','');
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
			<a href="<?=$url;?>manage/setting/">
				設定
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
	$secret_text='setting_borrow';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<table>
			<thead>
				<tr>
					<th>
						ユーザー
					</th>
					<th>
						貸出冊数
					</th>
					<th>
						返却期限
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>
						管理者
					</th>
					<td>
						<span class="book_number">
							<input type="number" name="admin_number" placeholder="貸出冊数" title="貸出冊数を入力してください" min="0" value="<?=$number[0];?>" required>
						</span>
					</td>
					<td>
						<span class="day">
							<input type="number" name="admin_until" placeholder="返却期限" title="返却期限を入力してください" min="0" value="<?=$deadline[0];?>" required>
						</span>
					</td>
				</tr>
				<tr>
					<th>
						教師
					</th>
					<td>
						<span class="book_number">
							<input type="number" name="teacher_number" placeholder="貸出冊数" title="貸出冊数を入力してください" min="0" value="<?=$number[1];?>" required>
						</span>
					</td>
					<td>
						<span class="day">
							<input type="number" name="teacher_until" placeholder="返却期限" title="返却期限を入力してください" min="0" value="<?=$deadline[1];?>" required>
						</span>
					</td>
				</tr>
				<tr>
					<th>
						学校職員
					</th>
					<td>
						<span class="book_number">
							<input type="number" name="officer_number" placeholder="貸出冊数" title="貸出冊数を入力してください" min="0" value="<?=$number[2];?>" required>
						</span>
					</td>
					<td>
						<span class="day">
							<input type="number" name="officer_until" placeholder="返却期限" title="返却期限を入力してください" min="0" value="<?=$deadline[2];?>" required>
						</span>
					</td>
				</tr>
				<tr>
					<th>
						一般
					</th>
					<td>
						<span class="book_number">
							<input type="number" name="student_number" placeholder="貸出冊数" title="貸出冊数を入力してください" min="0" value="<?=$number[3];?>" required>
						</span>
					</td>
					<td>
						<span class="day">
							<input type="number" name="student_until" placeholder="返却期限" title="返却期限を入力してください" min="0" value="<?=$deadline[3];?>" required>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			変更
		</button>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo "(int)\$_POST['{$key}'],";
		}*/
		if(isset($_POST['admin_number']) && isset($_POST['admin_until']) && isset($_POST['teacher_number']) && isset($_POST['teacher_until']) && isset($_POST['officer_number']) && isset($_POST['officer_until']) && isset($_POST['student_number']) && isset($_POST['student_until'])){
			SQL::execute("UPDATE {$school_id}_manage_setting_borrow SET cond=1,delete_date=? WHERE cond=0",array($Ymdt));
			$row=SQL::fetch("SELECT MAX(borrow_id) as max FROM {$school_id}_manage_setting_borrow");
			$sql="INSERT INTO {$school_id}_manage_setting_borrow  VALUES (?,?,?,?,?,?,?,?,?,?,?,0,NULL)";
			$values=array($row['max']+1,$user_id,(int)$_POST['admin_number'],(int)$_POST['teacher_number'],(int)$_POST['officer_number'],(int)$_POST['student_number'],(int)$_POST['admin_until'],(int)$_POST['teacher_until'],(int)$_POST['officer_until'],(int)$_POST['student_until'],$Ymdt);
			if(SQL::execute($sql,$values)){
				?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			登録が完了しました。
		</p>
	</div><?php
			}else{
				?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			登録に失敗しました。
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
			すべての入力必須項目への入力が確認できませんでした。
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