<?php
include_once('../../../data/link.php');
$title="その他設定";
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
	$secret_text='setting_other';
	if(!isset($_POST['btn'])){
		$reserve_alert=array("","","");
		$reserve=array("","");
		$book_reverse=array("","");
		$book_control=array(array("",""),array("",""),array("",""));
		$student_grade=array("","");
		$student_class=array("","");
		$student_number=array("","");
		$student_name=array("","");
		$student_sex=array("","");
		$borrow_public=array("","","");
		if($data=SQL::fetch("SELECT * FROM {$school_id}_manage_setting_other WHERE cond=0")){
			$reserve_alert[$data['reserve_info']]=' checked';
			$reserve[$data['reserve']]=' checked';
			$book_reverse[$data['reverse_info']]=' checked';
			$book_control[0][$data['book_teacher']]=' checked';
			$book_control[1][$data['book_officer']]=' checked';
			$book_control[2][$data['book_general']]=' checked';
			$student_grade[$data['grade']]=' checked';
			$student_class[$data['class']]=' checked';
			$student_number[$data['number']]=' checked';
			$student_name[$data['name']]=' checked';
			$student_sex[$data['sex']]=' checked';
			$borrow_public[$data['publish']]=' checked';
		}else{
			$reserve_alert=array(" checked","","");
			$reserve=array(" checked","");
			$book_reverse=array(" checked","");
			$book_control=array(array(" checked",""),array(" checked",""),array(" checked",""));
			$student_grade=array(" checked","");
			$student_class=array(" checked","");
			$student_number=array(" checked","");
			$student_name=array(" checked","");
			$student_sex=array(" checked","");
			$borrow_public=array("",""," checked");
		}
		?>
	<form method="post">
		<dl class="form_style">
			<dt>
				予約通知設定
			</dt>
			<dd>
				予約された本が返却された際(自動)もしくは準備ができた際(手動)に予約者に通知を行いますか？<br>
				<label>
					<input type="radio" name="reserve_alert" title="通知する" value="0"<?=$reserve_alert[0];?>>
					通知する(返却時)
				</label>
				<label>
					<input type="radio" name="reserve_alert" title="通知する" value="1"<?=$reserve_alert[1];?>>
					通知する(準備後)
				</label>
				<label>
					<input type="radio" name="reserve_alert" title="通知しない" value="2"<?=$reserve_alert[2];?>>
					通知しない
				</label>
				<br>
				※通知しないもしくはメールアドレスを登録していないユーザーにはプリントアウトした手紙を配布し，お知らせすることができます。
			</dd>
			<dt>
				予約設定
			</dt>
			<dd>
				貸出可能な図書の予約を許可しますか？<br>
				<label>
					<input type="radio" name="reserve" title="通知する" value="0"<?=$reserve[0];?>>
					許可する
				</label>
				<label>
					<input type="radio" name="reserve" title="通知しない" value="1"<?=$reserve[1];?>>
					許可しない
				</label>
			</dd>
			<dt>
				図書返却通知設定
			</dt>
			<dd>
				返却期限前日および延滞をしている場合にメールで返却通知を行いますか？<br>
				<label>
					<input type="radio" name="book_reverse" title="通知する" value="0"<?=$book_reverse[0];?>>
					通知する
				</label>
				<label>
					<input type="radio" name="book_reverse" title="通知しない" value="1"<?=$book_reverse[1];?>>
					通知しない
				</label>
				<br>
				※通知しないもしくはメールアドレスを登録していないユーザーにはプリントアウトした返却請求を配布し，返却請求をすることができます。
			</dd>
			<dt>
				図書貸出・返却設定
			</dt>
			<dd>
				窓口業務の図書の貸出・返却作業ができる範囲を設定してください。
				<table>
					<tbody>
						<tr>
							<th>
								管理者
							</th>
							<td>
								<label>
									
									許可する
								</label>
							</td>
						</tr>
						<tr>
							<th>
								教師
							</th>
							<td>
								<label>
									<input type="radio" name="book_control[0]" title="通知する" value="0"<?=$book_control[0][0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="book_control[0]" title="通知しない" value="1"<?=$book_control[0][1];?>>
									許可しない
								</label>
							</td>
						</tr>
						<tr>
							<th>
								学校職員
							</th>
							<td>
								<label>
									<input type="radio" name="book_control[1]" title="通知する" value="0"<?=$book_control[1][0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="book_control[1]" title="通知しない" value="1"<?=$book_control[1][1];?>>
									許可しない
								</label>
							</td>
						</tr>
						<tr>
							<th>
								一般*
							</th>
							<td>
								<label>
									<input type="radio" name="book_control[2]" title="通知する" value="0"<?=$book_control[2][0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="book_control[2]" title="通知しない" value="1"<?=$book_control[2][1];?>>
									許可しない
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				*事前に管理者ログインが必要
			</dd>
			<dt>
				利用者情報変更設定
			</dt>
			<dd>
				利用者自身が変更できる利用者情報の範囲を設定してください。管理者は全利用者の情報を変更することが可能です。（自分以外の管理者の情報は変更できません）<br>
				<table>
					<tbody>
						<tr>
							<th>
								学年
							</th>
							<td>
								<label>
									<input type="radio" name="student_grade" title="通知する" value="0"<?=$student_grade[0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="student_grade" title="通知しない" value="1"<?=$student_grade[1];?>>
									許可しない
								</label>
							</td>
						</tr>
						<tr>
							<th>
								組
							</th>
							<td>
								<label>
									<input type="radio" name="student_class" title="通知する" value="0"<?=$student_class[0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="student_class" title="通知しない" value="1"<?=$student_class[1];?>>
									許可しない
								</label>
							</td>
						</tr>
						<tr>
							<th>
								番号
							</th>
							<td>
								<label>
									<input type="radio" name="student_number" title="通知する" value="0"<?=$student_number[0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="student_number" title="通知しない" value="1"<?=$student_number[1];?>>
									許可しない
								</label>
							</td>
						</tr>
						<tr>
							<th>
								性別
							</th>
							<td>
								<label>
									<input type="radio" name="student_sex" title="通知する" value="0"<?=$student_sex[0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="student_sex" title="通知しない" value="1"<?=$student_sex[1];?>>
									許可しない
								</label>
							</td>
						</tr>
						<tr>
							<th>
								名前
							</th>
							<td>
								<label>
									<input type="radio" name="student_name" title="通知する" value="0"<?=$student_name[0];?>>
									許可する
								</label>
								<label>
									<input type="radio" name="student_name" title="通知しない" value="1"<?=$student_name[1];?>>
									許可しない
								</label>
							</td>
						</tr>
					</tbody>
				</table>
			</dd>
			<dt>
				貸出内容公開設定
			</dt>
			<dd>
				貸出内容の公開範囲を設定してください。感想文の公開範囲もこの設定に準じます。<br>
				<label>
					<input type="radio" name="borrow_public" title="通知する" value="0"<?=$borrow_public[0];?>>
					非公開
					*誰にも公開されません
				</label>
				<label>
					<input type="radio" name="borrow_public" title="通知しない" value="1"<?=$borrow_public[1];?>>
					学内
					*学校内のユーザーに公開されます
				</label>
				<label>
					<input type="radio" name="borrow_public" title="通知しない" value="2"<?=$borrow_public[2];?>>
					公開
					*システムを利用している他校にも公開されます
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
			echo "(int)\$_POST['{$key}'],";
		}*/
		if(isset($_POST['reserve_alert']) && isset($_POST['reserve']) && isset($_POST['book_reverse']) && isset($_POST['book_control']) && isset($_POST['student_grade']) && isset($_POST['student_class']) && isset($_POST['student_number']) && isset($_POST['student_sex']) && isset($_POST['student_name']) && isset($_POST['borrow_public'])){
			$row=SQL::fetch("SELECT MAX(other_id) as max FROM {$school_id}_manage_setting_other");
			$sql="INSERT INTO {$school_id}_manage_setting_other VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,NULL)";
			$values=array($row['max']+1,$user_id,(int)$_POST['reserve_alert'],(int)$_POST['reserve'],(int)$_POST['book_reverse'],(int)$_POST['book_control'][0],(int)$_POST['book_control'][1],(int)$_POST['book_control'][2],(int)$_POST['student_grade'],(int)$_POST['student_class'],(int)$_POST['student_number'],(int)$_POST['student_sex'],(int)$_POST['student_name'],(int)$_POST['borrow_public'],$Ymdt);
			SQL::execute("UPDATE {$school_id}_manage_setting_other SET cond=1,delete_date=? WHERE cond=0",array($Ymdt));
			if(SQL::execute($sql,$values)){
				?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			設定を登録しました。
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