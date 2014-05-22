<div style="width: 940px; margin: auto;"><p>
<?php  print render($page['footer']); ?>
</div>

<?php

$footer_menu = render($page['footer_menu']); 

#Add the login link just before the final </ul>
$menu_chunks = preg_split( "/<\/ul>/", $footer_menu );
$tail = array_pop( $menu_chunks );
$last_bit_of_menu = preg_replace( "/last leaf/","leaf", array_pop( $menu_chunks ));
if( $logged_in ) 
{
	$menu_chunks[]= $last_bit_of_menu."<li style='list-style: none' class='leaf last'><a href='/user/logout'>Log out</a></li>";
}
else
{
	$menu_chunks[]= $last_bit_of_menu."<li style='list-style: none' class='leaf last'><a href='/user/login' onclick='jQuery(\"#login\").fadeIn(\"fast\"); jQuery(\"#edit-name\").select(); return false;'>Website editor login</a></li>";
}
$menu_chunks[]=$tail;
$footer_menu = join( "</ul>", $menu_chunks );


?>
<div class="uos-sia-ls-row" id="uos-sia-ls-row-5">
  <div class="uos-sia-ls-fxr">
    <div class="uos-sia-ls-area" id="uos-sia-ls-row-5-area-1">
      <div class="uos-sia-ls-area-body">
        <div class="uos-sia-ls-cmp-wrap uos-sia-ls-1st" >
          <div class="uos-sia-iw_component" >
            <div id="uos-sia-footer">
              <?php print $footer_menu; ?>
            </div>
            <div class="uos-sia-clear"><!--clear--></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="uos-sia-ls-row-clr"></div>
</div>

<div id='login'>
  <div id='login-close'><a href='#' onclick='jQuery("#login").fadeOut("fast");return false;'>close</a></div>
  <?php print render($page['login']); ?>
</div>

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-N9827Q"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N9827Q');</script>
<!-- End Google Tag Manager -->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16499914-1']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_setAccount', 'UA-2003979-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script src="http://delivery.o.switchadhub.com/paul.js"> </script>

<?php
 $extra = theme_get_setting('southampton2_rawextra'); 
 if( isset( $extra ) ) { print $extra; }
?>
