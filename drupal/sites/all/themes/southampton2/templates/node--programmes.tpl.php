<?php
/**
 * @file
 * Zen theme's implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   - view-mode-[mode]: The view mode, e.g. 'full', 'teaser'...
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 *   The following applies only to viewers who are registered users:
 *   - node-by-viewer: Node is authored by the user currently viewing the page.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content. Currently broken; see http://drupal.org/node/823380
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see zen_preprocess_node()
 * @see template_process()
 */

require_once( "/var/aegir/libraries/snippits/render_section.php" );
require_once( "/var/aegir/libraries/snippits/uos_network.php" );
require_once( "/var/aegir/libraries/snippits/render_panel.php" );


$field = field_get_items('node', $node, 'field_theme_id');
if( sizeof( $field[0] ) )
{
	$theme_id = $field[0]["value"];
}
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if (!$page && $title): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>

<?php print prog_render_section( $content, "field_programme_summary" ); ?>

<script type="text/javascript" src="/sites/all/themes/southampton2/js/uos_tabs.js"></script>
<style>@import url("/sites/all/themes/southampton/css/uos_tabs.css");</style>
<style>
#pane_related table { border-collapse: collapse; margin-bottom: 1em; }
#pane_related td { padding: 3px; border: solid 1px #999 !important; }
.uos_tab_pane_title { display: none; }
</style>

     <div class='uos_tabBar'>
       <ul>
         <li class='uos_tabCurrent' id='tab_overview'><a href='#overview'>Programme overview</a></li>
         <li id='tab_entry'><a href='#entry'>Entry requirements</a></li>
<? if( @$theme_id ) { ?>
         <li id='tab_modules'><a href='#modules'>Modules</a></li>
<? } ?>
<!--         <li id='tab_learning'><a href='#learning'>Learning &amp; assessment</a></li>-->
         <li id='tab_related'><a href='#related'>Related programmes</a></li>
         <li id='tab_careers'><a href='#careers'>Career opportunities</a></li>
<?  if( isset( $content["field_pre_course_reading_lists"] ) ) { ?>
         <li id='tab_reading'><a href='#reading'>Pre-course reading lists</a></li>
<? } ?>
       </ul>
     </div>
<h2 class='uos_tab_pane_title'>Overview</h2>
     <div class='uos_tab' id='pane_overview' name='overview'>
     <div style='float:right; padding: 0em 0em 1em 1em; max-width: 200px'>
<?php
      $field = field_get_items('node', $node, 'field_ucas_jacs_code');
      $ucas = trim(render( field_view_value( 'node',$node,'field_ucas_jacs_code', $field[0]) ));
      if( $ucas != "" ) # logged in or on campus network
      {
         $kis_id = -1;

         foreach( file( "/var/aegir/etc/kis-codes.tsv" ) as $line )
         {
            list( $line_kis_id, $line_code, $name ) = preg_split( "/\t/", $line );
            if( $line_code == $ucas ) { $kis_id = $line_kis_id; }
         }
        
         if( $kis_id != -1 )
         {
            $kisurl = "http://widget.unistats.ac.uk/Widget/10007158/$kis_id/vertical/small";
            print '<div style="width:190px; border-left: 10px solid #fff">
             <iframe id="unistats-widget-frame" src="'.$kisurl.'" scrolling="no" frameborder="no" style="height: 480px; width:190px;background-color: #fff;">
               <p>Your browser doesn\'t support iframes; <a href="'.$kisurl.'">View KIS Information for this programme</a>.</p>
             </iframe><ul class="kispane"><li><p class="kisinfo">What is this</p></li><li>This information is based on historical data and may have been aggregated. It is also subject to the <a href="http://www.southampton.ac.uk/inf/disclaimer.html">University\'s disclaimer notice</a></li></ul>
                </div>
              ';
         }
         elseif( $logged_in )
         {
            print "<p>(warning only visible to logged in people): UCAS code $ucas, is not in the mapping table to KIS.</p>";
         }
      }
      else
      {
         print render($content['field_alternate_kis']);
      }


