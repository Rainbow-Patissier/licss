<?php
include_once('../../data/link.php');
$title="その他";
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
				その他
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>list/" class="manage_menu_link">
						台帳
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>statistics/" class="manage_menu_link">
						統計
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>info/" class="manage_menu_link">
						図書館からのお知らせ
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>letter/" class="manage_menu_link">
						図書館だより
					</a>
				</li>
			</ul>
		</li>
	</ul>
</section><?php
include_once('../../data/footer.php');
?>