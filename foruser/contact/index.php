<?php
include_once('../../data/link.php');
$title="個別連絡";
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
					登録日時
				</th>
				<th>
					更新日時
				</th>
				<th>
					連絡相手
				</th>
				<th>
					タイトル
				</th>
				<th>
					状態
				</th>
				<th>
					返信
				</th>
				<th>
					詳細
				</th>
			</tr>
		</thead>
		<tbody><?php
			$stmt=$pdo->prepare("SELECT chat_id,edit_date,teacher_user_id,title,title_iv,reply FROM {$school_id}_manage_user_info WHERE student_user_id=? GROUP BY chat_id ORDER BY edit_date");
			$stmt->execute(array($user_id));
			$stmt2=$pdo->prepare("SELECT edit_date,teacher_user_id,read_condition FROM {$school_id}_manage_user_info WHERE chat_id=? ORDER BY edit_date DESC");
			foreach($stmt as $row){
				$stmt2->execute(array($row['chat_id']));
				if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
					if((int)$row2['read_condition']===0){
						if((int)$row2['teacher_user_id']!==0){
							$read='送信済(未読)';
						}else{
							$read='未読<span class="red">New!</span>';
						}
					}else{
						if((int)$row2['teacher_user_id']!=0){
							$read='送信済(既読)';
						}else{
							$read='既読';
						}
					}
					if((int)$row['reply']===0){
						$reply='許可';
					}else{
						$reply='禁止';
					}
				?>
			<tr>
				<td>
					<?=$row['edit_date'];?>
				</td>
				<td>
					<?=$row2['edit_date'];?>
				</td>
				<td>
					<?=USER::name($row['teacher_user_id']);?>
				</td>
				<td>
					<?=ALL::h(SQL::decrypt_school($row['title'],$row['title_iv']));?>
				</td>
				<td>
					<?=$read;?>
				</td>
				<td>
					<?=$reply;?>
				</td>
				<td>
					<a href="<?=$request_url;?>view/?i=<?=SQL::encrypt_k($row['chat_id']);?>">
						詳細
					</a>
				</td>
			</tr><?php
				}
			}
			?>
		</tbody>
	</table>
</section><?php
include_once('../../data/footer.php');
?>