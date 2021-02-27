<?php
include_once('../../../data/link.php');
$title="雑誌一覧";
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
			<a href="<?=$url;?>manage/magazine/">
				雑誌管理
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
		<thead>
			<tr>
				<th>
					図書番号
				</th>
				<th>
					雑誌名
				</th>
				<th>
					受入日
				</th>
				<th>
					保管場所
				</th>
				<th>
					財源
				</th>
				<th>
					購入書店
				</th>
				<th>
					編集
				</th>
			</tr>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_magazine_info WHERE cond=0 ORDER BY book_id");
			$stmt->execute();
			foreach($stmt as $row){
				?>
			<tr>
				<td>
					<?=sprintf('%09d',$row['book_id']);?>
				</td>
				<td>
					<?=ALL::h($row['name']);?>
				</td>
				<td>
					<?=$row['entry_date'];?>
				</td>
				<td>
					<?=GET::location($row['location']);?>
				</td>
				<td>
					<?=GET::financial($row['financial']);?>
				</td>
				<td>
					<?=GET::store($row['store']);?>
				</td>
				<td>
					<a href="<?=$url;?>manage/magazine/entry/?i=<?=SQL::encrypt_k($row['book_id']);?>">
						編集
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