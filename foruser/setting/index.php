<?php
include_once('../../data/link.php');
$title="利用者情報";
include_once('../../data/header.php');
$data=SQL::fetch("SELECT user_right FROM {$school_id}_account_class WHERE cond=0 AND user_id=?",array($user_id));
$data2=SQL::fetch("SELECT sex FROM account_user WHERE cond=0 AND user_id=?",array($user_id));
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
	<div class="scroll">
		<table>
			<tbody>
				<tr>
					<th>
						利用者番号
					</th>
					<td>
						<?=sprintf('%08d',$user_id);?>
					</td>
				</tr>
				<tr>
					<th>
						所属
					</th>
					<td>
						<?=USER::belong();?>
					</td>
				</tr>
				<tr>
					<th>
						性別
					</th>
					<td>
						<?=$user_sex[$data2['sex']];?>
					</td>
				</tr>
				<tr>
					<th>
						名前
					</th>
					<td>
						<?=USER::name();?>
					</td>
				</tr>
				<tr>
					<th>
						権限
					</th>
					<td>
						<?=$user_right[$data['user_right']];?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div>
		<a href="<?=$request_url;?>edit/" class="link_button">
			登録内容変更
		</a>
	</div>
</section><?php
include_once('../../data/footer.php');
?>