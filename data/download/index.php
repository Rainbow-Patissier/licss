<?php
include_once('../link.php');
/*
f=file_path
n=filename
c=content_type
0=>pdf:application/pdf
1=>csv:text/csv
https://qiita.com/AkihiroTakamura/items/b93fbe511465f52bffaa
*/
if(isset($_GET['f']) && isset($_GET['n']) && isset($_GET['c'])){
	$content=array(
		'application/pdf',
		'text/csv'
	);
	$file_name=SQL::decrypt_k($_GET['f']);
	$filename=SQL::decrypt_k($_GET['n']);
	$content_type=$content[(int)SQL::decrypt_k($_GET['c'])];
	$path=DATA_DIR.'download/'.$file_name;
	$length=filesize($path);
	header('Content-Type: '.$content_type);    //コンテントタイプ
	header('Content-Disposition: attachment; filename="'.$filename.'"');   //DLの場合は、Content-Disposition: attachment;表示はinline
	header("Content-Length: $length");   //ファイルサイズ
	readfile ($path);
}else{
	$title="ファイルが見つかりません";
	include_once('../header.php');
	?>
	<section id="bread_crumb">
		<ul id="bread_crumb_list">
			<li class="bread_crumb_list">
				<a href="<?=$request_url;?>" class="bread_crumb_link">
					<?=$title;?>
				</a>
			</li>
		</ul>
	</section>
	<section id="main_content">
		<h2>
			ファイルが見つかりません
		</h2>
		お手数ですが，システム管理者までお問い合わせください。
	</section><?php
	include_once('../footer.php');
}
?>