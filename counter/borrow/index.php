<?php
include_once('../../data/link.php');
$title="貸出・返却・予約";
include_once('../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>counter/">
				窓口業務
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
		<a href="<?=$url;?>counter/reserve/list/" target="_blank">
			予約一覧
		</a>
		&nbsp;
		<a href="<?=$url;?>counter/borrow/list/" target="_blank">
			貸出中図書一覧
		</a>
		&nbsp;
		<a href="<?=$url;?>counter/borrow/request/" target="_blank">
			返却請求一覧
		</a>
	</div>
	<div id="counter_borrow_entry_user">
		貸出・返却作業を行う利用者番号を入力してください。<br>
		<input type="number" id="counter_borrow_entry_user_value" placeholder="利用者番号" title="利用者番号を入力してください" onChange="entry_user(this);" autofocus>
		<button type="button" onClick="entry_user(document.getElementById('counter_borrow_entry_user_value'));">
			次へ
		</button>
	</div>
	<div id="counter_borrow_wrapper">
		<ul id="counter_borrow_menu">
			<li class="counter_borrow_menu" onClick="change_menu('borrow');">
				貸出(F1)
			</li>
			<li class="counter_borrow_menu" onClick="change_menu('reverse');">
				返却(F2)
			</li>
			<li class="counter_borrow_menu" onClick="change_menu('reserve');">
				予約(F3)
			</li>
			<li class="counter_borrow_menu" id="counter_borrow_menu_user"></li>
		</ul>
		<div id="counter_borrow_deal_wrapper">
			<div id="counter_borrow_deal_borrow">
				<h3>
					貸出処理
				</h3>
				<div id="counter_borrow_deal_borrow_header">
					<div>
						<dl>
							<dt>
								利用者番号
							</dt>
							<dd>
								<input type="number" id="counter_borrow_deal_borrow_header_user" placeholder="利用者番号" title="利用者番号を入力してください" onChange="counter_get_user(this.value);"><br>
								利用者所属：<span id="counter_borrow_deal_borrow_header_user_belong"></span><br>
								利用者名：<span id="counter_borrow_deal_borrow_header_user_name"></span>
							</dd>
							<dt>
								図書番号
							</dt>
							<dd>
								<input type="number" id="counter_borrow_deal_borrow_header_book" placeholder="図書番号" title="図書番号を入力してください" onChange="counter_get_book(this.value);"><br>
								貸出制限数：<span id="counter_borrow_deal_borrow_header_book_limit"></span><input type="hidden" id="counter_borrow_deal_borrow_header_book_limit_value">
							</dd>
						</dl>
					</div>
					<div>
						<dl>
							<dt>
								貸出日・返却日
							</dt>
							<dd>
								貸出日：<input type="date" id="counter_borrow_deal_borrow_header_borrowdate" placeholder="貸出日" title="貸出日を入力してください" value="<?=$Ymd;?>" min="<?=$Ymd;?>"><br>
								返却日：<input type="date" id="counter_borrow_deal_borrow_header_reversedate" placeholder="返却日" title="返却日を入力してください" value="" min="<?=$Ymd;?>">
							</dd>
						</dl>
					</div>
					<div>
						<button type="button" onClick="counter_borrow_book();" class="big_button">貸出(F5)</button><br>
						<button type="button" onClick="borrow_cancel();" class="big_button">キャンセル(F6)</button>
					</div>
				</div>
				<div id="counter_borrow_deal_borrow_list_wrapper">
					<div>
						<ul id="counter_borrow_deal_borrow_list"></ul>
					</div>
					<div>
						未返却図書
						<ul id="counter_borrow_deal_borrowed_list"></ul>
					</div>
				</div>
			</div>
			<div id="counter_borrow_deal_reverse">
				<h3>
					返却処理
				</h3>
				<div id="counter_reverse_deal_wrapper">
					<div>
						<dl>
							<dt>
								図書番号
							</dt>
							<dd>
								<input type="number" id="counter_reverse_deal_book" placeholder="図書番号" title="図書番号を入力してください" onChange="counter_reverse_book();">
							</dd>
						</dl>
						<table>
							<tbody>
								<tr>
									<th>
										利用者
									</th>
									<td id="counter_reverse_deal_user"></td>
								</tr>
								<tr>
									<th>
										書名
									</th>
									<td id="counter_reverse_deal_bookname"></td>
								</tr>
								<tr>
									<th>
										図書番号
									</th>
									<td id="counter_reverse_deal_bookid"></td>
								</tr>
								<tr>
									<th>
										請求記号
									</th>
									<td id="counter_reverse_deal_seikyu"></td>
								</tr>
								<tr>
									<th>
										保管場所
									</th>
									<td id="counter_reverse_deal_location"></td>
								</tr>
								<tr>
									<th>
										貸出日
									</th>
									<td id="counter_reverse_deal_borrowdate"></td>
								</tr>
								<tr>
									<th>
										予約の有無
									</th>
									<td id="counter_reverse_deal_reserve"></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="scroll">
						未返却図書
						<table>
							<thead>
								<tr>
									<th>
										図書番号
									</th>
									<th>
										書名
									</th>
									<th>
										貸出日
									</th>
									<th>
										返却予定日
									</th>
								</tr>
							</thead>
							<tbody id="counter_reverse_deal_notreverse"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div id="counter_borrow_deal_reserve">
				<h3>
					予約
				</h3>
				<div id="counter_borrow_deal_reserve_header">
					<dl>
						<dt>
							図書番号
						</dt>
						<dd>
							<input type="number" id="counter_borrow_deal_reserve_book" placeholder="図書番号" title="図書番号を入力してください" onChange="counter_reserve_get_book(this.value);">
							<table>
								<tbody>
									<tr>
										<th>
											書名
										</th>
										<td id="counter_borrow_deal_reserve_table_bookname"></td>
									</tr>
									<tr>
										<th>
											請求記号
										</th>
										<td id="counter_borrow_deal_reserve_table_seikyu"></td>
									</tr>
									<tr>
										<th>
											保管場所
										</th>
										<td id="counter_borrow_deal_reserve_table_location"></td>
									</tr>
									<tr>
										<th>
											状態
										</th>
										<td id="counter_borrow_deal_reserve_table_condition"></td>
									</tr>
								</tbody>
							</table>
						</dd>
					</dl>
					<dl>
						<dt>
							利用者番号
						</dt>
						<dd>
							<input type="number" id="counter_borrow_deal_reserve_user" placeholder="利用者番号" title="利用者番号を入力してください" onChange="counter_reserve_get_user(this.value);"><br>
							利用者所属：<span id="counter_borrow_deal_reserve_user_belong"></span><br>
							利用者名：<span id="counter_borrow_deal_reserve_user_name"></span>
						</dd>
					</dl>
					<div>
						<button type="button" class="big_button" onClick="counter_reserve_book();">
							予約(F5)
						</button>
						<button type="button" class="big_button" onClick="reserve_cancel();">
							キャンセル(F6)
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		function entry_user(ele){
			counter_get_entry_user(ele.value,ele);
			change_menu('borrow');
			setTimeout(function(){
				document.getElementById('counter_borrow_deal_borrow_header_user').focus();
			},200);
		}
		function change_menu(value){
			switch(value){
				case 'borrow':
					document.getElementById('counter_borrow_deal_borrow').style.display='block';
					document.getElementById('counter_borrow_deal_reverse').style.display='none';
					document.getElementById('counter_borrow_deal_reserve').style.display='none';
					document.getElementById('counter_borrow_deal_borrow_header_user').focus();
					break;
				case 'reverse':
					document.getElementById('counter_borrow_deal_borrow').style.display='none';
					document.getElementById('counter_borrow_deal_reverse').style.display='block';
					document.getElementById('counter_borrow_deal_reserve').style.display='none';
					document.getElementById('counter_reverse_deal_book').focus();
					break;
				case 'reserve':
					document.getElementById('counter_borrow_deal_borrow').style.display='none';
					document.getElementById('counter_borrow_deal_reverse').style.display='none';
					document.getElementById('counter_borrow_deal_reserve').style.display='block';
					document.getElementById('counter_borrow_deal_reserve_book').focus();
					break;
			}
		}
		function borrow_cancel(){
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
		}
		function reserve_cancel(){
			document.getElementById('counter_borrow_deal_reserve_user_belong').innerHTML='';
			document.getElementById('counter_borrow_deal_reserve_user_name').innerHTML='';
			document.getElementById('counter_borrow_deal_reserve_table_bookname').innerHTML='';
			document.getElementById('counter_borrow_deal_reserve_table_seikyu').innerHTML='';
			document.getElementById('counter_borrow_deal_reserve_table_location').innerHTML='';
			document.getElementById('counter_borrow_deal_reserve_table_condtion').innerHTML='';
			document.getElementById('counter_borrow_deal_reserve_user').value='';
			document.getElementById('counter_borrow_deal_reserve_book').value='';
			document.getElementById('counter_borrow_deal_reserve_book').focus();
		}
		function change_user(){
			document.getElementById('counter_borrow_wrapper').style.display='none';
			document.getElementById('counter_borrow_deal_borrow').style.display='none';
			document.getElementById('counter_borrow_deal_reverse').style.display='none';
			document.getElementById('counter_borrow_deal_reserve').style.display='none';
			document.getElementById('counter_borrow_entry_user').style.display='block';
			document.getElementById('counter_borrow_entry_user_value').value='';
			document.getElementById('counter_borrow_entry_user_value').focus();
		}
		$(function() {
			shortcut.add("F1",function() {
				change_menu('borrow');
			});
			shortcut.add("F2",function() {
				change_menu('reverse');
			});
			shortcut.add("F3",function() {
				change_menu('reserve');
			});
			shortcut.add("F4",function() {
				change_user();
			});
			shortcut.add("F5",function() {
				if(document.getElementById('counter_borrow_deal_borrow').style.display==='block'){
					counter_borrow_book();
				}else if(document.getElementById('counter_borrow_deal_reserve').style.display==='block'){
					counter_reserve_book();
				}else{
					window.location.reload();
				}
			});
			shortcut.add("F6",function() {
				if(document.getElementById('counter_borrow_deal_borrow').style.display==='block'){
					borrow_cancel();
				}else if(document.getElementById('counter_borrow_deal_reserve').style.display==='block'){
					reserve_cancel();
				}
			});
		});
	</script>
</section><?php
include_once('../../data/footer.php');
?>