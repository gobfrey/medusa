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

<?php
 $extra = theme_get_setting('southampton2_rawextra'); 
 if( isset( $extra ) ) { print $extra; }
?>
