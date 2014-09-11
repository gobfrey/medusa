
jQuery(document).ready(function(){

		if(jQuery(".my-medusa").length>0)
		{

			jQuery.ajax(
				{	
					"url":Drupal.settings.basePath + "soton/api/mymedusa", 
					"success": function(result){
						jQuery(".my-medusa").html(result);
					}
				}
			);
		}
		var completion_checkbox = jQuery(".module-completion input");
		if(completion_checkbox.length>0)
		{

			jQuery.ajax(
				{	
					"url":Drupal.settings.basePath + "soton/api/module_status", 
					"data": { "module": completion_checkbox.attr("data-module") },
					"success": function(result){
						if(result == "complete")
						{
							completion_checkbox.attr('checked', true);
							completion_checkbox.attr("disabled", true);
						}
						return; 
					}
				}
			);
			jQuery(".module-completion input").click(function(){
				jQuery.ajax(
					{	
						"url":Drupal.settings.basePath + "soton/api/complete_module", 
						"data": { "module": completion_checkbox.attr("data-module") },
						"success": function(result){ 
							completion_checkbox.attr("disabled", true);
							return; 
						}
					}
				);
			});
		}
});
