<?php
include_once('../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!=0){
		$name=USER::name($id);
		$title=$name."-プロフィール";
		$data=SQL::fetch("SELECT grade,class,number FROM {$school_id}_account_class WHERE cond=0 AND user_id=?",$id);
		$data2=SQL::fetch("SELECT birthday FROM account_user WHERE cond=0 AND user_id=?",$id);
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
					所属
				</th>
				<td>
					<?=ALL::h($data['grade']);?>年<?=ALL::h($data['class']);?>組<?=ALL::h($data['number']);?>番
				</td>
			</tr>
			<tr>
				<th>
					利用者番号
				</th>
				<td>
					<?=$id;?>
				</td>
			</tr>
			<tr>
				<th>
					名前
				</th>
				<td>
					<?=$name;?>
				</td>
			</tr>
			<tr>
				<th>
					生年月日
				</th>
				<td>
					<?=$data2['birthday'];?>
				</td>
			</tr>
			<tr>
				<th>
					貸出中図書
				</th>
				<td>
					<ul class="no_maru"><?php
						$stmt=$pdo->prepare("SELECT book_id,borrow_date FROM {$school_id}_counter_borrow WHERE user_id=? AND reserve_date=NULL ORDER BY borrow_id");
						$stmt->execute(array($id));
						foreach($stmt as $row){
							?>
						<li>
							<?=GET::book_name($row['book_id']);?>(<?=$row['borrow_date'];?>～)
						</li><?php
						}
						?>
					</ul>
				</td>
			</tr>
			<tr>
				<th>
					貸出履歴
				</th>
				<td>
					<ul class="no_maru"><?php
						$stmt=$pdo->prepare("SELECT book_id,borrow_date,reserve_date FROM {$school_id}_counter_borrow WHERE user_id=? AND reserve_date!=NULL ORDER BY borrow_id");
						$stmt->execute(array($id));
						foreach($stmt as $row){
							?>
						<li>
							<?=GET::book_name($row['book_id']);?>(<?=$row['borrow_date'];?>～<?=$row['reserve_date'];?>)
						</li><?php
						}
						?>
					</ul>
				</td>
			</tr>
			<tr>
				<th>
					感想文履歴
				</th>
				<td>
					<ul class="no_maru"><?php
						$public=array('下書き','非公開','学内公開','公開');
						$stmt=$pdo->prepare("SELECT book_id,public FROM {$school_id}_foruser_impressions WHERE user_id=? AND cond=0 ORDER BY impressions_id");
						$stmt->execute(array($id));
						foreach($stmt as $row){
							?>
						<li>
							<?=GET::book_name($row['book_id']);?>(<?=$public[$row['public']];?>)
						</li><?php
						}
						?>
					</ul>
				</td>
			</tr>
			<tr>
				<th>
					個別連絡履歴
				</th>
				<td>
					<ul class="no_maru"><?php
						$stmt=$pdo->prepare("SELECT title,title_iv,chat_id FROM {$school_id}_manage_user_info WHERE edit_user_id=? AND student_user_id=? GROUP BY chat_id ORDER BY chat_id");
						$stmt->execute(array($user_id,$id));
						foreach($stmt as $row){
							?>
						<li>
							<a href="<?=$url;?>manage/user/info/view/?i=<?=SQL::encrypt_k($row['chat_id']);?>">
								<?=ALL::h(SQL::decrypt_school($row['title'],$row['title_iv']));?>
							</a>
						</li><?php
						}
						?>
					</ul>
				</td>
			</tr>
			<tr>
				<th>
					学校歴
				</th>
				<td>
					<ul class="no_maru"><?php
						$stmt=$pdo->prepare("SELECT school_id,DATE_FORMAT(edit_date,'%Y/%m/%d') as edit_date FROM account_user WHERE user_id=? GROUP BY school_id ORDER BY edit_date");
						$stmt->execute(array($id));
						$stmt2=$pdo->prepare("SELECT school,school_name FROM manage_school WHERE school_id=? ORDER BY edit_date DESC");
						foreach($stmt as $row){
							$stmt2->execute(array($row['school_id']));
							$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
							?>
						<li>
							<?=ALL::h($row2['school_name']).$school_kind[$row2['school']];?>(<?=$row['edit_date'];?>～)
						</li><?php
						}
						?>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>
	<a href="<?=$url;?>manage/user/edit/?i=<?=SQL::encrypt_k($id);?>">
		登録内容変更
	</a>
	<a href="<?=$url;?>manage/user/info/entry/?i=<?=SQL::encrypt_k($id);?>">
		個別連絡
	</a>
</section><?php
include_once('../../../data/footer.php');
?>