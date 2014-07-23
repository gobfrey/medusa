<?php
/**
 * @file
 * Zen theme's implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $secondary_menu_heading: The title of the menu used by the secondary links.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 * - $page['bottom']: Items to appear at the bottom of the page below the footer.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see zen_preprocess_page()
 * @see template_process()
 */
?>


<style>
#uos-sia-header{
	min-height: 63px;
	*height: 63px;
	border: 0;
	padding-top: 210px;
}
h1{
	display: none;
}
div#uos-sia-header p#uos-sia-summary em {
	display: block;
	height: 34px;
	padding-top: 5px;
}
div#uos-sia-header p#uos-sia-summary em ,
div#uos-sia-header p#uos-sia-summary em a
{
	font-size: 16px !important;
	font-family: Lucidia Sans,sans-serif !important;
}
div#uos-sia-nav ul ul{
	border-top: 3px solid #fff !important;
}
div#uos-sia-nav ul ul ul{
	border-top: 0 !important;
}
#uos-sia-page-title {
	margin-top: 8pt;
	padding-bottom: 8pt;
	margin-bottom: 8pt;
	border-bottom: 1px solid #ccc;
}
	
/* Navigation position */
/***********************/
/* (#header height + #header padding) - 91px = margin-top offset */

.region-under-menu {
	margin-left: 20px;
}

#uos-sia-content {
    background: none repeat scroll 0 0 transparent;
    float: left;
    margin-top: 0;
    overflow: hidden;
    width: 460px;
    padding: 0 0 15px 245px !important;
    z-index: 0;
}
.uos-sia-full #uos-sia-content{
	padding: 0 13px 15px 0 !important;
}
#uos-left-bar {
	width: 210px !important;
	float: left;
	margin: -202px -245px 0 20px !important;
	position: relative;
	z-index: 1;
	display: inline;
}
div#uos-sia-nav ul ul{
	border-top: 0 !important;
}


</style>


<div style='width:960px; margin:auto'>
	<ul id="uos-sia-accessibility">
		<li><a href="/sitemap">Site map</a></li>
		<li class="uos-sia-last"><a href="javascript:(function(){d=document;lf=d.createElement('script');lf.type='text/javascript';lf.id='ToolbarStarter';lf.text='var%20StudyBarNoSandbox=true';d.getElementsByTagName('head')[0].appendChild(lf);jf=d.createElement('script');jf.src='http://access.ecs.soton.ac.uk/ToolBar/channels/toolbar-stable/JTToolbar.user.js';jf.type='text/javascript';jf.id='ToolBar';d.getElementsByTagName('head')[0].appendChild(jf);})()" target="" title="">Accessibility Tools</a></li>
    </ul>
</div>

	<div id="uos-sia-container" class="uos-sia-medium">
<?php
#readfile( "http://widgets.ecs.soton.ac.uk/news-banner.php" );
?>
<?php
$field = field_get_items('node', $node, 'field_banner_nodes');
if( 1 && isset( $field ) && is_array( $field ))
{
	#print "<pre>!".htmlspecialchars( print_r( $node, true ))."!</pre>";
	$first = true;
	foreach( $field as $item )
	{
		$alias = $item["value"];
		$path = drupal_lookup_path( "source", $alias );
		list( $type, $node_id ) = preg_split( '/\//', $path );
		
    		$feature_node = node_load($node_id);
		if( !isset( $feature_node ) ){ continue; }

		$field = field_get_items('node', $feature_node, 'field_banner_caption');
		$caption = render( field_view_value( 'node',$feature_node,'field_banner_caption', $field[0]) );
		$field = field_get_items('node', $feature_node, 'field_banner_image');
		$filename=$field[0]["filename"];

		$link_url = "/".$alias;
		$field = field_get_items('node', $feature_node, 'field_banner_redirect_url');
		if( is_array($field) && sizeof( $field )==1 )
		{
			$link_url = $field[0]["value"];
		}
		$s1 = "";
		if( !$first ) { $s1="display:none;"; }
   		$slide = '      
                <div id="uos-sia-header" style="'.$s1.' background: url(../../files/'.$filename.')">
                        <h1 id="uos-sia-logo">University of Southampton</h1>
                        <p id="uos-sia-summary"><em><a style="color:inherit" href="'.$link_url.'">'.$caption.'</a></em>
                </div>
';
		$slides []= $slide;
		$first = false;
	}

	$delay = 6000;
	$field = field_get_items('node', $node, 'field_banner_delay');
	if(isset( $field ) && is_array( $field ))
	{
		$delay = $field[0]["value"];
	}

	$effect = 'fade';
	$field = field_get_items('node', $node, 'field_banner_animation');
	if(isset( $field ) && is_array( $field ))
	{
		$effect = $field[0]["value"];
	}


	print '
<script src="http://widgets.ecs.soton.ac.uk/js/jquery.cycle.all.latest.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery(".slideshow").cycle({
		fx: "'.$effect.'", // choose your transition type, ex: fade, scrollUp, shuffle, etc...
                timeout: '.$delay.'
	});
});
</script>
';
	print '<div class="slideshow">';
	print join( '', $slides );
	print "</div>";
}
else
{
	# single banner image from local fields, picked at random if there's several
	$field = field_get_items('node', $node, 'field_banner_caption');
	$caption = render( field_view_value( 'node',$node,'field_banner_caption', $field[0]) );
	$field = field_get_items('node', $node, 'field_banner_image');
	if( !$field )
	{
		// some older sites have a different field name
		$field = field_get_items('node', $node, 'field_banner');
	}
	# later on we might want another optional field on this type of page to select
	# what to do if there's multiple images. (ie. pick or slideshow)
	shuffle( $field ); 
	$filename=$field[0]["filename"];
        $file_uri = file_create_url(file_build_uri($filename));
?>

		<div id="uos-sia-header">
			<h1 id="uos-sia-logo">University of Southampton</h1>
<?php if( $caption != "" ) { ?>
			<p id="uos-sia-summary"><em> <?php print $caption; ?> </em></p>
<?php } ?>
		</div>
		<style> #uos-sia-header{ background: url(<?php print $file_uri; ?>) no-repeat; } </style>

<?
}
?>
<div id='uos-left-bar'>
    <?php require_once( "menu.tpl.php" ); ?>
    <?php print render($page['under_menu']); ?>
</div>



		<div id="uos-sia-content">

      <?php print render($page['highlighted']); ?>
      <?php print $breadcrumb; ?>
      <a id="uos-sia-main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h2 class="uos-sia-title" id="uos-sia-page-title"><?php print $title; ?></h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print $messages; ?>
      <?php if ($tabs = render($tabs)): ?>
        <div class="uos-sia-tabs"><?php print $tabs; ?></div>
      <?php endif; ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="uos-sia-action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>

		</div>
&nbsp;
	</div> 

<?php include( "footer.tpl.php" ); ?>
<div style='position:absolute; top:50px; width:100%'><div style='width:940px;margin:0px auto 0px auto'><a href='http://www.southampton.ac.uk' style='float:right; margin-right:20px;width:300px;height:75px;display:block;z-index:10'></a></div></div>
