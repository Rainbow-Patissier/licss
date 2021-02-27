<?php
include_once('../../../data/link.php');
$title="図書館だより";
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
	<button type="button" onClick="window.location.href='<?=$request_url;?>entry/'">
		新規登録
	</button>
	<table>
		<caption>
			図書館だより一覧
		</caption>
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
</section><?php
include_once('../../../data/footer.php');
?>