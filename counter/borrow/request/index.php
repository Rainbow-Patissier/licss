<?php
include_once('../../../data/link.php');
$title="返却請求一覧";
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
			<a href="<?=$url;?>counter/borrow/">
				貸出・返却・予約
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
			未返却者一覧
		</caption>
		<thead>
			<tr>
				<th>
					図書番号
				</th>
				<th>
					図書名
				</th>
				<th>
					利用者
				</th>
				<th>
					貸出日時
				</th>
				<th>
					返却予定日
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT book_id,user_id,borrow_date,reserve_scheduled FROM {$school_id}_counter_borrow WHERE reserve_scheduled<? AND reserve_date IS NULL ORDER BY user_id");
			$stmt->execute(array($Ymd));
			foreach($stmt as $row){
				?>
			<tr>
				<td>
					<?=sprintf('%09d',$row['book_id']);?>
				</td>
				<td>
					<?=GET::book_name($row['book_id']);?>
				</td>
				<td>
					[<?=USER::belong($row['user_id']);?>]<?=USER::name($row['user_id']);?>(<?=sprintf('%08d',$row['user_id']);?>)
				</td>
				<td>
					<?=$row['borrow_date'];?>
				</td>
				<td>
					<?=$row['reserve_scheduled'];?>
				</td>
				<td>
					<button type="button" onClick="window.open('<?=$request_url;?>print/?i=<?=SQL::encrypt_k($row['user_id']);?>','request_print','width=600px,height=800px,left=0,top=0')">
						返却請求
					</button>
				</td>
			</tr><?php
			}
			?>
		</tbody>
	</table>
</section><?php
include_once('../../../data/footer.php');
?>