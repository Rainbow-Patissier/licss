<?php
include_once('../../../data/link.php');
$secret_text='books_entry';
if(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
	if(isset($_POST['book_id']) && isset($_POST['isbn']) && isset($_POST['receive_date']) && isset($_POST['series']) && isset($_POST['name']) && isset($_POST['kana']) && isset($_POST['sub_name']) && isset($_POST['author']) && isset($_POST['author_kana']) && isset($_POST['author_introduction']) && isset($_POST['publisher']) && isset($_POST['publish_date']) && isset($_POST['size']) && isset($_POST['pages']) && isset($_POST['price']) && isset($_POST['seikyu']) && isset($_POST['color']) && isset($_POST['ndc']) && isset($_POST['save_place']) && isset($_POST['financial']) && isset($_POST['store']) && isset($_POST['book_img']) && isset($_POST['note'])){
		//画像取得方法 https://liginc.co.jp/programmer/archives/229
		if(!empty($_POST['book_img'])){
			$image=file_get_contents($_POST['book_img']);
			$file_name='img/book_image/'.$school_id.'_'.sprintf('%09d',(int)$_POST['book_id']).(int)$_POST['isbn'];
			$save_path=LINK::path_public().$file_name;
			file_put_contents($save_path, $image);
		}else{
			$file_name='';
		}
		if(isset($_POST['kintai']) && $_POST['kintai']=='true'){
			$not_reserve=1;
		}else{
			$not_reserve=0;
		}
		if(isset($_POST['delete']) && $_POST['delete']=='true' && isset($_POST['delete_date'])){
			$delete_date=new DateTime($_POST['delete_date']);
			$delete_date=$delete_date->format('Y-m-d');
		}else{
			$delete_date=NULL;
		}
		$sql="INSERT INTO {$school_id}_manage_books_info VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,NULL)";
		$values=array(
			(int)$_POST['book_id'],
			$user_id,
			(int)$_POST['isbn'],
			$_POST['receive_date'],
			$_POST['series'],
			$_POST['name'],
			$_POST['kana'],
			$_POST['sub_name'],
			$_POST['author'][0],
			$_POST['author_kana'][0],
			$_POST['author_introduction'][0],
			$_POST['author'][1],
			$_POST['author_kana'][1],
			$_POST['author_introduction'][1],
			$_POST['author'][2],
			$_POST['author_kana'][2],
			$_POST['author_introduction'][2],
			$_POST['author'][3],
			$_POST['author_kana'][3],
			$_POST['author_introduction'][3],
			$_POST['publisher'],
			$_POST['publish_date'],
			$_POST['size'],
			(int)$_POST['pages'],
			(int)$_POST['price'],
			(int)$_POST['seikyu'][0],
			$_POST['seikyu'][1],
			$_POST['seikyu'][2],
			(int)SQL::decrypt_ks($_POST['color']),
			(int)SQL::decrypt_ks($_POST['ndc']),
			(int)SQL::decrypt_ks($_POST['save_place']),
			(int)SQL::decrypt_ks($_POST['financial']),
			(int)SQL::decrypt_ks($_POST['store']),
			$not_reserve,
			$delete_date,
			$file_name,
			$_POST['note'],
			$Ymdt
					 );
		SQL::execute("UPDATE {$school_id}_manage_books_info SET cond=1,delete_date=? WHERE book_id=? AND cond=0",array($Ymdt,(int)$_POST['book_id']));
		if(SQL::execute($sql,$values)){
			$_SESSION[$session_head.'form_result']=array(0,"登録しました");
			header("Location: {$request_url}");
		}else{
			$_SESSION[$session_head.'form_result']=array(0,"登録に失敗しました");
			header("Location: {$request_url}");
		}
	}else{
		$_SESSION[$session_head.'form_result']=array(0,"未入力項目があるため，登録できませんでした");
		header("Location: {$request_url}");
	}
}else{
	if(isset($_SESSION[$session_head.'form_result'][0]) && $_SESSION[$session_head.'form_result'][0]<2){
		++$_SESSION[$session_head.'form_result'][0];
	}else{
		unset($_SESSION[$session_head.'form_result']);
	}
}
if(isset($_GET['i'])){
	$book_id=(int)SQL::decrypt_k($_GET['i']);
	if($book_id!=0 && $data=SQL::fetch("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 AND book_id=?",array($book_id))){
		$title="蔵書の編集";
		$isbn=$data['isbn'];
		$receive_date=new DateTime($data['entry_date']);
		$series=$data['series'];
		$name=$data['book_name'];
		$sub_name=$data['sub_name'];
		$kana=$data['book_name_ruby'];
		$author=array($data['author1'],$data['author2'],$data['author3'],$data['author4']);
		$author_kana=array($data['author1_ruby'],$data['author2_ruby'],$data['author3_ruby'],$data['author4_ruby']);
		$author_introduction=array($data['author1_biography'],$data['author2_biography'],$data['author3_biography'],$data['author4_biography']);
		$ndc=(int)$data['ndc'];
		$location=(int)$data['location'];
		$financial=(int)$data['financial'];
		$store=(int)$data['store'];
		$publisher=$data['publisher'];
		if(empty($data['publish_date'])){
			$publish_date="";
		}else{
			$publish_date=$data['publish_date'];
		}
		$size=$data['size'];
		$pages=$data['page'];
		$price=$data['price'];
		$seikyu=array($data['seikyu1'],$data['seikyu2'],$data['seikyu3']);
		$seikyu_color=(int)$data['color'];
		if((int)$data['not_reserve']===1){
			$kintai=' checked';
		}else{
			$kintai='';
		}
		if(empty($data['drop_date'])){
			$delete='';
			$delete_date=$Ymd;
		}else{
			$delete=' checked';
			$delete_date=$data['drop_date'];
		}
		$introduction=$data['note'];
		if(empty($data['book_img'])){
			$book_img='img/book_noimage.jpg';
		}else{
			$book_img=$data['book_img'];
		}
		$read=' readonly';
	}else{
		$title="エラーが発生";
		$book_id=0;
	}
}else{
	$title="蔵書の登録";
	$row=SQL::fetch("SELECT MAX(book_id) as max FROM {$school_id}_manage_books_info");
	$book_id=$row['max']+1;
	$isbn="";
	$receive_date=new DateTime();
	$series="";
	$name="";
	$sub_name="";
	$kana="";
	$author=array("","","","");
	$author_kana=array("","","","");
	$author_introduction=array("","","","");
	$ndc=0;
	$location=0;
	$financial=0;
	$store=0;
	$publisher="";
	$publish_date="";
	$size="";
	$pages="";
	$price="";
	$seikyu=array("","","");
	$seikyu_color='';
	$kintai='';
	$delete='';
	$delete_date=$Ymd;
	$introduction='';
	$book_img='img/book_noimage.jpg';
	$read='';
}
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>manage/">
				管理業務
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/books/">
				蔵書管理
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
	if(isset($_SESSION[$session_head.'form_result'][1]) && $_SESSION[$session_head.'form_result'][0]<2){
		?>
	<div class="result">
		<?=ALL::h($_SESSION[$session_head.'form_result'][1]);?>
	</div><?php
	}
		?>
	<form method="post">
		<div id="manage_books_entry_wrapper">
			<div class="manage_books_entry_book_info">
				<div>
					図書番号
				</div>
				<input type="number" name="book_id" id="manage_books_entry_id" placeholder="図書番号" title="図書番号を入力してください" value="<?=$book_id;?>"<?=$read;?> required>
				<div>
					ISBN
				</div>
				<div>
					<input type="text" name="isbn" id="manage_books_entry_isbn" placeholder="ISBN" title="ISBNを入力してください" value="<?=$isbn;?>" onClick="this.type='text';" onChange="input_isbn(this);" autofocus required>
					<button type="button" onClick="input_isbn(document.getElementById('manage_books_entry_isbn'));">
						ISBNから書誌情報を取得
					</button>
					※入力内容は破棄されます。
				</div>
				<div>
					受入日
				</div>
				<input type="date" name="receive_date" id="manage_books_entry_receive_date" placeholder="受入日" title="受入日を入力してください" value="<?=$receive_date->format('Y-m-d');?>" required>
				<div>
					シリーズ名
				</div>
				<input type="text" name="series" id="manage_books_entry_series" placeholder="シリーズ名" title="シリーズ名を入力してください" value="<?=$series;?>">
				<div>
					書名
				</div>
				<div id="manage_books_entry_names_wrapper">
					<input type="text" name="name" id="manage_books_entry_name" class="manage_books_entry_names" placeholder="書名" title="書名を入力してください" value="<?=$name;?>" required>
					<input type="text" name="kana" id="manage_books_entry_kana" class="manage_books_entry_names" placeholder="読み" title="読みを入力してください" value="<?=$kana;?>">
				</div>
				<div>
					副書名
				</div>
				<input type="text" name="sub_name" id="manage_books_entry_sub_name" placeholder="副書名" title="副書名を入力してください" value="<?=$sub_name;?>"><?php
		foreach($author as $key=>$val){
			?>
				<div>
					著作者<?=$key+1;?>
				</div>
				<div>
					<input type="text" name="author[<?=$key;?>]" id="manage_books_entry_author_<?=$key;?>" class="manage_books_entry_autor" placeholder="著作者<?=$key+1;?>" title="著作者<?=$key+1;?>を入力してください" value="<?=$val;?>"><br>
					（読み）<input type="text" name="author_kana[<?=$key;?>]" id="manage_books_entry_author_kana_<?=$key;?>" class="manage_books_entry_autor" placeholder="著作者（読み）<?=$key+1;?>" title="著作者（読み）<?=$key+1;?>を入力してください" value="<?=$author_kana[$key];?>">
					（著者紹介）<textarea name="author_introduction[<?=$key;?>]" id="manage_books_entry_author_introduction_<?=$key;?>" placeholder="著者紹介" title="著者紹介を入力してください"></textarea>
				</div><?php
		}
		?>
			<!--</div>
			<div class="manage_books_entry_book_info">-->
				<div>
					出版社
				</div>
				<input type="text" name="publisher" id="manage_books_entry_publisher" placeholder="出版社" title="出版社を入力してください" value="<?=$publisher;?>">
				<div>
					出版日
				</div>
				<span>
					<input type="date" name="publish_date" id="manage_books_entry_publish_date" placeholder="出版日" title="出版日を入力してください" value="<?=$publish_date;?>">
				</span>
				<div>
					サイズ
				</div>
				<span>
					<input type="text" name="size" id="manage_books_entry_size" placeholder="サイズ" title="サイズを入力してください" value="<?=$size;?>">
				</span>
				<div>
					ページ数
				</div>
				<span class="pages">
					<input type="number" name="pages" id="manage_books_entry_pages" placeholder="ページ数" title="ページ数を入力してください" value="<?=$pages;?>">
				</span>
				<div>
					金額
				</div>
				<span class="price">
					<input type="number" name="price" id="manage_books_entry_price" placeholder="金額" title="金額を入力してください" value="<?=$price;?>">
				</span>
				<div>
					請求記号
				</div>
				<div>
					<input type="number" name="seikyu[0]" id="manage_books_entry_seikyu_0" placeholder="請求記号" title="請求記号を入力してください" value="<?=$seikyu[0];?>">
					-
					<input type="text" name="seikyu[1]" id="manage_books_entry_seikyu_1" placeholder="請求記号" title="請求記号を入力してください" value="<?=$seikyu[1];?>">
					-
					<input type="text" name="seikyu[2]" id="manage_books_entry_seikyu_2" placeholder="請求記号" title="請求記号を入力してください" value="<?=$seikyu[2];?>"><br>
					カラー
					<select name="color" title="請求ラベルのカラーを選択してください">
						<option label="選択してください"></option><?php
		foreach($back_label_color as $key=>$val){
			if((int)$key===$seikyu_color){
				$select=' selected';
			}else{
				$select='';
			}
			?>
						<option value="<?=SQL::encrypt_ks($key);?>" title="<?=$val;?>"<?=$select;?>>
							<?=$val;?>
						</option><?php
		}
		?>
					</select>
				</div>
				<div>
					NDC
				</div>
				<div>
					<select name="ndc" title="NDCを選択してください" id="manage_books_entry_ndc" onChange="change_ndc(this);" required>
						<option label="選択してください"></option><?php
		$stmt=$pdo->prepare("SELECT ndc_id,name,name_iv,ndc_code FROM {$school_id}_manage_setting_ndc WHERE cond=0 ORDER BY ndc_code");
		$stmt->execute();
		foreach($stmt as $row){
			if((int)$row['ndc_id']===$ndc){
				$select=' selected';
			}else{
				$select='';
			}
			$name=ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']));
			?>
						<option value="<?=SQL::encrypt_ks($row['ndc_id']);?>" title="<?=ALL::h($row['ndc_code']);?>&nbsp;<?=$name;?>" data-code="<?=ALL::h($row['ndc_code']);?>"<?=$select;?>>
							<?=ALL::h($row['ndc_code']);?>&nbsp;<?=$name;?>
						</option><?php
		}
		?>
					</select>
				</div>
				<div>
					保管場所
				</div>
				<div>
					<select name="save_place" title="保管場所を選択してください" required>
						<option label="選択してください"></option><?php
		$stmt=$pdo->prepare("SELECT location_id,name,name_iv FROM {$school_id}_manage_setting_location WHERE cond=0 ORDER BY location_id");
		$stmt->execute();
		foreach($stmt as $row){
			if((int)$row['location_id']===$location){
				$select=' selected';
			}else{
				$select='';
			}
			$name=ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']));
			?>
						<option value="<?=SQL::encrypt_ks($row['location_id']);?>" title="<?=$name;?>"<?=$select;?>>
							<?=$name;?>
						</option><?php
		}
		?>
					</select>
				</div>
				<div>
					財源
				</div>
				<div>
					<select name="financial" title="財源を選択してください" required>
						<option label="選択してください"></option><?php
		$stmt=$pdo->prepare("SELECT financial_id,name,name_iv FROM {$school_id}_manage_setting_financial WHERE cond=0 ORDER BY financial_id");
		$stmt->execute();
		foreach($stmt as $row){
			if((int)$row['financial_id']===$financial){
				$select=' selected';
			}else{
				$select='';
			}
			$name=ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']));
			?>
						<option value="<?=SQL::encrypt_ks($row['financial_id']);?>" title="<?=$name;?>"<?=$select;?>>
							<?=$name;?>
						</option><?php
		}
		?>
					</select>
				</div>
				<div>
					購入書店
				</div>
				<div>
					<select name="store" title="財源を選択してください" required>
						<option label="選択してください"></option><?php
		$stmt=$pdo->prepare("SELECT store_id,name,name_iv FROM {$school_id}_manage_setting_store WHERE cond=0 ORDER BY store_id");
		$stmt->execute();
		foreach($stmt as $row){
			if((int)$row['store_id']===$store){
				$select=' selected';
			}else{
				$select='';
			}
			$name=ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']));
			?>
						<option value="<?=SQL::encrypt_ks($row['store_id']);?>" title="<?=$name;?>"<?=$select;?>>
							<?=$name;?>
						</option><?php
		}
		?>
					</select>
				</div>
				<div>
					禁帯出
				</div>
				<div>
					<label>
						<input type="checkbox" name="kintai" title="禁帯出" value="true"<?=$kintai;?>>禁帯出
					</label>
				</div>
				<div>
					廃棄
				</div>
				<div>
					<label>
						<input type="checkbox" name="delete" title="廃棄" value="true" id="manage_books_entry_delete" onChange="change_delete(this);"<?=$delete;?>>廃棄
					</label>
					<input type="date" name="delete_date" id="manage_books_entry_delete_date" placeholder="廃棄日" title="廃棄日を入力してください" value="<?=$delete_date;?>">
				</div>
			</div>
			<img alt="書影" id="manage_books_entry_img" src="<?=$url.$book_img;?>">
			<input type="hidden" name="book_img" id="manage_books_entry_img_input">
		</div>
		<div>
			内容紹介等<br>
			<textarea name="note" id="manage_books_entry_note" placeholder="内容紹介等" title="内容紹介等を入力してください"><?=$introduction;?></textarea>
		</div><?php
		$token=ALL::csrf_token($secret_text);
		//echo $token;
		?>
		<input type="hidden" name="token" value="<?=$token;?>" onChange="alert('change')">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
		<div id="manage_books_entry_anounce_back"></div>
		<div id="manage_books_entry_anounce_wrapper">
			<div id="manage_books_entry_announce">
				 <div class="balls-guruguru">
					<span class="ball ball-1"></span>
					<span class="ball ball-2"></span>
					<span class="ball ball-3"></span>
					<span class="ball ball-4"></span>
					<span class="ball ball-5"></span>
					<span class="ball ball-6"></span>
					<span class="ball ball-7"></span>
					<span class="ball ball-8"></span>
				</div>
			</div>
		</div>
		<script>
			document.getElementById('manage_books_entry_id').onkeypress=function(e){
				if(e.keyCode===13){
					document.getElementById('manage_books_entry_isbn').focus();
				}
			}
			document.getElementById('manage_books_entry_isbn').onkeypress=function(e){
				if(e.keyCode===13){
					document.getElementById('manage_books_entry_receive_date').focus();
				}
			}
			change_delete(document.getElementById('manage_books_entry_delete'));
			function input_isbn(ele){
				document.getElementById('manage_books_entry_anounce_back').style.visibility='visible';
				document.getElementById('manage_books_entry_anounce_wrapper').style.visibility='visible';
				var str=ele.value;
				ele.value=str.replace('-','');
				ele.type='number';
				manage_books_entry_get_book_info(ele.value);
			}
			function change_delete(ele){
				if(ele.checked){
					document.getElementById('manage_books_entry_delete_date').style.display='inline';
					document.getElementById('manage_books_entry_delete_date').setAttribute('required','');
				}else{
					document.getElementById('manage_books_entry_delete_date').style.display='none';
					document.getElementById('manage_books_entry_delete_date').removeAttribute('required');
				}
			}
			function change_ndc(ele){
				var seikyu=document.getElementById('manage_books_entry_seikyu_0');
				if(seikyu==''){
					seikyu.value=ele.dataset.code;
				}
			}
			$(function(){
				$('#manage_books_entry_ndc').select2();
			});
		</script>
	</form><?php
		ALL::remove_alert();
	?>
</section><?php
include_once('../../../data/footer.php');
?>