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
		<link rel="stylesheet" href="<?=$url;?>/data/css/normalize.css?<?=$date->format('His');?>">
		<link rel="stylesheet" href="<?=$url;?>/data/css/style.css?<?=$date->format('His');?>">
		<link rel="stylesheet" href="<?=$url;?>/data/css/sp.css?<?=$date->format('His');?>">
		<link rel="stylesheet" href="<?=$url;?>/data/css/print.css?<?=$date->format('His');?>" media="print">
		<!--JavaScript-->
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<script src="https://kit.fontawesome.com/6c585d1429.js" crossorigin="anonymous"></script>
		<script src="<?=$url;?>/data/js/main.js?<?=$date->format('His');?>"></script>
		<script src="<?=$url;?>/data/js/ajax.js?<?=$date->format('His');?>"></script>
		<script src="<?=$url;?>/data/js/jquery.nw7.js?<?=$date->format('His');?>"></script>
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
		</header>
		<section id="wrapper">