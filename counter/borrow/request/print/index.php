<?php
include_once('../../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0){
		$title="返却請求";
	}else{
		$title="エラーが発生";
	}
}else{
	$id=0;
	$title="エラーが発生";
}
include_once('../../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>counter/">
				窓口業務
			</a>
		</li>
		<li>
			<a href="<?=$url;?>counter//">
				貸出・返却・予約
			</a>
		</li>
		<li>
			<a href="<?=$url;?>counter///">
				返却請求一覧
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
	<div class="no_print">
		<button type="button" onClick="window.print();">
			印刷
		</button>
		<button type="button" onClick="">
			メールの送信
		</button>
	</div>
	<div>
		<div>
			<?=USER::belong($id);?>&nbsp;&nbsp;<?=USER::name($id);?>(<?=sprintf('%08d',$id);?>) 様
		</div>
		<div>
			下記の図書が返却期限を過ぎたにも関わらず返却されておりません。早急に返却をお願い致します。<br>
			なお，授業や課題等で利用する場合は延長処理を行ってください。
		</div>
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
						貸出日時
					</th>
					<th>
						返却予定日
					</th>
				</tr>
			</thead>
			<tbody><?php
				$stmt=$pdo->prepare("SELECT book_id,borrow_date,reserve_scheduled FROM {$school_id}_counter_borrow WHERE reserve_scheduled<? AND reserve_date IS NULL AND user_id=?");
				$stmt->execute(array($Ymd,$id));
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
						<?=$row['borrow_date'];?>
					</td>
					<td>
						<?=$row['reserve_scheduled'];?>
					</td>
				</tr><?php
				}
				?>
			</tbody>
		</table>
	</div>
	<style>
		@media print{
			#main_content{
				margin: 10mm;
			}
		}
	</style>
</section><?php
include_once('../../../../data/footer.php');
?>