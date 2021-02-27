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
<section id="main_content"><?php
	$secret_text='manage_books_label';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<div id="manage_books_label_menu">
			<p>
				印刷するラベルを選択してください
			</p>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('<?=SQL::encrypt_ks('book_id');?>');">
						図書番号
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('<?=SQL::encrypt_ks('back_label');?>');">
						背ラベル
					</a>
				</li>
			</ul>
		</div>
		<div id="manage_books_label_setting" class="no_print">
			図書番号が<input type="text" name="from" id="manage_books_label_from_book" min="0">から<input type="text" name="to" id="manage_books_label_to_book" min="0">まで<br>
			（途中から印刷する場合）空白ラベル数<input type="number" name="margin" id="manage_books_label_margin" min="0" value="0"><br>
			<button type="submit" name="btn" id="manage_books_label_type" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
				印刷
			</button>
		</div>
		<div id="manage_books_label_printout">
			<!--<div class="manage_books_label_row">
				<div>
					<div class="manage_books_label_school_name school_color"><?=$school_name;?></div>
					<div class="nw7_barcode">a00000001a</div>
					<div class="manage_books_label_book_id">00000001</div>
					<div class="manage_books_label_book_name">かいけつゾロリ</div>
				</div>
			</div>
			<div class="manage_books_back_label_row">
				<div class="manage_books_back_label_wrapper">
					<div>
						<div class="manage_books_back_label_number">913</div>
						<div class="manage_books_back_label_hiragana">あ</div>
						<div class="manage_books_back_label_series">1</div>
					</div>
				</div>
			</div>-->
		</div>
		<script>
			function select_menu(value){
				document.getElementById('manage_books_label_type').value=value;
				document.getElementById('manage_books_label_menu').style.height=0;
				document.getElementById('manage_books_label_setting').style.height='140px';
			}
		</script>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
	</form><?php
		#ALL::remove_alert();
	}elseif(isset($_POST['btn']) && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['margin']) && (int)$_POST['from']<=(int)$_POST['to']){
			$from=(int)$_POST['from'];
			$to=(int)$_POST['to'];
			$margin=(int)$_POST['margin'];
			$id=$from;
			if(SQL::decrypt_ks($_POST['btn'])==='book_id'){
				//図書番号
				?>
		<button class="no_print" type="button" onClick="window.print();">
			印刷
		</button>
		<div id="manage_books_label_printout"><?php
				$stmt=$pdo->prepare("SELECT book_name FROM {$school_id}_manage_books_info WHERE book_id=? AND cond=0");
				for($i=1;$id<=$to;++$i){
					if($i===12){
						?>
			<div class="manage_books_label_row_margin"></div><?php
						$i=1;
					}
					?>
			<div class="manage_books_label_row"><?php
					for($n=0;$n<4;++$n){
						if($margin>0){
							?>
				<div></div><?php
							--$margin;
						}elseif($id<=$to){
							$stmt->execute(array($id));
							if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
								$name=ALL::h($row['book_name']);
							}else{
								$name='';
							}
						?>
				<div>
					<div class="manage_books_label_school_name school_color"><?=$school_name;?></div>
					<div class="nw7_barcode">a<?=sprintf('%08d',$id);?>a</div>
					<div class="manage_books_label_book_id"><?=sprintf('%08d',$id);?></div>
					<div class="manage_books_label_book_name"><?=$name?></div>
				</div><?php
							++$id;
						}
					}
					?>
			</div><?php
				}
				?>
		</div>
		<style>
			@page{
				size: a4;
			}
			@media print{
				html{
					width: 210mm;
					height: 297mm;
				}
				#manage_books_label_printout{
					margin: 8.8mm 8.4mm;
				}
			}
		</style>
		<script>
			window.print();
		</script><?php
			}elseif(SQL::decrypt_ks($_POST['btn'])==='back_label'){
				//背ラベル
				?>
		<button class="no_print" type="button" onClick="window.print();">
			印刷
		</button>
		<div id="manage_books_label_printout"><?php
				$stmt=$pdo->prepare("SELECT seikyu1,seikyu2,seikyu3,color,ndc FROM {$school_id}_manage_books_info WHERE book_id=? AND cond=0");
				for($i=1;$id<=$to;++$i){
					if($i===14){
						?>
			<div class="manage_books_back_label_row_margin"></div><?php
						$i=1;
					}
					?>
			<div class="manage_books_back_label_row"><?php
					for($n=0;$n<5;++$n){
						if($margin>0){
							?>
				<div></div><?php
							--$margin;
						}elseif($id<=$to){
							$stmt->execute(array($id));
							if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
								$seikyu=array($row['seikyu1'],$row['seikyu2'],$row['seikyu3']);
								$color_code=$back_label_color_code[$row['color']];
							}else{
								$seikyu=array('','','');
								$color_code='#000000';
							}
						?>
				<div class="manage_books_back_label_wrapper">
					<div style="border: 5px <?=$color_code;?> solid;">
						<div class="manage_books_back_label_number"><?=$seikyu[0];?></div>
						<div class="manage_books_back_label_hiragana"><?=$seikyu[1];?></div>
						<div class="manage_books_back_label_series"><?=$seikyu[2];?></div>
					</div>
				</div><?php
							++$id;
						}
					}
					?>
			</div><?php
				}
				?>
		</div>
		<style>
			@page{
				size: a4;
			}
			@media print{
				html{
					width: 210mm;
					height: 297mm;
				}
				#manage_books_label_printout{
					margin: 10.92mm 4.75mm;
				}
			}
		</style>
		<script>
			window.print();
		</script><?php
			}else{
				?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			無効なボタンがクリックされました。
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
			有効な入力内容が確認できなかったため，処理が完了できませんでした。
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