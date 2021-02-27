<?php
include_once('../../../data/link.php');
$title="基本設定";
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
	$secret_text='setting_basis';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<div id="manage_setting_before_form">
			<p>
				下記の中からメニューをお選びください
			</p>
			<ul class="manage_menu_link_wrapper">
				<li class="manage_menu_link_list">
					<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('save_place');">
						保管場所設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('financial_resources');">
						財源設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('book_store');">
						書店設定
					</a>
				</li>
				<li class="manage_menu_link_list">
					<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('ndc');">
						NDC設定
					</a>
				</li>
			</ul>
		</div>
		<div id="manage_setting_basis">
			<div class="scroll">
				<input type="hidden" name="type" id="manage_setting_basis_type">
				<ul id="basis_list"></ul>
				<a href="javascript:void(0)" onClick="add_data();">
					新規追加
				</a>
				<div id="manage_setting_basis_button">
					<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
					<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
						すべての変更を保存
					</button>
				</div>
			</div>
			<div id="setting_input">
				<dl class="form_style">
					<dt class="manage_basis_ndc">
						NDC
					</dt>
					<dd class="manage_basis_ndc">
						<input type="number" id="change_ndc" placeholder="NDC" title="NDCを入力してください" onChange="change_data();">
					</dd>
					<dt>
						名前
					</dt>
					<dd>
						<input type="text" id="change_name" placeholder="名前" title="名前を入力してください" onChange="change_data();">
					</dd>
					<dt class="manage_basis_code">
						書店コード
					</dt>
					<dd class="manage_basis_code">
						<input type="number" id="change_code" placeholder="書店コード" title="書店コードを入力してください" onChange="change_data();">
					</dd>
				</dl>
			</div>
		</div>
		<script>
			document.getElementById('change_name').oninput=change_data;
			document.getElementById('change_ndc').oninput=change_data;
			document.getElementById('change_code').oninput=change_data;
			function select_menu(value){
				manage_setting_basis(value);
				switch(value){
					case 'save_place':
						document.getElementById('manage_setting_basis_type').value=value;
						break;
					case 'financial_resources':
						document.getElementById('manage_setting_basis_type').value=value;
						break;
					case 'book_store':
						document.getElementById('manage_setting_basis_type').value=value;
						var code=document.getElementsByClassName('manage_basis_code');
						code[0].style.display='block';
						code[1].style.display='block';
						break;
					case 'ndc':
						document.getElementById('manage_setting_basis_type').value=value;
						var ndc=document.getElementsByClassName('manage_basis_ndc');
						ndc[0].style.display='block';
						ndc[1].style.display='block';
						break;
				}
				var before=document.getElementById('manage_setting_before_form').style;
				before.height='0px';
				document.getElementById('manage_setting_basis').style.height='700px';
			}
			function select_data(element){
				document.getElementById('setting_input').style.opacity=1;
				var parent=element.parentNode;
				var input=parent.getElementsByTagName('input');
				var target=document.getElementById('change_name')
				if(document.getElementById('manage_setting_basis_type').value==='ndc'){
					document.getElementById('change_ndc').value=input[3].value;
				}
				if(document.getElementById('manage_setting_basis_type').value==='book_store'){
					document.getElementById('change_code').value=input[3].value;
				}
				target.value=input[2].value;
				target.dataset.id=parent.dataset.id;
				input[1].value='change';
				target.focus();
			}
			function change_data(){
				var input=document.getElementById('change_name');
				var id=input.dataset.id;
				var li=document.getElementById('basis_list').getElementsByTagName('li');
				for(var i=0;i<li.length;++i){
					if(li[i].dataset.id==id){
						li[i].getElementsByTagName('input')[2].value=input.value;
						if(document.getElementById('manage_setting_basis_type').value==='ndc'){
							li[i].getElementsByTagName('input')[3].value=document.getElementById('change_ndc').value;
							li[i].getElementsByTagName('a')[0].innerHTML=document.getElementById('change_ndc').value+'&nbsp;'+input.value;
						}else if(document.getElementById('manage_setting_basis_type').value==='book_store'){
							li[i].getElementsByTagName('input')[3].value=document.getElementById('change_code').value;
							li[i].getElementsByTagName('a')[0].innerHTML=input.value+'('+document.getElementById('change_code').value+')';
						}else{
							li[i].getElementsByTagName('a')[0].innerHTML=input.value;
						}
						break;
					}
				}
			}
			function add_data(){
				var parent=document.getElementById('basis_list');
				var cnt=parent.getElementsByTagName('li').length+1;
				var child=document.createElement('li');
				child.dataset.id=cnt;
				if(document.getElementById('manage_setting_basis_type').value==='ndc'){
					child.innerHTML='<a href="javascript:void(0)" onClick="select_data(this);">0&nbsp;新規</a><input type="hidden" name="id['+cnt+']" value="0"><input type="hidden" name="cond['+cnt+']" value="keep"><input type="hidden" name="name['+cnt+']" value="新規"><input type="hidden" name="ndc['+cnt+']" value="0">';
				}else if(document.getElementById('manage_setting_basis_type').value==='book_store'){
					child.innerHTML='<a href="javascript:void(0)" onClick="select_data(this);">新規()</a><input type="hidden" name="id['+cnt+']" value="0"><input type="hidden" name="cond['+cnt+']" value="keep"><input type="hidden" name="name['+cnt+']" value="新規"><input type="hidden" name="code['+cnt+']" value="0">';
				}else{
					child.innerHTML='<a href="javascript:void(0)" onClick="select_data(this);">新規</a><input type="hidden" name="id['+cnt+']" value="0"><input type="hidden" name="cond['+cnt+']" value="keep"><input type="hidden" name="name['+cnt+']" value="新規">';
				}
				parent.appendChild(child);
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
		if(isset($_POST['type']) && isset($_POST['id']) && isset($_POST['cond']) && isset($_POST['name'])){
			switch((string)$_POST['type']){
				case 'save_place':
					$insert="INSERT INTO {$school_id}_manage_setting_location VALUES (?,?,?,?,?,0,NULL)";
					$update="UPDATE {$school_id}_manage_setting_location SET cond=1,delete_date=? WHERE cond=0 AND location_id=?";
					$row=SQL::fetch("SELECT MAX(location_id) as max FROM {$school_id}_manage_setting_location");
					$record_id=$row['max']+1;
					$check=true;
					break;
				case 'financial_resources':
					$insert="INSERT INTO {$school_id}_manage_setting_financial VALUES (?,?,?,?,?,0,NULL)";
					$update="UPDATE {$school_id}_manage_setting_financial SET cond=1,delete_date=? WHERE cond=0 AND financial_id=?";
					$row=SQL::fetch("SELECT MAX(financial_id) as max FROM {$school_id}_manage_setting_financial");
					$record_id=$row['max']+1;
					$check=true;
					break;
				case 'book_store':
					$insert="INSERT INTO {$school_id}_manage_setting_store VALUES (?,?,?,?,?,?,0,NULL)";
					$update="UPDATE {$school_id}_manage_setting_store SET cond=1,delete_date=? WHERE cond=0 AND store_id=?";
					$row=SQL::fetch("SELECT MAX(store_id) as max FROM {$school_id}_manage_setting_store");
					$record_id=$row['max']+1;
					$check=false;
					$code=$_POST['code'];
					break;
				case 'ndc':
					$insert="INSERT INTO {$school_id}_manage_setting_ndc VALUES (?,?,?,?,?,?,0,NULL)";
					$update="UPDATE {$school_id}_manage_setting_ndc SET cond=1,delete_date=? WHERE cond=0 AND ndc_id=?";
					$row=SQL::fetch("SELECT MAX(ndc_id) as max FROM {$school_id}_manage_setting_ndc");
					$record_id=$row['max']+1;
					$check=false;
					$code=$_POST['ndc'];
					break;
			}
			$post_id=$_POST['id'];
			$cond=$_POST['cond'];
			$post_name=$_POST['name'];
			$failed=array();
			foreach($cond as $key=>$val){
				if($val==='change'){
					$id=(int)SQL::decrypt_ks($post_id[$key]);
					$name=SQL::encrypt_school($post_name[$key]);
					if($id===0){
						//new record
						if($check){
							$values=array($record_id,$user_id,$name[0],$name[1],$Ymdt);
						}else{
							$values=array($record_id,$user_id,$name[0],$name[1],$code[$key],$Ymdt);
						}
						++$record_id;
					}else{
						//change record
						SQL::execute($update,array($Ymdt,$id));
						if($check){
							$values=array($id,$user_id,$name[0],$name[1],$Ymdt);
						}else{
							$values=array($id,$user_id,$name[0],$name[1],$code[$key],$Ymdt);
						}
					}
					#echo $_POST['name'][$key];
					if(!SQL::execute($insert,$values)){
						$failed[]=ALL::h($post_name[$key]);
					}
				}
			}
			?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			下記以外の登録が完了しました。
		</p>
		<p>
			登録失敗
			<div>
				<?=implode('<br>',$failed);?>
			</div>
		</p>
	</div><?php
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