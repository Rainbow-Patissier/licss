<?php
include_once('../../../data/link.php');
$title="貸出中図書一覧";
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
				<th>
					状態
				</th>
			</tr>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT book_id,user_id,borrow_date,reserve_scheduled,reserve_date FROM {$school_id}_counter_borrow ORDER BY borrow_date DESC");
			$stmt->execute();
			foreach($stmt as $row){
				if($row['reserve_date']==NULL){
					$condition='貸出中';
				}else{
					$condition='返却済';
				}
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
					<?=$condition;?>
				</td>
			</tr><?php
			}
			?>
		</tbody>
	</table>
</section><?php
include_once('../../../data/footer.php');
?>