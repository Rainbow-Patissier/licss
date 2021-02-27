<?php
if(isset($_SESSION[$session_head."user_right"])){
	switch((int)$_SESSION[$session_head."user_right"]){
		case 2:
			if(strpos($request_url,$url."store/")!==false){
				header('Location: '.$url);
				exit();
			}
			break;
		case 3:
			if(strpos($request_url,$url."manage/")!==false || strpos($request_url,$url."counter/")!==false || strpos($request_url,$url."store/")!==false){
				header('Location: '.$url);
				exit();
			}
			break;
		case 4:
			if(strpos($request_url,$url."manage/")!==false || strpos($request_url,$url."counter/")!==false || strpos($request_url,$url."store/")!==false){
				header('Location: '.$url);
				exit();
			}
			break;
	}
}else{
	if(strpos($request_url,'/not-logged/')===false){
		#ログインページ遷移
		header('Location: '.$url.'not-logged/login/?url='.SQL::encrypt_k($request_url));
		exit();
	}
}
?>