// JavaScript Document



$(function() {
	
	$('input.date').datepicker('option', 'onSelect', function(dateText) {
		if($(this).is('.start')) {
			var start = $.datepicker.parseDate('yy-mm-dd',dateText);
			var end = $.datepicker.parseDate('yy-mm-dd',$('input.date.end').val());
		
			if(!end || start.getTime() > end.getTime()) {
				$('input.date.end').val(dateText);
			}
		} else if($(this).is('.end')) {
			var start = $.datepicker.parseDate('yy-mm-dd',$('input.date.start').val());
			var end = $.datepicker.parseDate('yy-mm-dd',dateText);
		
			if(!end || start.getTime() > end.getTime()) {
				$('input.date.start').val(dateText);
			}
		}
	});
	
	$('select.hour, select.minute').change(function() {
		var start = $.datepicker.parseDate('yy-mm-dd',$('input.date.start').val());
		var end = $.datepicker.parseDate('yy-mm-dd',$('input.date.end').val());
		
		start.setHours(parseInt($('select[name=datestart_hour]').val()));
		end.setHours(parseInt($('select[name=dateend_hour]').val()));
		start.setMinutes(parseInt($('select[name=datestart_minute]').val()));
		end.setMinutes(parseInt($('select[name=dateend_minute]').val()));
		
		if(start.getTime() > end.getTime()) {
			if(parseInt($('select[name=datestart_hour]').val()) == 23) {
				$('select[name=dateend_hour]').val($('select[name=datestart_hour]').val());
			} else {
				$('select[name=dateend_hour]').val((parseInt($('select[name=datestart_hour]').val())+1)+'');
			}
		}
	});
	
});