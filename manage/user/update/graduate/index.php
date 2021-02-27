<?php
include_once('../../../../data/link.php');
$title="卒業生削除";
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
			<a href="<?=$url;?>manage/user/update/">
				年度更新
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
	$secret_text='manage_update_graduate';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<dl class="form_style">
			<dt>
				卒業する学年
			</dt>
			<dd>
				<input type="number" name="grade" placeholder="卒業する学年" title="卒業する学年を入力してください" min="0" required>年
			</dd>
		</dl>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text.'_grade');?>" onClick="alert_cancel();">
			登録
		</button>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text.'_grade' && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		if(isset($_POST['grade'])){
		?>
	<form method="post">
		<dl class="form_style">
			<dt>
				卒業生
			</dt>
			<dd>
				<input type="hidden" name="grade" value="<?=ALL::h($_POST['grade']);?>">
				<div id="manage_user_update_grade_graduate_list">
					<table>
						<thead>
							<tr>
								<th>
									利用者番号
								</th>
								<th>
									所属
								</th>
								<th>
									名前
								</th>
							</tr>
						</thead>
						<tbody><?php
			$stmt=$pdo->prepare("SELECT user_id,grade,class,number FROM {$school_id}_account_class WHERE cond=0 AND grade=? ORDER BY grade,class,number");
			$stmt->execute(array((int)$_POST['grade']));
			foreach($stmt as $row){
				?>
							<tr>
								<td>
									<?=sprintf('%08d',$row['user_id']);?>
								</td>
								<td>
									<?=ALL::h($row['grade'].'年'.$row['class'].'組'.$row['number'].'番');?>
								</td>
								<td>
									<?=USER::name($row['user_id']);?>
								</td>
							</tr><?php
			}
		?>
						</tbody>
					</table>
				</div>
			</dd>
			<dt>
				卒業しない児童生徒(留年等)
			</dt>
			<dd>
				<input type="number" id="manage_user_update_graduate_id">
				<button type="button" onClick="enter_code();">
					登録
				</button>
				<ul id="manage_user_update_grade_id_list"></ul>
			</dd>
		</dl>
		<script>
			document.getElementById('manage_user_update_graduate_id').onkeypress=function(e){
				if(e.keyCode===13){
					enter_code();
					return false;
				}
			}
			function enter_code(){
				var id=document.getElementById('manage_user_update_graduate_id');
				var value=id.value;
				var li=document.createElement('li');
				li.innerHTML=value;
				document.getElementById('manage_user_update_grade_id_list').appendChild(li);
				var child=document.createElement('input');
				child.type='hidden';
				child.name='user_id[]';
				child.value=value
				li.appendChild(child);
				id.value='';
				id.focus();
			}
		</script>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form><?php
		ALL::remove_alert();
		}else{
			?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			必要事項の入力が確認できませんでした。
		</p>
		<p>
			恐れ入りますが，もう一度始めからやり直してください。
		</p>
	</div><?php
		}
	}elseif(isset($_POST['btn']) && isset($_POST['grade']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['user_id']) && is_array($_POST['user_id'])){
			$user=$_POST['user_id'];
		}else{
			$user=array();
		}
		$stmt=$pdo->prepare("SELECT user_id,grade,class,number FROM {$school_id}_account_class WHERE cond=0 AND grade=? ORDER BY grade,class,number");
		$stmt->execute(array((int)$_POST['grade']));
		$stmt2=$pdo->prepare("UPDATE {$school_id}_account_class SET cond=2,delete_date=? WHERE cond=0 AND user_id=?");
		$res=false;
		foreach($stmt as $row){
			if(!in_array($row['user_id'],$user)){
				$res=$stmt2->execute(array($Ymdt,$row['user_id']));
			}
		}
		if($res){
			?>
	<div>
		<h2>
			削除完了
		</h2>
		<p>
			卒業生を削除しました。
		</p>
		<p>
			次に在校生の所属情報(学年・組・番号)を更新してください。(住んでいる場合は除く)<br>
			<button type="button" onClick="window.location.href='<?=$url;?>managge/user/grade/'">
				在校生所属情報更新
			</button>
		</p>
	</div><?php
		}else{
			?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			予期せぬエラーが発生したため，卒業生を削除できませんでした。
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
include_once('../../../../data/footer.php');
?>