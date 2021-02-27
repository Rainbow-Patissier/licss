<?php
include_once('../../../data/link.php');
$title="統計";
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>foruser/">
				利用者機能
			</a>
		</li>
		<li>
			<a href="<?=$url;?>foruser/books/">
				図書
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
	$row=SQL::fetch("SELECT publish FROM {$school_id}_manage_setting_other WHERE cond=0");
	if($row['publish']!=0){
		?>
	<div class="no_print">
		表示したい統計を選択してください<br>
		<select id="manage_other_statistics_kind" required>
			<option label="選択してください"></option>
			<option value="<?=SQL::encrypt_ks(5);?>">図書貸出数</option>
			<option value="<?=SQL::encrypt_ks(6);?>">生徒貸出数</option>
			<option value="<?=SQL::encrypt_ks(7);?>">学年貸出数</option>
			<option value="<?=SQL::encrypt_ks(8);?>">クラス貸出数</option>
		</select>
		<button type="button" onClick="create_statistics();">
			作成
		</button>
		<button type="button" id="manage_other_statistics_button" onClick="window.print();">
			印刷
		</button>
	</div>
	<table>
		<thead id="manage_other_statistics_table_head"></thead>
		<tbody id="manage_other_statistics_table_body"></tbody>
	</table>
	<div id="announce_back"></div>
	<div id="announce_wrapper">
		<div id="announce">
			 <div class="balls-guruguru">
				<span class="ball ball-1"></span>
				<span class="ball ball-2"></span>
				<span class="ball ball-3"></span>
				<span class="ball ball-4"></span>
				<span class="ball ball-5"></span>
				<span class="ball ball-6"></span>
				<span class="ball ball-7"></span>
				<span class="ball ball-8"></span>
			</div>
		</div>
	</div>
	<script>
		function create_statistics(){
			var select=document.getElementById('manage_other_statistics_kind');
			document.getElementById('announce_back').style.display='block';
			document.getElementById('announce_wrapper').style.display='block';
			manage_other_statistics_create(select.options[select.selectedIndex].value);
		}
	</script>
	<style>
		@page{
			size: A4;
		}
		@media print{
			html{
				width: 210mm;
				height: 297mm;
				margin: 0;
				padding: 0;
			}
			body{
				margin: 10mm;
			}
		}
	</style><?php
	}else{
		?>
	閲覧権限がありません<?php
	}
	?>
</section><?php
include_once('../../../data/footer.php');
?>