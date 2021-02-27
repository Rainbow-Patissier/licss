<?php
include_once('../../../data/link.php');
$title="蔵書検索";
include_once('../../../data/header.php');
?>
<nav id="bread_crumb">
	<ul id="bread_crumb_list">
		<li>
			<a href="<?=$url;?>foruser/">
				利用者機能
			</a>
		</li>
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
	<div>
		<table>
			<tbody>
				<tr>
					<th>
						書名
					</th>
					<td>
						<input type="search" id="counter_search_name" placeholder="書名" title="書名を入力してください">
					</td>
				</tr>
				<tr>
					<th>
						著者
					</th>
					<td>
						<input type="search" id="counter_search_author" placeholder="著者" title="著者を入力してください">
					</td>
				</tr>
				<tr>
					<th>
						出版社
					</th>
					<td>
						<input type="search" id="counter_search_publisher" placeholder="出版社" title="出版社を入力してください">
					</td>
				</tr>
				<tr>
					<th>
						キーワード
					</th>
					<td>
						<input type="search" id="counter_search_keyword" placeholder="キーワード" title="キーワードを入力してください">
					</td>
				</tr>
				<tr>
					<th>
						検索方法
					</th>
					<td>
						<select id="counter_search_method" title="検索方法を選択してください">
							<option value="and">かつ(AND)</option>
							<option value="or">または(OR)</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						検索範囲
					</th>
					<td>
						<select id="counter_search_range" title="検索範囲を選択してください">
							<option value="private">学内</option>
							<option value="area">市・町・村内</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<button type="reset" onClick="cancel();">
			リセット
		</button>
		<button type="button" onClick="search();">
			検索
		</button>
	</div>
	<div>
		<table>
			<caption>
				検索結果
			</caption>
			<thead>
				<tr>
					<th>
						図書番号
					</th>
					<th>
						書名
					</th>
					<th>
						著者
					</th>
					<th>
						出版社
					</th>
					<th>
						請求記号
					</th>
					<th>
						保管場所
					</th>
					<th>
						状態
					</th>
					<th>
						詳細
					</th>
				</tr>
			</thead>
			<tbody id="counter_search_result"></tbody>
		</table>
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
		function search(){
			document.getElementById('announce_back').style.display='block';
			document.getElementById('announce_wrapper').style.display='block';
			counter_search();
		}
		function cancel(){
			document.getElementById('counter_search_name').value='';
			document.getElementById('counter_search_author').value='';
			document.getElementById('counter_search_publisher').value='';
			document.getElementById('counter_search_keyword').value='';
		}
	</script>
</section><?php
include_once('../../../data/footer.php');
?>