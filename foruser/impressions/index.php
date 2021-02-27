<?php
include_once('../../data/link.php');
$title="感想文一覧";
include_once('../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
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
<section id="main_content">
	<table>
		<thead>
			<tr>
				<th>
					図書名
				</th>
				<th>
					文字数
				</th>
				<th>
					投稿日時
				</th>
				<th>
					状態
				</th>
			</tr>
		</thead>
		<tbody><?php
			$public=array('下書き','非公開','学内公開','公開');
			$stmt=$pdo->prepare("SELECT impressions_id,book_id,words,public FROM {$school_id}_foruser_impressions WHERE user_id=? AND cond=0 ORDER BY impressions_id DESC");
			$stmt->execute(array($user_id));
			$stmt2=$pdo->prepare("SELECT DATE_FORMAT(edit_date,'%Y.%m.%d') as edit_date FROM {$school_id}_foruser_impressions WHERE impressions_id=? ORDER BY edit_date");
			foreach($stmt as $row){
				$stmt2->execute(array($row['impressions_id']));
				$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
				?>
			<tr>
				<td>
					<?=GET::book_name($row['book_id']);?>(<?=sprintf('%09d',$row['book_id']);?>)
				</td>
				<td>
					<?=number_format($row['words']);?>
				</td>
				<td>
					<?=$row2['edit_date'];?>
				</td>
				<td>
					<?=$public[$row['public']];?>
					<a href="<?=$request_url;?>detail/?i=<?=SQL::encrypt_k($row['impressions_id']);?>">
						詳細
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