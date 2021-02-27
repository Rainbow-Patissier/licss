<?php
include_once('../../data/link.php');
$result=array();
$_POST['a']='ikeyu.brassband.trumpet@gmail.com';
if(isset($_POST['a'])){
	if(strpos($_POST['a'],'@')!==false){
		$stmt=$pdo->prepare("SELECT user_id,email,email_iv FROM account_user WHERE cond=0");
		$stmt->execute();
		$user=0;
		foreach($stmt as $row){
			if(SQL::decrypt($row['email'],$row['email_iv'])===$_POST['a']){
				$user=$row['user_id'];
				break;
			}
		}
		if($user===0){
			$result['res']=false;
		}else{
			$row=SQL::fetch("SELECT account FROM account_info WHERE cond=0 AND user_id=?",array($user));
			$account=$row['account'];
			$result['res']=true;
			$mail=$_POST['a'];
		}
	}else{
		if($row=SQL::fetch("SELECT user_id FROM account_info WHERE account=? AND cond=0",array($_POST['a']))){
			$user=$row['user_id'];
			if($row=SQL::fetch("SELECT email,email_iv FROM account_user WHERE cond=0 AND user_id=?",array($user))){
				$mail=SQL::decrypt($row['email'],$row['email_iv']);
				$result['res']=true;
				$account=$_POST['a'];
			}else{
				$result['res']=false;
			}
		}else{
			$result['res']=false;
		}
	}
	if($result['res']){
		$deadline=new DateTime();
		$deadline->modify('+10 minutes');
		$message=USER::name($user).'様<br><br>下記のURLからパスワードをリセットしてください。<br>なお，有効期限は10分間です。<br><br>アカウント名：'.$account.'<br>パスワードリセット：<a href="'.$url.'not-logged/reset/?i='.SQL::encrypt_kf($user).'&d='.SQL::encrypt_kf($deadline->format('Y-m-d H:i:s')).'">パスワードリセット用URL</a>';
		#$result['content']=$message;
		#ALL::send_html_mail($mail,"パスワードリセット",$message);
	}
}else{
	$result['res']=false;
}
header("Content-Type:application/json");
echo json_encode($result);