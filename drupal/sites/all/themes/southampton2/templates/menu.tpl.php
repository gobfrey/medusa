<?php 
 global $base_url;
 $homename = theme_get_setting('southampton2_homename'); 
 $searchurl = theme_get_setting('southampton2_searchurl'); 
 if( !isset( $homename ) || $homename == "" ) { $homename = "Home"; }
 if( !isset( $searchurl ) || $searchurl == "" ) { $searchurl = "$base_url/sites/all/themes/southampton2/search-redirect.php"; }
?>
<navigation>
                <div id="uos-sia-nav"><ul><li id="uos-sia-searchBox"><form action="<?php print $searchurl ?>"  method="get"><fieldset><input class="uos-sia-text" id="uos-sia-searchText" name="term" onfocus="if(this.value==this.defaultValue)this.value=''" value="Search" type="text"/><button class="uos-sia-submit" type="submit" name="submit">Submit</button><span id="uos-sia-rad"><label for="uos-sia-here" title="search only this site">This site</label><input id="uos-sia-here" checked="checked" name="s" title="search only the this site" type="radio" value="This site"/><label for="uos-sia-there" title="search all University of Southampton sites">University</label><input id="uos-sia-there" value="All" name="s" title="search all University of Southampton sites" type="radio"/></span></fieldset></form></li><li class="uos-sia-home"><a href="<?php echo $base_url; ?>/"><?php print $homename ?></a></li><li class="uos-sia-pages" id="uos-sia-main-menu">

    <?php  print render($page['menu']); ?>

</li><li class="uos-sia-sotonhome"><a href="http://www.southampton.ac.uk/" title="University of Southampton home page">University Home</a></li></ul></div>
</navigation>
