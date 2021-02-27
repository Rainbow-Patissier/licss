<?php
include_once('../../../data/link.php');
if(isset($_GET['i']) && $_GET['i']!=0 && $data=SQL::fetch("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 AND book_id=?",array($_GET['i']))){
	$id=(int)$_GET['i'];
	$title=ALL::h($data['book_name']);
}else{
	$id=0;
	$title="エラーが発生";
}
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>counter/">
				窓口業務
			</a>
		</li>
		<li>
			<a href="<?=$url;?>counter//">
				蔵書検索
			</a>
		</li>
		<li>
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content">
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
						$row=SQL::fetch("SELECT COUNT(borrow_id) as cnt FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($_GET['i']));
						if($row['cnt']==0){
							echo '貸出可';
						}else{
							echo '貸出中';
						}
							if($row2=SQL::fetch("SELECT reserve FROM {$school_id}_manage_setting_other WHERE cond=0")){
								if(($row['cnt']==0 && $row2['reserve']==0) || $row['cnt']!=0){
									$secret_text='counter_search';
									if(!isset($_POST['btn'])){
							?>
						<form method="post">
							<button type="button" id="counter_search_view_before_reserve" onClick="reserve_click();">
								予約する
							</button>
							<div id="counter_search_view_reserve">
								利用者番号<input type="number" name="user_id" placeholder="利用者番号" title="利用者番号を入力してください" required>
								<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
								<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
									登録
								</button>
							</div>
						</form>
						<script>
							function reserve_click(){
								document.getElementById('counter_search_view_before_reserve').style.display='none';
								document.getElementById('counter_search_view_reserve').style.display='block';
							}
						</script><?php
									}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
										if(isset($_POST['user_id'])){
											//予約処理
											$user=(int)$_POST['user_id'];
											$book=(int)$_GET['i'];
											$row=SQL::fetch("SELECT MAX(reserve_id) as max FROM {$school_id}_counter_reserve");
											$reserve_id=$row['max']+1;
											if(SQL::execute("INSERT INTO {$school_id}_counter_reserve VALUES (?,?,?,?,?,?,?)",array($reserve_id,$user_id,$book,$user,$Ymdt,1,$Ymdt))){
									?>
						<span>
							予約しました
						</span><?php
											}else{
									?>
						<span>
							予約できませんでした。
						</span><?php
											}
										}
									}else{
							?>
		<div>
			<h2>
				エラーが発生
			</h2>
			<p>
				必要事項すべての入力が確認できませんでした。
			</p>
			<p>
				恐れ入りますが，もう一度始めからやり直してください。
			</p>
		</div><?php
								}
							}else{
									echo '(予約が許可されていません)';
								}
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
</section><?php
include_once('../../../data/footer.php');
?>