<?php
include_once('../../../data/link.php');
if(isset($_GET['i'])){
	$id=(int)SQL::decrypt_k($_GET['i']);
	if($id!==0 && $data=SQL::fetch("SELECT * FROM {$school_id}_manage_magazine_info WHERE cond=0 AND book_id=?",array($id))){
		$title="雑誌編集";
		$read=' readonly';
		$name=$data['name'];
		$kango=$data['series'];
		$entry_date=$data['entry_date'];
		$location=$data['location'];
		$financial=$data['financial'];
		$store=$data['store'];
	}else{
		$title="エラーが発生";
	}
}else{
	$title="雑誌登録";
	$row=SQL::fetch("SELECT MAX(book_id) as max FROM {$school_id}_manage_books_info");
	$id=$row['max']+1;
	$read='';
	$name='';
	$kango='';
	$entry_date=$Ymd;
	$location='';
	$financial='';
	$store='';
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
			<a href="<?=$url;?>manage/magazine/">
				雑誌管理
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
	$secret_text='manage_magazine';
	if(!isset($_POST['btn']) && $id!==0){
		?>
	<form method="post">
		<table>
			<tbody>
				<tr>
					<th>
						図書番号
					</th>
					<td>
						<input type="number" name="id" placeholder="図書番号" title="図書番号を入力してください" value="<?=$id;?>"<?=$read;?> required>
					</td>
				</tr>
				<tr>
					<th>
						雑誌名
					</th>
					<td>
						<input type="text" name="name" placeholder="雑誌名" title="雑誌名を入力してください" value="<?=$name;?>" required>
					</td>
				</tr>
				<tr>
					<th>
						巻号
					</th>
					<td>
						<input type="text" name="kango" placeholder="巻号" title="巻号を入力してください" value="<?=$kango;?>">
					</td>
				</tr>
				<tr>
					<th>
						受入日
					</th>
					<td>
						<input type="date" name="date" placeholder="受入日" title="受入日を入力してください" value="<?=$entry_date;?>" required>
					</td>
				</tr>
				<tr>
					<th>
						保管場所
					</th>
					<td>
						<select name="location" title="保管場所を選択してください" required>
							<option label="選択してください"></option><?php
		$stmt=$pdo->prepare("SELECT location_id,name,name_iv FROM {$school_id}_manage_setting_location WHERE cond=0 ORDER BY location_id");
		$stmt->execute();
		foreach($stmt as $row){
			if($row['location_id']==$location){
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
					</td>
				</tr>
				<tr>
					<th>
						財源
					</th>
					<td>
						<select name="finacial" title="財源を選択してください" required>
							<option label="選択してください"></option><?php
		$stmt=$pdo->prepare("SELECT financial_id,name,name_iv FROM {$school_id}_manage_setting_financial WHERE cond=0 ORDER BY financial_id");
		$stmt->execute();
		foreach($stmt as $row){
			if($row['financial_id']==$financial){
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
					</td>
				</tr>
				<tr>
					<th>
						購入書店
					</th>
					<td>
						<select name="store" title="購入書店を選択してください" required>
							<option label="選択してください"></option><?php
		$stmt=$pdo->prepare("SELECT store_id,name,name_iv FROM {$school_id}_manage_setting_store WHERE cond=0 ORDER BY store_id");
		$stmt->execute();
		foreach($stmt as $row){
			if($row['store_id']==$store){
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
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
		<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text);?>" onClick="alert_cancel();">
			登録
		</button>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		echo '<br>';
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['kango']) && isset($_POST['date']) && isset($_POST['location']) && isset($_POST['finacial']) && isset($_POST['store']) ){
			$sql="INSERT INTO {$school_id}_manage_magazine_info VALUES (?,?,?,?,?,?,?,?,?,0,NULL)";
			$values=array();
			$values[]=(int)$_POST['id'];
			$values[]=$user_id;
			$values[]=$_POST['name'];
			$values[]=$_POST['kango'];
			$values[]=$_POST['date'];
			$values[]=(int)SQL::decrypt_ks($_POST['location']);
			$values[]=(int)SQL::decrypt_ks($_POST['finacial']);
			$values[]=(int)SQL::decrypt_ks($_POST['store']);
			$values[]=$Ymdt;
			SQL::execute("UPDATE {$school_id}_manage_magazine_info SET cond=1,delete_date=? WHERE cond=0 AND book_id=?",array($Ymdt,(int)$_POST['id']));
			if(SQL::execute($sql,$values)){
				?>
	<div>
		<h2>
			登録完了
		</h2>
		<p>
			雑誌の登録が完了しました。
		</p>
	</div><?php
			}else{
				?>
	<div>
		<h2>
			登録失敗
		</h2>
		<p>
			雑誌の登録に失敗しました。
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