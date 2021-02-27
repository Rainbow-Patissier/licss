<?php
include_once('../../../data/link.php');
$title="台帳";
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
		作成したい台帳を選択してください<br>
		<select id="manage_other_list_kind" title="台帳を選択してください" required>
			<option label="選択してください"></option>
			<option value="<?=SQL::encrypt_ks(1);?>">図書台帳</option>
			<option value="<?=SQL::encrypt_ks(2);?>">NDC台帳</option>
			<option value="<?=SQL::encrypt_ks(3);?>">保管場所台帳</option>
			<option value="<?=SQL::encrypt_ks(4);?>">財源台帳</option>
			<option value="<?=SQL::encrypt_ks(5);?>">廃棄図書台帳</option>
			<option value="<?=SQL::encrypt_ks(6);?>">新着図書台帳</option>
		</select>
		<button type="button" onClick="button_click();">
			作成
		</button>
		<button id="manage_other_print_button" type="button" onClick="window.print();">
			印刷
		</button>
	</div>
	<table id="manage_other_list_table">
		<thead>
			<tr>
				<th>
					図書番号
				</th>
				<th>
					登録日時
				</th>
				<th>
					図書名
				</th>
				<th>
					著作者
				</th>
				<th>
					NDC
				</th>
				<th>
					保管場所
				</th>
				<th>
					財源
				</th>
				<th>
					書店
				</th>
				<th>
					価格
				</th>
				<th>
					出版社
				</th>
				<th>
					出版年
				</th>
				<th>
					サイズ
				</th>
				<th>
					ページ数
				</th>
				<th>
					請求記号
				</th>
				<th>
					廃棄日
				</th>
			</tr>
		</thead>
		<tbody id="manage_other_list_table_body"></tbody>
	</table>
	<style>
		#manage_other_list_table{
			font-size: 8pt;
		}
		@page{
			size: A4 landscape;
		}
		@media print{
			html{
				width: 297mm;
				height: 210mm;
				margin: 0;
				padding: 0;
			}
			body{
				margin: 0 10mm 10mm 10mm;
			}
			thead{
				margin: 10mm 0 0 0;
			}
		}
	</style>
	<div id="manage_other_list_back" class="no_print"></div>
	<div id="manage_other_list_msg_wrapper" class="no_print">
		<div id="manage_other_list_msg">
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
		function button_click(){
			document.getElementById('manage_other_list_back').style.display='block';
			document.getElementById('manage_other_list_msg_wrapper').style.display='block';
			var select=document.getElementById('manage_other_list_kind');
			manage_other_list_get_book_info(select.options[select.selectedIndex].value);
		}
	</script>
</section><?php
include_once('../../../data/footer.php');
?>