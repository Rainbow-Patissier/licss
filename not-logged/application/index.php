<?php
include_once('../../data/link.php');
$title="利用申請";
include_once('../../data/not_logged_header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>">
				LiCSS
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
	$secret_text='application';
	if(!isset($_POST['btn'])){
		?>
	<form method="post">
		<div id="application_explanation">
			<p>
				この度は，LiCSS-図書館管理システムのご利用についてご検討をしていただき，大変感謝申し上げます。
			</p>
			<p>
				LiCSSを利用するためには，まず担当者が利用申請を行うことが必要となります。利用申請前にいくつかの注意点がございますのでその点を理解した上で利用申請をするようにお願い申し上げます。
			</p>
			<ol>
				<li>
					制作は個人であり，プログラマーを生業としていない者である
				</li>
				<li>
					セキュリティには万全を期しているが，明示的にも黙示的にも安全を保証していない
				</li>
				<li>
					情報漏洩が発生したとしても賠償をすることは不可能である（個人で運営しており，賠償できる財力がないため）
				</li>
				<li>
					システムにバグや不具合などが発生したとしても即座の対応が不可能な場合がある
				</li>
			</ol>
			<p>
				以上4点の注意事項を理解した上で利用申請を続けてください。<br>
				利用申請を続けますか？<br>
				<button type="button" onClick="check_continue_application(1);">
					続ける
				</button>
				<button type="button" onClick="check_continue_application(2);">
					中断する
				</button>
			</p>	
		</div>
		<div id="application_terms">
			<p>
				ご利用申請前に利用規約およびプライバシーポリシーに同意していただく必要がございます。また，申請者が同意をすることでLiCSSに登録される全ユーザーは同意されているものとみなします。
			</p>
			<div id="terms_wrapper">
				<div id="terms_of_use" class="scroll">
					<iframe src="<?=$url;?>data/term.pdf" frameborder="0" class="term_iframe"></iframe>
					<label>
						<input type="checkbox" name="terms_of_use" id="application_terms_of_use" value="accepted" onChange="check_accept_terms();">
						同意する
					</label>
				</div>
				<div id="privacy_policy" class="scroll">
					<iframe src="<?=$url;?>data/privacypolicy.pdf" frameborder="0" class="term_iframe"></iframe>
					<label>
						<input type="checkbox" name="privacy_policy" id="application_privacy_policy" value="accepted" onChange="check_accept_terms();">
						同意する
					</label>
				</div>
			</div>
		</div>
		<div id="application_accepted">
			<p>
				ご利用開始前に初期設定を行っていただきます。*は必須項目です。
			</p>
			<h2>
				学校に関わる項目
			</h2>
			<dl class="form_style" id="application_form_element">
				<dt>
					学校名*
				</dt>
				<dd>
					<input type="text" name="school_name" class="school_name_input" placeholder="学校名" title="学校名を入力してください" required>
					<select name="school_kind" title="校種を選択してください" required>
						<option label="選択してください"></option>
						<option value="<?=SQL::encrypt_ks(1);?>">小学校</option>
						<option value="<?=SQL::encrypt_ks(2);?>">中学校</option>
						<option value="<?=SQL::encrypt_ks(3);?>">高等学校</option>
						<option value="<?=SQL::encrypt_ks(4);?>">義務教育学校</option>
						<option value="<?=SQL::encrypt_ks(5);?>">中・高等学校</option>
						<option value="<?=SQL::encrypt_ks(6);?>">中等教育学校</option>
						<option value="<?=SQL::encrypt_ks(7);?>">特別支援学校</option>
						<option value="<?=SQL::encrypt_ks(8);?>">塾・予備校等</option>
						<option value="<?=SQL::encrypt_ks(9);?>">その他</option>
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
				?>
							<option value="<?=$code;?>" title="<?=$val;?>">
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
						<option label="選択してください"></option>
					</select>
					<input type="text" name="address" class="address_input" placeholder="町名以下" title="町名以下を入力してください" required>
				</dd>
				<dt>
					学校ホームページURL
				</dt>
				<dd>
					<input type="url" name="school_url" placeholder="学校ホームページ" title="学校ホームページのURLを入力してください"><br>
					http://もしくはhttps://から始まるURLを入力してください
				</dd>
				<dt>
					学校イメージカラー*
				</dt>
				<dd>
					<input type="color" name="school_color" title="学校イメージカラーを選択してください" required>
				</dd>
				<dt>
					学校電話番号*
				</dt>
				<dd>
					<input type="text" name="school_phone" placeholder="学校電話番号" title="学校電話番号を入力してください" pattern="[0-9]+" required><br>
					-（ハイフン）はなしで入力してください
				</dd>
				<dt>
					図書室直通電話番号
				</dt>
				<dd>
					<input type="text" name="library_phone" placeholder="図書室直通電話番号" title="図書室直通電話番号を入力してください" pattern="[0-9]+"><br>
					-（ハイフン）はなしで入力してください（直通電話番号がない場合は空欄）
				</dd>
				<dt>
					担当者名*
				</dt>
				<dd>
					<input type="text" name="person_name" placeholder="担当者名" title="担当者名を入力してください" onChange="document.getElementById('application_name').value=this.value;" required>
				</dd>
				<dt>
					担当者メールアドレス*
				</dt>
				<dd>
					<input type="email" name="person_email" placeholder="担当者メールアドレス" title="担当者メールアドレスを入力してください" required><br>
					※個人メールアドレス(学校から与えられている場合)がない場合，学校のメールアドレスで可<br>
					※仕事用のメールアドレスのことであり，担当者が個人で使っているメールアドレスではない
				</dd>
			</dl>
			<h2>
				申請者に関わる項目
			</h2>
			学校に関わる設定は以上です。<br>
			次に申請者自身のアカウントを作成します。
			<dl class="form_style">
				<dt>
					名前*
				</dt>
				<dd>
					<input type="text" name="name" id="application_name" placeholder="名前" title="名前を入力してください" required>
				</dd>
				<dt>
					生年月日*
				</dt>
				<dd>
					<input type="date" name="birthday" placeholder="誕生日" title="誕生日を入力してください" value="1980-01-01" required>
				</dd>
				<dt>
					性別*
				</dt>
				<dd>
					<select name="sex" title="性別を選択してください" required>
						<option label="選択してください"></option>
						<option value="<?=SQL::encrypt_ks(1);?>">男性</option>
						<option value="<?=SQL::encrypt_ks(2);?>">女性</option>
						<option value="<?=SQL::encrypt_ks(3);?>">中性</option>
						<option value="<?=SQL::encrypt_ks(0);?>">秘密</option>
					</select>
				</dd>
				<dt>
					個人メールアドレス
				</dt>
				<dd>
					<input type="email" name="email" placeholder="個人メールアドレス" title="個人メールアドレスを入力してください"><br>
					※システムからの連絡等で利用しますが，仕事用のメールアドレスでも可
				</dd>
				<dt>
					アカウント名*
				</dt>
				<dd>
					<input type="text" name="account" id="application_account" placeholder="アカウント名" title="アカウント名を入力してください" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]+$" required>
					<span id="application_account_message" class="red"></span><br>
					※ログイン時に利用<br>
					※半角英数字のみ
				</dd>
				<dt>
					パスワード*
				</dt>
				<dd>
					<input type="password" name="password" id="application_password" placeholder="パスワード" title="※12文字以上かつ1文字以上の数字・小文字アルファベット・大文字アルファベット・記号がそれぞれ含まれているパスワードを入力してください" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[!-/:-@[-`{-~]).{12,}" autocomplete="new-password" required><br>
					※12文字以上かつ1文字以上の数字・小文字アルファベット・大文字アルファベット・記号がそれぞれ含まれていること
				</dd>
				<dt>
					確認用パスワード*
				</dt>
				<dd>
					<input type="password" name="re_password" id="application_re_passowrd" placeholder="確認用パスワード" title="確認用パスワードを入力してください" required><br>
					<span id="application_password_message" class="red"></span>
				</dd>
			</dl>
			<input type="hidden" name="token" value="<?=ALL::csrf_token($secret_text);?>">
			<button type="submit" name="btn" value="<?=SQL::encrypt_ks($secret_text.'_send');?>" onClick="return check_password();">
				登録
			</button>
		</div>
		<script>
			document.getElementById('application_account').oninput=function(){
				application_check_account(document.getElementById('application_account').value);
			}
			function check_continue_application(value){
				var explanation=document.getElementById('application_explanation');
				explanation.style.opacity='0';
				if(value===1){
					//続ける
					var terms=document.getElementById('application_terms').style;
					explanation.style.display='none';
					terms.opacity='1';
					terms.height='900px';
				}else{
					//続けない
					setTimeout(function(){
						explanation.innerHTML='<p>この度は，LiCSSのご利用についてのご検討をありがとうございました。</p><p>利用申請を中断されたということでご利用についてご希望に添えない点がございましたことについてお詫び申し上げます。</p><p>再度ご利用についてご検討いただけますと幸いでございます。</p><p>今後ともLiCSSをよろしくお願いいたします。</p>';
						explanation.style.opacity='1';
					},500);
				}
			}
			function check_accept_terms(){
				var terms=document.getElementById('application_terms_of_use');
				var privacy=document.getElementById('application_privacy_policy');
				var accepted=document.getElementById('application_accepted').style;
				if(terms.checked && privacy.checked){
					accepted.display='block';
					accepted.height='auto';
					accepted.opacity='1';
					var href= "#application_accepted";
					var target = $(href == "#" || href == "" ? 'html' : href);
					var position = target.offset().top;
					$("html, body").animate({scrollTop:position}, 1000, "swing");
				}else{
					accepted.opacity='0';
					accepted.height='0px';
					setTimeout(function(){
						accepted.display='none';
					},500);
				}
			}
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
			function check_password(){
				alert_cancel();
				if(document.getElementById('application_password').value===document.getElementById('application_re_passowrd').value && document.getElementById('application_account_message').innerHTML===''){
					document.getElementById('application_password_message').innerHTML='';
					return true;
				}else{
					document.getElementById('application_password_message').innerHTML='パスワードが一致しません';
					return false;
				}
			}
		</script>
	</form><?php
		ALL::remove_alert();
	}elseif(isset($_POST['btn']) && SQL::decrypt_ks($_POST['btn'])===$secret_text.'_send' && isset($_POST['token']) && ALL::csrf_check($_POST['token'],$secret_text)){
		/*print_r($_POST);
		foreach($_POST as $key=>$val){
			echo " isset(\$_POST['{$key}']) &&";
		}*/
		if(isset($_POST['terms_of_use']) && isset($_POST['privacy_policy']) && isset($_POST['school_name']) && isset($_POST['school_kind']) && isset($_POST['prefecture']) && isset($_POST['city']) && isset($_POST['address']) && isset($_POST['school_color']) && isset($_POST['school_phone']) && isset($_POST['person_name']) && isset($_POST['person_email']) && isset($_POST['name']) && isset($_POST['birthday']) && isset($_POST['sex']) && isset($_POST['account']) && isset($_POST['password']) && isset($_POST['re_password']) && $_POST['terms_of_use']=='accepted' && $_POST['privacy_policy']=='accepted' && $_POST['password']===$_POST['re_password']){
			//アカウント
			$row=SQL::fetch("SELECT COUNT(account) as cnt FROM account_info WHERE account=?",array($_POST['account']));
			if((int)$row['cnt']===0){
				//school
				$school_kind=(int)SQL::decrypt_ks($_POST['school_kind']);
				$prefecture_id=(int)$_POST['prefecture'];
				$city_id=(int)$_POST['city'];
				$row=SQL::fetch("SELECT MAX(id) as max FROM manage_school WHERE school=? AND prefecture=? AND city=?",array($school_kind,$prefecture_id,$city_id));
				$id=$row['max']+1;
				$school_id=$school_kind.sprintf('%02d',$prefecture_id).sprintf('%02d',$city_id).sprintf('%03d', $id);
				$_SESSION[$session_head.'school_id']=$school_id;
				//account
				$name=SQL::encrypt($_POST['name']);
				$birthday=new DateTime($_POST['birthday']);
				$sex=(int)SQL::decrypt_ks($_POST['sex']);
				$email=SQL::encrypt($_POST['email']);
				$account=$_POST['account'];
				$password=SQL::encrypt(password_hash($_POST['password'],PASSWORD_DEFAULT));
				$row=SQL::fetch("SELECT COUNT(user_id) as cnt FROM account_user WHERE birthday_year=?",array($birthday->format('Y')));
				$user_id=$birthday->format('y').sprintf('%06d',(int)$row['cnt']);
				$sql="INSERT INTO account_user (user_id,birthday_year,birthday,sex,name,name_iv,email,email_iv,school_id,edit_date,cond,delete_date) VALUES (?,?,?,?,?,?,?,?,?,?,0,NULL)";
				$values=array($user_id,$birthday->format('Y'),$birthday->format('Y-m-d'),$sex,$name[0],$name[1],$email[0],$email[1],$school_id,$Ymdt);
				SQL::execute($sql,$values);
				$sql="INSERT INTO account_info (user_id,account,pwd,pwd_iv,edit_date,cond,delete_date) VALUES (?,?,?,?,?,0,NULL)";
				$values=array($user_id,$account,$password[0],$password[1],$Ymdt);
				SQL::execute($sql,$values);
				
				//学校情報
				$school_name=$_POST['school_name'];
				$address=$_POST['address'];
				$school_url=$_POST['school_url'];
				$school_color=$_POST['school_color'];
				$school_phone=$_POST['school_phone'];
				$library_phone=$_POST['library_phone'];
				$person_name=SQL::encrypt($_POST['person_name']);
				$person_email=SQL::encrypt($_POST['person_email']);
				$sql="INSERT INTO manage_school (school_id,school,prefecture,city,id,town,school_name,color,hp,school_number,library_number,person,person_iv,email,email_iv,edit_date,cond,delete_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,NULL)";
				$values=array($school_id,$school_kind,$prefecture_id,$city_id,$id,$address,$school_name,$school_color,$school_url,$school_phone,$library_phone,$person_name[0],$person_name[1],$person_email[0],$person_email[1],$Ymdt);
				if(SQL::execute($sql,$values)){
					//encrypt 
					$encrypt_json=array("encrypt"=>
										array(
											"password"=>
											hash('sha256',str_shuffle(mb_substr(str_shuffle('0123456789'),0,6).mb_substr(str_shuffle('qazxswedcvfrtgbnhyujmkiolp'),0,10).mb_substr(str_shuffle(',+-./;()@:\][]'),0,4))),
											"method"=>'AES-256-CBC'
											)
										);
					$json=json_encode($encrypt_json);
					file_put_contents(DATA_DIR.$school_id.'.json',$json);
					//テーブル追加
					//manage
					SQL::execute("CREATE TABLE {$school_id}_manage_setting_borrow(borrow_id int NOT NULL,user_id int(8) NOT NULL,admin_number tinyint(2) NOT NULL,teacher_number tinyint(2) NOT NULL,officer_number tinyint(2) NOT NULL,student_number tinyint(2) NOT NULL,admin_deadline tinyint(2) NOT NULL,teacher_deadline tinyint(2) NOT NULL,officer_deadline tinyint(2) NOT NULL,student_deadline tinyint(2) NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_setting_location(location_id int NOT NULL,user_id int(8) NOT NULL,name text NOT NULL,name_iv text NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_setting_financial(financial_id int NOT NULL,user_id int(8) NOT NULL,name text NOT NULL,name_iv text NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_setting_store(store_id int NOT NULL,user_id int(8) NOT NULL,name text NOT NULL,name_iv text NOT NULL,store_code int NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_setting_ndc(ndc_id int NOT NULL,user_id int(8) NOT NULL,name text NOT NULL,name_iv text NOT NULL,ndc_code smallint(3) NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_setting_other(other_id int NOT NULL,user_id int NOT NULL,reserve_info tinyint(1) default '0' NOT NULL,reserve tinyint(1) default '0' NOT NULL,reverse_info tinyint(1) default '0' NOT NULL,book_teacher tinyint(1) default '0' NOT NULL,book_officer tinyint(1) default '0' NOT NULL,book_general tinyint(1) default '0' NOT NULL,grade tinyint(1) default '0' NOT NULL,class tinyint(1) default '0' NOT NULL,number tinyint(1) default '0' NOT NULL,sex tinyint(1) default '0' NOT NULL,name tinyint(1) default '0' NOT NULL,publish tinyint(1) default '2' NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_books_info(book_id int(9) NOT NULL,user_id int(8) NOT NULL,isbn bigint(13) NOT NULL,entry_date date NOT NULL,series text NOT NULL,book_name text NOT NULL,book_name_ruby text NOT NULL,sub_name text NOT NULL,author1 text NOT NULL,author1_ruby text NOT NULL,author1_biography text NOT NULL,author2 text NOT NULL,author2_ruby text NOT NULL,author2_biography text NOT NULL,author3 text NOT NULL,author3_ruby text NOT NULL,author3_biography text NOT NULL,author4 text NOT NULL,author4_ruby text NOT NULL,author4_biography text NOT NULL,publisher text NOT NULL,publish_date date NOT NULL,size text NOT NULL,page smallint NOT NULL,price smallint NOT NULL,seikyu1 smallint NOT NULL,seikyu2 text NOT NULL,seikyu3 smallint NOT NULL,color tinyint NOT NULL,ndc int NOT NULL,location int NOT NULL,financial int NOT NULL,store int NOT NULL,not_reserve tinyint(1) default '0' NOT NULL,drop_date date ,book_img text NOT NULL,note text NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_books_inspection(inspection_id int NOT NULL,group_id int NOT NULL,user_id int(8) NOT NULL,book_number int(9) NOT NULL,edit_date datetime NOT NULL)");
					SQL::execute("CREATE TABLE {$school_id}_manage_books_request(request_id int NOT NULL,user_id int(8) NOT NULL,book_name text NOT NULL,isbn bigint(13) ,note text NOT NULL,entry_date date NOT NULL,request_condition tinyint(1) default '0' NOT NULL,result_note text NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime)");
					SQL::execute("CREATE TABLE {$school_id}_manage_magazine_info(book_id int NOT NULL,user_id int(8) NOT NULL,name text NOT NULL,series text NOT NULL,entry_date date NOT NULL,location int NOT NULL,financial int NOT NULL,store int NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_user_info(userinfo_id int NOT NULL,chat_id int NOT NULL,edit_user_id int(8) NOT NULL,teacher_user_id int(8) NOT NULL,student_user_id int(8) NOT NULL,title text NOT NULL,title_iv text NOT NULL,content text NOT NULL,content_iv text NOT NULL,read_condition tinyint(1) default '0' NOT NULL,reply tinyint(1) default '1' NOT NULL,edit_date datetime NOT NULL)");
					#SQL::execute("CREATE TABLE {$school_id}_manage_user_impressions(impressions_id int NOT NULL,user_id int(8) NOT NULL,book_id int(9) NOT NULL,title text NOT NULL,title_iv text NOT NULL,content text NOT NULL,content_iv text NOT NULL,words int NOT NULL,publish_condition tinyint(1) default '0' NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					SQL::execute("CREATE TABLE {$school_id}_manage_other_info(info_id int NOT NULL,user_id int(8) NOT NULL,title text NOT NULL,title_iv text NOT NULL,content text NOT NULL,content_iv text NOT NULL,edit_date datetime NOT NULL)");
					SQL::execute("CREATE TABLE {$school_id}_manage_other_letter(letter_id int NOT NULL,user_id int(8) NOT NULL,title text NOT NULL,title_iv text NOT NULL,file text NOT NULL,file_iv text NOT NULL,edit_date datetime NOT NULL)");
					SQL::execute("CREATE TABLE {$school_id}_account_class(class_id int NOT NULL,user_id int(8) NOT NULL,grade tinyint default '1' NOT NULL,class text NOT NULL,number int default '1' NOT NULL,user_right tinyint default '4' NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime )");
					//counter
					SQL::execute("CREATE TABLE {$school_id}_counter_borrow(borrow_id int NOT NULL,entry_user int NOT NULL,book_id int NOT NULL,user_id int NOT NULL,borrow_date datetime NOT NULL,reserve_scheduled date NOT NULL,reserve_date datetime)");
					SQL::execute("CREATE TABLE {$school_id}_counter_reserve(reserve_id int NOT NULL,entry_user int NOT NULL,book_id int NOT NULL,user_id int NOT NULL,reserve_date datetime NOT NULL,cond tinyint NOT NULL,edit_date datetime NOT NULL)");
					//foruser
					SQL::execute("CREATE TABLE {$school_id}_foruser_impressions(impressions_id int NOT NULL,user_id int(8) NOT NULL,edit_user_id int(8) NOT NULL,title text NOT NULL,book_id int(9) NOT NULL,isbn int NOT NULL,content text NOT NULL,public tinyint(1) default '0' NOT NULL,words int NOT NULL,edit_date datetime NOT NULL,cond tinyint(1) default '0' NOT NULL,delete_date datetime)");
					
					//レコード追加
					//manage
					SQL::execute("INSERT INTO {$school_id}_manage_setting_borrow VALUES (1,00000001,5,5,5,5,14,14,14,14,?,0,NULL)",array($Ymdt));
					//setting_other
					SQL::execute("INSERT INTO {$school_id}_manage_setting_other VALUES (1,00000001,0,0,0,0,0,0,0,0,0,0,0,2,?,0,NULL)",array($Ymdt));
					//account_class
					SQL::execute("INSERT INTO {$school_id}_account_class VALUES (1,?,0,0,0,1,?,0,NULL)",array($user_id,$Ymdt));
				?>
	<div>
		<p>
			以下の内容で申請を処理しました。
		</p>
		<dl class="form_style" id="application_form_element">
			<dt>
				学校名
			</dt>
			<dd>
				<?=ALL::h($_POST['school_name']);?>
			</dd>
			<dt>
				学校所在地
			</dt>
			<dd>
				<?=$prefecture[$prefecture_id];?><?=$cities[$prefecture[$prefecture_id]][$city_id];?><?=ALL::h($_POST['address']);?>
			</dd>
			<dt>
				学校ホームページURL
			</dt>
			<dd>
				<?=ALL::h($_POST['school_url']);?>
			</dd>
			<dt>
				学校電話番号
			</dt>
			<dd>
				<?=ALL::h(ALL::format_phone_number($_POST['school_phone']));?>
			</dd>
			<dt>
				図書室直通電話番号
			</dt>
			<dd>
				<?=ALL::h(ALL::format_phone_number($_POST['library_phone']));?>
			</dd>
			<dt>
				担当者名
			</dt>
			<dd>
				<?=ALL::h($_POST['person_name']);?>
			</dd>
		</dl><?php
				}
				?>
		<p>
			申請者のアカウントも登録しました。<br>
			ログインすることでLiCSSが使えるようになりました。
		</p>
		<p>
			LiCSSのURLは<a href="<?=$url;?>"><?=$url;?></a>です。
		</p>
	</div><?php
				//NDC レコード登録
				$ndc=array(0=>'総記',1=>'',2=>'知識. 学問. 学術',3=>'',4=>'',5=>'',6=>'',7=>'情報科学',8=>'',9=>'',20=>'図書. 書誌学',21=>'著作. 編集',22=>'写本. 刊本. 造本',23=>'出版',24=>'図書の販売',25=>'一般書誌. 全国書誌',26=>'稀書目録. 善本目録',27=>'特殊目録',28=>'特定図書目録. 参考図書目録',29=>'蔵書目録. 総合目録',40=>'一般論文集. 一般講演集',41=>'日本語',42=>'中国語',43=>'英語',44=>'ドイツ語',45=>'フランス語',46=>'スペイン語',47=>'イタリア語',48=>'ロシア語',49=>'雑著',60=>'団体',61=>'学術・研究機関',62=>'',63=>'文化交流機関',64=>'',65=>'親睦団体. その他の団体',66=>'',67=>'',68=>'',69=>'博物館',80=>'叢書. 全集. 選集',81=>'日本語',82=>'中国語',83=>'英語',84=>'ドイツ語',85=>'フランス語',86=>'スペイン語',87=>'イタリア語',88=>'ロシア語',89=>'その他の諸言語',10=>'図書館. 図書館学',11=>'図書館政策. 図書館行財政',12=>'図書館建築. 図書館設備',13=>'図書館管理',14=>'資料の収集. 資料の整理. 資料の保管',15=>'図書館奉仕. 図書館活動',16=>'各種の図書館',17=>'　学校図書館',18=>'　専門図書館',19=>'読書. 読書法',30=>'百科事典',31=>'日本語',32=>'中国語',33=>'英語',34=>'ドイツ語',35=>'フランス語',36=>'スペイン語',37=>'イタリア語',38=>'ロシア語',39=>'用語索引＜一般＞',50=>'逐次刊行物',51=>'日本の雑誌',52=>'中国語',53=>'英語',54=>'ドイツ語',55=>'フランス語',56=>'スペイン語',57=>'イタリア語',58=>'ロシア語',59=>'一般年鑑',70=>'ジャ－ナリズム. 新聞',71=>'日本',72=>'アジア',73=>'ヨーロッパ',74=>'アフリカ',75=>'北アメリカ',76=>'南アメリカ',77=>'オセアニア. 南極地方',78=>'',79=>'',90=>'貴重書. 郷土資料. その他の特別コレクション',91=>'',92=>'',93=>'',94=>'',95=>'',96=>'',97=>'',98=>'',99=>'',100=>'哲学',101=>'哲学理論',102=>'哲学史',103=>'参考図書［レファレンスブック］',104=>'論文集.評論集.講演集',105=>'逐次刊行物',106=>'団体',107=>'研究法.指導法.哲学教育',108=>'叢書.全集.選集',109=>'',120=>'東洋思想',121=>'日本思想',122=>'中国思想.中国哲学',123=>'経書',124=>'先秦思想.諸子百家',125=>'中世思想.近代思想',126=>'インド哲学.バラモン教',127=>'',128=>'',129=>'その他のアジア・アラブ哲学',140=>'心理学',141=>'普通心理学.心理各論',142=>'',143=>'発達心理学',144=>'',145=>'異常心理学',146=>'臨床心理学.精神分析学',147=>'超心理学.心霊研究',148=>'相法.易占',149=>'応用心理学',160=>'宗教',161=>'宗教学.宗教思想',162=>'宗教史・事情',163=>'原始宗教.宗教民族学',164=>'神話.神話学',165=>'比較宗教',166=>'道徳',167=>'イスラム',168=>'ヒンズー教.ジャイナ教',169=>'その他の宗教.新興宗教',180=>'仏教',181=>'仏教教理.仏教哲学',182=>'仏教史',183=>'経典',184=>'法話.説教集',185=>'寺院.僧職',186=>'仏会',187=>'布教.伝道',188=>'各宗',189=>'',110=>'哲学各論',111=>'形而上学.存在論',112=>'自然哲学.宇宙論',113=>'人生観.世界観',114=>'人間学',115=>'認識論',116=>'論理学.弁証法［弁証法的論理学］.方法論',117=>'価値哲学',118=>'文化哲学.技術哲学',119=>'美学→701.1',130=>'西洋哲学',131=>'古代哲学',132=>'中世哲学',133=>'近代哲学',134=>'ドイツ・オーストリア哲学',135=>'フランス・オランダ哲学',136=>'スペイン・ポルトガル哲学',137=>'イタリア哲学',138=>'ロシア哲学',139=>'その他の哲学',150=>'倫理学.道徳',151=>'倫理各論',152=>'家庭倫理.性倫理',153=>'職業倫理',154=>'社会倫理［社会道徳］',155=>'国体論.詔勅',156=>'武士道',157=>'報徳教.石門心学',158=>'その他の特定主題',159=>'人生訓.教訓',170=>'神道',171=>'神道思想.神道説',172=>'神祇・神道史',173=>'神典',174=>'信仰録.説教集',175=>'神社.神職',176=>'祭祀',177=>'布教.伝道',178=>'各教派.教派神道',179=>'',190=>'キリスト教',191=>'教義.キリスト教神学',192=>'キリスト教史.迫害史',193=>'聖書',194=>'信仰録.説教集',195=>'教会.聖職',196=>'典礼.祭式.礼拝',197=>'布教.伝道',198=>'各教派.教会史',199=>'ユダヤ教',200=>'歴史',201=>'歴史学',202=>'歴史補助学',203=>'参考図書［レファレンスブック］',204=>'論文集.評論集.講演集',205=>'逐次刊行物',206=>'団体',207=>'研究法.指導法.歴史教育',208=>'叢書.全集.選集',209=>'世界史.文化史',220=>'アジア史.東洋史',221=>'朝鮮',222=>'中国',223=>'東南アジア',224=>'インドネシア',225=>'インド',226=>'西南アジア.中東［近東］→227',227=>'西南アジア.中東［近東］',228=>'アラブ諸国→227',229=>'アジア・ロシア',240=>'アフリカ史',241=>'北アフリカ',242=>'エジプト',243=>'バーバリー諸国',244=>'西アフリカ',245=>'東アフリカ',246=>'',247=>'',248=>'南アフリカ',249=>'インド洋のアフリカ諸島',260=>'南アメリカ史',261=>'北部諸国［カリブ沿海諸国］',262=>'ブラジル',263=>'パラグアイ',264=>'ウルグアイ',265=>'アルゼンチン',266=>'チリ',267=>'ボリビア',268=>'ペルー',269=>'',280=>'伝記',281=>'日本',282=>'アジア',283=>'ヨーロッパ',284=>'アフリカ',285=>'北アメリカ',286=>'南アメリカ',287=>'オセアニア.両極地方',288=>'系譜.家史.皇室',289=>'個人伝記',210=>'日本史',211=>'北海道地方',212=>'東北地方',213=>'関東地方',214=>'北陸地方',215=>'中部地方',216=>'近畿地方',217=>'中国地方',218=>'四国地方',219=>'九州地方',230=>'ヨ－ロッパ史.西洋史',231=>'古代ギリシア',232=>'古代ローマ',233=>'イギリス.英国',234=>'ドイツ.中欧',235=>'フランス',236=>'スペイン［イスパニア］',237=>'イタリア',238=>'ロシア［ソビエト連邦.独立国家共同体］',239=>'バルカン諸国',250=>'北アメリカ史',251=>'カナダ',252=>'',253=>'アメリカ合衆国',254=>'',255=>'ラテン・アメリカ［中南米］',256=>'メキシコ',257=>'中央アメリカ［中米諸国］',258=>'',259=>'西インド諸島',270=>'オセアニア史.両極地方史',271=>'オーストラリア',272=>'ニュージーランド',273=>'メラネシア',274=>'ミクロネシア',275=>'ポリネシア',276=>'ハワイ',277=>'両極地方',278=>'北極.北極地方',279=>'南極.南極地方',290=>'地理.地誌.紀行',291=>'日本',292=>'アジア',293=>'ヨーロッパ',294=>'アフリカ',295=>'北アメリカ',296=>'南アメリカ',297=>'オセアニア.両極地方',298=>'',299=>'海洋',300=>'社会科学',301=>'理論．方法論',302=>'政治・経済・社会・文化事情',303=>'参考図書［レファレンスブック］',304=>'論文集.評論集.講演集',305=>'逐次刊行物',306=>'団体',307=>'研究法.指導法.社会科学教育',308=>'叢書.全集.選集',309=>'社会思想',320=>'法律',321=>'法学',322=>'法制史',323=>'憲法',324=>'民法',325=>'商法',326=>'刑法・刑事法',327=>'司法・訴訟手続法',328=>'諸法',329=>'国際法',340=>'財政',341=>'財政学.財政思想',342=>'財政史・事情',343=>'財政政策.財務行政',344=>'予算.決算',345=>'租税',346=>'',347=>'公債.国債',348=>'専売.国有財産',349=>'地方財政',360=>'社会',361=>'社会学',362=>'社会史.社会体制',363=>'',364=>'社会保障',365=>'生活・消費者問題',366=>'労働経済.労働問題',367=>'家族問題.男性・女性問題.老人問題',368=>'社会病理',369=>'社会福祉',380=>'風俗習慣.民俗学.民族学',381=>'',382=>'風俗史.民俗誌.民族誌',383=>'衣食住の習俗',384=>'社会・家庭生活の習俗',385=>'通過儀礼.冠婚葬祭',386=>'年中行事.祭礼',387=>'民間信仰.迷信［俗信］',388=>'伝説.民話［昔話］',389=>'民族学.文化人類学',310=>'政治',311=>'政治学.政治思想',312=>'政治史・事情',313=>'国家の形態.政治体制',314=>'議会',315=>'政党.政治結社',316=>'国家と個人・宗教・民族',317=>'行政',318=>'地方自治.地方行政',319=>'外交.国際問題',330=>'経済',331=>'経済学・経済思想',332=>'経済史・事情・経済体制',333=>'経済政策・国際経済',334=>'人口.土地.資源',335=>'企業.経営',336=>'経営管理',337=>'貨幣.通貨',338=>'金融.銀行.信託',339=>'保険',350=>'統計',351=>'日本',352=>'アジア',353=>'ヨーロッパ',354=>'アフリカ',355=>'北アメリカ',356=>'南アメリカ',357=>'オセアニア.両極地方',358=>'人口統計.国勢調査',359=>'各種の統計書',370=>'教育',371=>'教育学.教育思想',372=>'教育史・事情',373=>'教育政策.教育制度.教育行財政',374=>'学校経営・管理.学校保健',375=>'教育課程.学習指導.教科別教育',376=>'幼児・初等・中等教育',377=>'大学・高等・専門教育.学術行政',378=>'障害児教育',379=>'社会教育',390=>'国防.軍事',391=>'戦争.戦略.戦術',392=>'国防史・事情.軍事史・事情',393=>'国防政策・行政・法令',394=>'軍事医学.兵食',395=>'軍事施設.軍需品',396=>'陸軍',397=>'海軍',398=>'空軍',399=>'古代兵法.軍学',400=>'自然科学',401=>'科学理論.科学哲学',402=>'科学史・事情',403=>'参考図書［レファレンスブック］',404=>'論文集.評論集.講演集',405=>'逐次刊行物',406=>'団体',407=>'研究法.指導法.科学教育',408=>'叢書.全集.選集',409=>'科学技術政策・科学技術行政',420=>'物理学',421=>'理論物理学',422=>'',423=>'力学',424=>'振動学.音響学',425=>'光学',426=>'熱学',427=>'電磁気学',428=>'物性物理学',429=>'原子物理学',440=>'天文学.宇宙科学',441=>'理論天文学.数理天文学',442=>'実地天文学.天体観測法',443=>'恒星.恒星天文学',444=>'太陽.太陽物理学',445=>'惑星.衛星',446=>'月',447=>'彗星.流星',448=>'地球.天文地理学',449=>'時法.暦学',460=>'生物科学.一般生物学',461=>'理論生物学.生命論',462=>'生物地理.生物誌',463=>'細胞学',464=>'生化学',465=>'微生物学',466=>'',467=>'遺伝学',468=>'生態学',469=>'人類学',480=>'動物学',481=>'一般動物学',482=>'動物地理.動物誌',483=>'無脊椎動物',484=>'軟体動物.貝類学',485=>'節足動物',486=>'昆虫類',487=>'脊椎動物',488=>'鳥類',489=>'哺乳類',410=>'数学',411=>'代数学',412=>'数論［整数学］',413=>'解析学',414=>'幾何学',415=>'位相数学',416=>'',417=>'確率論.数理統計学',418=>'計算法',419=>'和算.中国算法',430=>'化学',431=>'物理化学.理論化学',432=>'実験化学［化学実験法］',433=>'分析化学［化学分析］',434=>'合成化学［化学合成］',435=>'無機化学',436=>'金属元素とその化合物',437=>'有機化学',438=>'環式化合物の化学',439=>'天然物質の化学',450=>'地球科学.地学',451=>'気象学',452=>'海洋学',453=>'地震学',454=>'地形学',455=>'地質学',456=>'地史学.層位学',457=>'古生物学.化石',458=>'岩石学',459=>'鉱物学',470=>'植物学',471=>'一般植物学',472=>'植物地理.植物誌',473=>'葉状植物',474=>'藻類.菌類',475=>'コケ植物［蘚苔類］',476=>'シダ植物',477=>'種子植物',478=>'裸子植物',479=>'被子植物',490=>'医学',491=>'基礎医学',492=>'臨床医学.診断・治療',493=>'内科学',494=>'外科学',495=>'婦人科学.産科学',496=>'眼科学.耳鼻咽喉科学',497=>'歯科学',498=>'衛生学.公衆衛生.予防医学',499=>'薬学',500=>'技術.工学',501=>'工業基礎額',502=>'技術史.工学史',503=>'参考図書［レファレンスブック］',504=>'論文集.評論集.講演集',505=>'逐次刊行物',506=>'団体',507=>'研究法.指導法.技術教育',508=>'叢書.全集.選集',509=>'工業.工業経済',520=>'建築学',521=>'日本の建築',522=>'東洋の建築.アジアの建築',523=>'西洋の建築.その他の様式の建築',524=>'建築構造',525=>'建築計画・施工',526=>'各種の建築',527=>'在宅建築',528=>'建築設備.設備工学',529=>'建築意匠・装飾',540=>'電気工学',541=>'電気回路・計測・材料',542=>'電気機器',543=>'発電',544=>'送電.変電.配電',545=>'電灯.照明.電熱',546=>'電気鉄道',547=>'通信工学.電気通信',548=>'情報工学',549=>'電子工学',560=>'金属工学.鉱山工学',561=>'採鉱.選鉱',562=>'各種の金属鉱床・採掘',563=>'冶金.合金',564=>'鉄鋼',565=>'非鉄金属',566=>'金属加工.製造冶金',567=>'石炭',568=>'石油',569=>'非金属鉱物.土石採取業',580=>'製造工業',581=>'金属製品',582=>'事務機器.家庭機器.楽器',583=>'木工業.木製品',584=>'皮革工業.皮革製品',585=>'パルプ・製紙工業',586=>'繊維工業',587=>'染色加工.染色業',588=>'食品工業',589=>'その他の雑工業',510=>'建設工学.土木工学',511=>'土木力学.建設材料',512=>'測量',513=>'土木設計・施工法',514=>'道路工学',515=>'橋梁工学',516=>'鉄道工学',517=>'河海工学.河川工学',518=>'衛生工学.都市工学',519=>'公害.環境工学',530=>'機械工学',531=>'機械力学・材料・設計',532=>'機械工作.工作機械',533=>'熱機関.熱工学',534=>'流体機械.流体工学',535=>'精密機器.光学機器',536=>'運輸工学.車輌.運搬機械',537=>'自動車工学',538=>'航空宇宙工学',539=>'原子力工学',550=>'海洋工学.船舶工学',551=>'倫理造船学',552=>'船体構造・材料・施工',553=>'船体艤装.船舶設備',554=>'舶用機関［造機］',555=>'船舶修理.保守',556=>'各種の船舶・艦艇',557=>'航海.航海学',558=>'海洋開発',559=>'兵器.軍事工学',570=>'化学工業',571=>'化学工学.化学機器',572=>'電気化学工業',573=>'セラミックス.窯業.珪酸塩化学工業',574=>'化学薬品',575=>'燃料.爆発物',576=>'油脂類',577=>'染料',578=>'高分子化学工業',579=>'その他の化学工業',590=>'家政学.生活科学',591=>'家庭経済・経営',592=>'家庭理工学',593=>'衣服.裁縫',594=>'手芸',595=>'理容.美容',596=>'食品.料理',597=>'住居.家具調度',598=>'家庭衛生',599=>'育児',600=>'産業',601=>'産業政策・行政.総合開発',602=>'産業史・事情.物産誌',603=>'参考図書［レファレンスブック］',604=>'論文集.評論集.講演集',605=>'逐次刊行物',606=>'団体',607=>'研究法.指導法.産業教育',608=>'叢書.全集.選集',609=>'度量衡.計量法',620=>'園芸',621=>'園芸経済・行政・経営',622=>'園芸史・事情',623=>'園芸植物学.病虫害',624=>'温室.温床.園芸用具',625=>'果樹園芸',626=>'蔬菜園芸',627=>'花卉園芸［草花］',628=>'園芸利用',629=>'造園',640=>'畜産業',641=>'畜産経済・行政・経営',642=>'畜産史・事情',643=>'家畜の繁殖.家畜飼料',644=>'家畜の管理.畜舎.用具',645=>'家畜・畜産動物各論',646=>'家禽各論.飼鳥',647=>'みつばち.昆虫→646.9',648=>'畜産製造.畜産物',649=>'獣医学.比較医学',660=>'水産業',661=>'水産経済・行政・経営',662=>'水産業および漁業史・事情',663=>'水産基礎額',664=>'漁労.漁業各論',665=>'漁船.漁具',666=>'水産増殖.養殖業',667=>'水産製造.水産食品',668=>'水産物利用.水産利用工業',669=>'製塩.塩業',680=>'運輸.交通',681=>'交通政策・行政・経営',682=>'交通史・事情',683=>'海運',684=>'内水・運河交通',685=>'陸運.自動車運送',686=>'鉄道',687=>'航空運送',688=>'倉庫業',689=>'観光事業',610=>'農業',611=>'農業経済',612=>'農業史・事情',613=>'農業基礎額',614=>'農業工学',615=>'作物栽培.作物学',616=>'食用作物',617=>'工芸作物',618=>'繊維作物',619=>'農産物製造・加工',630=>'蚕糸業',631=>'蚕糸経済・行政・経営',632=>'蚕糸業史・事情',633=>'蚕学.蚕業基礎学',634=>'蚕種',635=>'飼育法',636=>'くわ.栽桑',637=>'蚕室.蚕具',638=>'まゆ',639=>'製糸.生糸.蚕糸利用',650=>'林業',651=>'林業経済・行政・経営',652=>'森林史.林業史・事情',653=>'森林立地.造林',654=>'森林保護',655=>'森林施業',656=>'森林工業',657=>'森林利用.林産物.木材学',658=>'林産製造',659=>'狩猟',670=>'商業',671=>'商業政策・行政',672=>'商業史・事情',673=>'商業経営.商店',674=>'広告.宣伝',675=>'マーケティング',676=>'取引所',677=>'',678=>'貿易',679=>'',690=>'通信事業',691=>'通信政策・行政・法令',692=>'通信事業史・事情',693=>'郵便.郵政事業',694=>'電気通信事業',695=>'',696=>'',697=>'',698=>'',699=>'放送事業',700=>'芸術.美術',701=>'芸術理論.美学',702=>'芸術史.美術史',703=>'参考図書［レファレンスブック］',704=>'論文集.評論集.講演集',705=>'逐次刊行物',706=>'団体',707=>'研究法.指導法.芸術教育',708=>'叢書.全集.選集',709=>'芸術政策.文化財',720=>'絵画',721=>'日本画',722=>'東洋画',723=>'洋画',724=>'絵画材料・技法',725=>'素描.描画',726=>'漫画.挿絵.童画',727=>'グラフィックデザイン.図案',728=>'書.書道',729=>'',740=>'写真',741=>'',742=>'写真器械・材料',743=>'撮影技術',744=>'現像.印画',745=>'複写技術',746=>'特殊写真',747=>'写真の応用',748=>'写真集',749=>'印刷',760=>'音楽',761=>'音楽の一般理論.音楽学',762=>'音楽史.各国の音楽');
				$ndc2=array(763=>'楽器.器楽',764=>'器楽合奏',765=>'宗教音楽.聖楽',766=>'劇音楽',767=>'声楽',768=>'邦楽',769=>'舞踊.バレエ',780=>'スポーツ.体育',781=>'体操.遊戯',782=>'陸上競技',783=>'球技',784=>'冬季競技',785=>'水上競技',786=>'戸外レクリエーション',787=>'釣魚.遊猟',788=>'相撲.拳闘.競馬',789=>'武術',710=>'彫刻',711=>'彫塑材料・技法',712=>'彫刻史.各国の彫刻',713=>'木彫',714=>'石彫',715=>'金属彫刻.鋳造',716=>'',717=>'粘土彫刻.塑造',718=>'仏像',719=>'オブジェ',730=>'版画',731=>'版画材料・技法',732=>'版画史.各国の版画',733=>'木版画',734=>'石版画',735=>'銅版画.鋼版画',736=>'リノリウム版画.ゴム版画',737=>'写真版画.孔版画',738=>'',739=>'印章.篆刻.印譜',750=>'工芸',751=>'陶磁工芸',752=>'漆工芸',753=>'染織工芸',754=>'木竹工芸',755=>'宝石・牙角・皮革工芸',756=>'金工芸',757=>'デザイン.装飾美術',758=>'美術家具',759=>'人形.玩具',770=>'演劇',771=>'劇場.演出.演技',772=>'演劇史.各国の演劇',773=>'能楽.狂言',774=>'歌舞伎',775=>'各種の演劇',776=>'',777=>'人形劇',778=>'映画',779=>'大衆演芸',790=>'諸芸.娯楽',791=>'茶道',792=>'香道',793=>'花道',794=>'撞球',795=>'囲碁',796=>'将棋',797=>'射倖ゲーム',798=>'室内娯楽',799=>'ダンス',800=>'言語',801=>'言語学',802=>'言語史・事情.言語政策',803=>'参考図書［レファレンスブック］',804=>'論文集.評論集.講演集',805=>'逐次刊行物',806=>'団体',807=>'研究法.指導法.言語教育',808=>'叢書.全集.選集',809=>'言語生活',820=>'中国語',821=>'音声.音韻.文字',822=>'語源.意味',823=>'辞典',824=>'語彙',825=>'文法.語法',826=>'文章.文体.作文',827=>'読本.解釈.会話',828=>'方言.訛語',829=>'その他の東洋の諸言語',840=>'ドイツ語',841=>'音声.音韻.文字',842=>'語源.意味',843=>'辞典',844=>'語彙',845=>'文法.語法',846=>'文章.文体.作文',847=>'読本.解釈.会話',848=>'方言.訛語',849=>'その他のゲルマン諸語',860=>'スペイン語',861=>'音声.音韻.文字',862=>'語源.意味',863=>'辞典',864=>'語彙',865=>'文法.語法',866=>'文章.文体.作文',867=>'読本.解釈.会話',868=>'方言.訛語',869=>'ポルトガル語',880=>'ロシア語',881=>'音声.音韻.文字',882=>'語源.意味',883=>'辞典',884=>'語彙',885=>'文法.語法',886=>'文章.文体.作文',887=>'読本.解釈.会話',888=>'方言.訛語',889=>'その他のスラヴ諸語',810=>'日本語',811=>'音声.音韻.文字',812=>'語源.意味',813=>'辞典',814=>'語彙',815=>'文法.語法',816=>'文章.文体.作文',817=>'読本.解釈.会話',818=>'方言.訛語',819=>'',830=>'英語',831=>'音声.音韻.文字',832=>'語源.意味',833=>'辞典',834=>'語彙',835=>'文法.語法',836=>'文章.文体.作文',837=>'読本.解釈.会話',838=>'方言.訛語',839=>'',850=>'フランス語',851=>'音声.音韻.文字',852=>'.語源.意味',853=>'辞典',854=>'語彙',855=>'文法.語法',856=>'文章.文体.作文',857=>'読本.解釈.会話',858=>'方言.訛語',859=>'プロヴァンス語',870=>'イタリア語',871=>'音声.音韻.文字',872=>'語源.意味',873=>'辞典',874=>'語彙',875=>'文法.語法',876=>'文章.文体.作文',877=>'読本.解釈.会話',878=>'方言.訛語',879=>'その他のロマンス諸語',890=>'その他の諸言語',891=>'ギリシャ語',892=>'ラテン語',893=>'その他のヨーロッパ諸言語',894=>'アフリカの諸言語',895=>'アメリカの諸言語',896=>'',897=>'オーストラリアの諸言語',898=>'',899=>'国際語［人工語］',900=>'文学',901=>'文学理論・作法',902=>'文学史.文学思想史',903=>'参考図書［レファレンスブック］',904=>'論文集.評論集.講演集',905=>'逐次刊行物',906=>'団体',907=>'研究法.指導法.文学教育',908=>'叢書.全集.選集',909=>'児童文学研究',920=>'中国文学',921=>'詩歌.韻文.詩文',922=>'戯曲',923=>'小説.物語',924=>'評論.エッセイ.随筆',925=>'日記.書簡.紀行',926=>'記録.手記.ルポルタージュ',927=>'箴言.アフォリズム.寸言',928=>'作品集',929=>'その他の東洋文学',940=>'ドイツ文学',941=>'詩',942=>'戯曲',943=>'小説.物語',944=>'評論.エッセイ.随筆',945=>'日記.書簡.紀行',946=>'記録.手記.ルポルタージュ',947=>'箴言.アフォリズム.寸言',948=>'作品集',949=>'その他のゲルマン文学',960=>'スペイン文学',961=>'詩',962=>'戯曲',963=>'小説.物語',964=>'評論.エッセイ.随筆',965=>'日記.書簡.紀行',966=>'記録.手記.ルポルタージュ',967=>'箴言.アフォリズム.寸言',968=>'作品集',969=>'ポルトガル文学',980=>'ロシア・ソヴィエト文学',981=>'詩',982=>'戯曲',983=>'小説.物語',984=>'評論.エッセイ.随筆',985=>'日記.書簡.紀行',986=>'記録.手記.ルポルタージュ',987=>'箴言.アフォリズム.寸言',988=>'作品集',989=>'その他のスラヴ文学',910=>'日本文学',911=>'詩歌',912=>'戯曲',913=>'小説.物語',914=>'評論.エッセイ.随筆',915=>'日記.書簡.紀行',916=>'記録.手記.ルポルタージュ',917=>'箴言.アフォリズム.寸言',918=>'作品集',919=>'漢詩文.日本漢文学',930=>'英米文学',931=>'詩',932=>'戯曲',933=>'小説.物語',934=>'評論.エッセイ.随筆',935=>'日記.書簡.紀行',936=>'記録.手記.ルポルタージュ',937=>'箴言.アフォリズム.寸言',938=>'作品集',939=>'アメリカ文学→930/938',950=>'フランス文学',951=>'詩',952=>'戯曲',953=>'小説.物語',954=>'評論.エッセイ.随筆',955=>'日記.書簡.紀行',956=>'記録.手記.ルポルタージュ',957=>'箴言.アフォリズム.寸言',958=>'作品集',959=>'プロヴァンス文学',970=>'イタリア文学',971=>'詩',972=>'戯曲',973=>'小説.物語',974=>'評論.エッセイ.随筆',975=>'日記.書簡.紀行',976=>'記録.手記.ルポルタージュ',977=>'箴言.アフォリズム.寸言',978=>'作品集',979=>'その他のロマンス文学',990=>'その他の諸文学',991=>'ギリシア文学',992=>'ラテン文学',993=>'その他のヨーロッパ文学',994=>'アフリカ文学',995=>'アメリカ先住民語の文学',996=>'',997=>'オーストラリア先住民語の文学',998=>'',999=>'国際語による文学');
				$sql="INSERT INTO {$school_id}_manage_setting_ndc (ndc_id,user_id,name,name_iv,ndc_code,edit_date,cond,delete_date) VALUES (?,?,?,?,?,?,0,NULL)";
				$ndc_id=1;
				foreach($ndc as $key=>$val){
					$name=SQL::encrypt_school($val);
					SQL::execute($sql,array($ndc_id,$user_id,$name[0],$name[1],$key,$Ymdt));
					++$ndc_id;
				}
				foreach($ndc2 as $key=>$val){
					$name=SQL::encrypt_school($val);
					SQL::execute($sql,array($ndc_id,$user_id,$name[0],$name[1],$key,$Ymdt));
					++$ndc_id;
				}
			}else{
				?>
	<div>
		<h2>
			エラーが発生
		</h2>
		<p>
			利用できないアカウント名です。
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
			入力必須項目で入力されていない項目があります。
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
include_once('../../data/footer.php');
?>