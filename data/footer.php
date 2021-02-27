		</section>
		<div id="to_pagetop"><i class="fas fa-angle-double-up"></i></div>
		<footer>
			<nav id="footer_menu">
				<ul class="inline">
					<li>
						<a href="<?=$url;?>data/term.pdf" target="_blank">
							利用規約
						</a>
					</li>
					<li>
						<a href="<?=$url;?>data/privacypolicy.pdf" target="_blank">
							プライバシーポリシー
						</a>
					</li>
					<!--<li>
						<a href="">
							マニュアル
						</a>
					</li>-->
				</ul>
			</nav>
			<section id="footer_copyright">
				Copyright &copy; 2020 <a href="https://rainbow-patissier.com/" target="_blank">虹色パティシエ</a> All Rights Reserved.
			</section>
		</footer>
	</body>
	<script>
		//Enterキーで送信無効
		document.getElementsByTagName('input').onkeypress=function(e){
			if(e.keyCode===13){
				return false;
			}
		}
		document.getElementsByTagName('button').onkeypress=function(e){
			if(e.keyCode===13){
				return false;
			}
		}
		//ページ内移動スクロール
		$(function(){
		  $('a[href^="#"]').click(function(){
			var speed = 500;
			var href= $(this).attr("href");
			var target = $(href == "#" || href == "" ? 'html' : href);
			var position = target.offset().top;
			$("html, body").animate({scrollTop:position}, speed, "swing");
			return false;
		  });
		});
		//上へボタン
		$(document).ready(function() {
			var offset = 100;
			var duration = 500;
			$(window).scroll(function() {
				if ($(this).scrollTop() > offset) {
					$('#to_pagetop').fadeIn(duration);
				} else {
					$('#to_pagetop').fadeOut(duration);
				}
			});
			$('#to_pagetop').click(function(event) {
				event.preventDefault();
				$('html, body').animate({scrollTop: 0}, duration);
				return false;
			})
		});
		//.result
		var rst=document.getElementsByClassName('result');
		var txt='';
		for(var i=0;i<rst.length;++i){
			rst[i].innerHTML='<a href="javascript:void(0)" onClick="close_info(this);" class="close_icon">×</a>'+rst[i].innerHTML;
		}
		<?php
		if(strpos($request_url,'not-logged/')===false){
			?>
		//マウスオーバー
		<?php
					if($_SESSION[$session_head.'user_right']<=2){
						?>
		document.getElementById('header_global_nav_manage_wrapper').addEventListener('mouseover',function(){
			var ul=document.getElementById('header_global_nav_manage');
			ul.style.height=ul.scrollHeight+'px';
		});
		document.getElementById('header_global_nav_manage_wrapper').addEventListener('mouseleave',function(){
			document.getElementById('header_global_nav_manage').style.height=0;
		});<?php
					}
		?>
		document.getElementById('header_global_nav_account_wrapper').addEventListener('mouseover',function(){
			var ul=document.getElementById('header_global_nav_account');
			ul.style.height=ul.scrollHeight+'px';
		});
		document.getElementById('header_global_nav_account_wrapper').addEventListener('mouseleave',function(){
			document.getElementById('header_global_nav_account').style.height=0;
		});
		<?php
		}
		?>
	</script>
	<amp-link-rewriter layout="nodisplay">
	  <script type="application/json">
		{
		  "output": "https://lsr.valuecommerce.com/ard?p=${vc_pid}&u=${href}&vcptn=${vc_ptn}&s=SOURCE_URL&r=DOCUMENT_REFERRER",
		  "vars": { "vc_pid": "886902778", "vc_ptn": "ampls" }
		}
	  </script>
	</amp-link-rewriter>
	<style>
		.school_color{
			background: aqua;
		}
	</style>
</html>
