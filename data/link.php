<?php
session_name('LiCSSACTIVATION');
session_start();
session_regenerate_id(true);
date_default_timezone_set('Asia/Tokyo');
/*新規class*/
class USER{
	/*
	貸出数・返却日取得
	return array(貸出数,返却日)
	*/
	function borrow_info($user_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		switch((int)USER::right($user_id)){
			case 1:
				$key='admin';
				break;
			case  2:
				$key='teacher';
				break;
			case 3:
				$key='officer';
				break;
			default:
				$key='student';
		}
		if($row=SQL::fetch("SELECT {$key}_number as number,{$key}_deadline as deadline FROM {$school_id}_manage_setting_borrow WHERE cond=0")){
			$date=new DateTime();
			$date->modify("+{$row['deadline']} days");
			return array($row['number'],$date->format('Y-m-d'));
		}else{
			return array('','');
		}
	}
	/*ユーザーメアド取得*/
	function email($user_id=0){
		if(empty($user_id)){
			$head=LINK::head();
			$user_id=$_SESSION[$head[0].'user_id'];
		}
		if($row=SQL::fetch("SELECT email,email_iv FROM account_user WHERE cond=0 AND user_id=?",array($user_id))){
			return ALL::h(SQl::decrypt($row['email'],$row['email_iv']));
		}else{
			return 'no-email@licss.i-yhp.com';
		}
	}
	/*ユーザー名前取得*/
	function name($user_id=0){
		if(empty($user_id)){
			$head=LINK::head();
			$user_id=$_SESSION[$head[0].'user_id'];
		}
		if($row=SQL::fetch("SELECT name,name_iv FROM account_user WHERE cond=0 AND user_id=?",array($user_id))){
			return ALL::h(SQl::decrypt($row['name'],$row['name_iv']));
		}else{
			return 'No Name';
		}
	}
	/*
	ユーザー所属取得
	return string
	*/
	function belong($user_id=0){
		$head=LINK::head();
		if(empty($user_id)){
			$user_id=$_SESSION[$head[0].'user_id'];
		}
		$school_id=$_SESSION[$head[0].'school_id'];
		if($row=SQL::fetch("SELECT grade,class,number FROM {$school_id}_account_class WHERE cond=0 AND user_id=? ORDER BY class_id DESC",array($user_id))){
			return ALL::h("{$row['grade']}年{$row['class']}組{$row['number']}番");
		}else{
			return '';
		}
	}
	/*ユーザー権限取得*/
	function right($user_id=0){
		$head=LINK::head();
		if(empty($user_id)){
			$user_id=$_SESSION[$head[0].'user_id'];
		}
		$school_id=$_SESSION[$head[0].'school_id'];
		if($row=SQL::fetch("SELECT user_right FROM {$school_id}_account_class WHERE cond=0 AND user_id=? ORDER BY class_id DESC",array($user_id))){
			return (int)$row['user_right'];
		}else{
			return 4;
		}
	}
}
class GET{
	/*図書情報取得*/
	function book_info($book_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		$stmt=$pdo->prepare("SELECT book_name,location,financial,store,not_reserve,drop_date FROM {$school_id}_manage_books_info WHERE book_id=? AND cond=0");
		$stmt->execute(array($book_id));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return array(ALL::h($row['book_name']),GET::location($row['location']),GET::financial($row['financial']),GET::store($row['store']),$row['not_reserve'],$row['drop_date']);
		}else{
			return array('','','','','','');
		}
	}
	/*請求記号取得*/
	function seikyu($book_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		$stmt=$pdo->prepare("SELECT seikyu1,seikyu2,seikyu3 FROM {$school_id}_manage_books_info WHERE book_id=? AND cond=0");
		$stmt->execute(array($book_id));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return ALL::h($row['seikyu1'].'-'.$row['seikyu2'].'-'.$row['seikyu3']);
		}else{
			return '';
		}
	}
	/*書名取得*/
	function book_name($book_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		$stmt=$pdo->prepare("SELECT book_name FROM {$school_id}_manage_books_info WHERE book_id=? AND cond=0");
		$stmt->execute(array($book_id));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return ALL::h($row['book_name']);
		}else{
			return 'No Book';
		}
	}
	/*保管場所取得*/
	function location($location_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		$stmt=$pdo->prepare("SELECT name,name_iv FROM {$school_id}_manage_setting_location WHERE location_id=? AND cond=0");
		$stmt->execute(array($location_id));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']));
		}else{
			return '';
		}
	}
	/*財源取得*/
	function financial($financial_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		$stmt=$pdo->prepare("SELECT name,name_iv FROM {$school_id}_manage_setting_financial WHERE financial_id=? AND cond=0");
		$stmt->execute(array($financial_id));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']));
		}else{
			return '';
		}
	}
	/*書店取得*/
	function store($store_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		$stmt=$pdo->prepare("SELECT name,name_iv FROM {$school_id}_manage_setting_store WHERE store_id=? AND cond=0");
		$stmt->execute(array($store_id));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return ALL::h(SQL::decrypt_school($row['name'],$row['name_iv']));
		}else{
			return '';
		}
	}
	/*NDC取得*/
	function ndc($ndc_id){
		$pdo=LINK::pdo();
		$head=LINK::head();
		$school_id=$_SESSION[$head[0].'school_id'];
		$stmt=$pdo->prepare("SELECT ndc_code FROM {$school_id}_manage_setting_ndc WHERE ndc_id=? AND cond=0");
		$stmt->execute(array($ndc_id));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return ALL::h($row['ndc_code']);
		}else{
			return '';
		}
	}
}
/*新規変数*/
$school_kind=array(1=>'小学校',2=>'中学校',3=>'高等学校',4=>'義務教育学校',5=>'中・高等学校',6=>'中等教育学校',7=>'特別支援学校',8=>'塾・予備校等',9=>'その他');
$user_right=array("総合管理者","管理者","教師","学校職員","一般");
$user_sex=array("秘密","男性","女性","中性");
$back_label_color=array('黒','赤','青','緑','オレンジ','茶','黄','グレー','黄緑','紫');
$back_label_color_code=array('#000000','#FF0000','#0000FF','#008000','#FF6633','#663300','#FFFF00','#808080','#00FF00','#800080');
/*変数設定*/
$url=LINK::url();
$request_url=LINK::request_url();
$pdo=LINK::pdo();
$date=new DateTime();
$Ymd=$date->format('Y-m-d');
$Ymdt=$date->format('Y-m-d H:i:s');
$year=$date->format('Y');
$next_year=$year+1;
$last_year=$year-1;
$head=LINK::head();
$session_head=$head[0];//セッション接頭語
/*ログインページへ遷移*/
#$_SESSION[$session_head.'user_id']=1000;
#session_destroy();
#print_r($_SESSION);
if(!isset($_SESSION[$session_head.'user_id'])){
	if(strpos($request_url,'/not-logged/')===false){
		#ログインページ遷移
		header('Location: '.$url.'not-logged/login/?url='.SQL::encrypt_k($request_url));
		exit();
	}
}else{
	include_once('check_access.php');
	$user_id=$_SESSION[$session_head.'user_id'];
	$school_id=$_SESSION[$session_head.'school_id'];
	$school_name=$_SESSION[$session_head.'school_name'];
	if(strpos($request_url,'/not-logged/login/')!==false){
		#ログインしている場合、ログインページへ移動不可
		header('Location: '.$url.'foruser');
		exit();
	}
}
class ALL{
	/*
	HTMLメール送信
	content=bodyの中
	*/
	static public function send_html_mail($to,$subject,$html_content){
		$url=LINK::url();
		$date=new DateTime();
		$boundary = "--".uniqid(rand(),1);
		$from_name=mb_encode_mimeheader(mb_convert_encoding("MAtTO","UTF-8"));
		$from_mail='no-reply@gw.hirodai-ok.com';
		$header="From: ".$from_name."<".$from_mail.">"." \r\n";
		$header.='Content-Type:text/html; charset="UTF-8"'." \r\n";
		$header.="Content-Transfer-Encoding: BASE64 \r\n";
		$header.="X-Mailer: PHP/".phpversion()." \r\n";
        $header.="Return-Path: postmaster@gw.hirodai-ok.com \r\n";
        $header.="Reply-To: {$from_name}<{$from_mail}> \r\n";
		$header.="Sender: {$from_mail} \r\n";
		if(empty($subject)){
			$subject="【MAtTO】";
		}else{
			$subject.="【MAtTO】";
		}
		$html_content='<!doctype html>
<html>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" href="'.$url.'data/css/normalize.css">
	<link rel="stylesheet" href="'.$url.'data/css/content.css">
	<link rel="stylesheet" href="'.$url.'data/css/font.css">
	<link rel="stylesheet" href="'.$url.'data/css/style.css">
	<link rel="stylesheet" href="'.$url.'data/css/sp.css">
	<body>'.$html_content.'
		<br><br>
		<footer>
			<div>
				&lt;本メールにお心当たりのない方&gt;<br>
				このメールはシステムより<strong>自動</strong>で送信されています。<br><br>
				※本メールにお心当たりがない場合、他者にメールアドレスを無断使用されている恐れがございます。<br>
				メールアドレスをご変更いただくことをお勧めいたします。<br>
				なお、メールアドレス変更による責任を当方は一切行いませんのでご了承ください。<br>また、お手数ですが、下記のメールアドレスまでご連絡ください。<br>
				<a href="mailto:postmaster@gw.hirodai-ok.com">MAtTO管理者</a>
			</div>
			<div>送信元：<a href="'.$url.'">MAtTO</a><br>
				送信日時：'.$date->format('Y.m.d H:i:s').'<br>
				&copy;&nbsp;<a href="'.$url.'">MAtTO</a>
			</div>
		</footer>
		<style>
			
		</style>
	</body>
</html>';
		$subject=mb_convert_encoding($subject,"UTF-8");
		$html_content=base64_encode($html_content);
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		if(mb_send_mail($to,$subject,$html_content,$header,"-f".$from_mail)){
			return true;
		}else{
			return false;
		}
	}
	/*メール送信*/
	static public function send_mail($to,$subject="",$content=""){
		$url=LINK::url();
		$from_name=mb_encode_mimeheader(mb_convert_encoding("MAtTO","UTF-8"));
		$from_mail='no-reply@gw.hirodai-ok.com';
		$header="From: {$from_name}<{$from_mail}>";
		$header.="\r\n";
		$header.="Content-Type:text/plain \r\n";
        $header.="Return-Path: postmaster@gw.hirodai-ok.com \r\n";
        $header.="Reply-To: {$from_name}<{$from_mail}> \r\n";
		if(empty($subject)){
			$subject="MAtTO";
		}else{
			$subject.="【MAtTO】";
		}
		$content.="\n\n<本メールにお心当たりのない方>\nこのメールはシステムより自動で送信されています。\n\n※本メールにお心当たりがない場合、他者にメールアドレスを無断使用されている恐れがございますのでメールアドレスをご変更いただくことをお勧めいたします。\nなお、メールアドレス変更による責任を当方は一切行いませんのでご了承ください。\nまた、お手数ですが、下記のメールアドレスまでご連絡ください。\nsupport@gw.hirodai-ok.com\n\nMAtTO： ".$url;
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");
		if(mb_send_mail($to,$subject,$content,$header)){
			return true;
		}else{
			return false;
		}
	}
	/*editor 出力*/
	static public function echo_editor($raw_data){
		if(!empty($raw_data)){
			$json_data=json_decode($raw_data,true);
			$html='';
			if(!empty($json_data['blocks'])){
				foreach($json_data['blocks'] as $data){
					switch($data['type']){
						case 'header':
							$html.='<h'.$data['data']['level'].'>'.$data['data']['text'].'</h'.$data['data']['level'].'>';
							break;
						case 'paragraph':
							$html.='<p>'.$data['data']['text'].'</p>';
							break;
						case 'list':
							if($data['data']['style']=='ordered'){
								$html.='<ol>';
							}else{
								$html.='<ul>';
							}
							foreach($data['data']['items'] as $val){
								$html.='<li>'.$val.'</li>';
							}
							if($data['data']['style']=='ordered'){
								$html.='</ol>';
							}else{
								$html.='</ul>';
							}
							break;
						case 'quote':
							$html.='<div class="quote_wrapper"><div class="quote_area '.$data['data']['alignment'].'">'.$data['data']['text'].'</div><div class="quote_caption_area">'.$data['data']['caption'].'</div></div>';
							break;
						case 'code':
							$html.='<div class="code_area"><code>'.ALL::h($data['data']['code']).'</code></div>';
							break;
						case 'table':
							$html.='<table><tbody>';
							foreach($data['data']['content'] as $row){
								$html.='<tr>';
								foreach($row as $val){
									$html.='<td>'.$val.'</td>';
								}
								$html.='</tr>';
							}
							$html.='</tbody></table>';
							break;
						case 'raw':
							$txt=$data['data']['html'];
							$txt=str_replace('<script>',ALL::h('<script>'),$txt);
							$txt=str_replace('</script>',ALL::h('</script>'),$txt);
							$txt=str_replace('<?php',ALL::h('<?php'),$txt);
							$html.=$txt;
							break;
						case 'delimiter':
							$html.='<hr>';
							break;
						case 'warning':
							$html.='<div class="warning_area"><div class="warning_title_area">'.$data['data']['title'].'</div><div class="warning_message_area">'.$data['data']['message'].'</div></div>';
							break;
					}
				}	
			}
			return $html;
		}
	}
	/*
	電話番号-つける
	@return phone_number(str)
	*/
	static public function format_phone_number($input, $strict = false) {
		$groups = array(
			5 => 
			array (
				'01564' => 1,
				'01558' => 1,
				'01586' => 1,
				'01587' => 1,
				'01634' => 1,
				'01632' => 1,
				'01547' => 1,
				'05769' => 1,
				'04992' => 1,
				'04994' => 1,
				'01456' => 1,
				'01457' => 1,
				'01466' => 1,
				'01635' => 1,
				'09496' => 1,
				'08477' => 1,
				'08512' => 1,
				'08396' => 1,
				'08388' => 1,
				'08387' => 1,
				'08514' => 1,
				'07468' => 1,
				'01655' => 1,
				'01648' => 1,
				'01656' => 1,
				'01658' => 1,
				'05979' => 1,
				'04996' => 1,
				'01654' => 1,
				'01372' => 1,
				'01374' => 1,
				'09969' => 1,
				'09802' => 1,
				'09912' => 1,
				'09913' => 1,
				'01398' => 1,
				'01377' => 1,
				'01267' => 1,
				'04998' => 1,
				'01397' => 1,
				'01392' => 1,
			),
			4 => 
			array (
				'0768' => 2,
				'0770' => 2,
				'0772' => 2,
				'0774' => 2,
				'0773' => 2,
				'0767' => 2,
				'0771' => 2,
				'0765' => 2,
				'0748' => 2,
				'0747' => 2,
				'0746' => 2,
				'0826' => 2,
				'0749' => 2,
				'0776' => 2,
				'0763' => 2,
				'0761' => 2,
				'0766' => 2,
				'0778' => 2,
				'0824' => 2,
				'0797' => 2,
				'0796' => 2,
				'0555' => 2,
				'0823' => 2,
				'0798' => 2,
				'0554' => 2,
				'0820' => 2,
				'0795' => 2,
				'0556' => 2,
				'0791' => 2,
				'0790' => 2,
				'0779' => 2,
				'0558' => 2,
				'0745' => 2,
				'0794' => 2,
				'0557' => 2,
				'0799' => 2,
				'0738' => 2,
				'0567' => 2,
				'0568' => 2,
				'0585' => 2,
				'0586' => 2,
				'0566' => 2,
				'0564' => 2,
				'0565' => 2,
				'0587' => 2,
				'0584' => 2,
				'0581' => 2,
				'0572' => 2,
				'0574' => 2,
				'0573' => 2,
				'0575' => 2,
				'0576' => 2,
				'0578' => 2,
				'0577' => 2,
				'0569' => 2,
				'0594' => 2,
				'0827' => 2,
				'0736' => 2,
				'0735' => 2,
				'0725' => 2,
				'0737' => 2,
				'0739' => 2,
				'0743' => 2,
				'0742' => 2,
				'0740' => 2,
				'0721' => 2,
				'0599' => 2,
				'0561' => 2,
				'0562' => 2,
				'0563' => 2,
				'0595' => 2,
				'0596' => 2,
				'0598' => 2,
				'0597' => 2,
				'0744' => 2,
				'0852' => 2,
				'0956' => 2,
				'0955' => 2,
				'0954' => 2,
				'0952' => 2,
				'0957' => 2,
				'0959' => 2,
				'0966' => 2,
				'0965' => 2,
				'0964' => 2,
				'0950' => 2,
				'0949' => 2,
				'0942' => 2,
				'0940' => 2,
				'0930' => 2,
				'0943' => 2,
				'0944' => 2,
				'0948' => 2,
				'0947' => 2,
				'0946' => 2,
				'0967' => 2,
				'0968' => 2,
				'0987' => 2,
				'0986' => 2,
				'0985' => 2,
				'0984' => 2,
				'0993' => 2,
				'0994' => 2,
				'0997' => 2,
				'0996' => 2,
				'0995' => 2,
				'0983' => 2,
				'0982' => 2,
				'0973' => 2,
				'0972' => 2,
				'0969' => 2,
				'0974' => 2,
				'0977' => 2,
				'0980' => 2,
				'0979' => 2,
				'0978' => 2,
				'0920' => 2,
				'0898' => 2,
				'0855' => 2,
				'0854' => 2,
				'0853' => 2,
				'0553' => 2,
				'0856' => 2,
				'0857' => 2,
				'0863' => 2,
				'0859' => 2,
				'0858' => 2,
				'0848' => 2,
				'0847' => 2,
				'0835' => 2,
				'0834' => 2,
				'0833' => 2,
				'0836' => 2,
				'0837' => 2,
				'0846' => 2,
				'0845' => 2,
				'0838' => 2,
				'0865' => 2,
				'0866' => 2,
				'0892' => 2,
				'0889' => 2,
				'0887' => 2,
				'0893' => 2,
				'0894' => 2,
				'0897' => 2,
				'0896' => 2,
				'0895' => 2,
				'0885' => 2,
				'0884' => 2,
				'0869' => 2,
				'0868' => 2,
				'0867' => 2,
				'0875' => 2,
				'0877' => 2,
				'0883' => 2,
				'0880' => 2,
				'0879' => 2,
				'0829' => 2,
				'0550' => 2,
				'0228' => 2,
				'0226' => 2,
				'0225' => 2,
				'0224' => 2,
				'0229' => 2,
				'0233' => 2,
				'0237' => 2,
				'0235' => 2,
				'0234' => 2,
				'0223' => 2,
				'0220' => 2,
				'0192' => 2,
				'0191' => 2,
				'0187' => 2,
				'0193' => 2,
				'0194' => 2,
				'0198' => 2,
				'0197' => 2,
				'0195' => 2,
				'0238' => 2,
				'0240' => 2,
				'0260' => 2,
				'0259' => 2,
				'0258' => 2,
				'0257' => 2,
				'0261' => 2,
				'0263' => 2,
				'0266' => 2,
				'0265' => 2,
				'0264' => 2,
				'0256' => 2,
				'0255' => 2,
				'0243' => 2,
				'0242' => 2,
				'0241' => 2,
				'0244' => 2,
				'0246' => 2,
				'0254' => 2,
				'0248' => 2,
				'0247' => 2,
				'0186' => 2,
				'0185' => 2,
				'0144' => 2,
				'0143' => 2,
				'0142' => 2,
				'0139' => 2,
				'0145' => 2,
				'0146' => 2,
				'0154' => 2,
				'0153' => 2,
				'0152' => 2,
				'0138' => 2,
				'0137' => 2,
				'0125' => 2,
				'0124' => 2,
				'0123' => 2,
				'0126' => 2,
				'0133' => 2,
				'0136' => 2,
				'0135' => 2,
				'0134' => 2,
				'0155' => 2,
				'0156' => 2,
				'0176' => 2,
				'0175' => 2,
				'0174' => 2,
				'0178' => 2,
				'0179' => 2,
				'0184' => 2,
				'0183' => 2,
				'0182' => 2,
				'0173' => 2,
				'0172' => 2,
				'0162' => 2,
				'0158' => 2,
				'0157' => 2,
				'0163' => 2,
				'0164' => 2,
				'0167' => 2,
				'0166' => 2,
				'0165' => 2,
				'0267' => 2,
				'0250' => 2,
				'0533' => 2,
				'0422' => 2,
				'0532' => 2,
				'0531' => 2,
				'0436' => 2,
				'0428' => 2,
				'0536' => 2,
				'0299' => 2,
				'0294' => 2,
				'0293' => 2,
				'0475' => 2,
				'0295' => 2,
				'0297' => 2,
				'0296' => 2,
				'0495' => 2,
				'0438' => 2,
				'0466' => 2,
				'0465' => 2,
				'0467' => 2,
				'0478' => 2,
				'0476' => 2,
				'0470' => 2,
				'0463' => 2,
				'0479' => 2,
				'0493' => 2,
				'0494' => 2,
				'0439' => 2,
				'0268' => 2,
				'0480' => 2,
				'0460' => 2,
				'0538' => 2,
				'0537' => 2,
				'0539' => 2,
				'0279' => 2,
				'0548' => 2,
				'0280' => 2,
				'0282' => 2,
				'0278' => 2,
				'0277' => 2,
				'0269' => 2,
				'0270' => 2,
				'0274' => 2,
				'0276' => 2,
				'0283' => 2,
				'0551' => 2,
				'0289' => 2,
				'0287' => 2,
				'0547' => 2,
				'0288' => 2,
				'0544' => 2,
				'0545' => 2,
				'0284' => 2,
				'0291' => 2,
				'0285' => 2,
				'0120' => 3,
				'0570' => 3,
				'0800' => 3,
				'0990' => 3,
			),
			3 => 
			array (
				'099' => 3,
				'054' => 3,
				'058' => 3,
				'098' => 3,
				'095' => 3,
				'097' => 3,
				'052' => 3,
				'053' => 3,
				'011' => 3,
				'096' => 3,
				'049' => 3,
				'015' => 3,
				'048' => 3,
				'072' => 3,
				'084' => 3,
				'028' => 3,
				'024' => 3,
				'076' => 3,
				'023' => 3,
				'047' => 3,
				'029' => 3,
				'075' => 3,
				'025' => 3,
				'055' => 3,
				'026' => 3,
				'079' => 3,
				'082' => 3,
				'027' => 3,
				'078' => 3,
				'077' => 3,
				'083' => 3,
				'022' => 3,
				'086' => 3,
				'089' => 3,
				'045' => 3,
				'044' => 3,
				'092' => 3,
				'046' => 3,
				'017' => 3,
				'093' => 3,
				'059' => 3,
				'073' => 3,
				'019' => 3,
				'087' => 3,
				'042' => 3,
				'018' => 3,
				'043' => 3,
				'088' => 3,
				'050' => 4,
			),
			2 => 
			array (
				'04' => 4,
				'03' => 4,
				'06' => 4,
			),
		);
		$groups[3] += 
			$strict ?
			array(
				'020' => 3,
				'070' => 3,
				'080' => 3,
				'090' => 3,
			) :
			array(
				'020' => 4,
				'070' => 4,
				'080' => 4,
				'090' => 4,
			)
		;
		$number = preg_replace('/[^\d]++/', '', $input);
		foreach ($groups as $len => $group) {
			$area = substr($number, 0, $len);
			if (isset($group[$area])) {
				$formatted = implode('-', array(
					$area,
					substr($number, $len, $group[$area]),
					substr($number, $len + $group[$area])
				));
				return strrchr($formatted, '-') !== '-' ? $formatted : $input;
			}
		}
		$pattern = '/\A(00(?:[013-8]|2\d|91[02-9])\d)(\d++)\z/';
		if (preg_match($pattern, $number, $matches)) {
			return $matches[1] . '-' . $matches[2];
		}
		return $input;
	}
	/*
	文字コード調整
	@return string
	*/
	static public function string_code($string){
		return mb_convert_encoding($string, 'UTF-8',"ASCII,JIS,UTF-8,EUC-JP,SJIS");
	}
	/*
	csrf対策　トークン生成
	*/
	static public function csrf_token($name=''){
		$head=LINK::head();
		$session_head=$head[0];//セッション接頭語
        $token=bin2hex(openssl_random_pseudo_bytes(15,$cstrong));
        while(!$cstrong){
            $token=bin2hex(openssl_random_pseudo_bytes(15,$cstrong));
        }
        $_SESSION[$session_head.$name.'_DgTfc_csrf']=$token;
        return $token;
	}
	/*
	csrf対策　トークン確認
	@return boolean
	true is ok
	false is csrf?
	*/
	static public function csrf_check($token,$name=''){
		$head=LINK::head();
		$session_head=$head[0];//セッション接頭語
		if($token===$_SESSION[$session_head.$name.'_DgTfc_csrf']){
			#$_SESSION[$session_head.'DgTfc_csrf']="";#トークンのリセット
			return true;
		}else{
			#$_SESSION[$session_head.'DgTfc_csrf']="";#トークンのリセット
			return false;
		}
	}
	/*
	htmlchars
	@return String
	*/
	static public function h($text){
		return nl2br(str_replace('&lt;br&gt;','<br>',htmlspecialchars($text,ENT_QUOTES,'UTF-8')));
	}
	/*
	ページ遷移アラート
	@return string
	*/
	static public function remove_alert(){
		?>
        <script>
            window.onbeforeunload = function(e){
                return "このページを離れますか？"; // Google Chrome以外
                e.returnValue = "このページを離れますか？"; // Google Chrome
            }
        </script><?php
	}
	/*
	データドロップスクリプト
	@return string
	div->id="file_area"
    input->type="file"name="file"id="file"title="ファイルを選択してください"
	<div id="file_area"><input type="file" name="file" title="ファイルを選択してください" id="file"></div>
	*/
	static public function drop_script(){
		?>
            <script>
                /*データドロップ*/
                var fileArea = document.getElementById('file_area');
                var fileInput = document.getElementById('file');
                fileArea.addEventListener('dragover', function(evt){
                  evt.preventDefault();
                  fileArea.classList.add('dragover');
                });
                fileArea.addEventListener('dragleave', function(evt){
                    evt.preventDefault();
                    fileArea.classList.remove('dragover');
                });
                fileArea.addEventListener('drop', function(evt){
                    evt.preventDefault();
                    fileArea.classList.remove('dragenter');
                    var files = evt.dataTransfer.files;
                    fileInput.files = files;
                });
            </script><?php
	}
}
class LINK{
	/*
	絶対パス
	@return string
	*/
	static public function path_public(){
		if(isset($_SERVER['HTTP_HOST'])){
			if($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='192.168.11.7'){
				$path="CC:\xampp\htdocs\licss";
			}else{
				$path='/virtual/yhp/public_html/';
			}
		}else{
			$path='/virtual/yhp/public_html/';
		}
		return $path;
	}
	/*
	絶対パス
	@return string
	*/
	static public function path(){
		if(isset($_SERVER['HTTP_HOST'])){
			if($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='192.168.11.7'){
				$path="C:/xampp/htdocs/data/licss/";
			}else{
				$path='/virtual/yhp/data/licss/';
			}
		}else{
			$path='/virtual/yhp/data/licss/';
		}
		return $path;
	}
	/*
	接頭語
	@return array(session,table)
	*/
	public static function head(){
		return array('licss_','');
	}
	/*
	@return URL(string)
	*/
	public static function url(){
		if($_SERVER['HTTP_HOST']=='localhost'){
			return "http://localhost/licss/";
		}else{
			return "https://licsss.com/";
		}
	}
	public static function request_url(){
		if(strpos($_SERVER['SERVER_PROTOCOL'],"2")!==FALSE){
			return "https://licsss.com".$_SERVER['REQUEST_URI'];
		}else{
			return "http://localhost".$_SERVER['REQUEST_URI'];
		}
	}
	/*
	パスワード等json取得
	@return array
	*/
	static public function get_pwd_data($filename=''){
		/*データ保存パス*/
		if(!defined('DATA_DIR')){
			define('DATA_DIR',LINK::path());
		}
		if(empty($filename)){
			$file = DATA_DIR."property.json";
		}else{
			$file=DATA_DIR.$filename.'.json';
		}
		$json = file_get_contents($file);
		$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		return json_decode($json, true);
	}
	/*
	@return pdo(string)
	*/
	public static function pdo(){
		$data=LINK::get_pwd_data();
		$dbname=$data['database']['dbname'];
		$uname=$data['database']['uname'];
		$upass=$data['database']['upass'];
        $dbn="mysql:dbname={$dbname};host=localhost:3306;charset=utf8";
        try{
            $pdo=new PDO($dbn,$uname,$upass);
        }
        catch(PDOException $e){
            exit('接続に失敗しました'.$e->getMessage());
        }
        return $pdo;
	}
}
class SQL{
	static public function execute($sql,$execute=array()){
		$pdo=LINK::PDO();
		$stmt=$pdo->prepare($sql);
        return $stmt->execute($execute);
	}
	static public function fetch($sql,$execute=array()){
		$pdo=LINK::PDO();
		$stmt=$pdo->prepare($sql);
		if(is_array($execute)){
			$stmt->execute($execute);
		}else{
			$stmt->execute((array)$execute);
		}
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			return $row;
		}else{
			return '';
		}
	}
	static public function fetch_all($sql,$execute=array()){
		$pdo=LINK::PDO();
		$stmt=$pdo->prepare($sql);
		if(is_array($execute)){
			$stmt->execute($execute);
		}else{
			$stmt->execute((array)$execute);
		}
		if($row=$stmt->fetchALL(PDO::FETCH_ASSOC)){
			return $row;
		}else{
			return '';
		}
	}
    static public function encrypt_school($text){
        //https://pgmemo.tokyo/data/archives/1118.html
		$head=LINK::head();
		$data=LINK::get_pwd_data($_SESSION[$head[0].'school_id']);
		$password=$data['encrypt']['password'];
        $method=$data['encrypt']['method'];
		$options = 0;
        $iv_length = openssl_cipher_iv_length($method);
        $iv=openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($text, $method, $password, $options, $iv);
        $iv = bin2hex($iv);
        return array($encrypted,$iv);
    }
    static public function decrypt_school($text,$iv=''){
        //https://pgmemo.tokyo/data/archives/1118.html
        if(empty($iv)){
            return $text;
        }else{
			$head=LINK::head();
			$data=LINK::get_pwd_data($_SESSION[$head[0].'school_id']);
			$password=$data['encrypt']['password'];
			$method=$data['encrypt']['method'];
            $options = 0;
            $iv=hex2bin($iv);
            $decrypted = openssl_decrypt($text, $method, $password, $options, $iv);
            return $decrypted;
        }
    }
    static public function encrypt($text){
        //https://pgmemo.tokyo/data/archives/1118.html
		$data=LINK::get_pwd_data();
		$password=$data['encrypt']['password'];
        $method=$data['encrypt']['method'];
		$options = 0;
        $iv_length = openssl_cipher_iv_length($method);
        $iv=openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($text, $method, $password, $options, $iv);
        $iv = bin2hex($iv);
        return array($encrypted,$iv);
    }
    static public function decrypt($text,$iv=''){
        //https://pgmemo.tokyo/data/archives/1118.html
        if(empty($iv)){
            return $text;
        }else{
			$data=LINK::get_pwd_data();
			$password=$data['encrypt']['password'];
			$method=$data['encrypt']['method'];
            $options = 0;
            $iv=hex2bin($iv);
            $decrypted = openssl_decrypt($text, $method, $password, $options, $iv);
            return $decrypted;
        }
    }
    static public function encrypt_k($data){
        //https://pgmemo.tokyo/data/archives/1118.html
		$head=LINK::head();
        $method = 'AES-256-CTR';
		$session_head=$head[0];//セッション接頭語
		if(isset($_SESSION[$session_head.'J7k8aVEs_p']) && isset($_SESSION[$session_head.'e7SaAQfc_v'])){
			$password=$_SESSION[$session_head.'J7k8aVEs_p'];
			$iv=hex2bin($_SESSION[$session_head.'e7SaAQfc_v']);
		}else{
			$password=bin2hex(openssl_random_pseudo_bytes(30,$cstrong));
			while(!$cstrong){
				$password=bin2hex(openssl_random_pseudo_bytes(30,$cstrong));
			}
			$options = 0;
			$iv_length = openssl_cipher_iv_length($method);
			$iv=openssl_random_pseudo_bytes($iv_length);
			$_SESSION[$session_head.'J7k8aVEs_p']=$password;
			$_SESSION[$session_head.'e7SaAQfc_v']=bin2hex($iv);
		}
        $options = 0;
        $encrypted = openssl_encrypt($data, $method, $password, $options, $iv);
        for($i=0;$i<=3;++$i){
            $encrypted = openssl_encrypt($encrypted, $method, $password, $options, $iv);
        }
		if(!$encrypted){
			SQL::encrypt_k($data);
		}
        return str_replace('+','@',$encrypted);
    }
    static public function decrypt_k($data){
        //https://pgmemo.tokyo/data/archives/1118.html
		$head=LINK::head();
		$session_head=$head[0];//セッション接頭語
		if(isset($_SESSION[$session_head.'J7k8aVEs_p']) && isset($_SESSION[$session_head.'e7SaAQfc_v'])){
			$method = 'AES-256-CTR';
			$data=str_replace('@','+',$data);
			$password=$_SESSION[$session_head.'J7k8aVEs_p'];
			$iv=hex2bin($_SESSION[$session_head.'e7SaAQfc_v']);
			$options = 0;
			$decrypted = openssl_decrypt($data, $method, $password, $options, $iv);
			for($i=0;$i<=3;++$i){
				$decrypted = openssl_decrypt($decrypted, $method, $password, $options, $iv);
			}
			return $decrypted;
		}
    }
    static public function encrypt_kf($data){
        //https://pgmemo.tokyo/data/archives/1118.html
        $property=LINK::get_pwd_data();
        $iv=hex2bin($property['encrypt_f']['iv']);
        $password=$property['encrypt_f']['password'];
		$method=$property['encrypt_f']['method'];
        $options = 0;
        $encrypted = openssl_encrypt($data, $method, $password, $options, $iv);
        for($i=0;$i<=3;++$i){
            $encrypted = openssl_encrypt($encrypted, $method, $password, $options, $iv);
        }
        return str_replace('+','@',$encrypted);
    }
    static public function decrypt_kf($data){
        //https://pgmemo.tokyo/data/archives/1118.html
        $data=str_replace('@','+',$data);
        $property=LINK::get_pwd_data();
        $iv=hex2bin($property['encrypt_f']['iv']);
        $password=$property['encrypt_f']['password'];
		$method=$property['encrypt_f']['method'];
        $options = 0;
        $decrypted = openssl_decrypt($data, $method, $password, $options, $iv);
        for($i=0;$i<=3;++$i){
            $decrypted = openssl_decrypt($decrypted, $method, $password, $options, $iv);
        }
        return $decrypted;
    }
    static public function encrypt_kfs($data){
        //https://pgmemo.tokyo/data/archives/1118.html
        $property=LINK::get_pwd_data();
        $iv=hex2bin($property['encrypt_fs']['iv']);
        $password=$property['encrypt_fs']['password'];
		$method=$property['encrypt_fs']['method'];
        $options = 0;
        $encrypted = openssl_encrypt($data, $method, $password, $options, $iv);
        return str_replace('+','@',$encrypted);
    }
    static public function decrypt_kfs($data){
        //https://pgmemo.tokyo/data/archives/1118.html
        $data=str_replace('@','+',$data);
        $property=LINK::get_pwd_data();
        $iv=hex2bin($property['encrypt_fs']['iv']);
        $password=$property['encrypt_fs']['password'];
		$method=$property['encrypt_fs']['method'];
        $options = 0;
        $decrypted = openssl_decrypt($data, $method, $password, $options, $iv);
        return $decrypted;
    }
    static public function encrypt_ks($data){
        //https://pgmemo.tokyo/data/archives/1118.html
        $head=LINK::head();
        $method = 'AES-256-CTR';
		$session_head=$head[0];//セッション接頭語
		if(isset($_SESSION[$session_head.'J7k8aVEs_p']) && isset($_SESSION[$session_head.'e7SaAQfc_v'])){
			$password=$_SESSION[$session_head.'J7k8aVEs_p'];
			$iv=hex2bin($_SESSION[$session_head.'e7SaAQfc_v']);
		}else{
			$password=bin2hex(openssl_random_pseudo_bytes(30,$cstrong));
			while(!$cstrong){
				$password=bin2hex(openssl_random_pseudo_bytes(30,$cstrong));
			}
			$options = 0;
			$iv_length = openssl_cipher_iv_length($method);
			$iv=openssl_random_pseudo_bytes($iv_length);
			$_SESSION[$session_head.'J7k8aVEs_p']=$password;
			$_SESSION[$session_head.'e7SaAQfc_v']=bin2hex($iv);
		}
        $options = 0;
        $encrypted = openssl_encrypt($data, $method, $password, $options, $iv);
        return str_replace('+','@',$encrypted);
    }
    static public function decrypt_ks($data){
        //https://pgmemo.tokyo/data/archives/1118.html
        $head=LINK::head();
		$session_head=$head[0];//セッション接頭語
		if(isset($_SESSION[$session_head.'J7k8aVEs_p']) && isset($_SESSION[$session_head.'e7SaAQfc_v'])){
			$method = 'AES-256-CTR';
			$data=str_replace('@','+',$data);
			$password=$_SESSION[$session_head.'J7k8aVEs_p'];
			$iv=hex2bin($_SESSION[$session_head.'e7SaAQfc_v']);
			$options = 0;
			$decrypted = openssl_decrypt($data, $method, $password, $options, $iv);
			return $decrypted;
		}
    }
}
/*データ保存パス*/
if(!defined('DATA_DIR')){
    define('DATA_DIR',LINK::path());
}
/*地方・都道府県・市区町村*/
include_once('area.php');
?>