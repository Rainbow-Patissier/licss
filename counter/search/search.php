<?php
include_once('../../data/link.php');
$result=array();
$stmt=$pdo->prepare("SELECT * FROM {$school_id}_manage_books_info WHERE cond=0 ORDER BY book_id");
$stmt->execute();
$name=$_POST['name'];
$author=$_POST['author'];
$publisher=$_POST['publisher'];
$keyword=$_POST['keyword'];
$method=$_POST['method'];
$range=$_POST['range'];
if($method==='and'){
	foreach($stmt as $row){
		if(empty($name) || strpos($row['book_name'],$name)!==false){
			if(empty($author) || strpos($row['author1'],$author)!==false || strpos($row['author2'],$author)!==false || strpos($row['author3'],$author)!==false || strpos($row['author4'],$author)!==false){
				if(empty($publisher) || strpos($row['publisher'],$publisher)!==false){
					if(!empty($keyword)){
						foreach($row as $val){
							if(strpos($val,$keyword)!==false){
								if(!empty($row['drop_date'])){
									$borrow='廃棄済';
								}elseif(!$row['not_reserve']){
									$row2=SQL::fetch("SELECT COUNT(borrow_id) as cnt FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($row['book_id']));
									if($row2['cnt']==0){
										$borrow='貸出可';
									}else{
										$borrow='貸出中';
									}
								}else{
									$borrow='禁貸';
								}
								$result[]=array(
									sprintf('%09d',$row['book_id']),
									$row['book_name'],
									array_filter(array($row['author1'],$row['author2'],$row['author3'],$row['author4'])),
									$row['publisher'],
									$row['seikyu1'].'-'.$row['seikyu2'].'-'.$row['seikyu3'],
									GET::location($row['location']),
									$borrow,
									'<a href="'.$url.'/counter/search/view/?i='.sprintf('%09d',$row['book_id']).'" target="_blank">詳細</a>'
								);
								break;
							}
						}
					}else{
						if(!empty($row['drop_date'])){
							$borrow='廃棄済';
						}elseif(!$row['not_reserve']){
							$row2=SQL::fetch("SELECT COUNT(borrow_id) as cnt FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($row['book_id']));
							if($row2['cnt']==0){
								$borrow='貸出可';
							}else{
								$borrow='貸出中';
							}
						}else{
							$borrow='禁貸';
						}
						$result[]=array(
							sprintf('%09d',$row['book_id']),
							$row['book_name'],
							array_filter(array($row['author1'],$row['author2'],$row['author3'],$row['author4'])),
							$row['publisher'],
							$row['seikyu1'].'-'.$row['seikyu2'].'-'.$row['seikyu3'],
							GET::location($row['location']),
							$borrow,
							'<a href="'.$url.'counter/search/view/?i='.sprintf('%09d',$row['book_id']).'" target="_blank">詳細</a>'
						);
					}
				}
			}
		}
	}
}else{
	foreach($stmt as $row){
		$check=false;
		if(empty($name) || strpos($row['book_name'],$name)!==false){
			$check=true;
		}elseif(empty($author) || strpos($row['author1'],$author)!==false){
			$check=true;
		}elseif(empty($author) || strpos($row['author2'],$author)!==false){
			$check=true;
		}elseif(empty($author) || strpos($row['author3'],$author)!==false){
			$check=true;
		}elseif(empty($author) || strpos($row['author4'],$author)!==false){
			$check=true;
		}elseif(empty($publisher) || strpos($row['publisher'],$publisher)!==false){
			$check=true;
		}elseif(empty($keyword)){
			$check=true;
		}elseif(!empty($keyword)){
			foreach($row as $val){
				if(strpos($val,$keyword)!==false){
					$check=true;
					break;
				}
			}
		}
		if($check){
			if(!empty($row['drop_date'])){
				$borrow='廃棄済';
			}elseif(!$row['not_reserve']){
				$row2=SQL::fetch("SELECT COUNT(borrow_id) as cnt FROM {$school_id}_counter_borrow WHERE book_id=? AND reserve_date IS NULL",array($row['book_id']));
				if($row2['cnt']==0){
					$borrow='貸出可';
				}else{
					$borrow='貸出中';
				}
			}else{
				$borrow='禁貸';
			}
			$result[]=array(
				sprintf('%09d',$row['book_id']),
				$row['book_name'],
				array_filter(array($row['author1'],$row['author2'],$row['author3'],$row['author4'])),
				$row['publisher'],
				$row['seikyu1'].'-'.$row['seikyu2'].'-'.$row['seikyu3'],
				GET::location($row['location']),
				$borrow,
				'<a href="'.$url.'/counter/search/view/?i='.sprintf('%09d',$row['book_id']).'" target="_blank">詳細</a>'
			);
		}
	}
}

header('Content-Type: application/json');
echo json_encode($result);