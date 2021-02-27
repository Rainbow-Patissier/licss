<?php
include_once('../data/link.php');
$title="管理業務";
include_once('../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$request_url;?><?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content">
	<ul id="manage_menu"><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				設定
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>setting/school/" class="manage_menu_link">
						学校設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>setting/borrow/" class="manage_menu_link">
						貸出冊数・返却期限設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>setting/basis/" class="manage_menu_link">
						基本設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>setting/other/" class="manage_menu_link">
						その他設定
					</a>
				</li>
			</ul>
		</li>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				蔵書管理
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/entry/" class="manage_menu_link">
						登録
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/list/" class="manage_menu_link">
						一覧
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/label/" class="manage_menu_link">
						ラベル印刷
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/inspection/" class="manage_menu_link">
						蔵書点検
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>books/request/" class="manage_menu_link">
						購入依頼
					</a>
				</li>
			</ul>
		</li>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				雑誌管理
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>magazine/entry/" class="manage_menu_link">
						登録
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>magazine/list/" class="manage_menu_link">
						一覧
					</a>
				</li>
			</ul>
		</li><?php
						}
		?>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				利用者管理
			</span>
			<ul class="manage_menu_link_wrapper"><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>user/entry/" class="manage_menu_link">
						登録
					</a>
				</li><?php
						}
				?>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>user/list/" class="manage_menu_link">
						一覧
					</a>
				</li><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>user/info/" class="manage_menu_link">
						個別連絡
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>user/label/" class="manage_menu_link">
						ラベル印刷
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>user/update/" class="manage_menu_link">
						年度更新
					</a>
				</li><?php
						}
				?>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>user/impressions/" class="manage_menu_link">
						感想文管理
					</a>
				</li>
			</ul>
		</li><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
		<li class="manage_menu_list">
			<span class="manage_menu_head">
				その他
			</span>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>other/list/" class="manage_menu_link">
						台帳
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>other/statistics/" class="manage_menu_link">
						統計
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>other/info/" class="manage_menu_link">
						図書館からのお知らせ
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="<?=$request_url;?>other/letter/" class="manage_menu_link">
						図書館だより
					</a>
				</li>
			</ul>
		</li><?php
						}
?>
	</ul>
</section><?php
include_once('../data/footer.php');
?>