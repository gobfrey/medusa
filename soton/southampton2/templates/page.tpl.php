<?php
$page_template = "default";
if( isset($node) )
{
	if( $node->type == "highly_visual" ) 
	{
		$page_template = "highly_visual";
	}
	else
	{
		$field = field_get_items('node', $node, 'field_banner_image');
		if( $field ) { $page_template = "highly_visual"; }
		$field = field_get_items('node', $node, 'field_banner');
		if( $field ) { $page_template = "highly_visual"; }
	}
}
include "page--$page_template.tpl.php";
