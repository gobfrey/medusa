jQuery(document).ready(function(){

		if(jQuery(".my-medusa").length>0)
		{

		jQuery.ajax({url:"/medusa/soton/api/mymedusa", 
			"success": function(result){
			jQuery(".my-medusa").html(result);
			}
			});
		}
});
