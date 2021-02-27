<?php
include_once('../../../data/link.php');
$title="ラベル印刷";
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
			<a href="<?=$url;?>manage/user/">
				利用者管理
			</a>
		</li>
		<li>
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content"><?php
	$secret_text='manage_user_label';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<div class="no_print">
			利用者番号が<input type="number" name="from" min="0" required>から<input type="number" name="to" min="0" required>まで<br>
			（途中から印刷する場合）空白ラベル数<input type="number" name="margin" min="0" value="0" required>
		</div>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			印刷
		</button>
	</form><?php
		#ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['margin'])){
			$id=(int)$_POST['from'];
			$from=(int)$_POST['from'];
			$to=(int)$_POST['to'];
			$margin=(int)$_POST['margin'];
			?>
	<div id="manage_user_label_printout"><?php
			$stmt=$pdo->prepare("SELECT user_id FROM {$school_id}_account_class WHERE cond=0 AND user_id BETWEEN ? AND ? ORDER BY user_id");
			$stmt->execute(array($from,$to));
			$rownum=1;
			$column_num=1;
			foreach($stmt as $row){
				if($rownum===45){
					?>
			<div class="manage_user_label_margin"></div><?php
					$rownum=1;
				}
				if($column_num===1){
					
				?>
		<div class="manage_user_label_row"><?php
				}
					?>
			<div>
				<div class="manage_user_label_school_name school_color"><?=$school_name;?></div>
				<div class="nw7_barcode">a<?=$row['user_id'];?>a</div>
				<div class="manage_user_label_book_id"><?=$row['user_id'];?></div>
				<div class="manage_user_label_book_name"><?=USER::name($row['user_id']);?></div>
			</div><?php
				if($column_num===4){
				?>
		</div><?php
					$column_num=0;
				}
				++$rownum;
				++$column_num;
			}
			/*for($rownum=1;$id<=$to;++$rownum){
				if($rownum===12){
					?>
			<div class="manage_user_label_margin"></div><?php
					$rownum=1;
				}
				?>
		<div class="manage_user_label_row"><?php
				for($i=0;$i<4;++$i){
					if($margin>0){
						?>
			<div></div><?php
						--$margin;
					}elseif($id<=$to){
					?>
			<div>
				<div class="manage_user_label_school_name school_color"><?=$school_name;?></div>
				<div class="nw7_barcode">a<?=$id;?>a</div>
				<div class="manage_user_label_book_id"><?=$id;?></div>
				<div class="manage_user_label_book_name">ユーザー<?=$i;?></div>
			</div><?php
						++$id;
					}
				}
				?>
		</div><?php
			}*/
			?>
	</div>
	<script>
		document.getElementById('manage_user_label_printout').style.margin='8.8mm 8.4mm';
		window.print();
	</script><?php
		}else{
			?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			入力の確認ができなかったため，処理が完了できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
		}
	}else{
		?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			予期せぬエラーが発生したため，処理が完了できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
	}
	?>
</section><?php
include_once('../../../data/footer.php');
?>