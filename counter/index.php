<?php
include_once('../data/link.php');
$title="窓口業務";
include_once('../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content">
	<ul id="manage_menu">
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				図書
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>borrow/" class="manage_menu_link">
						貸出・返却
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>reserve/" class="manage_menu_link">
						予約
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>search/" class="manage_menu_link">
						蔵書検索
					</a>
				</li>
			</ul>
		</li>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				その他
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>reserve/list/" class="manage_menu_link">
						予約一覧
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>borrow/list/" class="manage_menu_link">
						貸出中図書一覧
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>borrow/request/" class="manage_menu_link">
						返却請求一覧
					</a>
				</li>
			</ul>
		</li>
	</ul>
</section><?php
include_once('../data/footer.php');
?>