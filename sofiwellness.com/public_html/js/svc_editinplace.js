

$(document).ready(function() {

    $('.edit').editable('http://www.example.com/save.php', {
         indicator : 'Saving...',
         tooltip   : 'Click to edit...'
     });
     $('.edit_area').editable('http://www.example.com/save.php', { 
         type      : 'textarea',
         cancel    : 'Cancel',
         submit    : 'OK',
         indicator : '<img src="img/indicator.gif">',
         tooltip   : 'Click to edit...'
     });


/*  
	//for jeditable.js
   $("#1").editable("/service/index/save",{
   indicator: "Saving...",
   onblur: 'submit'
   }); 
*/
 });


/*
$(document).ready(function(){
	
	$("#1").editInPlace({ 
		url: "http://localhost.source1/service/save",
		bg_over: "#cff",
		field_type: "textarea",
		textarea_rows: "15",
		textarea_cols: "35",
		show_buttons: true
	  }); 


	$("#2").editInPlace({
		url: "http://localhost.source1/service/save",
		bg_over: "#cff",
		field_type: "textarea",
		textarea_rows: "15",
		textarea_cols: "35",
		show_buttons: true
	});
});
*/