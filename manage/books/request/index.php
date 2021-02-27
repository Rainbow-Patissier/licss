<?php
include_once('../../../data/link.php');
$title="購入依頼";
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
			<a href="<?=$url;?>manage/books/">
				蔵書管理
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
		<caption>
			購入依頼一覧
		</caption>
		<thead>
			<tr>
				<th>
					依頼者
				</th>
				<th>
					図書名
				</th>
				<th>
					ISBN
				</th>
				<th>
					依頼日
				</th>
				<th>
					状態
				</th>
			</tr>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT request_id,user_id,book_name,isbn,DATE_FORMAT(edit_date,'%Y/%m/%e %H:%i:%s') as date,request_condition FROM {$school_id}_manage_books_request WHERE cond=0 AND user_id=? ORDER BY entry_date DESC");
			$stmt->execute(array($user_id));
			$request_condition=array('未確認','承諾','却下');
			foreach($stmt as $row){
				?>
			<tr>
				<td>
					<?=USER::name($row['user_id']);?>(<?=USER::belong($row['user_id']);?>)
				</td>
				<td>
					<?=ALL::h($row['book_name']);?>
				</td>
				<td>
					<?=ALL::h($row['isbn']);?>
				</td>
				<td>
					<?=ALL::h($row['date']);?>
				</td>
				<td>
					<?=$request_condition[$row['request_condition']];?>
					<a href="<?=$request_url;?>detail/?i=<?=SQL::encrypt_k($row['request_id']);?>">
						詳細
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