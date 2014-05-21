<?
$u_agent = $_SERVER['HTTP_USER_AGENT']; 
if( !preg_match('/MSIE/i',$u_agent) || preg_match('/Opera/i',$u_agent)) 
{
?>
<script type="text/javascript" src="/sites/all/themes/southampton2/js/jquery.color.js"></script>
<script>
jQuery(document).ready( function() { 
    	jQuery("body").prepend( "<div id='xmasanim' style='height:1px;overflow:visible'><div style='width:100%;height:1000px;background-color:#cccccc;position:fixed;z-index:-1001'> <div id='xmas1'>&#x2744;</div> <div id='xmas2'>&#x2745;</div> <div id='xmas3'>&#x2746;</div> <div id='xmas4'>&#x2747;</div> <div id='xmas5'>&#x2744;</div> <div id='xmas6'>&#x2745;</div> <div id='xmas7'>&#x2746;</div> <div id='xmas8'>&#x2747;</div> </div></div> <style> #xmas1,#xmas2,#xmas3,#xmas4, #xmas5,#xmas6,#xmas7,#xmas8 { font-size: 150px; color: #cccccc; position: absolute; z-index:-1000; }</style> ");
	var delay = 10000;
	setTimeout( function() { snowAnim( jQuery("#xmas1"),'left' ); }, Math.random()*delay );
	setTimeout( function() { snowAnim( jQuery("#xmas2"),'left' ); }, Math.random()*delay );
	setTimeout( function() { snowAnim( jQuery("#xmas3"),'left' ); }, Math.random()*delay );
	setTimeout( function() { snowAnim( jQuery("#xmas4"),'left' ); }, Math.random()*delay );
	setTimeout( function() { snowAnim( jQuery("#xmas5"),'right' ); }, Math.random()*delay );
	setTimeout( function() { snowAnim( jQuery("#xmas6"),'right' ); }, Math.random()*delay );
	setTimeout( function() { snowAnim( jQuery("#xmas7"),'right' ); }, Math.random()*delay );
	setTimeout( function() { snowAnim( jQuery("#xmas8"),'right' ); }, Math.random()*delay );
	function snowAnim(elem, side) { 
		elem.css(side, (Math.random()*20-5)+"%" );
		elem.css("top", Math.random()*100+"%" );
		elem
			.animate( { "color": "#ddf", "zIndex":  -500 }, 5000, "linear" )
			.animate( { "color": "#cccccc", "zIndex": -1000 }, 5000, "linear", function() {
				setTimeout( function() { snowAnim( elem, side ); }, Math.random()*delay );
			} );
	}
});
</script>
<? } ?>