####################################
      if( isset( $content["field_right_hand_minipanels"] ) && is_array( $content["field_right_hand_minipanels"] ) )
      {
         foreach( $content["field_right_hand_minipanels"]["#items"] as $item )
	 {
            print snippit_render_panel( $item["value"] );
         }
      }
      #print snippit_render_panel( "highfield_campus" );
####################################
      print "</div>";

 print prog_render_section( $content, "field_programmeoverview" );
 print prog_render_section( $content, "field_toapply", "<h3>To Apply</h3>" );
 print prog_render_section( $content, "field_accreditation", "<h3>Accreditation</h3>" );
 print prog_render_section( $content, "field_programme_structure", "<h3>Programme Structure</h3>" );
 print prog_render_section( $content, "field_keyfactstable", "<h3>Key Facts</h3>" );
 print prog_render_section( $content, "field_quote", "<h3>Quotes</h3>" );
?>
     </div>
<h2 class='uos_tab_pane_title'>Entry requirements</h2>
     <div class='uos_tab' id='pane_entry' name='entry'>
<h3>Typical Entry Requirements</h3>
<?php
print prog_render_section( $content, "field_entry_requirements_gcses" , "<h4>GCSEs:</h4>" );
print prog_render_section( $content, "field_entry_requirement_a_levels" , "<h4>A Levels:</h4>" );
print prog_render_section( $content, "field_entry_requirements_ho_nour" , "<h4>Honours Degree:</h4>" );
print prog_render_section( $content, "field_industrial_experience" , "<h4>Industrial Experience:</h4>" );
print prog_render_section( $content, "field_ib"  , "<h4>IB:</h4>" );
print prog_render_section( $content, "field_englishlanguagerequireme"  , "<h4>English Language Requirements:</h4>" );

print prog_render_section( $content, "field_alternative_qualifications"  , "<h4>Alternative Qualifications:</h4>" );
print prog_render_section( $content, "field_international_applications"  , "<h4>International Qualifications:</h4>" );
print prog_render_section( $content, "field_matureapplicants"  , "<h4>Mature Applicants:</h4>" );
print prog_render_section( $content, "field_selection_process"  , "<h4>Selection Process:</h4>" );
print prog_render_section( $content, "field_selection_process_long" , "<h4>Selection Process:</h4>" );
?>
     </div>
     <div class='uos_tab' id='pane_modules' >
