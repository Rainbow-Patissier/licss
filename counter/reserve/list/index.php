<?php
include_once('../../../data/link.php');
$title="予約一覧";
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>counter/">
				窓口業務
			</a>
		</li>
		<li>
			<a href="<?=$url;?>counter/reserve/">
				予約
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
		<select onChange="change_filter(this);">
			<option value="0">すべて</option>
			<option value="1">予約中</option>
			<option value="2">予約キャンセル済</option>
			<option value="3">貸出済</option>
		</select>
		<a href="javascript:void(0)" onClick="counter_reserve_list(-1);" id="counter_reserve_previous_button">前へ</a>
		<span id="counter_reserve_list_current_page">1</span>/<span id="counter_reserve_list_all_pages">5</span>ページ
		<input type="hidden" id="counter_reserve_current_page_value" value="1">
		<a href="javascript:void(0)" onClick="counter_reserve_list(+1);" id="counter_reserve_next_button">次へ</a>
	</div>
	<div>
		<span id="counter_reserve_list_all_records">450</span>件中<span id="counter_reserve_list_first_record">1</span>-<span id="counter_reserve_list_last_record">100</span>件目を表示
	</div>
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
					予約者
				</th>
				<th>
					予約日時
				</th>
				<th>
					状態
				</th>
			</tr>
		</thead>
		<tbody id="counter_reserve_list_body">
			<tr data-condition="3">
				<td>
					<?=sprintf('%09d',1);?>
				</td>
				<td>
					本の名前
				</td>
				<td>
					[6-1-1]池田悠真(99000001)
				</td>
				<td>
					2020.10.10 10:10:10
				</td>
				<td>
					貸出済
				</td>
			</tr>
		</tbody>
	</table>
	<div id="announce_back" class="no_print"></div>
	<div id="announce_wrapper" class="no_print">
		<div id="announce_msg">
			 <div class="balls-guruguru">
				<span class="ball ball-1"></span>
				<span class="ball ball-2"></span>
				<span class="ball ball-3"></span>
				<span class="ball ball-4"></span>
				<span class="ball ball-5"></span>
				<span class="ball ball-6"></span>
				<span class="ball ball-7"></span>
				<span class="ball ball-8"></span>
			</div>
		</div>
	</div>
	<script>
		counter_reserve_list(0);
		function change_filter(ele){
			var value=Number(ele.options[ele.selectedIndex].value);
			var tr=document.getElementById('counter_reserve_list_body').getElementsByTagName('tr');
			if(value===0){
				for(var i=0;i<tr.length;++i){
					tr[i].style.display='table-row';
				}
			}else{
				for(var i=0;i<tr.length;++i){
					if(tr[i].dataset.condition==value){
						tr[i].style.display='table-row';
					}else{
						tr[i].style.display='none';
					}
				}
			}
		}
	</script>
</section><?php
include_once('../../../data/footer.php');
?>