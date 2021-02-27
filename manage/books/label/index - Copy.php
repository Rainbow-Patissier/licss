<?php
include_once('../../../data/link.php');
$title="ラベル印刷";
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
			<a href="<?=$url;?>manage/books/">
				蔵書管理
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
	<div id="manage_books_label_menu">
		<p>
			印刷するラベルを選択してください
		</p>
		<ul class="manage_menu_link_wrapper">
			<li class="manage_menu_link_list">
				<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('book_id');">
					図書番号
				</a>
			</li>
			<li class="manage_menu_link_list">
				<a href="javascript:void(0)" class="manage_menu_link" onClick="select_menu('back_label');">
					背ラベル
				</a>
			</li>
		</ul>
	</div>
	<div id="manage_books_label_setting" class="no_print">
		<input type="hidden" id="manage_books_label_type">
		図書番号が<input type="text" id="manage_books_label_from_book" min="0">から<input type="text" id="manage_books_label_to_book" min="0">まで<br>
		（途中から印刷する場合）空白ラベル数<input type="number" id="manage_books_label_margin" min="0" value="0">
		<button type="button" onClick="create_preview();">表示</button>
		<button type="button" onClick="window.print();" id="manage_books_label_print_btn" class="no_print">印刷</button>
	</div>
	<div id="manage_books_label_printout">
		<!--<div class="manage_books_label_row">
			<div>
				<div class="manage_books_label_school_name school_color"><?=$school_name;?></div>
				<div class="nw7_barcode">a00000001a</div>
				<div class="manage_books_label_book_id">00000001</div>
				<div class="manage_books_label_book_name">かいけつゾロリ</div>
			</div>
		</div>
		<div class="manage_books_back_label_row">
			<div class="manage_books_back_label_wrapper">
				<div>
					<div class="manage_books_back_label_number">913</div>
					<div class="manage_books_back_label_hiragana">あ</div>
					<div class="manage_books_back_label_series">1</div>
				</div>
			</div>
		</div>-->
	</div>
	<script>
		$(function(){
			$('.manage_books_label_barcode').nw7({start:"a", density:"3"});
		});
		function select_menu(value){
			document.getElementById('manage_books_label_type').value=value;
			document.getElementById('manage_books_label_menu').style.height=0;
			document.getElementById('manage_books_label_setting').style.height='100px';
		}
		function create_preview(){
			if(document.getElementById('manage_books_label_type').value==='book_id'){
				document.getElementById('manage_books_label_print_btn').style.opacity=1;
				var margin=Number(document.getElementById('manage_books_label_margin').value);
				var from=Number(document.getElementById('manage_books_label_from_book').value);
				var to=Number(document.getElementById('manage_books_label_to_book').value);
				if(from>to){
					alert('数値が無効です');
					return false;
				}
				var parent=document.getElementById('manage_books_label_printout');
				parent.innerHTML='';
				var child='';
				var child2='';
				var child3='';
				var display_id='';
				var id=from-margin;
				for(var r=1;id<=to;++r){
					if(r===12){
						child=document.createElement('div');
						child.setAttribute('class','manage_books_label_row_margin');
						parent.appendChild(child);
						r=1;
					}
					child=document.createElement('div');
					child.setAttribute('class','manage_books_label_row');
					parent.appendChild(child);
					for(var i=0;i<4;++i){
						if(id<=to && id>=from){
							display_id=('00000000'+id).slice(-8);
							child2=document.createElement('div');
							child.appendChild(child2);
							child3=document.createElement('div');
							child3.setAttribute('class','manage_books_label_school_name school_color');
							child3.innerHTML='<?=$school_name;?>';
							child2.appendChild(child3);
							child3=document.createElement('div');
							child3.setAttribute('class','nw7_barcode');
							child3.innerHTML='a'+display_id+'a';
							child2.appendChild(child3);
							child3=document.createElement('div');
							child3.setAttribute('class','manage_books_label_book_id');
							child3.innerHTML=display_id;
							child2.appendChild(child3);
							child3=document.createElement('div');
							child3.setAttribute('class','manage_books_label_book_name');
							book_name(id,child3);
							child2.appendChild(child3);
						}else{
							child2=document.createElement('div');
							child.appendChild(child2);
						}
						++id;
					}
				}
				document.getElementById('manage_books_label_printout').style.margin='8.8mm 8.4mm';
			}else if(document.getElementById('manage_books_label_type').value==='back_label'){
				document.getElementById('manage_books_label_print_btn').style.opacity=1;
				var margin=Number(document.getElementById('manage_books_label_margin').value);
				var from=Number(document.getElementById('manage_books_label_from_book').value);
				var to=Number(document.getElementById('manage_books_label_to_book').value);
				if(from>to){
					alert('数値が無効です');
					return false;
				}
				var parent=document.getElementById('manage_books_label_printout');
				parent.innerHTML='';
				var child='';
				var child2='';
				var child3='';
				var child4='';
				var child5='';
				var child6='';
				var id=from-margin;
				for(var r=1;id<=to;++r){
					if(r===14){
						child=document.createElement('div');
						child.setAttribute('class','manage_books_back_label_row_margin');
						parent.appendChild(child);
						r=1;
					}
					child=document.createElement('div');
					child.setAttribute('class','manage_books_back_label_row');
					parent.appendChild(child);
					for(var i=0;i<5;++i){
						if(id<=to && id>=from){
							child2=document.createElement('div');
							child2.setAttribute('class','manage_books_back_label_wrapper')
							child.appendChild(child2);
							child3=document.createElement('div');
							child2.appendChild(child3);
							child4=document.createElement('div');
							child4.setAttribute('class','manage_books_back_label_number');
							child3.appendChild(child4);
							child5=document.createElement('div');
							child5.setAttribute('class','manage_books_back_label_hiragana');
							child3.appendChild(child5);
							child6=document.createElement('div');
							child6.setAttribute('class','manage_books_back_label_series');
							child3.appendChild(child6);
							book_back_label(id,child4,child5,child6);
						}else{
							child2=document.createElement('div');
							child.appendChild(child2);
						}
						++id;
					}
				}
				document.getElementById('manage_books_label_printout').style.margin='10.92mm 4.75mm';
			}
			setTimeout(function(){window.print();},1000);
		}
	</script>
</section><?php
include_once('../../../data/footer.php');
?>