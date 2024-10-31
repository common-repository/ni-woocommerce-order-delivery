// JavaScript Document
jQuery(function($){


	$("#frm_ni_order_delivery" ).submit(function( event ) {
		$.ajax({
			url:ni_od_ajax_object.ni_od_ajaxurl,
			data:$(this).serialize() ,
			success:function(data) {
				$(".ajax_content").html(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
				//alert("e");
			}
		}); 
		
		return false;
	});
	/*Form Submit*/
	$("#frm_ni_order_delivery").trigger("submit");
});