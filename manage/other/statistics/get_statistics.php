<?php
include_once('../../../data/link.php');
$record=array();
if(isset($_POST['k'])){
	$k=(int)SQL::decrypt_ks($_POST['k']);
	if($k===0){
		$record['res']=false;
	}else{
		$record['res']=true;
		$record['body']=array();
		switch($k){
			case 1:
				#登録年度別
				$record['head']=array('NDC別');
				$row=SQL::fetch("SELECT MIN(entry_date) as min FROM {$school_id}_manage_books_info WHERE cond=0");
				$min_date=new DateTime($row['min']);
				$stmt=$pdo->prepare("SELECT COUNT(book_id) as cnt FROM {$school_id}_manage_books_info WHERE cond=0 AND ?<=entry_date AND entry_date<=? AND ndc=?");
				$stmt2=$pdo->prepare("SELECT SUM(price) as sum FROM {$school_id}_manage_books_info WHERE cond=0 AND ?<=entry_date AND entry_date<=? AND ndc=?");
				$n=0;
				$stmt3=$pdo->prepare("SELECT ndc_id,ndc_code FROM {$school_id}_manage_setting_ndc WHERE cond=0 ORDER BY ndc_code");
				$stmt3->execute();
				foreach($stmt3 as $key=>$row){
					for($i=$year;$i>=$min_date->format('Y');--$i){
						if($key===0){
							$record['head'][]=$i.'年度';
						}
						$record['body'][$n]['number']=array($row['ndc_code']);
						$record['body'][$n]['price']=array('');
						$stmt->execute(array($i.'-01-01',$i.'-12-31',$row['ndc_id']));
						if($row2=$stmt->fetch(PDO::FETCH_ASSOC)){
							$record['body'][$n]['number'][]=$row2['cnt'].'冊';
						}
						$stmt2->execute(array($i.'-01-01',$i.'-12-31',$row['ndc_id']));
						if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
							$record['body'][$n]['price'][]=number_format($row2['sum']).'円';
						}
						++$n;
					}
				}
				#print_r($record['body'][0]);
				#print_r(array('number'=>array(0,'5冊','5冊'),'price'=>array(0,'500円','1000円')));
				#$record['body'][]=array('number'=>array(0,'5冊','5冊'),'price'=>array(0,'500円','1000円'));
				break;
			case 2:
				#NDC分類別
				$record['head']=array('NDC分類番号','冊数','金額');
				$stmt=$pdo->prepare("SELECT ndc_id,ndc_code FROM {$school_id}_manage_setting_ndc WHERE cond=0 ORDER BY ndc_code");
				$stmt->execute();
				$stmt2=$pdo->prepare("SELECT COUNT(book_id) as cnt FROM {$school_id}_manage_books_info WHERE cond=0 AND ndc=?");
				$stmt3=$pdo->prepare("SELECT SUM(price) as sum FROM {$school_id}_manage_books_info WHERE cond=0 AND ndc=?");
				foreach($stmt as $key=>$row){
					$stmt2->execute(array($row['ndc_id']));
					if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						$number=$row2['cnt'];
					}else{
						$number=0;
					}
					$stmt3->execute(array($row['ndc_id']));
					if($row3=$stmt3->fetch(PDO::FETCH_ASSOC)){
						$price=$row3['sum'];
					}else{
						$price=0;
					}
					$record['body'][$key]=array($row['ndc_code'],number_format($number).'冊',number_format($price).'円');
				}
				#$record['body'][]=array(0,10,1500);
				break;
			case 3:
				#保管場所別
				$record['head']=array('保管場所','冊数','金額');
				$stmt=$pdo->prepare("SELECT location_id,name,name_iv FROM {$school_id}_manage_setting_location WHERE cond=0 ORDER BY location_id");
				$stmt->execute();
				$stmt2=$pdo->prepare("SELECT COUNT(book_id) as cnt FROM {$school_id}_manage_books_info WHERE cond=0 AND location=?");
				$stmt3=$pdo->prepare("SELECT SUM(price) as sum FROM {$school_id}_manage_books_info WHERE cond=0 AND location=?");
				foreach($stmt as $key=>$row){
					$stmt2->execute(array($row['location_id']));
					if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						$number=$row2['cnt'];
					}else{
						$number=0;
					}
					$stmt3->execute(array($row['location_id']));
					if($row3=$stmt3->fetch(PDO::FETCH_ASSOC)){
						$price=$row3['sum'];
					}else{
						$price=0;
					}
					$record['body'][$key]=array(ALL::h(SQL::decrypt_school($row['name'],$row['name_iv'])),number_format($number).'冊',number_format($price).'円');
				}
				#$record['body'][]=array('開架',10,1500);
				break;
			case 4:
				#財源別
				$record['head']=array('財源','冊数','金額');
				$stmt=$pdo->prepare("SELECT financial_id,name,name_iv FROM {$school_id}_manage_setting_financial WHERE cond=0 ORDER BY financial_id");
				$stmt->execute();
				$stmt2=$pdo->prepare("SELECT COUNT(book_id) as cnt FROM {$school_id}_manage_books_info WHERE cond=0 AND financial=?");
				$stmt3=$pdo->prepare("SELECT SUM(price) as sum FROM {$school_id}_manage_books_info WHERE cond=0 AND financial=?");
				foreach($stmt as $key=>$row){
					$stmt2->execute(array($row['financial_id']));
					if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						$number=$row2['cnt'];
					}else{
						$number=0;
					}
					$stmt3->execute(array($row['financial_id']));
					if($row3=$stmt3->fetch(PDO::FETCH_ASSOC)){
						$price=$row3['sum'];
					}else{
						$price=0;
					}
					$record['body'][$key]=array(ALL::h(SQL::decrypt_school($row['name'],$row['name_iv'])),number_format($number).'冊',number_format($price).'円');
				}
				#$record['body'][]=array('予算',10,1500);
				break;
			case 5:
				#図書貸出数
				$record['head']=array('図書番号','図書名','回数');
				$record['body']=array();
				$stmt=$pdo->prepare("SELECT book_id,COUNT(*) as cnt FROM {$school_id}_counter_borrow GROUP BY book_id ORDER BY COUNT(*) DESC");
				$stmt->execute();
				foreach($stmt as $row){
					$record['body'][]=array(sprintf('%09d',$row['book_id']),GET::book_name($row['book_id']),$row['cnt']);
				}
				break;
			case 6:
				#生徒貸出数
				$record['head']=array('利用者番号','利用者','冊数');
				$record['body']=array();
				$stmt=$pdo->prepare("SELECT user_id,COUNT(*) as cnt FROM {$school_id}_counter_borrow GROUP BY user_id ORDER BY COUNT(*) DESC");
				$stmt->execute();
				foreach($stmt as $row){
					$record['body'][]=array(sprintf('%08d',$row['user_id']),USER::name($row['user_id']),$row['cnt']);
				}
				break;
			case 7:
				#学年貸出数
				$record['head']=array('学年','人数','冊数','一人当たり');
				$stmt=$pdo->prepare("SELECT user_id,grade FROM {$school_id}_account_class WHERE cond=0 ORDER BY grade");
				$stmt->execute();
				$stmt2=$pdo->prepare("SELECT COUNT(*) as cnt FROM {$school_id}_counter_borrow WHERE user_id=?");
				$grade_cnt=array();
				$grade_num=array();
				foreach($stmt as $row){
					$stmt2->execute(array($row['user_id']));
					if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						if(isset($grade_cnt[$row['grade']])){
							$grade_cnt[$row['grade']]+=$row2['cnt'];
							++$grade_num[$row['grade']];
						}else{
							$grade_cnt[$row['grade']]=$row2['cnt'];
							$grade_num[$row['grade']]=1;
						}
					}
				}
				$record['body']=array();
				foreach($grade_cnt as $key=>$val){
					$record['body'][]=array($key,$grade_num[$key],$val,round($val/$grade_num[$key],2));
				}
				break;
			case 8:
				#クラス貸出数
				$record['head']=array('クラス','人数','冊数','一人当たり');
				$record['body'][]=array('6-1','30人','10','3');
				$stmt=$pdo->prepare("SELECT user_id,grade,class FROM {$school_id}_account_class WHERE cond=0 ORDER BY grade");
				$stmt->execute();
				$stmt2=$pdo->prepare("SELECT COUNT(*) as cnt FROM {$school_id}_counter_borrow WHERE user_id=?");
				$grade_cnt=array();
				$grade_num=array();
				foreach($stmt as $row){
					$stmt2->execute(array($row['user_id']));
					if($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						if(isset($grade_cnt[$row['grade'].'-'.$row['class']])){
							$grade_cnt[$row['grade'].'-'.$row['class']]+=$row2['cnt'];
							++$grade_num[$row['grade'].'-'.$row['class']];
						}else{
							$grade_cnt[$row['grade'].'-'.$row['class']]=$row2['cnt'];
							$grade_num[$row['grade'].'-'.$row['class']]=1;
						}
					}
				}
				$record['body']=array();
				foreach($grade_cnt as $key=>$val){
					$record['body'][]=array($key,$grade_num[$key],$val,round($val/$grade_num[$key],2));
				}
				break;
		}
	}
}else{
	$record['res']=false;
}
header("Content-Type: application/json");
echo json_encode($record);