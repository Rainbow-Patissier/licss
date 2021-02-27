<?php
include_once('../../../../data/link.php');
if(isset($_GET['i'])){
	$secret_text='foruser_impressions_detail;';
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0 && $data=SQL::fetch("SELECT impressions_id,user_id,edit_user_id,title,book_id,isbn,content,public,words FROM {$school_id}_foruser_impressions WHERE cond=0 AND impressions_id=?",array($id))){
		$public=array('下書き','非公開','学内公開','公開');
		$data2=SQL::fetch("SELECT DATE_FORMAT(edit_date,'%Y.%m.%d') as edit_date FROM {$school_id}_foruser_impressions WHERE impressions_id=? ORDER BY edit_date",array($id));
		if(isset($_POST['btn']) && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
			if(SQL::decrypt_ks($_POST['btn'])===$secret_text.'_edit'){
				if(isset($_POST['content']) && isset($_POST['public'])){
					$data['content']=$_POST['content'];
					$data['public']=(int)SQL::decrypt_ks($_POST['public']);
					$data['words']=(int)mb_strlen(str_replace("\n","",$_POST['content']))+1-(int)count(explode("\n",$_POST['content']));
				}else{
					$_SESSION[$session_head.'form_result']=array(0,"未入力項目があるため，登録できませんでした");
					header("Location: {$request_url}");
					exit();
				}
			}else{
				$data['public']=(int)SQL::decrypt_ks($_POST['btn']);
			}
			SQL::execute("UPDATE {$school_id}_foruser_impressions SET cond=1,delete_date=NOW() WHERE impressions_id=? AND cond=0",array($id));
			if(SQL::execute("INSERT INTO {$school_id}_foruser_impressions VALUES (?,?,?,?,?,?,?,?,?,NOW(),0,NULL)",array_values($data))){
				$_SESSION[$session_head.'form_result']=array(0,"登録しました");
				header("Location: {$request_url}");
			}else{
				$_SESSION[$session_head.'form_result']=array(0,"登録に失敗しました");
				header("Location: {$request_url}");
			}
		}else{
			if(isset($_SESSION[$session_head.'form_result'][0]) && $_SESSION[$session_head.'form_result'][0]<2){
				++$_SESSION[$session_head.'form_result'][0];
			}else{
				unset($_SESSION[$session_head.'form_result']);
			}
		}
		$title="感想文詳細";
	}else{
		$title="エラーが発生";
	}
}else{
	$id=0;
	$title="エラーが発生";
}
include_once('../../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>manage/">
				管理業務
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/user/">
				利用者管理
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/user/impressions/">
				感想文管理
			</a>
		</li>
		<li>
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content"><?php
	if($id!==0){
		if(isset($_SESSION[$session_head.'form_result'][1]) && $_SESSION[$session_head.'form_result'][0]<2){
		?>
	<div class="result">
		<?=ALL::h($_SESSION[$session_head.'form_result'][1]);?>
	</div><?php
		}
	?>
	<form method="post">
		<table>
			<tbody>
				<tr>
					<th>
						図書名
					</th>
					<td>
						<?=GET::book_name($data['book_id']);?>(<?=sprintf('%09d',$data['book_id']);?>)
					</td>
				</tr>
				<tr>
					<th>
						文字数
					</th>
					<td>
						<?=number_format($data['words']);?>
					</td>
				</tr>
				<tr>
					<th>
						投稿日時
					</th>
					<td>
						<?=$data2['edit_date'];?>
					</td>
				</tr>
				<tr>
					<th>
						内容
					</th>
					<td>
						<?=ALL::h($data['content']);?>
					</td>
				</tr>
				<tr>
					<th>
						状態
					</th>
					<td>
						<?=$public[$data['public']];?>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>"><?php
		if($data['public']!=0){
			foreach($public as $key=>$val){
				if($key!==0 && $key!=$data['public']){
					?>
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($key);?>" onClick="alert_cancel();">
			<?=$val;?>
		</button><?php
				}
			}
		}
		?>
	</form><?php
	}else{
		?>
	<h2>
		エラーが発生
	</h2>
	<div>
		<p>
			ページのURLの期限が切れています。
		</p>
		<p>
			恐れ入りますが，はじめからやり直してください。
		</p>
	</div><?php
	}
	?>
</section><?php
include_once('../../../../data/footer.php');
?>