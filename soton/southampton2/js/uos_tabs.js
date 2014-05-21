
//var uos_tab_sets = 0;
//var uos_tab_info = [];
function uos_bindTabs( tabsetinfo )
{
	// if there's a valid tab selected in the hash fragment in the URL
	// use that in preference to the selected flag in the data
	var use_hash_fragment = false;
	for( tab_i in tabsetinfo )
	{
		info = tabsetinfo[tab_i];
		if( '#'+info['fragment'] == window.location.hash ) { use_hash_fragment = true; }
	}


	//var tab_set_id = uos_tab_sets++;
	//uos_tab_info[ tab_set_id ] = tabsetinfo;
	for( tab_i in tabsetinfo )
	{
		info = tabsetinfo[tab_i];
		var selected = false;
		if( use_hash_fragment )
		{
			if( '#'+info['fragment'] == window.location.hash ) { selected = true; }
			// set the uos_tabCurrent class on the tab mentioned in the hash fragment
			if( selected )
			{
				jQuery('#'+info["tab"]).addClass( "uos_tabCurrent" );
			}
			else
			{
				jQuery('#'+info["tab"]).removeClass( "uos_tabCurrent" );
			}
		}
		else
		{
			if( info["selected"] ) { selected = true; }
		}
			
		if( !selected ) { jQuery('#'+info['pane']).hide(); }

		jQuery('#'+info["tab"]).click( 
			{ "tab":info["tab"], "tabsetinfo":tabsetinfo } ,
			uos_showTab
		);
	}
}
function uos_showTab( e )
{
	for( tab_i in e.data.tabsetinfo )
	{
		info = e.data.tabsetinfo[tab_i];
		if( e.data.tab == info['tab'] ) 
		{ 
			jQuery('#'+info['pane']).show(); 
			jQuery('#'+info['tab']).addClass( "uos_tabCurrent" );
			jQuery('#'+info['tab']+" a").blur();
			
		}
		else
		{ 
			jQuery('#'+info['pane']).hide(); 
			jQuery('#'+info['tab']).removeClass( "uos_tabCurrent" );
		}
	}
}
