<?php
include_once('../../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0 && $data=SQL::fetch("SELECT request_id,user_id,book_name,isbn,DATE_FORMAT(edit_date,'%Y/%m/%e %H:%i:%s') as date,request_condition,note,result_note FROM {$school_id}_manage_books_request WHERE cond=0 AND request_id=?",array($id))){
		$title="図書購入依頼詳細";
		$secret_text='manage_books_request';
		$request_condition=array('未確認','承諾','却下');
		if(isset($_POST['btn']) && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
			if(isset($_POST['note'])){
				switch(SQL::decrypt_ks($_POST['btn'])){
					case 'accept':
						$row=array_values(SQL::fetch("SELECT request_id,user_id,book_name,isbn,note,entry_date FROM {$school_id}_manage_books_request WHERE cond=0 AND request_id=?",array($id)));
						$row[]=1;
						$row[]=$_POST['note'];
						SQL::execute("UPDATE {$school_id}_manage_books_request SET cond=1,delete_date=NOW() WHERE cond=0 AND request_id=?",array($id));
						if(SQL::execute("INSERT INTO {$school_id}_manage_books_request VALUES (?,?,?,?,?,?,?,?,NOW(),0,NULL)",$row)){
							$_SESSION[$session_head.'form_result']=array(0,"審査結果の登録が完了しました。承諾の場合，本をご購入ください。");
							header("Location: {$request_url}");
						}else{
							$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生したため，登録できませんでした。");
							header("Location: {$request_url}");
						}
						break;
					case 'unaccept':
						$row=array_values(SQL::fetch("SELECT request_id,user_id,book_name,isbn,note,entry_date FROM {$school_id}_manage_books_request WHERE cond=0 AND request_id=?",array($id)));
						$row[]=2;
						$row[]=$_POST['note'];
						SQL::execute("UPDATE {$school_id}_manage_books_request SET cond=1,delete_date=NOW() WHERE cond=0 AND request_id=?",array($id));
						if(SQL::execute("INSERT INTO {$school_id}_manage_books_request VALUES (?,?,?,?,?,?,?,?,NOW(),0,NULL)",$row)){
							$_SESSION[$session_head.'form_result']=array(0,"審査結果の登録が完了しました。承諾の場合，本をご購入ください。");
							header("Location: {$request_url}");
						}else{
							$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生したため，登録できませんでした。");
							header("Location: {$request_url}");
						}
						break;
						break;
					default:
						$_SESSION[$session_head.'form_result']=array(0,"未入力項目があるため，登録できませんでした");
						header("Location: {$request_url}");
				}
				$data=SQL::fetch("SELECT request_id,user_id,book_name,isbn,DATE_FORMAT(edit_date,'%Y/%m/%e %H:%i:%s') as date,request_condition,note,result_note FROM {$school_id}_manage_books_request WHERE cond=0 AND request_id=?",array($id));
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
	}else{
		$title="エラーが発生";
		$id=0;
	}
}else{
	$id=0;
	$title="エラーが発生";
}
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
			<a href="<?=$url;?>manage/books/">
				蔵書管理
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/books/request/">
				購入依頼
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
	if($id){
		if(isset($_SESSION[$session_head.'form_result'][1]) && $_SESSION[$session_head.'form_result'][0]<2){
		?>
	<div class="result">
		<?=ALL::h($_SESSION[$session_head.'form_result'][1]);?>
	</div><?php
		}
		?>
	<form method="post">
		<table>
			<tbody>
				<tr>
					<th>
						依頼者
					</th>
					<td>
						<?=USER::name($data['user_id']);?>(<?=USER::belong($data['user_id']);?>)
					</td>
				</tr>
				<tr>
					<th>
						図書名
					</th>
					<td>
						<?=ALL::h($data['book_name']);?>
						<br>
						<a href="https://shopping.yahoo.co.jp/search?p=<?=ALL::h($data['book_name']);?>" target="_blank">商品検索結果</a>
					</td>
				</tr>
				<tr>
					<th>
						ISBN
					</th>
					<td>
						<?=ALL::h($data['isbn']);?><br>
						<a href="https://shopping.yahoo.co.jp/search?p=<?=ALL::h($data['isbn']);?>" target="_blank">商品検索結果</a>
					</td>
				</tr>
				<tr>
					<th>
						備考
					</th>
					<td>
						<?=ALL::h($data['note']);?>
					</td>
				</tr>
				<tr>
					<th>
						依頼日時
					</th>
					<td>
						<?=$data['date'];?>
					</td>
				</tr>
				<tr>
					<th>
						状態
					</th>
					<td><?php
			echo $request_condition[$data['request_condition']];
			if(!$data['request_condition']){
			?>
						<br>
						<textarea name="note" placeholder="審査理由・備考等&#13;※申請者に公開されます" title="審査理由・備考等を記入してください。※申請者に公開されます"></textarea>
						<button type="submit" name="btn" value="<?=SQL::encrypt_ks('accept');?>" onClick="alert_cancel();">
							承諾
						</button>
						<button type="submit" name="btn" value="<?=SQL::encrypt_ks('unaccept');?>" onClick="alert_cancel();">
							却下
						</button><?php
				ALL::remove_alert();
			}else{
				echo "<br>".ALL::h($data['result_note']);
			}
		?>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
	</form><?php
	}else{
		?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			予期せぬエラーが発生しました。
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