<?php
include_once('../../../data/link.php');
$title="学校設定";
include_once('../../../data/header.php');
$data=SQL::fetch("SELECT * FROM manage_school WHERE school_id=? AND cond=0",array($school_id));
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>manage/">
				管理業務
			</a>
		</li>
		<li>
			<a href="<?=$url;?>manage/setting/">
				設定
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
	$secret_text='setting_school';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<p>
		*は必須項目です。
		</p>
		<dl class="form_style" id="application_form_element">
			<dt>
				学校名*
			</dt>
			<dd>
				<input type="text" name="school_name" class="school_name_input" placeholder="学校名" title="学校名を入力してください" value="<?=$data['school_name'];?>" required>
				<select name="school_kind" title="校種を選択してください" required>
					<option label="選択してください"></option><?php
		foreach($school_kind as $key=>$val){
			if($key===(int)$data['school'])	{
				$selected=' selected';
			}else{
				$selected='';
			}
			?>
					<option value="<?=SQL::encrypt_ks($key);?>"<?=$selected;?>><?=ALL::h($val);?></option><?php
		}
		?>
				</select>
			</dd>
			<dt>
				学校所在地*
			</dt>
			<dd>
				<select name="prefecture" title="都道府県を選択してください" onChange="select_prefecture(this);" required>
					<option label="選択してください"></option><?php
	$code=0;
	foreach($chiho as $key=>$row){
		?>
					<optgroup label="<?=$key;?>"><?php
		foreach($row as $val){
			if($code===(int)$data['prefecture']){
				$selected=' selected';
			}else{
				$selected='';
			}
			?>
						<option value="<?=$code;?>" title="<?=$val;?>"<?=$selected;?>>
							<?=$val;?>
						</option><?php
			++$code;
		}
		?>
					</optgroup><?php
	}
	?>
				</select>
				<select name="city" id="application_city" title="市区町村を選択してください" required>
					<option label="選択してください"></option><?php
		foreach($cities[$prefecture[$data['prefecture']]] as $key=>$val){
			if($key===(int)$data['city']){
				$selected=' selected';
			}else{
				$selected='';
			}
			?>
						<option value="<?=$key;?>" title="<?=$val;?>"<?=$selected;?>>
							<?=$val;?>
						</option><?php
		}
		?>
				</select>
				<input type="text" name="address" class="address_input" placeholder="町名以下" title="町名以下を入力してください" value="<?=$data['town'];?>" required>
			</dd>
			<dt>
				学校ホームページURL
			</dt>
			<dd>
				<input type="url" name="school_url" placeholder="学校ホームページ" title="学校ホームページのURLを入力してください" value="<?=$data['hp'];?>"><br>
				http://もしくはhttps://から始まるURLを入力してください
			</dd>
			<dt>
				学校イメージカラー*
			</dt>
			<dd>
				<input type="color" name="color" placeholder="学校イメージカラー" title="学校イメージカラーを選択してください" value="<?=$data['color'];?>" required>
			</dd>
			<dt>
				学校電話番号*
			</dt>
			<dd>
				<input type="text" name="school_phone" placeholder="学校電話番号" title="学校電話番号を入力してください" pattern="[0-9]+" value="<?=$data['school_number'];?>" required><br>
				-（ハイフン）はなしで入力してください
			</dd>
			<dt>
				図書室直通電話番号
			</dt>
			<dd>
				<input type="text" name="library_phone" placeholder="図書室直通電話番号" title="図書室直通電話番号を入力してください" pattern="[0-9]+" value="<?=$data['library_number'];?>"><br>
				-（ハイフン）はなしで入力してください（直通電話番号がない場合は空欄）
			</dd>
			<dt>
				担当者名*
			</dt>
			<dd>
				<input type="text" name="person_name" placeholder="担当者名" title="担当者名を入力してください" value="<?=SQL::decrypt($data['person'],$data['person_iv']);?>" required>
			</dd>
			<dt>
				担当者メールアドレス*
			</dt>
			<dd>
				<input type="email" name="email" placeholder="担当者メールアドレス" title="担当者メールアドレスを入力してください" value="<?=SQL::decrypt($data['email'],$data['email_iv']);?>" required><br>
				個人メールアドレスがない場合，学校のメールアドレスで可
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			変更
		</button>
		<script>
			function select_prefecture(ele){
				var prefecture=ele.options[ele.selectedIndex].title;<?php
		$todo=array();
		foreach($prefecture as $val){
			$todo[]="'{$val}'";
		}
		?>
				
				var city_ary=[<?=implode(',',$todo);?>];
				<?php
		foreach($cities as $key=>$row){
			$city=array();
			foreach($row as $val){
				$city[]="'{$val}'";
			}
				?>city_ary['<?=$key;?>']=[<?=implode(',',$city);?>];<?php
		}
		?>
				var city=document.getElementById('application_city');
				city.innerHTML='<option label="選択してください"></option>';
				for(var i=0;i<Object.keys(city_ary[prefecture]).length;++i){
					var child=document.createElement('option');
					child.setAttribute('value',i);
					child.setAttribute('title',city_ary[prefecture][i]);
					child.innerHTML=city_ary[prefecture][i];
					city.appendChild(child);
				}
			}
		</script>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['school_name']) && isset($_POST['school_kind']) && isset($_POST['prefecture']) && isset($_POST['city']) && isset($_POST['address']) && isset($_POST['school_url']) && isset($_POST['color']) && isset($_POST['school_phone']) && isset($_POST['library_phone']) && isset($_POST['person_name']) && isset($_POST['email'])){
			//school
			$school_kind=(int)SQL::decrypt_ks($_POST['school_kind']);
			$prefecture_id=(int)$_POST['prefecture'];
			$city_id=(int)$_POST['city'];
			$school_name=$_POST['school_name'];
			$address=$_POST['address'];
			$school_url=$_POST['school_url'];
			$school_color=$_POST['color'];
			$school_phone=$_POST['school_phone'];
			$library_phone=$_POST['library_phone'];
			$person_name=SQL::encrypt($_POST['person_name']);
			$person_email=SQL::encrypt($_POST['email']);
			SQL::execute("UPDATE manage_school SET cond=1,delete_date=? WHERE cond=0 AND school_id=?",array($Ymdt,$school_id));
			$sql="INSERT INTO manage_school VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,NULL)";
			$values=array($school_id,$school_kind,$prefecture_id,$city_id,$data['id'],$address,$school_name,$school_color,$school_url,$school_phone,$library_phone,$person_name[0],$person_name[1],$person_email[0],$person_email[1],$Ymdt);
			if(SQL::execute($sql,$values)){
				?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			登録が完了しました。
		</p>
	</div><?php
			}else{
				?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			登録に失敗しました。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
			}
		}else{
			?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			入力の確認ができなかったため，処理が完了できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
		}
	}else{
		?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			予期せぬエラーが発生したため，処理が完了できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
	}
	?>
</section><?php
include_once('../../../data/footer.php');
?>