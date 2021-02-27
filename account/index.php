<?php
include_once('../data/link.php');
$title="アカウント";
$data=SQL::fetch("SELECT account FROM account_info WHERE cond=0 AND user_id=?",array($user_id));
$data2=SQL::fetch("SELECT email,email_iv FROM account_user WHERE cond=0 AND user_id=?",array($user_id));
include_once('../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content">
	<h3>
		アカウント情報
	</h3>
	<table>
		<tbody>
			<tr>
				<th>
					アカウント名
				</th>
				<td>
					<?=ALL::h($data['account']);?>
				</td>
			</tr>
			<tr>
				<th>
					登録メールアドレス
				</th>
				<td>
					<?=ALL::h(SQL::decrypt($data2['email'],$data2['email_iv']));?>
				</td>
			</tr>
		</tbody>
	</table>
	<a href="<?=$request_url;?>edit/" class="link_button">
		アカウント情報変更
	</a>
	<a href="<?=$request_url;?>password/" class="link_button">
		パスワード変更
	</a>
	<h3>
		図書館だより
	</h3>
	<table>
		<thead>
			<th>
				登録日時
			</th>
			<th>
				登録者
			</th>
			<th>
				タイトル
			</th>
			<th>
				閲覧
			</th>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_other_letter ORDER BY edit_date DESC");
			$stmt->execute();
			foreach($stmt as $row){
				$title=ALL::h(SQL::decrypt_school($row['title'],$row['title_iv']));
				?>
			<tr>
				<td>
					<?=ALL::h($row['edit_date']);?>
				</td>
				<td>
					<?=USER::name($row['user_id']);?>
				</td>
				<td>
					<?=$title;?>
				</td>
				<td>
					<a href="<?=$url;?>data/show/?f=<?=SQL::encrypt_k(SQL::decrypt_school($row['file'],$row['file_iv']));?>&n=<?=SQL::encrypt_k($title);?>&d=<?=SQL::encrypt_k('letter');?>" target="_blank">
						閲覧
					</a>
				</td>
			</tr><?php
			}
			?>
		</tbody>
	</table>
	<h3>
		運営からのお知らせ
	</h3>
	<table>
		<thead>
			<th>
				登録日時
			</th>
			<th>
				タイトル
			</th>
			<th>
				閲覧
			</th>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT info_id,title,title_iv, edit_date FROM system_info ORDER BY edit_date DESC");
			$stmt->execute();
			foreach($stmt as $row){
				$title=ALL::h(SQL::decrypt($row['title'],$row['title_iv']));
				?>
			<tr>
				<td>
					<?=ALL::h($row['edit_date']);?>
				</td>
				<td>
					<?=$title;?>
				</td>
				<td>
					<a href="<?=$url;?>info/view/post/?i=<?=SQL::encrypt_k($row['info_id']);?>" target="_blank">
						閲覧
					</a>
				</td>
			</tr><?php
			}
			?>
		</tbody>
	</table>
</section><?php
include_once('../data/footer.php');
?>