<style>
.uos_progmodules_year { clear: left; margin-bottom: 0em; }
.uos_progmodules_part { width: 48%; margin-right: 1.5%; padding-bottom: 0em; float:left;}
.uos_progmodules_year .odd { clear: left; }
.uos_progmodules_year h3 { text-align: center; font-size: 220%; }
/*
.uos_module_option_compulsory { font-size: 110%; }
.uos_module_option_optional { font-size: 90%; }
*/
.uos_module_option_even { background-color: #E6F6F3; }
.uos_module_option_odd { background-color: #fff; }
.uos_information_list li { list-style: none; margin: 0px !important; padding: 5px; }
.uos_information_list { padding-left: 0px; margin-left: 0px }

</style>

<?php

if( @$theme_id )
{
	print "<h2 class='uos_tab_pane_title'>Modules</h2>";
	readfile( "http://widgets.ecs.soton.ac.uk/programme-modules.php?theme=".$theme_id );
}
?>
<br style='clear:left' />
     </div>
     <h2 class='uos_tab_pane_title'>Learning</h2>
     <div class='uos_tab' id='pane_learning' >
<?php
 print prog_render_section( $content, "field_learning_and_teaching" );
 print prog_render_section( $content, "field_toapply", "<h3>To Apply</h3>" );
?>
     </div>
     <h2 class='uos_tab_pane_title'>Related programmes</h2>
     <div class='uos_tab' id='pane_related' >
<script>
jQuery(document).ready( function() {
	jQuery("#pane_related tr:odd" ).addClass( "odd" );
	jQuery("#pane_related tr:even" ).addClass( "even" );
} );
</script>
<style> 
#pane_related table {
	border-collapse: collapse;
	border:0px !important;
}
#pane_related tr.odd td { background-color: #e6f6f3; }
#pane_related tr td { border: 0px !important; padding-left: 10px}
</style> 

<?php
 $related_block = prog_render_section( $content, "field_related_programmes_2" );
 print $related_block;
?>
     </div>

     <h2 class='uos_tab_pane_title'>Career opportunities</h2>
     <div class='uos_tab' id='pane_careers' >
<?php
	print prog_render_section( $content, "field_careeropportunities" );
?>
     </div>

     <h2 class='uos_tab_pane_title'>Pre-course reading lists</h2>
     <div class='uos_tab' id='pane_reading' >
<?php
	print prog_render_section( $content, "field_pre_course_reading_lists" );
?>
     </div>
<script>
uos_bindTabs( [
        { "fragment": "overview","tab": "tab_overview","pane": "pane_overview", "selected": true },
        { "fragment": "entry",   "tab": "tab_entry",   "pane": "pane_entry" },
        { "fragment": "modules", "tab": "tab_modules", "pane": "pane_modules" },
        { "fragment": "learning","tab": "tab_learning","pane": "pane_learning" },
        { "fragment": "related", "tab": "tab_related", "pane": "pane_related" },
        { "fragment": "careers", "tab": "tab_careers", "pane": "pane_careers" },
        { "fragment": "reading", "tab": "tab_reading", "pane": "pane_reading" }
] );
</script>
<?php

      hide( $content["field_programmename"] );
      hide( $content["field_programmeoverview"] );
      hide( $content["field_ucas_jacs_code"] );
      hide( $content["field_programme_type"] );
      hide( $content["field_navigation"] );
      hide( $content["field_pagefilename"] );
      hide( $content["field_duration"] );

      hide( $content["field_right_hand_content"] );
      hide( $content["field_programmeowner"] );
      hide( $content["field_year"] );
      hide( $content["field_location"] );

      hide( $content["field_facebook_feed"] );
      hide( $content["field_flickrfeed"] );
      hide( $content["field_youtubefeed"] );
      hide( $content["field_linkedin"] );
      hide( $content["field_twitter_feed"] );
      hide( $content["field_blog_freed_text"] );
      hide( $content["field_programme_priority"] );

      hide( $content["field_modules"] );
      hide( $content["field_theme_id"] );

      hide( $content["comments"] );

      print render($content);
#<iframe id="unistats-widget-frame" title="Unistats KIS Widget" src="http://stg.unistats.eduserv.org.uk/Widget.aspx?i=500&amp;c=500&amp;o=horizontal" scrolling="no" style="overflow: hidden; border: 0px none transparent; width: 800px; height: 160px; z-index: 20; position:absolute; margin-left:-138px;margin-top: 5px;"></iframe>
?>

<div style='height:180px'></div>
<?php
?>
</div><!-- /.node -->
 
<?php
function prog_render_section( &$content, $field, $title=null )
{
	$block = snippit_render_section( $content, $field, $title );
	$block = preg_replace( '/preprod.www.ecs.soton.ac.uk/', 'www.ecs.soton.ac.uk', $block );
	$block = preg_replace( '/preprod.phys.ecs.soton.ac.uk/', 'www.phys.soton.ac.uk', $block );
	return $block;
}
#Programme name	field_programmename	Text	Text field	edit	delete
#UCAS/JACS Code	field_ucas_jacs_code	Text	Text field	edit	delete
#Programme type	field_programme_type	List (text)	Select list	edit	delete
#Navigation	field_navigation	List (text)	Check boxes/radio buttons	edit	delete
#Modules	field_modules	Text	Text field	edit	delete
#Duration	field_duration	Text	Text field	edit	delete
#Programme owner	field_programmeowner	Text	Text field	edit	delete
#Entry year	field_year	List (text)	Select list	edit	delete
#

#



#Facebook feed	field_facebook_feed	Text	Text field	edit	delete
#Flickr feed	field_flickrfeed	Text	Text field	edit	delete
#YouTube feed	field_youtubefeed	Text	Text field	edit	delete
#LinkedIn feed	field_linkedin	Text	Text field	edit	delete
#Twitter feed	field_twitter_feed	Text	Text field	edit	delete
#Blog feed	field_blog_feed	Text	Text field	edit	delete
?>
