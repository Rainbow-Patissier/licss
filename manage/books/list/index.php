<?php
include_once('../../../data/link.php');
$title="蔵書一覧";
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
<section id="main_content">
	<button id="manage_books_list_print_button" class="no_print">
		印刷
	</button>
	<div id="manage_books_list_booklist_wrapper" class="scroll">
		<table id="manage_books_list_booklist_wrapper">
			<thead>
				<tr>
					<th>
						図書番号
					</th>
					<th>
						ISBN
					</th>
					<th>
						受入日
					</th>
					<th>
						シリーズ名
					</th>
					<th>
						書名
					</th>
					<th>
						副書名
					</th>
					<th>
						著者
					</th>
					<th>
						出版社
					</th>
					<th>
						出版日
					</th>
					<th>
						サイズ
					</th>
					<th>
						ページ数
					</th>
					<th>
						NDC
					</th>
					<th>
						請求記号
					</th>
					<th>
						保管場所
					</th>
					<th>
						操作
					</th>
				</tr>
			</thead>
			<tbody id="manage_books_list_body"></tbody>
		</table>
	</div>
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
		document.getElementById('announce_back').style.display='block';
		document.getElementById('announce_wrapper').style.display='block';
		manage_books_list_get_book_info();
	</script>
	<style>
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
				margin: 10mm;
				font-size: 8pt;
			}
			thead{
				margin-top: 10mm;
			}
		}
	</style>
</section><?php
include_once('../../../data/footer.php');
?>