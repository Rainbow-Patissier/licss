<?php
include_once('../../data/link.php');
$title="運営からのお知らせ";
include_once('../../data/header.php');
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
	<table>
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
		$stmt=$pdo->prepare("SELECT info_id,user_id,title,title_iv,edit_date FROM system_info ORDER BY edit_date DESC");
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
					<?=ALL::h(SQL::decrypt($row['title'],$row['title_iv']));?>
				</td>
				<td>
					<a href="<?=$url;?>info/view/post/?i=<?=SQL::encrypt_k($row['info_id']);?>">
						閲覧
					</a>
				</td>
			</tr><?php
		}
		?>
		</tbody>
	</table>
</section><?php
include_once('../../data/footer.php');
?>