<?php
include_once('../../../data/link.php');
$title="図書館からのお知らせ";
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
			<a href="<?=$url;?>manage/other/">
				その他
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
	<button type="button" onClick="window.location.href='<?=$request_url;?>entry/';">
		新規登録
	</button>
	<table>
		<caption>
			お知らせ一覧
		</caption>
		<thead>
			<tr>
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
			</tr>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT info_id,user_id,edit_date,title,title_iv FROM {$school_id}_manage_other_info ORDER BY edit_date DESC");
			$stmt->execute();
			foreach($stmt as $row){
				?>
			<tr>
				<td>
					<?=ALL::h($row['edit_date']);?>
				</td>
				<td>
					<?=USER::name($row['user_id']);?>
				</td>
				<td>
					<?=ALL::h(SQL::decrypt_school($row['title'],$row['title_iv']));?>
				</td>
				<td>
					<a href="<?=$request_url;?>view/?i=<?=SQL::encrypt_k($row['info_id']);?>">
						閲覧
					</a>
				</td>
			</tr><?php
			}
			?>
		</tbody>
	</table>
</section><?php
include_once('../../../data/footer.php');
?>