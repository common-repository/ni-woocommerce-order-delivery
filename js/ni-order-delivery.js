//http://jsfiddle.net/Fa8Xx/3462/
//http://jsfiddle.net/b6V3W/370/
//https://businessbloomer.com/woocommerce-visual-hook-guide-checkout-page/
var delivary_days = {};
var holiday_date = {};
//var delivary_days2 = {};
jQuery(function($){
	//alert("a");
	//alert(ni_order_delivery_option.ni_delivery_start_day);
	//alert(JSON.stringify(ni_order_delivery_option.delivery_day));
	//alert(ni_order_delivery_option.delivery_day)	
		
	//alert(JSON.stringify(delivary_days2))	
	//alert(JSON.stringify(ni_order_delivery_option.delivery_day));	
	
	//alert(delivary_days2[1]);
	
	
	//delivary_days  =get_delivary_days();	
	//holiday_date = get_holiday_date();
	
	 $("#ni_order_delivery_date").datepicker({
    	  dateFormat: "yy-mm-dd", 
		   showButtonPanel: true,
		   changeMonth: true,
     	   changeYear: true
	 });	
	 $("#ni_order_delivery_date").datepicker('option', 'maxDate', ni_order_delivery_option.ni_delivery_end_day);
	 $("#ni_order_delivery_date").datepicker('option', 'minDate', ni_order_delivery_option.ni_delivery_start_day);
	 //$("#ni_order_delivery_date").datepicker('option', 'beforeShowDay', 9);
	 $("#ni_order_delivery_date").datepicker('option', 'beforeShowDay', renderCalendarCallback);
});
function renderCalendarCallback(date){
	var day = date.getDay();
	if (delivary_days2[day]){
		return [true] ;
	} 
	else{
		 return [false] ; 
	}
	/*Dont Remove it User In next update for hpliday*/
	 //test12  = get_formated_date(date);
	/*if (jQuery.inArray(test12, holiday_date) == -1) {
		var day = date.getDay();
		if (delivary_days2[day]){
			return [true] ;
		} 
		else{
			 return [false] ; 
		}
	} else {
		return [false] ; 
	}
	*/	
}
/*
function get_delivary_days(){
	 delivary_days = {};
	//days['Sunday'] 		= '0';
	//days['Monday'] 		= '1';
	//days['Tuesday'] 	= '2';
	//delivary_days['Wednesday']   = '3';
	//days['Thursday'] 	= '4';
	//delivary_days['Friday'] 		= '5';
	//days['Saturday'] 	= '6';
	
	delivary_days = {};
	delivary_days['0'] 	 = 'sunday';
	delivary_days['1'] 	 = 'monday';
	delivary_days['2'] 	 = 'tuesday';
	delivary_days['3']   = 'wednesday'; 
	delivary_days['4'] 	 = 'thursday';
	delivary_days['5']   = 'friday'; 
	delivary_days['6'] 	 = 'saturday';
	 
	//alert(JSON.stringify(delivary_days2))	
//	jQuery.each(ni_order_delivery_option.delivery_day, function (index, value) {
		//alert(index  +" " + value);
//	 });
	 //alert(JSON.stringify(ni_order_delivery_option.delivery_day));
	 
	return delivary_days;
}
*/
/*
function get_formated_date(dateObject) {
   // var d = dateObject;
    var day = dateObject.getDate();
    var month = dateObject.getMonth() + 1;
    var year = dateObject.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
   // var date = day + "/" + month + "/" + year;
   
    var date = year + "-" + month + "-" + day;

    return date;
}

function get_holiday_date(){
	 holiday_date = {};
	 //holiday_date["2017-04-04"] = "2017-04-04";
	 
	 holiday_date = ["2017-04-04","2017-04-05"];
	 
	 return holiday_date;
}
*/