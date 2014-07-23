<?php

if( @$_GET["s"] == "All" )
{
	$url = 'https://search.sharepoint.soton.ac.uk/Pages/results.aspx?k='.urlencode($_GET['term']).'&submit=&s=All';
	
}
else
{
	$url = '/search/node/'.urlencode($_GET['term']);
}

header( "Location: $url" );

print "<p>Redirecting to: <a href='$url'>".htmlspecialchars($url)."</a></p>";
