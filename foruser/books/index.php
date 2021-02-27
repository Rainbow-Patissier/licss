<?php
include_once('../../data/link.php');
$title="利用状況";
include_once('../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>foruser/">
				利用者機能
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
	<div class="two_columns">
		<div>
			<h2>
				貸出履歴
			</h2>
			<div>
				<a href="javascript:void(0)" onClick="foruser_change_page_borrow('top');" class="top_previous_button page_button usable_page_button"></a>
				<a href="javascript:void(0)" onClick="foruser_change_page_borrow(-1);" class="previous_button  page_button usable_page_button"></a>
				<span id="borrow_history_page"></span><input type="hidden" id="borrow_history_current_page" value="1">
				<a href="javascript:void(0)" onClick="foruser_change_page_borrow(+1);" class="next_button  page_button usable_page_button"></a>
				<a href="javascript:void(0)" onClick="foruser_change_page_borrow('last');" class="last_next_button page_button usable_page_button"></a>
			</div>
			<div class="scroll">
				<table>
					<thead>
						<tr>
							<th>
								図書番号
							</th>
							<th>
								図書名
							</th>
							<th>
								貸出日時
							</th>
							<th>
								返却予定日
							</th>
							<th>
								返却日時
							</th>
						</tr>
					</thead>
					<tbody id="borrow_history"></tbody>
				</table>
			</div>
		</div>
		<div>
			<h2>
				予約履歴
			</h2>
			<div>
				<a href="javascript:void(0)" onClick="foruser_change_page_reserve('top');" class="top_previous_button page_button usable_page_button"></a>
				<a href="javascript:void(0)" onClick="foruser_change_page_reserve(-1);" class="previous_button  page_button usable_page_button"></a>
				<span id="reserve_history_page"></span><input type="hidden" id="reserve_history_current_page" value="1">
				<a href="javascript:void(0)" onClick="foruser_change_page_reserve(+1);" class="next_button  page_button usable_page_button"></a>
				<a href="javascript:void(0)" onClick="foruser_change_page_reserve('last');" class="last_next_button page_button usable_page_button"></a>
			</div>
			<div class="scroll">
				<table>
					<thead>
						<tr>
							<th>
								図書番号
							</th>
							<th>
								図書名
							</th>
							<th>
								予約日時
							</th>
							<th>
								状況
							</th>
						</tr>
					</thead>
					<tbody id="reserve_history"></tbody>
				</table>
			</div>
		</div>
		<div>
			<h2>
				ログイン履歴
			</h2>
			<div>
				<a href="javascript:void(0)" onClick="foruser_change_page_log('top');" class="top_previous_button page_button usable_page_button"></a>
				<a href="javascript:void(0)" onClick="foruser_change_page_log(-1);" class="previous_button  page_button usable_page_button"></a>
				<span id="log_history_page"></span><input type="hidden" id="log_history_current_page" value="1">
				<a href="javascript:void(0)" onClick="foruser_change_page_log(+1);" class="next_button  page_button usable_page_button"></a>
				<a href="javascript:void(0)" onClick="foruser_change_page_log('last');" class="last_next_button page_button usable_page_button"></a>
			</div>
			<div class="scroll">
				<table>
					<thead>
						<tr>
							<th>
								ログイン日時
							</th>
							<th>
								ログアウト日時
							</th>
						</tr>
					</thead>
					<tbody id="log_history"></tbody>
				</table>
			</div>
		</div>
	</div>
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
		foruser_change_page_borrow(0);
		setTimeout(function(){
			foruser_change_page_reserve(0);
			setTimeout(function(){
				foruser_change_page_log(0);
			},200);
		},200);
	</script>
</section><?php
include_once('../../data/footer.php');
?>