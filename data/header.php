<?php
if(isset($title) && !empty($title)){
	$page_title=$title.'｜LiCSS';
}else{
	$page_title='LiCSS';
}
?><!doctype html>
<html>
	<head>
		<meta content="学校図書館に特化した図書管理システムLiCSS-無料で利用ができるかつ高性能なWebアプリケーションです。" name="description">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<title><?=$page_title;?></title>
		<!--Icon-->
		<link rel="shortcut icon" href="<?=$url;?>img/icon.ico">
		<!--Style Sheet-->
		<link rel="stylesheet" href="<?=$url;?>data/css/normalize.css?<?=$date->format('His');?>">
		<link rel="stylesheet" href="<?=$url;?>data/css/style.css?<?=$date->format('His');?>">
		<link rel="stylesheet" href="<?=$url;?>data/css/sp.css?<?=$date->format('His');?>" media="screen and (max-width: 1300px)">
		<link rel="stylesheet" href="<?=$url;?>data/css/print.css?<?=$date->format('His');?>" media="print">
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
		<!--JavaScript-->
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<script src="https://kit.fontawesome.com/6c585d1429.js" crossorigin="anonymous"></script>
		<script src="<?=$url;?>data/js/main.js?<?=$date->format('His');?>"></script>
		<script src="<?=$url;?>data/js/ajax.js?<?=$date->format('His');?>"></script>
		<script src="<?=$url;?>data/js/shortcut.js?<?=$date->format('His');?>"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
		<script data-ad-client="ca-pub-1155156698000438" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script async custom-element="amp-link-rewriter" src="https://cdn.ampproject.org/v0/amp-link-rewriter-0.1.js"></script>
		<script type="text/javascript" language="javascript">var vc_pid = "886902778";</script>
		<script type="text/javascript" src="//aml.valuecommerce.com/vcdal.js" async></script>
	</head>
	<body>
		<header>
			<div id="header_logo_wrapper">
				<h1>
					<a href="<?=$url;?>">
						<img src="<?=$url;?>img/logo.jpg" alt="LiCSS" title="LiCSS" id="header_logo">
					</a>
				</h1>
			</div>
			<nav id="header_global_nav_wrapper">
				<ul id="header_global_nav"><?php
					if($_SESSION[$session_head.'user_right']<=2){
						?>
					<li class="header_global_nav_category" id="header_global_nav_manage_wrapper">
						<a href="javascript:void(0)" class="header_global_nav_head" onClick="header_global_menu_display(this,'<?=$url;?>manage/',598);">
							管理業務
						</a>
						<ul id="header_global_nav_manage" class="header_global_sub_nav"><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									設定
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>manage/setting/school/" class="header_global_sub_sub_menu_link">
											学校設定
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/setting/borrow/" class="header_global_sub_sub_menu_link">
											貸出冊数・返却期限設定
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/setting/basis/" class="header_global_sub_sub_menu_link">
											基本設定
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/setting/other/" class="header_global_sub_sub_menu_link">
											その他設定
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									蔵書管理
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>manage/books/entry/" class="header_global_sub_sub_menu_link">
											登録
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/books/list/" class="header_global_sub_sub_menu_link">
											一覧
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/books/label/" class="header_global_sub_sub_menu_link">
											ラベル印刷
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/books/bookinfo/" class="header_global_sub_sub_menu_link">
											書誌情報
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/books/inspection/" class="header_global_sub_sub_menu_link">
											蔵書点検
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/books/request/" class="header_global_sub_sub_menu_link">
											購入依頼
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									雑誌管理
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>manage/magazine/entry/" class="header_global_sub_sub_menu_link">
											登録
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/magazine/list/" class="header_global_sub_sub_menu_link">
											一覧
										</a>
									</li>
								</ul>
							</li><?php
						}
						?>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									利用者管理
								</span>
								<ul class="header_global_sub_sub_menu"><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
									<li>
										<a href="<?=$url;?>manage/user/entry/" class="header_global_sub_sub_menu_link">
											登録
										</a>
									</li><?php
						}
						?>
									<li>
										<a href="<?=$url;?>manage/user/list/" class="header_global_sub_sub_menu_link">
											一覧
										</a>
									</li><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
									<li>
										<a href="<?=$url;?>manage/user/info/" class="header_global_sub_sub_menu_link">
											個別連絡
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/user/label/" class="header_global_sub_sub_menu_link">
											ラベル印刷
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/user/update/" class="header_global_sub_sub_menu_link">
											年度更新
										</a>
									</li><?php
						}
						?>
									<li>
										<a href="<?=$url;?>manage/user/impressions/" class="header_global_sub_sub_menu_link">
											感想文管理
										</a>
									</li>
								</ul>
							</li><?php
						if($_SESSION[$session_head.'user_right']<=1){
							?>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									その他
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>manage/other/list/" class="header_global_sub_sub_menu_link">
											台帳
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/other/statistics/" class="header_global_sub_sub_menu_link">
											統計
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/other/info/" class="header_global_sub_sub_menu_link">
											図書館からのお知らせ
										</a>
									</li>
									<li>
										<a href="<?=$url;?>manage/other/letter/" class="header_global_sub_sub_menu_link">
											図書館だより
										</a>
									</li>
								</ul>
							</li><?php
						}
						?>
						</ul>
					</li>
					<li class="header_global_nav_category">
						<a href="javascript:void(0)" class="header_global_nav_head" onClick="header_global_menu_display(this,'<?=$url;?>counter/',180);">
							窓口業務
						</a>
						<ul id="header_global_nav_counter" class="header_global_sub_nav">
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									図書
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>counter/borrow/" class="header_global_sub_sub_menu_link">
											貸出・返却・予約
										</a>
									</li>
									<li>
										<a href="<?=$url;?>counter/search/" class="header_global_sub_sub_menu_link">
											蔵書検索
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									その他
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>counter/reserve/list/" class="header_global_sub_sub_menu_link">
											予約一覧
										</a>
									</li>
									<li>
										<a href="<?=$url;?>counter/borrow/list/" class="header_global_sub_sub_menu_link">
											貸出中図書一覧
										</a>
									</li>
									<li>
										<a href="<?=$url;?>counter/borrow/request/" class="header_global_sub_sub_menu_link">
											返却請求一覧
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</li><?php
					}
					#if($_SESSION[$session_head.'user_right']<=1){
					if($_SESSION[$session_head.'user_right']<0){
						?>
					<li class="header_global_nav_category">
						<a href="javascript:void(0)" class="header_global_nav_head" onClick="header_global_menu_display(this,'',180);">
							書店連携
						</a>
						<ul id="header_global_nav_store" class="header_global_sub_nav">
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									発注
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="" class="header_global_sub_sub_menu_link">
											発注
										</a>
									</li>
									<li>
										<a href="" class="header_global_sub_sub_menu_link">
											履歴
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									受入
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="" class="header_global_sub_sub_menu_link">
											受入
										</a>
									</li>
									<li>
										<a href="" class="header_global_sub_sub_menu_link">
											履歴
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
					<?php
					}
					?>
					<li class="header_global_nav_category">
						<a href="javascript:void(0)" class="header_global_nav_head" onClick="header_global_menu_display(this,'<?=$url;?>foruser/',360);">
							利用者機能
						</a>
						<ul id="header_global_nav_foruser" class="header_global_sub_nav">
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									利用者情報
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>foruser/setting/" class="header_global_sub_sub_menu_link">
											内容確認
										</a>
									</li>
									<li>
										<a href="<?=$url;?>foruser/setting/edit/" class="header_global_sub_sub_menu_link">
											登録内容変更
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									図書
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>foruser/books/" class="header_global_sub_sub_menu_link">
											利用状況
										</a>
									</li>
									<li>
										<a href="<?=$url;?>foruser/books/search/" class="header_global_sub_sub_menu_link">
											蔵書検索
										</a>
									</li>
									<li>
										<a href="<?=$url;?>foruser/books/statistics/" class="header_global_sub_sub_menu_link">
											統計
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									感想文
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>foruser/impressions/" class="header_global_sub_sub_menu_link">
											一覧
										</a>
									</li>
									<li>
										<a href="<?=$url;?>foruser/impressions/entry/" class="header_global_sub_sub_menu_link">
											登録
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									図書購入依頼
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>foruser/request/" class="header_global_sub_sub_menu_link">
											一覧
										</a>
									</li>
									<li>
										<a href="<?=$url;?>foruser/request/entry/" class="header_global_sub_sub_menu_link">
											申請
										</a>
									</li>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									連絡事項
								</span>
								<ul class="header_global_sub_sub_menu">
									<li>
										<a href="<?=$url;?>foruser/contact/" class="header_global_sub_sub_menu_link">
											個別連絡
										</a>
									</li>
									<li>
										<a href="<?=$url;?>foruser/info/" class="header_global_sub_sub_menu_link">
											図書館からのお知らせ
										</a>
									</li>
									<li>
										<a href="<?=$url;?>foruser/letter/" class="header_global_sub_sub_menu_link">
											図書館だより
										</a>
									</li>
									<li>
										<a href="<?=$url;?>info/view/" class="header_global_sub_sub_menu_link">
											運営からのお知らせ
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
					<li class="header_global_nav_category" id="header_global_nav_account_wrapper">
						<a href="javascript:void(0)" id="header_global_nav_head_account" class="header_global_nav_head" onClick="header_global_menu_display(this,'<?=$url;?>account/',370);">
							<?=$_SESSION[$session_head.'user_name'];?>さん
						</a>
						<ul id="header_global_nav_account" class="header_global_sub_nav">
							<li class="header_global_sub_menu">
								<span class="header_global_sub_menu_head">
									運営からのお知らせ
								</span>
								<ul class="header_global_info"><?php
									$stmt=$pdo->prepare("SELECT info_id,title,title_iv FROM system_info ORDER BY edit_date DESC LIMIT 3");
									$stmt->execute();
									foreach($stmt as $row){
										?>
									<li>
										<a href="<?=$url;?>info/view/post/?i=<?=SQL::encrypt_k($row['info_id']);?>" class="header_global_account">
											<?=ALL::h(SQL::decrypt($row['title'],$row['title_iv']));?>
										</a>
									</li><?php
									}
									?>
								</ul>
								<span class="header_global_sub_menu_head">
									図書館からのお知らせ
								</span>
								<ul class="header_global_info"><?php
									$stmt=$pdo->prepare("SELECT info_id,title,title_iv FROM {$school_id}_manage_other_info ORDER BY edit_date DESC LIMIT 3");
									$stmt->execute();
									foreach($stmt as $row){
										?>
									<li>
										<a href="<?=$url;?>foruser/info/view/?i=<?=SQL::encrypt_k($row['info_id']);?>" class="header_global_account">
											<?=ALL::h(SQL::decrypt_school($row['title'],$row['title_iv']));?>
										</a>
									</li><?php
									}
									?>
								</ul>
							</li>
							<li class="header_global_sub_menu">
								<a href="<?=$url;?>account/">
									アカウント
								</a>
							</li>
							<li class="header_global_sub_menu">
								<a href="<?=$url;?>not-logged/logout/" id="header_global_logout">
									ログアウト
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</nav>
			<div id="sp_nav_wrapper">
				<span id="sp_nav_button" onClick="sp_global_menu_display();"></span>
			</div>
		</header>
		<section id="wrapper">