<?php
include_once('../data/link.php');
$title="利用者機能";
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
				利用者情報
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>setting/" class="manage_menu_link">
						内容確認
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>setting/edit/" class="manage_menu_link">
						登録内容変更
					</a>
				</li>
			</ul>
		</li>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				図書
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/" class="manage_menu_link">
						利用状況
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/search/" class="manage_menu_link">
						蔵書検索
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/statistics/" class="manage_menu_link">
						統計
					</a>
				</li>
			</ul>
		</li>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				感想文
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>impressions/" class="manage_menu_link">
						一覧
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>impressions/entry/" class="manage_menu_link">
						登録
					</a>
				</li>
			</ul>
		</li>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				図書購入依頼
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>request/" class="manage_menu_link">
						一覧
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>request/entry/" class="manage_menu_link">
						申請
					</a>
				</li>
			</ul>
		</li>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				連絡事項
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>contact/" class="manage_menu_link">
						個別連絡
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
				<li class="manage_menu_link_list">
					<a href="<?=$url;?>info/view/" class="manage_menu_link">
						運営からのお知らせ
					</a>
				</li>
			</ul>
		</li>
	</ul>
</section><?php
include_once('../data/footer.php');
?>