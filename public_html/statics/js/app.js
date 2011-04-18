// JavaScript Document

$(function() {
	
	
	$('input.date').datepicker({
		'dateFormat' : 'yy-mm-dd',
		'buttonImage' : '/statics/icons/calendar_16.png',
		'changeYear' : true,
		'changeMonth' : true,
		'constrainInput' : true,
		'autoSize' : true,
		'minDate' : new Date(1900, 1 - 1, 1),
		'maxDate' : '+50y',
		'yearRange' : '1900:c+50'
	});
	
	
});