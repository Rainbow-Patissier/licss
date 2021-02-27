<?php
include_once('../../data/link.php');
$title="利用者管理";
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
				利用者登録
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>entry/" class="manage_menu_link">
						登録
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>list/" class="manage_menu_link">
						一覧
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>info/" class="manage_menu_link">
						個別連絡
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>label/" class="manage_menu_link">
						ラベル印刷
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>update/" class="manage_menu_link">
						年度更新
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>impressions/" class="manage_menu_link">
						感想文管理
					</a>
				</li>
			</ul>
		</li>
	</ul>
</section><?php
include_once('../../data/footer.php');
?>