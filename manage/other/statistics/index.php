<?php
include_once('../../../data/link.php');
$title="統計";
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
	<div class="no_print">
		作成したい統計を選択してください<br>
		<select id="manage_other_statistics_kind" required>
			<option label="選択してください"></option>
			<optgroup label="集計">
				<option value="<?=SQL::encrypt_ks(1);?>">登録年度別</option>
				<option value="<?=SQL::encrypt_ks(2);?>">NDC分類別</option>
				<option value="<?=SQL::encrypt_ks(3);?>">保管場所別</option>
				<option value="<?=SQL::encrypt_ks(4);?>">財源別</option>
			</optgroup>
			<optgroup label="ランキング">
				<option value="<?=SQL::encrypt_ks(5);?>">図書貸出数</option>
				<option value="<?=SQL::encrypt_ks(6);?>">生徒貸出数</option>
				<option value="<?=SQL::encrypt_ks(7);?>">学年貸出数</option>
				<option value="<?=SQL::encrypt_ks(8);?>">クラス貸出数</option>
			</optgroup>
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
	</style>
</section><?php
include_once('../../../data/footer.php');
?>