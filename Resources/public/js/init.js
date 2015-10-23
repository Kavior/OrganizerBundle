
function getCurrentDate(){
	var dateUnit = $('.currentDay');
	var year = dateUnit.data('year');
	var month = dateUnit.data('month') > 8 ? dateUnit.data('month') + 1 : '0' + (dateUnit.data('month') + 1) ;

	var selectedDay = $('.ui-state-hover');
	var day = selectedDay.text().length > 1
		? selectedDay.text()
		: '0' + (selectedDay.text());
	var chosenDate = year + '-' + month + '-' + day;

	return chosenDate;
}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function addListToSelect(select, listId, listName){
	var optionWithSameId = select.find('option[value="' + listId + '"]');
	//if option is not already in select 
	if(optionWithSameId.length == 0){ 
		var infoOption = select.find('option').eq(0); //first option like "--select--"
		var choose = infoOption.text();
		infoOption.remove();
		
		var newOption = $('<option value="' + listId + '">' + listName + '</option>');
		select.append(newOption);
	
		var options = select.find('option');
		var listsDataArray = [];
		
		options.each(function(){
			var value = this.value;
			var text = this.text;
	
			listsDataArray.push([value , text]);
		});
		
		listsDataArray.sort(function(a,b) {
			 if (a[1] === b[1]) {
		        return 0;
		    }
		    else {
		        return (a[1] < b[1]) ? -1 : 1;
		    }
		});
		
		
		var newOptions = '<option value="">' + choose + '</option>';
	
		for(i = 0; i< listsDataArray.length; i++){
			var value = listsDataArray[i][0];
			var text = listsDataArray[i][1];
	
			var optionToAppend = '<option value="' + value +'">' + text + '</option>';
			newOptions += optionToAppend;
		}
		select.find('option').remove();
		select.append(newOptions);
		
		select.trigger("chosen:updated");
	}
}

function removeListFromSelect(select, listId){ //Remove option with id from select
	var option = select.find('option[value="'+listId+'"]');
	option.remove();
	select.trigger("chosen:updated");
}

function adjustContainer(){ //Adjust main container to the height of the document
	var wh = $(document).height();
	$('.main').css('height', wh);
}

function initSlider(element, currentValue){
	var value = typeof currentValue !== 'undefined' && currentValue != null ? currentValue : 5;

	element.slider({
		min: 0,
		max : 10,
		value: value,
		orientation: "vertical"
	}).slider("pips");
}

function addTitleToWeightSlider(title){
	$('.ui-slider-handle').each(function(){
		
		if(!$(this).attr('title')){
			$(this).attr({
				rel: 'tooltip',
				title : title,
				
			});
		}	
	});
}

$(function () {
	adjustContainer();

	$(window).resize(function() {
		adjustContainer();
	});
	
	

	$(".chosen-select").chosen({disable_search_threshold: 10});
	
	$('.bs-popover').popover({ 
		html : true,
	});
	
	$('[data-toggle="tooltip"]').tooltip();

	$('.navbar .app-info').mouseleave(function(){
		$('.tooltip').hide();
	});
	
	var spinner = $( ".spinner" ).spinner();
	 
    $( "#disable" ).click(function() {
	  if ( spinner.spinner( "option", "disabled" ) ) {
	    spinner.spinner( "enable" );
	  } else {
	    spinner.spinner( "disable" );
	  }
	});
	    
	$( "#destroy" ).click(function() {
	  if ( spinner.spinner( "instance" ) ) {
	    spinner.spinner( "destroy" );
	  } else {
	    spinner.spinner();
	  }
	});
	$( "#getvalue" ).click(function() {
	  alert( spinner.spinner( "value" ) );
	});
	$( "#setvalue" ).click(function() {
	  spinner.spinner( "value", 5 );
	    });
	 
    $( "button" ).button();
		 
});

$.fn.showLoader = function(){
	$(this).LoadingOverlay("show");
	var url = location.href;
	
	if(url.match(/\d+/g)){
		var overlay = $('.loadingoverlay');
		var currentImage = overlay.css('background-image');
		currentImage = currentImage.replace(/http\:(.*?)\/images/g, '../images');

		overlay.css('background-image', currentImage);
	}
	
}

$.fn.hideLoader = function(){
	$(this).LoadingOverlay('hide');
}

