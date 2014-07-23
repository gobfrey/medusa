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



<div class="uos-sia-ls-canvas uos-sia-canvas" id="uos-sia-ls-canvas">

  <div class="uos-sia-ls-row" id="uos-sia-ls-row-1"><div class="uos-sia-ls-fxr">

    <div class="uos-sia-ls-area" id="uos-sia-ls-row-1-area-1"><div class="uos-sia-ls-area-body"><div class="uos-sia-ls-cmp-wrap uos-sia-ls-1st" ><div class="uos-sia-iw_component" ><ul id="uos-sia-accessibility"><li><a href="/sitemap" target="" title="">Site map</a></li><li class="uos-sia-last"><a href="javascript:(function(){d=document;lf=d.createElement('script');lf.type='text/javascript';lf.id='ToolbarStarter';lf.text='var%20StudyBarNoSandbox=true';d.getElementsByTagName('head')[0].appendChild(lf);jf=d.createElement('script');jf.src='http://access.ecs.soton.ac.uk/ToolBar/channels/toolbar-stable/JTToolbar.user.js';jf.type='text/javascript';jf.id='ToolBar';d.getElementsByTagName('head')[0].appendChild(jf);})();" target="" title="">Accessibility Tools</a></li></ul></div></div></div></div>

    <div class="uos-sia-ls-row-clr"></div>

  </div></div>

  <div class="uos-sia-ls-row" id="uos-sia-ls-row-2">
    <div class="uos-sia-ls-fxr">
      <div class="uos-sia-ls-area" id="uos-sia-header"><div class="uos-sia-ls-area-body"><div class="uos-sia-ls-cmp-wrap uos-sia-ls-1st" ><div class="uos-sia-iw_component" ><h1 id="uos-sia-logo"><?php print $site_name; ?>, University of Southampton</h1></div></div></div></div>

      <div class="uos-sia-ls-row-clr"></div>
    </div>
  </div>
  <div class="uos-sia-ls-row" id="uos-sia-ls-row-3"><div class="uos-sia-ls-fxr">
    <div class="uos-sia-ls-area" id="uos-sia-sitename"><div class="uos-sia-ls-area-body"><div class="uos-sia-ls-cmp-wrap uos-sia-ls-1st" ><div class="uos-sia-iw_component" ><div id="uos-sia-heading"><h2><?php print $site_name; ?></h2></div></div></div></div></div>
  
    <div class="uos-sia-ls-row-clr"></div>
  </div></div>
  <div class="uos-sia-ls-row" id="uos-sia-container"><div class="uos-sia-ls-fxr">
    <div class="uos-sia-ls-area" id="uos-sia-ls-row-4-area-1"><div class="uos-sia-ls-area-body"><div class="uos-sia-ls-cmp-wrap ls-1st" ><div class="uos-sia-iw_component" ><div id="uos-sia-navposdefault">
    <?php include( "menu.tpl.php" ); ?>

    <?php  print render($page['under_menu']); ?>
  </div></div></div></div></div>

  <div class="uos-sia-ls-area" id="uos-sia-content"><div class="uos-sia-ls-area-body">
    <div class="uos-sia-ls-cmp-wrap uos-sia-ls-1st" ><div class="uos-sia-iw_component" >


      <?php print render($page['highlighted']); ?>
      <?php print $breadcrumb; ?>
      <a id="uos-sia-main-content"></a>
      <!--<div style="float:right;margin-right:10px;font-size:130%"><div id="shareNice" ></div></div>-->
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h2 class="uos-sia-title" id="uos-sia-page-title"><?php print $title; ?></h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print $messages; ?>
      <?php if ($tabs = render($tabs)): ?>
        <div class="tabs"><?php print $tabs; ?></div>
      <?php endif; ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>

    </div></div>

  </div></div>
    <div class="uos-sia-ls-row-clr"></div>
</div>
</div>

<?php include( "footer.tpl.php" ); ?>
<div style='position:absolute; left:0%; top:50px; width:100%'><div style='width:940px;margin:0px auto 0px auto'><a href='http://www.southampton.ac.uk' style='float:right; margin-right:20px;width:230px;height:50px;display:block'></a></div></div>
