// JavaScript Document
//not-logged/application
function application_check_account(account){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "check_account.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "a":account
        }
	}).then(
		function(data){
			if(data['res']){
				if(data['usable']){
					document.getElementById('application_account_message').innerHTML='';
				}else{
					document.getElementById('application_account_message').innerHTML='このアカウント名は利用出来ません。';
				}
			}else{
				document.getElementById('application_account_message').innerHTML='アカウント名が利用できるかわかりません。もう一度入力してください。';
			}
		},
		function(data){
			console.log('情報の取得に失敗しました。初めからやり直してください');
		}
	);
}
//not-logged/forget-password
function forget_check_account(){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "check.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "a":document.getElementById('account_or_mail').value
        }
	}).then(
		function(data){
			console.log(data);
			if(data['res']){
				document.getElementById('check_account_display').innerHTML='';
				document.getElementById('check_account_display').removeAttribute('class');
				document.getElementById('check_account').style.display='none';
				document.getElementById('check_account').innerHTML='再送信';
				setTimeout(function(){
					document.getElementById('check_account').style.display='inline';
					document.getElementById('check_account_display').innerHTML='メールが届かない場合は再送信してください。(迷惑メールフォルダもご確認ください。)';
				},30000);
			}else{
				document.getElementById('check_account_display').innerHTML='アカウント名もしくはメールアドレスが登録されていません';
				document.getElementById('check_account_display').setAttribute('class','red');
				document.getElementById('check_account').innerHTML='送信';
				document.getElementById('check_account').style.display='inline';
			}
		},
		function(data){
			console.log('情報の取得に失敗しました。初めからやり直してください');
		}
	);
}
//manage/setting/basis
function manage_setting_basis(type){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_list.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "t":type
        }
	}).then(
		function(data){
			if(data['res']){
				var parent=document.getElementById('basis_list');
				parent.innerHTML="";
				if(type==='ndc'){
					for(var i=0;i<Object.keys(data['list']).length;++i){
						var child=document.createElement('li');
						child.dataset.id=i;
						child.innerHTML='<a href="javascript:void(0)" onClick="select_data(this);">'+data['list'][i].ndc+'&nbsp;'+data['list'][i].name+'</a><input type="hidden" name="id['+i+']" value="'+data['list'][i].id+'"><input type="hidden" name="cond['+i+']" value="keep"><input type="hidden" name="name['+i+']" value="'+data['list'][i].name+'"><input type="hidden" name="ndc['+i+']" value="'+data['list'][i].ndc+'">';
						parent.appendChild(child);
					}
				}else if(type==='book_store'){
					for(var i=0;i<Object.keys(data['list']).length;++i){
						var child=document.createElement('li');
						child.dataset.id=i;
						child.innerHTML='<a href="javascript:void(0)" onClick="select_data(this);">'+data['list'][i].name+'('+data['list'][i].code+')'+'</a><input type="hidden" name="id['+i+']" value="'+data['list'][i].id+'"><input type="hidden" name="cond['+i+']" value="keep"><input type="hidden" name="name['+i+']" value="'+data['list'][i].name+'"><input type="hidden" name="code['+i+']" value="'+data['list'][i].code+'">';
						parent.appendChild(child);
					}
				}else{
					for(var i=0;i<Object.keys(data['list']).length;++i){
						var child=document.createElement('li');
						child.dataset.id=i;
						child.innerHTML='<a href="javascript:void(0)" onClick="select_data(this);">'+data['list'][i].name+'</a><input type="hidden" name="id['+i+']" value="'+data['list'][i].id+'"><input type="hidden" name="cond['+i+']" value="keep"><input type="hidden" name="name['+i+']" value="'+data['list'][i].name+'">';
						parent.appendChild(child);
					}
				}
			}
		},
		function(data){
			console.log(data);
			alert('情報の取得に失敗しました。初めからやり直してください');
		}
	);
}
//manage/books/entry
function manage_books_entry_get_book_info(isbn){
	$.ajax({
		// リクエスト方法
        type: "GET",
        // 送信先ファイル名
        url: "https://api.openbd.jp/v1/get",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "isbn":isbn
        }
	}).then(
		function(data){
			if(data[0]){
				if(data[0]['onix']['DescriptiveDetail']){
					var DescriptiveDetail=data[0]['onix']['DescriptiveDetail'];
					if(DescriptiveDetail['TitleDetail']){
						if(DescriptiveDetail['TitleDetail']['TitleElement']){
							if(DescriptiveDetail['TitleDetail']['TitleElement']['TitleText']){
								//書名
								if(DescriptiveDetail['TitleDetail']['TitleElement']['TitleText']['content']){
									document.getElementById('manage_books_entry_name').value=DescriptiveDetail['TitleDetail']['TitleElement']['TitleText']['content'];
								}else{
									document.getElementById('manage_books_entry_name').value="";
								}
								//ふりがな
								if(DescriptiveDetail['TitleDetail']['TitleElement']['TitleText']['collationkey']){
									document.getElementById('manage_books_entry_kana').value=DescriptiveDetail['TitleDetail']['TitleElement']['TitleText']['collationkey'];
								}else{
									document.getElementById('manage_books_entry_kana').value='';
								}
							}
							//副書名
							if(DescriptiveDetail['TitleDetail']['TitleElement']['Subtitle']){
								document.getElementById('manage_books_entry_sub_name').value=DescriptiveDetail['TitleDetail']['TitleElement']['Subtitle']['content'];
							}else{
								document.getElementById('manage_books_entry_sub_name').value='';
							}
						}
					}
					//シリーズ
					if(DescriptiveDetail['Collection']){
						if(DescriptiveDetail['Collection']['TitleDetail']){
							if(DescriptiveDetail['Collection']['TitleDetail']['TitleElement']){
								document.getElementById('manage_books_entry_series').value=DescriptiveDetail['Collection']['TitleDetail']['TitleElement'][0]['TitleText']['content'];
							}
						}
					}
					//著者
					if(DescriptiveDetail['Contributor']){
						for(var i=0;i<Object.keys(DescriptiveDetail['Contributor']).length;++i){
							if(DescriptiveDetail['Contributor'][i]['PersonName']['content']){
								document.getElementById('manage_books_entry_author_'+i).value=DescriptiveDetail['Contributor'][i]['PersonName']['content'];
							}else{
								document.getElementById('manage_books_entry_author_'+i).value='';
							}
							if(DescriptiveDetail['Contributor'][i]['PersonName']['collationkey']){
								document.getElementById('manage_books_entry_author_kana_'+i).value=DescriptiveDetail['Contributor'][i]['PersonName']['collationkey'];
							}else{
								document.getElementById('manage_books_entry_author_kana_'+i).value="";
							}
							if(DescriptiveDetail['Contributor'][i]['BiographicalNote']){
								document.getElementById('manage_books_entry_author_introduction_'+i).value=DescriptiveDetail['Contributor'][i]['BiographicalNote'];
							}else{
								document.getElementById('manage_books_entry_author_introduction_'+i).value='';
							}
						}
					}else{
						for(var i=0;i<Object.keys(DescriptiveDetail['Contributor']).length;++i){
							document.getElementById('manage_books_entry_author_'+i).value='';
							document.getElementById('manage_books_entry_author_kana_'+i).value="";
							document.getElementById('manage_books_entry_author_introduction_'+i).value='';
						}
					}
					//サイズ
					if(DescriptiveDetail['Measure']){
						var content="";
						for(var i=0;i<Object.keys(DescriptiveDetail['Measure']).length;++i){
							if(DescriptiveDetail['Measure'][i]['MeasureType']==='01'){
								content=DescriptiveDetail['Measure'][i]['Measurement']+DescriptiveDetail['Measure'][i]['MeasureUnitCode'];
								break;
							}
						}
						for(var i=0;i<Object.keys(DescriptiveDetail['Measure']).length;++i){
							if(DescriptiveDetail['Measure'][i]['MeasureType']==='02'){
								content+='×'+DescriptiveDetail['Measure'][i]['Measurement']+DescriptiveDetail['Measure'][i]['MeasureUnitCode'];
								break;
							}
						}
						for(var i=0;i<Object.keys(DescriptiveDetail['Measure']).length;++i){
							if(DescriptiveDetail['Measure'][i]['MeasureType']==='03'){
								content+='×'+DescriptiveDetail['Measure'][i]['Measurement']+DescriptiveDetail['Measure'][i]['MeasureUnitCode'];
								break;
							}
						}
						document.getElementById('manage_books_entry_size').value=content;
					}else{
						document.getElementById('manage_books_entry_size').value='';
					}
					//ページ数
					if(DescriptiveDetail['Extent']){
						if(DescriptiveDetail['Extent'][0]['ExtentValue']){
							document.getElementById('manage_books_entry_pages').value=DescriptiveDetail['Extent'][0]['ExtentValue'];
						}else{
							document.getElementById('manage_books_entry_pages').value='';
						}
					}
				}
				if(data[0]['onix']['PublishingDetail']){
					var PublishingDetail=data[0]['onix']['PublishingDetail'];
					//出版社
					if(PublishingDetail['Imprint']){
						if(PublishingDetail['Imprint']['ImprintName']){
							document.getElementById('manage_books_entry_publisher').value=PublishingDetail['Imprint']['ImprintName'];
						}else{
							document.getElementById('manage_books_entry_publisher').value='';
						}
					}
					if(PublishingDetail['PublishingDate']){
						//出版日
						if(PublishingDetail['PublishingDate'][0]['Date']){
							var publish_date=PublishingDetail['PublishingDate'][0]['Date'];
							document.getElementById('manage_books_entry_publish_date').value=publish_date.substring(0,4)+'-'+publish_date.substring(4,6)+'-'+publish_date.substring(6);
						}else{
							document.getElementById('manage_books_entry_publish_date').value='';
						}
					}
					
				}
				if(data[0]['onix']['CollateralDetail']){
					var CollateralDetail=data[0]['onix']['CollateralDetail'];
					//内容紹介
					if(CollateralDetail['TextContent']){
						var content="";
						for(var i=0;i<Object.keys(CollateralDetail['TextContent']).length;++i){
							content+=CollateralDetail['TextContent'][i]['Text'];
						}
						document.getElementById('manage_books_entry_note').value=content;
					}else{
						document.getElementById('manage_books_entry_note').value='';
					}
					if(CollateralDetail['SupportingResource']){
						if(CollateralDetail['SupportingResource'][0]){
							if(CollateralDetail['SupportingResource'][0]['ResourceVersion']){
								if(CollateralDetail['SupportingResource'][0]['ResourceVersion'][0]){
									if(CollateralDetail['SupportingResource'][0]['ResourceVersion'][0]['ResourceLink']){
										//書影
										if(CollateralDetail['SupportingResource'][0]['ResourceVersion'][0]['ResourceLink']){
											document.getElementById('manage_books_entry_img').src=CollateralDetail['SupportingResource'][0]['ResourceVersion'][0]['ResourceLink'];
											document.getElementById('manage_books_entry_img_input').value=CollateralDetail['SupportingResource'][0]['ResourceVersion'][0]['ResourceLink'];
										}else{
											document.getElementById('manage_books_entry_img').src='book_noimage.jpg';
											document.getElementById('manage_books_entry_img_input').value='';
										}
									}
								}
							}
						}
					}
					
				}
				if(data[0]['onix']['ProductSupply']){
					var ProductSupply=data[0]['onix']['ProductSupply'];
					//金額
					if(ProductSupply['SupplyDetail']){
						if(ProductSupply['SupplyDetail']['Price']){
							if(ProductSupply['SupplyDetail']['Price'][0]['PriceAmount']){
								document.getElementById('manage_books_entry_price').value=ProductSupply['SupplyDetail']['Price'][0]['PriceAmount'];
							}else{
								document.getElementById('manage_books_entry_price').value='';
							}
						}
					}
				}
				document.getElementById('manage_books_entry_anounce_back').style.visibility='hidden';
				document.getElementById('manage_books_entry_anounce_wrapper').style.visibility='hidden';
			}else{
				alert('情報の取得に失敗しました');
				document.getElementById('manage_books_entry_anounce_back').style.visibility='hidden';
				document.getElementById('manage_books_entry_anounce_wrapper').style.visibility='hidden';
			}
		},
		function(data){
			alert('情報の取得に失敗しました');
		}
	);
}
//manage/books/entry
function manage_books_list_get_book_info(){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_bookinfo.php",
        // 受け取りデータの種類
        datatype: "json"
	}).then(
		function(data){
			if(data['res']){
				var parent=document.getElementById('manage_books_list_body');
				var tr='';
				var td='';
				var n=0;
				for(var i=0;i<Object.keys(data['info']).length;++i){
					tr=document.createElement('tr');
					parent.appendChild(tr);
					for(n=0;n<Object.keys(data['info'][i]).length;++n){
						td=document.createElement('td');
						td.innerHTML=data['info'][i][n];
						tr.appendChild(td);
					}
				}
			}
			document.getElementById('announce_back').style.display='none';
			document.getElementById('announce_wrapper').style.display='none';
			window.print();
			document.getElementById('manage_books_list_print_button').style.opacity=1;
		},
		function(data){
			alert('情報の取得に失敗しました');
		}
	);
}
//NDC取得
function get_ndc_from_ndl(isbn){
	/*$.ajax({
		type: 'GET',
        // 送信先ファイル名
        url: "http://iss.ndl.go.jp/api/sru?operation=searchRetrieve&query=isbn%3d%22"+isbn+"%22",
        // 受け取りデータの種類
        datatype: "xml"
	}).then(
		function(data){
			console.log(data);
		},
		function(data){
			console.log('情報の取得に失敗しました。初めからやり直してください');
			//window.location.reload();
		}
	);*/
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200) {
				console.log(xmlhttp.responseXML.documentElement);
			} else {
				alert("status = " + xmlhttp.status);
			}
		}
	}
	xmlhttp.open("GET", "https://iss.ndl.go.jp/api/sru?operation=searchRetrieve&query=isbn%3d%22"+isbn+"%22");
	xmlhttp.send();
}
//manage/books/label
function book_name(book_id,element){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_bookname.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "i":book_id
        }
	}).then(
		function(data){
			if(data['res']){
				element.innerHTML=data['book_name'];
			}else{
				console.log('本の名前を取得できませんでした');
			}
		},
		function(data){
			console.log('本の名前を取得できませんでした');
		}
	);
}
//manage/books/label
function book_back_label(book_id,number,hiragana,series){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_back.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "i":book_id
        }
	}).then(
		function(data){
			if(data['res']){
				number.innerHTML=data['number'];
				hiragana.innerHTML=data['hiragana'];
				series.innerHTML=data['series'];
			}else{
				console.log('本の請求記号を取得できませんでした');
			}
		},
		function(data){
			console.log('本の請求記号を取得できませんでした');
		}
	);
}
//manage/other/list
function manage_other_list_get_book_info(kind){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_bookinfo.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "k":kind
        }
	}).then(
		function(data){
			var body=document.getElementById('manage_other_list_table_body');
			body.innerHTML='';
			if(data['res']){
				var tr='';
				var td='';
				var res='';
				for(var i=0;i<Object.keys(data['result']).length;++i){
					res=data['result'][i];
					tr=document.createElement('tr');
					body.appendChild(tr);
					td=document.createElement('td');
					td.innerHTML=res['book_id'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['date'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['book_name'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['author'].join('<br>');
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['ndc'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['location'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['financial'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['store'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['price'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['publisher'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['publish_year'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['size'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['page'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['seikyu'];
					tr.appendChild(td);
					td=document.createElement('td');
					td.innerHTML=res['delete'];
					tr.appendChild(td);
				}
			}
			document.getElementById('manage_other_list_back').style.display='none';
			document.getElementById('manage_other_list_msg_wrapper').style.display='none';
			window.print();
			document.getElementById('manage_other_print_button').style.opacity=1;
		},
		function(data){
			alert('情報の取得に失敗しました');
		}
	);
}
//manage/other/statistics
function manage_other_statistics_create(kind){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_statistics.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "k":kind
        }
	}).then(
		function(data){
			//console.log(data);
			if(data['res']){
				var head=document.getElementById('manage_other_statistics_table_head');
				var body=document.getElementById('manage_other_statistics_table_body');
				head.innerHTML='';
				body.innerHTML='';
				var th='';
				var td='';
				var be='';
				var n=0;
				var tr=document.createElement('tr');
				head.appendChild(tr);
				for(var i=0;i<Object.keys(data['head']).length;++i){
					th=document.createElement('th');
					th.innerHTML=data['head'][i];
					tr.appendChild(th);
				}
				for(i=0;i<Object.keys(data['body']).length;++i){
					be=data['body'][i];
					tr=document.createElement('tr');
					body.appendChild(tr);
					if(be['number']){
						td=document.createElement('td');
						td.innerHTML=be['number'][0];
						tr.appendChild(td);
						for(n=1;n<Object.keys(be['number']).length;++n){
							td=document.createElement('td');
							td.innerHTML=be['number'][n]+'<br>'+be['price'][n];
							tr.appendChild(td);
						}
					}else{
						for(n=0;n<Object.keys(be).length;++n){
							td=document.createElement('td');
							td.innerHTML=be[n];
							tr.appendChild(td);
						}
					}
				}
			}
			document.getElementById('announce_back').style.display='none';
			document.getElementById('announce_wrapper').style.display='none';
		//	window.print();
			document.getElementById('manage_other_statistics_button').style.opacity=1;
		},
		function(data){
			alert('情報の取得に失敗しました');
		}
	);
}
//manage/other/statistics
function manage_user_entry_get_name(id,element){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_user.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "i":id
        }
	}).then(
		function(data){
			if(data['res']){
				if(data['name']==='Exist'){
					element.dataset.accept='none';
					alert('既に追加されています');
				}else{
					element.innerHTML=data['name'];
				}
			}else{
				element.dataset.accept='none';
				alert('存在しない利用者番号です');
			}
		},
		function(data){
			alert('情報の取得に失敗しました');
		}
	);
}
//counter/borrow
function counter_get_entry_user(id,ele){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_entry_user.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "i":id
        }
	}).then(
		function(data){
			if(data['res']){
				document.getElementById('counter_borrow_menu_user').innerHTML=data['name']+'('+id+')が作業中<br><a href="javascript:void(0)" onClick="change_user();">変更(F4)</a>';
				document.getElementById('counter_borrow_entry_user').style.display='none';
				document.getElementById('counter_borrow_wrapper').style.display='block';
			}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert(data['reason']);
					ele.value='';
					ele.focus();
				},500);
			}
		},
		function(data){
			
		}
	);
}
//counter/borrow
function counter_get_user(id){
	$.ajax({
		// リクエスト方法
        type: "POST",
        // 送信先ファイル名
        url: "get_user.php",
        // 受け取りデータの種類
        datatype: "json",
        // 送信データ
        data:{
            "i":id
        }
	}).then(
		function(data){
			console.log(data);
			document.getElementById('counter_borrow_deal_borrow_list').innerHTML='';
			var borrowing_list=document.getElementById('counter_borrow_deal_borrowed_list');
			borrowing_list.innerHTML='';
			document.getElementById('counter_borrow_deal_borrow_header_user_belong').innerHTML='';
			document.getElementById('counter_borrow_deal_borrow_header_user_name').innerHTML='';
			document.getElementById('counter_borrow_deal_borrow_header_reversedate').value='';
			document.getElementById('counter_borrow_deal_borrow_header_book_limit').innerHTML='';
			document.getElementById('counter_borrow_deal_borrow_header_book_limit_value').value='';
			if(data['res']){
				document.getElementById('counter_borrow_deal_borrow_header_user_belong').innerHTML=data['belong'];
				document.getElementById('counter_borrow_deal_borrow_header_user_name').innerHTML=data['name'];
				document.getElementById('counter_borrow_deal_borrow_header_reversedate').value=data['reverse_date'];
				document.getElementById('counter_borrow_deal_borrow_header_book_limit').innerHTML=data['limit'];
				document.getElementById('counter_borrow_deal_borrow_header_book_limit_value').value=data['limit'];
				var li='';
				for(var i=0;i<Object.keys(data['borrowing']).length;++i){
					li=document.createElement('li');
					li.innerHTML=data['borrowing'][i]['id']+'&nbsp;&nbsp;'+data['borrowing'][i]['name'];
					borrowing_list.appendChild(li);
				}
				document.getElementById('counter_borrow_deal_borrow_header_book').focus();
			}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert('存在しないユーザーです');
				},500);
				document.getElementById('counter_borrow_deal_borrow_header_user').focus();
				document.getElementById('counter_borrow_deal_borrow_header_user').value='';
			}
		},
		function(data){
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('情報の取得に失敗しました');
			},500);
		}
	);
}
//counter/borrow
function counter_get_book(id){
	if(document.getElementById('counter_borrow_deal_borrow_header_user').value!=''){
		var limit=document.getElementById('counter_borrow_deal_borrow_header_book_limit_value').value;
		var cnt=Number(document.getElementById('counter_borrow_deal_borrow_list').getElementsByTagName('li').length)+Number(document.getElementById('counter_borrow_deal_borrowed_list').getElementsByTagName('li').length);
		var book_id=document.getElementById('counter_borrow_deal_borrow_header_book');
		var ul=document.getElementById('counter_borrow_deal_borrow_list');
		var list=ul.getElementsByTagName('li');
		var res=true;
		for(var i=0;i<list.length;++i){
			console.log(list[i].dataset.id)
			if(list[i].dataset.id==id){
				res=false;
				break;
			}
		}
		if(cnt>=limit){
			//制限数オーバー
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('貸し出し冊数を超えたため，貸出出来ません');
			},500);
		}else if(!res){
			//被り
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('既に登録されている本です');
			},500);
		}else{
			$.ajax({
				// リクエスト方法
				type: "POST",
				// 送信先ファイル名
				url: "get_book.php",
				// 受け取りデータの種類
				datatype: "json",
				// 送信データ
				data:{
					"i":id
				}
			}).then(
				function(data){
					if(data['res']){
						var li=document.createElement('li');
						li.innerHTML=data['id']+'&nbsp;&nbsp;'+data['name']+'<input type="hidden" class="counter_borrow_book" value="'+data['id']+'">';
						li.dataset.id=id;
						ul.appendChild(li);
					}else{
						sound_audio('alert.mp3');
						setTimeout(function(){
							alert(data['reason']);
						},500);
					}
				},
				function(data){
					sound_audio('alert.mp3');
					setTimeout(function(){
						alert('情報の取得に失敗しました');
					},500);
				}
			);
		}
		book_id.value='';
		book_id.focus();
	}else{
		sound_audio('alert.mp3');
		setTimeout(function(){
			alert('利用者番号を入力してください');
		},500);
		document.getElementById('counter_borrow_deal_borrow_header_book').value='';
		document.getElementById('counter_borrow_deal_borrow_header_user').focus();
	}
}
//counter/borrow
function counter_borrow_book(){
	if(document.getElementById('counter_borrow_deal_borrow_header_user').value!='' && document.getElementById('counter_borrow_deal_borrow_header_borrowdate').value!='' && document.getElementById('counter_borrow_deal_borrow_header_reversedate').value!=''){
		var ary=document.getElementsByClassName('counter_borrow_book');
		var book_id=[];
		for(var i=0;i<ary.length;++i){
			book_id.push(ary[i].value);
		}
		$.ajax({
			// リクエスト方法
			type: "POST",
			// 送信先ファイル名
			url: "borrow_book.php",
			// 受け取りデータの種類
			datatype: "json",
			// 送信データ
			data:{
				"book":book_id.join('-'),
				"user":document.getElementById('counter_borrow_deal_borrow_header_user').value,
				"borrow_date":document.getElementById('counter_borrow_deal_borrow_header_borrowdate').value,
				"reverse_date":document.getElementById('counter_borrow_deal_borrow_header_reversedate').value,
				"entry_user":document.getElementById('counter_borrow_entry_user_value').value
			}
		}).then(
			function(data){
				console.log(data);
				if(data['res']){
					document.getElementById('counter_borrow_deal_borrow_header_user').value='';
					document.getElementById('counter_borrow_deal_borrow_header_book').value='';
					document.getElementById('counter_borrow_deal_borrow_header_reversedate').value='';
					document.getElementById('counter_borrow_deal_borrow_list').innerHTML='';
					document.getElementById('counter_borrow_deal_borrowed_list').innerHTML='';
					document.getElementById('counter_borrow_deal_borrow_header_user_belong').innerHTML='';
					document.getElementById('counter_borrow_deal_borrow_header_user_name').innerHTML='';
					document.getElementById('counter_borrow_deal_borrow_header_book_limit').innerHTML='';
					document.getElementById('counter_borrow_deal_borrow_header_book_limit_value').value='';
					document.getElementById('counter_borrow_deal_borrow_header_user').focus();
				}else{
					sound_audio('alert.mp3');
					setTimeout(function(){
						alert(data['reason']);
					},500);
				}
			},
			function(data){
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert('貸出処理ができませんでした');
				},500);
			}
		);
	}else{
		sound_audio('alert.mp3');
		setTimeout(function(){
			alert('必要事項を入力してください');
		},500);
		document.getElementById('counter_borrow_deal_borrow_header_book').value='';
		document.getElementById('counter_borrow_deal_borrow_header_user').focus();
	}
}
//counter/borrow
function counter_reverse_book(){
	var book=document.getElementById('counter_reverse_deal_book');
	if(book.value!=''){
		$.ajax({
			// リクエスト方法
			type: "POST",
			// 送信先ファイル名
			url: "reverse_book.php",
			// 受け取りデータの種類
			datatype: "json",
			// 送信データ
			data:{
				"i":book.value
			}
		}).then(
			function(data){
				console.log(data);
				if(data['res']){
					document.getElementById('counter_reverse_deal_user').innerHTML=data['user'];
					document.getElementById('counter_reverse_deal_bookname').innerHTML=data['bookname'];
					document.getElementById('counter_reverse_deal_bookid').innerHTML=data['bookid'];
					document.getElementById('counter_reverse_deal_seikyu').innerHTML=data['seikyu'];
					document.getElementById('counter_reverse_deal_location').innerHTML=data['location'];
					document.getElementById('counter_reverse_deal_borrowdate').innerHTML=data['borrowdate'];
					var reserve=document.getElementById('counter_reverse_deal_reserve');
					if(!data['reserve']){
						reserve.innerHTML='無';
						reserve.removeAttribute('class');
					}else{
						sound_audio('reserve.mp3');
						setTimeout(function(){
							reserve.innerHTML=data['reserve'];
							reserve.setAttribute('class','red');
						},500);
					}
					var body=document.getElementById('counter_reverse_deal_notreverse');
					body.innerHTML='';
					var tr='';
					var td='';
					for(var i=0;i<Object.keys(data['notreverse']).length;++i){
						tr=document.createElement('tr');
						body.appendChild(tr);
						td=document.createElement('td');
						td.innerHTML=data['notreverse'][i]['id'];
						tr.appendChild(td);
						td=document.createElement('td');
						td.innerHTML=data['notreverse'][i]['name'];
						tr.appendChild(td);
						td=document.createElement('td');
						td.innerHTML=data['notreverse'][i]['borrow'];
						tr.appendChild(td);
						td=document.createElement('td');
						td.innerHTML=data['notreverse'][i]['reverse'];
						tr.appendChild(td);
					}
				}else{
					sound_audio('alert.mp3');
					setTimeout(function(){
						alert(data['reason']);
					},500);
				}
			},
			function(data){
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert('返却処理ができませんでした2');
				},500);
			}
		);
	}else{
		sound_audio('alert.mp3');
		setTimeout(function(){
			alert('図書番号を入力してください');
		},500);
	}
	book.value='';
	book.focus();
}
//counter/borrow
function counter_reserve_get_book(id){
	var book=document.getElementById('counter_borrow_deal_reserve_book');
	if(book.value!=''){
		$.ajax({
			type: "POST",
			url: "reserve/get_book.php",
			datatype: "json",
			data:{
				"i":id
			}
		}).then(
			function(data){
				if(data['res']){
					document.getElementById('counter_borrow_deal_reserve_table_bookname').innerHTML=data['name'];
					document.getElementById('counter_borrow_deal_reserve_table_seikyu').innerHTML=data['seikyu'];
					document.getElementById('counter_borrow_deal_reserve_table_location').innerHTML=data['location'];
					document.getElementById('counter_borrow_deal_reserve_table_condtion').innerHTML=data['condition'];
					document.getElementById('counter_borrow_deal_reserve_user').value='';
					document.getElementById('counter_borrow_deal_reserve_user').focus();
				}else{
					sound_audio('alert.mp3');
					setTimeout(function(){
						alert(data['reason']);
						document.getElementById('counter_borrow_deal_reserve_user').value='';
						document.getElementById('counter_borrow_deal_reserve_table_bookname').innerHTML='';
						document.getElementById('counter_borrow_deal_reserve_table_seikyu').innerHTML='';
						document.getElementById('counter_borrow_deal_reserve_table_location').innerHTML='';
						document.getElementById('counter_borrow_deal_reserve_table_condtion').innerHTML='';
						book.value='';
						book.focus();
					},500);
				}
			},
			function(data){
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert('情報を取得できませんでした');
				},500);
			}
		);
	}else{
		sound_audio('alert.mp3');
		setTimeout(function(){
			alert('図書番号を入力してください');
		},500);
	}
}
//counter/borrow
function counter_reserve_get_user(id){
	var user=document.getElementById('counter_borrow_deal_reserve_user');
	if(user.value!=''){
		$.ajax({
			type: "POST",
			url: "reserve/get_user.php",
			datatype: "json",
			data:{
				"i":id
			}
		}).then(
			function(data){
				if(data['res']){
					document.getElementById('counter_borrow_deal_reserve_user_belong').innerHTML=data['belong'];
					document.getElementById('counter_borrow_deal_reserve_user_name').innerHTML=data['name'];
				}else{
					sound_audio('alert.mp3');
					setTimeout(function(){
						alert(data['reason']);
					},500);
				}
			},
			function(data){
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert('情報を取得できませんでした');
				},500);
			}
		);
	}else{
		sound_audio('alert.mp3');
		setTimeout(function(){
			alert('利用者番号を入力してください');
		},500);
	}
}
//counter/borrow
function counter_reserve_book(){
	var user=document.getElementById('counter_borrow_deal_reserve_user');
	var book=document.getElementById('counter_borrow_deal_reserve_book');
	if(user.value!='' && book.value!=''){
		$.ajax({
			type: "POST",
			url: "reserve/reserve_book.php",
			datatype: "json",
			data:{
				"user":user.value,
				"book":book.value,
				"entry_user":document.getElementById('counter_borrow_entry_user_value').value
			}
		}).then(
			function(data){
				if(data['res']){
					book.value='';
					user.value='';
					book.focus();
				}else{
					sound_audio('alert.mp3');
					setTimeout(function(){
						alert(data['reason']);
					},500);
				}
			},
			function(data){
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert('情報を取得できませんでした');
				},500);
			}
		);
	}else{
		sound_audio('alert.mp3');
		setTimeout(function(){
			alert('利用者番号を入力してください');
		},500);
	}
}
//counter/search
function counter_search(){
	document.getElementById('announce_back').style.display='block';
	document.getElementById('announce_wrapper').style.display='block';
	var method=document.getElementById('counter_search_method');
	var range=document.getElementById('counter_search_range');
	$.ajax({
		type: "POST",
		url: "search.php",
		datatype: "json",
		data:{
			"name":document.getElementById('counter_search_name').value,
			"author":document.getElementById('counter_search_author').value,
			"publisher":document.getElementById('counter_search_publisher').value,
			"keyword":document.getElementById('counter_search_keyword').value,
			"method":method.options[method.selectedIndex].value,
			"range":range.options[range.selectedIndex].value
		}
	}).then(
		function(data){
			//console.log(data);
			//if(data['res']){
			var body=document.getElementById('counter_search_result');
			body.innerHTML='';
			var tr='';
			var td='';
			var n=0;
			for(var i=0;i<Object.keys(data).length;++i){
				tr=document.createElement('tr');
				body.appendChild(tr);
				for(n=0;n<Object.keys(data[i]).length;++n){
					td=document.createElement('td');
					td.innerHTML=data[i][n];
					tr.appendChild(td);
				}
			}
				document.getElementById('announce_back').style.display='none';
				document.getElementById('announce_wrapper').style.display='none';
			/*}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert(data['reason']);
					document.getElementById('announce_back').style.display='none';
					document.getElementById('announce_wrapper').style.display='none';
				},500);
			}*/
		},
		function(data){
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('情報を取得できませんでした');
			},500);
		}
	);
}
//counter/reserve/list
function counter_reserve_list(page){
	$.ajax({
		type: "GET",
		url: "get_list.php",
		datatype: "json",
		data:{
			"page":Number(page) + Number(document.getElementById('counter_reserve_current_page_value').value)
		}
	}).then(
		function(data){
			console.log(data);
			if(data['res']){
				document.getElementById('counter_reserve_list_current_page').innerHTML=Number(data['current_page']);
				document.getElementById('counter_reserve_current_page_value').value=Number(data['current_page']);
				if(data['current_page']==1){
					document.getElementById('counter_reserve_previous_button').style.display='none';
				}else{
					document.getElementById('counter_reserve_previous_button').style.display='inline';
				}
				if(data['current_page']==data['all_pages']){
					document.getElementById('counter_reserve_next_button').style.display='none';
				}else{
					document.getElementById('counter_reserve_next_button').style.display='inline';
				}
				document.getElementById('counter_reserve_list_all_pages').innerHTML=Number(data['all_pages']);
				document.getElementById('counter_reserve_list_all_records').innerHTML=Number(data['all_records']);
				document.getElementById('counter_reserve_list_first_record').innerHTML=Number(data['first_record']);
				document.getElementById('counter_reserve_list_last_record').innerHTML=Number(data['last_record']);
				var body=document.getElementById('counter_reserve_list_body');
				body.innerHTML='';
				var tr='';
				var td='';
				var n=0;
				for(var i=0;i<Object.keys(data['record']).length;++i){
					tr=document.createElement('tr');
					tr.dataset.condition=data['record'][i]['condition_id'];
					body.appendChild(tr);
					for(n=0;n<Object.keys(data['record'][i]).length-1;++n){
						td=document.createElement('td');
						td.innerHTML=data['record'][i][n];
						tr.appendChild(td);
					}
				}
				document.getElementById('announce_back').style.display='none';
				document.getElementById('announce_wrapper').style.display='none';
			}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert(data['reason']);
					document.getElementById('announce_back').style.display='none';
					document.getElementById('announce_wrapper').style.display='none';
				},500);
			}
		},
		function(data){
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('情報を取得できませんでした');
			},500);
		}
	);
}
//foruser/books/
function foruser_change_page_borrow(page){
	document.getElementById('announce_back').style.display='block';
	document.getElementById('announce_wrapper').style.display='block';
	$.ajax({
		type: "GET",
		url: "borrow.php",
		datatype: "json",
		data:{
			"p":page,
			"c":document.getElementById('borrow_history_current_page').value
		}
	}).then(
		function(data){
			console.log(data);
			if(data['res']){
				var history=document.getElementById('borrow_history');
				history.innerHTML='';
				var tr='';
				var td='';
				var n=0;
				for(var i=0;i<Object.keys(data['record']).length;++i){
					tr=document.createElement('tr');
					history.appendChild(tr);
					for(n=0;n<Object.keys(data['record'][i]).length;++n){
						td=document.createElement('td');
						td.innerHTML=data['record'][i][n];
						tr.appendChild(td);
					}
				}
				document.getElementById('borrow_history_page').innerHTML=data['current_page']+'/'+data['all_page']+'頁(全'+data['cnt']+'件)';
				document.getElementById('borrow_history_current_page').value=data['current_page'];
				if(data['current_page']===1){
					document.getElementsByClassName('previous_button')[0].classList.add('usable_page_button');
					document.getElementsByClassName('top_previous_button')[0].classList.add('usable_page_button');
				}else{
					document.getElementsByClassName('previous_button')[0].classList.remove('usable_page_button');
					document.getElementsByClassName('top_previous_button')[0].classList.remove('usable_page_button');
				}
				if(data['current_page']===data['all_page']){
					document.getElementsByClassName('next_button')[0].classList.add('usable_page_button');
					document.getElementsByClassName('last_next_button')[0].classList.add('usable_page_button');
				}else{
					document.getElementsByClassName('next_button')[0].classList.remove('usable_page_button');
					document.getElementsByClassName('last_next_button')[0].classList.remove('usable_page_button');
				}
				document.getElementById('announce_back').style.display='none';
				document.getElementById('announce_wrapper').style.display='none';
			}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert(data['reason']);
					document.getElementById('announce_back').style.display='none';
					document.getElementById('announce_wrapper').style.display='none';
				},500);
			}
		},
		function(data){
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('情報を取得できませんでした');
			},500);
		}
	);
}
//foruser/books/
function foruser_change_page_reserve(page){
	document.getElementById('announce_back').style.display='block';
	document.getElementById('announce_wrapper').style.display='block';
	$.ajax({
		type: "GET",
		url: "reserve.php",
		datatype: "json",
		data:{
			"p":page,
			"c":document.getElementById('reserve_history_current_page').value
		}
	}).then(
		function(data){
			console.log(data);
			if(data['res']){
				var history=document.getElementById('reserve_history');
				history.innerHTML='';
				var tr='';
				var td='';
				var n=0;
				for(var i=0;i<Object.keys(data['record']).length;++i){
					tr=document.createElement('tr');
					history.appendChild(tr);
					for(n=0;n<Object.keys(data['record'][i]).length;++n){
						td=document.createElement('td');
						td.innerHTML=data['record'][i][n];
						tr.appendChild(td);
					}
				}
				document.getElementById('reserve_history_page').innerHTML=data['current_page']+'/'+data['all_page']+'頁(全'+data['cnt']+'件)';
				document.getElementById('reserve_history_current_page').value=data['current_page'];
				if(data['current_page']===1){
					document.getElementsByClassName('previous_button')[1].classList.add('usable_page_button');
					document.getElementsByClassName('top_previous_button')[1].classList.add('usable_page_button');
				}else{
					document.getElementsByClassName('previous_button')[1].classList.remove('usable_page_button');
					document.getElementsByClassName('top_previous_button')[1].classList.remove('usable_page_button');
				}
				if(data['current_page']===data['all_page']){
					document.getElementsByClassName('next_button')[1].classList.add('usable_page_button');
					document.getElementsByClassName('last_next_button')[1].classList.add('usable_page_button');
				}else{
					document.getElementsByClassName('next_button')[1].classList.remove('usable_page_button');
					document.getElementsByClassName('last_next_button')[1].classList.remove('usable_page_button');
				}
				document.getElementById('announce_back').style.display='none';
				document.getElementById('announce_wrapper').style.display='none';
			}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert(data['reason']);
					document.getElementById('announce_back').style.display='none';
					document.getElementById('announce_wrapper').style.display='none';
				},500);
			}
		},
		function(data){
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('情報を取得できませんでした');
			},500);
		}
	);
}
//foruser/books/
function foruser_change_page_log(page){
	document.getElementById('announce_back').style.display='block';
	document.getElementById('announce_wrapper').style.display='block';
	$.ajax({
		type: "GET",
		url: "log.php",
		datatype: "json",
		data:{
			"p":page,
			"c":document.getElementById('log_history_current_page').value
		}
	}).then(
		function(data){
			if(data['res']){
				var history=document.getElementById('log_history');
				history.innerHTML='';
				var tr='';
				var td='';
				var n=0;
				for(var i=0;i<Object.keys(data['record']).length;++i){
					tr=document.createElement('tr');
					history.appendChild(tr);
					for(n=0;n<Object.keys(data['record'][i]).length;++n){
						td=document.createElement('td');
						td.innerHTML=data['record'][i][n];
						tr.appendChild(td);
					}
				}
				document.getElementById('log_history_page').innerHTML=data['current_page']+'/'+data['all_page']+'頁(全'+data['cnt']+'件)';
				document.getElementById('log_history_current_page').value=data['current_page'];
				if(data['current_page']===1){
					document.getElementsByClassName('previous_button')[2].classList.add('usable_page_button');
					document.getElementsByClassName('top_previous_button')[2].classList.add('usable_page_button');
				}else{
					document.getElementsByClassName('previous_button')[2].classList.remove('usable_page_button');
					document.getElementsByClassName('top_previous_button')[2].classList.remove('usable_page_button');
				}
				if(data['current_page']===data['all_page']){
					document.getElementsByClassName('next_button')[2].classList.add('usable_page_button');
					document.getElementsByClassName('last_next_button')[2].classList.add('usable_page_button');
				}else{
					document.getElementsByClassName('next_button')[2].classList.remove('usable_page_button');
					document.getElementsByClassName('last_next_button')[2].classList.remove('usable_page_button');
				}
				document.getElementById('announce_back').style.display='none';
				document.getElementById('announce_wrapper').style.display='none';
			}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert(data['reason']);
					document.getElementById('announce_back').style.display='none';
					document.getElementById('announce_wrapper').style.display='none';
				},500);
			}
		},
		function(data){
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('情報を取得できませんでした');
			},500);
		}
	);
}
//foruser/impression/entry/
function foruser_get_isbn(book_id){
	document.getElementById('announce_back').style.display='block';
	document.getElementById('announce_wrapper').style.display='block';
	$.ajax({
		type: "GET",
		url: "isbn.php",
		datatype: "json",
		data:{
			"book_id":book_id
		}
	}).then(
		function(data){
			console.log(data);
			if(data['res']){
				document.getElementById('isbn').value=data['isbn'];
				document.getElementById('announce_back').style.display='none';
				document.getElementById('announce_wrapper').style.display='none';
			}else{
				sound_audio('alert.mp3');
				setTimeout(function(){
					alert(data['reason']);
					document.getElementById('announce_back').style.display='none';
					document.getElementById('announce_wrapper').style.display='none';
				},500);
			}
		},
		function(data){
			sound_audio('alert.mp3');
			setTimeout(function(){
				alert('情報を取得できませんでした');
			},500);
		}
	);
}