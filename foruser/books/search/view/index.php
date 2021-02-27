<?php
include_once('../../../../data/link.php');
$secret_text='search_reserve';
if(isset($_GET['i']) && $_GET['i']!=0 && $data=SQL::fetch("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 AND book_id=?",array($_GET['i']))){
	$id=(int)$_GET['i'];
	$title=ALL::h($data['book_name']);
	if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		$row=SQL::fetch("SELECT MAX(reserve_id) as max FROM {$school_id}_counter_reserve");
		$reserve_id=$row['max']+1;
		if(SQL::execute("INSERT INTO {$school_id}_counter_reserve VALUES (?,?,?,?,NOW(),1,NOW())",array($reserve_id,$user_id,$id,$user_id))){
			$_SESSION[$session_head.'form_result']=array(0,"予約しました");
			header("Location: {$request_url}");
		}else{
			$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生したため予約できませんでした");
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
	$id=0;
	$title="エラーが発生";
	if(isset($_POST['btn'])){
		$_SESSION[$session_head.'form_result']=array(0,"予期せぬエラーが発生したため予約できませんでした");
		header("Location: {$request_url}");
	}else{
		if(isset($_SESSION[$session_head.'form_result'][0]) && $_SESSION[$session_head.'form_result'][0]<2){
			++$_SESSION[$session_head.'form_result'][0];
		}else{
			unset($_SESSION[$session_head.'form_result']);
		}
	}
}
include_once('../../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>foruser/">
				利用者機能
			</a>
		</li>
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
	<div class="result">
		<?=ALL::h($_SESSION[$session_head.'form_result'][1]);?>
	</div><?php
	}
	if($id!==0){
	?>
	<div class="scroll">
		<table>
			<tbody>
				<tr>
					<th>
						図書番号
					</th>
					<td>
						<?=ALL::h(sprintf('%09d',$id));?>
					</td>
				</tr>
				<tr>
					<th>
						ISBN
					</th>
					<td>
						<?=ALL::h($data['isbn']);?>
					</td>
				</tr>
				<tr>
					<th>
						受入日
					</th>
					<td>
						<?=ALL::h($data['entry_date']);?>
					</td>
				</tr>
				<tr>
					<th>
						シリーズ名
					</th>
					<td>
						<?=ALL::h($data['series']);?>
					</td>
				</tr>
				<tr>
					<th>
						図書名
					</th>
					<td>
						<?=ALL::h($data['book_name']);?>(<?=ALL::h($data['book_name_ruby']);?>)
					</td>
				</tr>
				<tr>
					<th>
						副書名
					</th>
					<td>
						<?=ALL::h($data['sub_name']);?>
					</td>
				</tr>
				<tr>
					<th>
						著者
					</th>
					<td>
						<dl><?php
							for($i=1;$i<5;++$i){
								if(!empty($data['author'.$i])){
									if(!empty($data['author'.$i.'_ruby'])){
										$ruby='('.ALL::h($data['author_ruby'.$i]).')';
									}else{
										$ruby='';
									}
									?>
							<dt>
								<?=ALL::h($data['author'.$i]).$ruby;?>
							</dt><?php
									if(!empty($data['author'.$i.'_biography'])){
									?>	
							<dd>
								<?=ALL::h($data['author'.$i.'_biography']);;?>
							</dd><?php
									}
								}
							}
							?>
						</dl>
					</td>
				</tr>
				<tr>
					<th>
						出版社
					</th>
					<td>
						<?=ALL::h($data['publisher']);?>
					</td>
				</tr>
				<tr>
					<th>
						出版日
					</th>
					<td>
						<?=ALL::h($data['publish_date']);?>
					</td>
				</tr>
				<tr>
					<th>
						サイズ
					</th>
					<td>
						<?=ALL::h($data['size']);?>
					</td>
				</tr>
				<tr>
					<th>
						ページ数
					</th>
					<td>
						<?=ALL::h($data['page']);?>
					</td>
				</tr>
				<tr>
					<th>
						価格
					</th>
					<td>
						<?=number_format(ALL::h($data['price']));?>
					</td>
				</tr>
				<tr>
					<th>
						請求記号
					</th>
					<td>
						<?=ALL::h(implode('-',array_filter(array($data['seikyu1'],$data['seikyu2'],$data['seikyu3']))));?>
					</td>
				</tr>
				<tr>
					<th>
						NDC
					</th>
					<td>
						<?=GET::ndc($data['ndc']);?>
					</td>
				</tr>
				<tr>
					<th>
						保管場所
					</th>
					<td>
						<?=GET::location($data['location']);?>
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
						状態
					</th>
					<td><?php
						if(!empty($data['drop_date'])){
							echo '廃棄済';
						}elseif($data['not_reserve']==1){
							echo '貸出禁止';
						}else{
							$row_borrow=SQL::fetch("SELECT COUNT(borrow_id) as cnt FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($_GET['i']));
							if($row_borrow['cnt']==0){
								echo '貸出可';
							}else{
								echo '貸出中';
							}
						}
						?>
					</td>
				</tr>
				<tr>
					<th>
						予約
					</th>
					<td><?php
						$row=SQL::fetch("SELECT COUNT(reserve_id) as cnt FROM {$school_id}_counter_reserve WHERE book_id=? AND cond=1",array($_GET['i']));
						echo $row['cnt'].'名';
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div><?php
		if(isset($row_borrow) && $row2=SQL::fetch("SELECT reserve FROM {$school_id}_manage_setting_other WHERE cond=0")){
			if(($row_borrow['cnt']==0 && $row2['reserve']==0) || $row_borrow['cnt']!=0){
			?>
	<form method="post">
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			予約
		</button>
	</form><?php
			}
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