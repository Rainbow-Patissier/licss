// JavaScript Document

function alert_cancel(){
	window.onbeforeunload = null; 
}
//パスワードチェック
function check_w_password(){
	if(document.getElementById('new_password').value===document.getElementById('check_password').value){
		return true;
	}else{
		alert('パスワードが一致しません');
		return false;
	}
}
//audio
function sound_audio(value){
	var audio=new Audio('http://localhost/licss/data/audio/'+value);
	audio.play();
	setTimeout(function(){
		audio.pause();
		audio.currentTime=0;
	},1000);
}
//editor.js save
function editor_save(textarea_element){
	editor.save().then((outputData) => {
		textarea_element.value=JSON.stringify(outputData)
	}).catch((error) => {
		console.log('Saving failed: ', error)
	});
}
//top-header_account
function header_account_display(){
	var style=document.getElementById('header_account_nav').style;
	if(style.display=='block'){
		style.display='none';
	}else{
		style.display='block';
	}
}
//top-header_global_menu
function header_global_menu_display(ele,url,height){
	if(window.innerWidth>1300){
		window.location.href=url;
	}else{
		var ul=ele.parentNode.getElementsByClassName('header_global_sub_nav')[0].style;
		var display=ul.height;
		var sub_nav=document.getElementsByClassName('header_global_sub_nav');
		for(var i=0;i<sub_nav.length;++i){
			sub_nav[i].style.height='0px';
			//sub_nav[i].style.overflow='hidden';
		}
		if(display==height+'px'){
			ul.height='0px';
			//ul.overflow='hidden';
		}else{
			ul.height=height+'px';
			setTimeout(function(){
			//	ul.overflow='visible';
			},500);
		}
	}
}
//top-sp_global_menu
function sp_global_menu_display(){
	var sub_nav=document.getElementsByClassName('header_global_sub_nav');
	for(var i=0;i<sub_nav.length;++i){
		sub_nav[i].style.height='0px';
		//sub_nav[i].style.overflow='hidden';
	}
	var style=document.getElementById('header_global_nav_wrapper').style;
	var span=document.getElementById('sp_nav_button');
	if(style.height=="calc(100% - 70px)"){
		style.height='0';
		//style.width="0";
		style.overflow='hidden';
		span.removeAttribute('class');
	}else{
		style.height="calc(100% - 70px)";
		//style.width="100%";
		style.overflow='visible';
		span.setAttribute('class','sp_nav_button_active');
	}
}
//お知らせ削除
function close_info(ele){
	var parent=ele.parentNode.style;
	parent.opacity=0;
	parent.height=0;
}