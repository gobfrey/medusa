<?php
$url ="http://widgets.ecs.soton.ac.uk/people-search.php?nameq=".urlencode($_GET["nameq"])."&submitted=1&picsize=thumbnail&intro=0";
readfile( $url );
