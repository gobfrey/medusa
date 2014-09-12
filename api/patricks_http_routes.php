<?php
function fast_track($f3)
{
        $current_node = node_load($f3->get('REQUEST.current_node'));
	$module_node = node_load($current_node->book['bid']);
	$nodes = menu_tree_all_data($current_node->book['menu_name']);

#	print_r($current_node);exit;
	$node_ids = unpack_book_nodes($nodes);
	print_r($node_ids);
	$link_reached = false; 
	$final_node;
	foreach($node_ids as $node_id)
	{
		if(!$link_reached)
		{
			if($node_id == $current_node->nid)
			{
				$link_reached = true;
			}
			continue;
		}
		$node = node_load($node_id);
		#print_r($node);exit;
		if($node->type != "book")
		{
			continue;
		}

		if(preg_match('#\[embed:completioncheck#', $node->body['und'][0]['value']))
		{
			# final page is the one where you tick to say its complete
			$final_node = $node;
		}

		$fast_track = @field_view_field('node', $node, 'field_fast_track');
		if($fast_track["#items"][0]["value"])
		{
			$f3->reroute($f3->get("drupal_base")."/node/".$node->nid);
		}
	}
	#if there is nothing left on the fast track go to the last page of the module
	$f3->reroute($f3->get("drupal_base")."/node/".$final_node->nid);
}

function unpack_book_nodes($nodes, $node_ids=array())
{
	foreach($nodes as $node)
	{
		#echo $node["link"]["link_title"]. ": ". $node["link"]["link_path"] . "<br />\n";
		$node_ids[] = preg_replace('#node/#', '', $node["link"]["link_path"]);
		if(is_array($node["below"]))
		{
			$node_ids = array_merge($node_ids, unpack_book_nodes($node["below"]));
		}
	}

	return $node_ids;
}
