<?php
include_once('../../../data/link.php');
$title="利用者一覧";
include_once('../../../data/header.php');
$row=SQL::fetch_all("SELECT grade,class FROM {$school_id}_account_class WHERE cond=0");
$grade=array_unique(array_column($row,'grade'));
$class=array_unique(array_column($row,'class'));
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
			<a href="<?=$request_url;?>">
				<?=$title;?>
			</a>
		</li>
	</ul>
</nav>
<section id="main_content">
	<div>
		<button type="button" onClick="window.location.href='<?=$request_url;?>download/?c=<?=SQL::encrypt_k($Ymd);?>'">CSVエクスポート</button><br>
		フィルター<br>
		学年：
		<select id="manage_user_list_grade" title="学年を選択してください" onChange="change_filter();">
			<option value="all">選択してください</option><?php
			foreach($grade as $val){
				?>
			<option value="<?=$val;?>"><?=$val;?>年</option><?php
			}
			?>
		</select>
		クラス：
		<select id="manage_user_list_class" title="クラスを選択してください" onChange="change_filter();">
			<option value="all">選択してください</option><?php
			foreach($class as $val){
				?>
			<option value="<?=$val;?>"><?=$val;?>組</option><?php
			}
			?>
		</select>
		権限：
		<select id="manage_user_list_right" title="権限を選択してください" onChange="change_filter();">
			<option value="all">選択してください</option>
			<option value="admin">管理者</option>
			<option value="teacher">教師</option>
			<option value="officer">職員</option>
			<option value="general">一般</option>
		</select>
	</div>
	<table>
		<thead>
			<tr>
				<th>
					利用者番号
				</th>
				<th>
					学年
				</th>
				<th>
					クラス
				</th>
				<th>
					番号
				</th>
				<th>
					性別
				</th>
				<th>
					名前
				</th>
				<th>
					権限
				</th>
			</tr>
		</thead>
		<tbody id="manage_user_list_body"><?php
			$stmt=$pdo->prepare("SELECT user_id,grade,class,number,user_right FROM {$school_id}_account_class WHERE cond=0 ORDER BY user_id,grade,class,number");
			$stmt->execute();
			$stmt2=$pdo->prepare("SELECT name,name_iv,sex FROM account_user WHERE cond=0 AND user_id=?");
			$rights=array('admin','admin','teacher','officer','general');
			$sex=array('秘密','男性','女性','中性');
			foreach($stmt as $row){
				$stmt2->execute(array($row['user_id']));
				if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
					$name=ALL::h(SQL::decrypt($row2['name'],$row2['name_iv']));
					$sex_id=(int)$row2['sex'];
				}else{
					$name='no name';
					$sex_id=0;
				}
				?>
			<tr data-grade="<?=ALL::h($row['grade']);?>" data-class="<?=ALL::h($row['class']);?>" data-right="<?=$rights[$row['user_right']];?>">
				<td>
					<?=ALL::h(sprintf('%08d',$row['user_id']));?>
				</td>
				<td>
					<?=ALL::h($row['grade']);?>
				</td>
				<td>
					<?=ALL::h($row['class']);?>
				</td>
				<td>
					<?=ALL::h($row['number']);?>
				</td>
				<td>
					<?=$sex[$sex_id];?>
				</td>
				<td>
					<a href="<?=$url;?>manage/user/profile/?i=<?=SQL::encrypt_k($row['user_id']);?>"><?=$name;?></a>
				</td>
				<td>
					<?=$user_right[$row['user_right']];?>
					<a href="<?=$url;?>manage/user/right/?i=<?=SQL::encrypt_k($row['user_id']);?>">権限変更</a>
				</td>
			</tr><?php
			}
			?>
		</tbody>
	</table>
	<script>
		function change_filter(){
			var grade_select=document.getElementById('manage_user_list_grade');
			var class_select=document.getElementById('manage_user_list_class');
			var right_select=document.getElementById('manage_user_list_right');
			var grade_value=grade_select.options[grade_select.selectedIndex].value;
			var class_value=class_select.options[class_select.selectedIndex].value;
			var right_value=right_select.options[right_select.selectedIndex].value;
			var tr=document.getElementById('manage_user_list_body').getElementsByTagName('tr');
			var tr_data='';
			for(var i=0;i<tr.length;++i){
				tr_data=tr[i].dataset;
				if((grade_value=='all' || grade_value==Number(tr_data.grade)) && (class_value=='all' || class_value==tr_data.class) && (right_value=='all' || right_value==tr_data.right)){
					tr[i].style.display='table-row';
				}else{
					tr[i].style.display='none';
				}
			}
		}
	</script>
</section><?php
include_once('../../../data/footer.php');
?>