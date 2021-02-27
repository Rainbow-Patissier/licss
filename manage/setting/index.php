<?php
include_once('../../data/link.php');
$title="設定";
include_once('../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>manage/">
				管理業務
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
	<ul id="manage_menu">
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				設定
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>school/" class="manage_menu_link">
						学校設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>borrow/" class="manage_menu_link">
						貸出冊数・返却期限設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>basis/" class="manage_menu_link">
						基本設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>other/" class="manage_menu_link">
						その他設定
					</a>
				</li>
			</ul>
		</li>
	</ul>
</section><?php
include_once('../../data/footer.php');
?>