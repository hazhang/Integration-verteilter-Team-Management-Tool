$(document).ready(function() {
	$('#create').click((function(){
        return function()
        {
           $('#contactInputField').show('slow');
		   $('.textInputField').removeAttr('disabled');
		   $.ajax({
			  type: "POST",
			  url: "contactManagementProcess.php",
			  data: 'action=create',
			  dataType: "xml",
			  success: function(xml) {			
				$('#contactInputField').show('slow');
				$('.textInputField').removeAttr('disabled');
				$('#contactList input[type=radio]:checked').removeAttr('checked');
				parseXMLToHtmlFormInput(xml);
			  }
			});
        }
        })());
	$('#read').click((function(){
        return function()
        {
			var selectedID = $('#contactList input[type=radio]:checked').val();
			$.ajax({
			  type: "POST",
			  url: "contactManagementProcess.php",
			  data: 'id='+selectedID+'&action=read',
			  dataType: "xml",
			  success: function(xml) {			
				$('#contactInputField').show('slow');
				$('.textInputField').attr('disabled', 'disabled');
				$('#contactList input[type=radio]:checked').removeAttr('checked');
				parseXMLToHtmlFormInput(xml);
			  }
			});
        }
        })());
	$('#update').click((function(){
        return function()
        {
		   var selectedID = $('#contactList input[type=radio]:checked').val();
			$.ajax({
			  type: "POST",
			  url: "contactManagementProcess.php",
			  data: 'id='+selectedID+'&action=update',
			  dataType: "xml",
			  success: function(xml) {			
				$('#contactInputField').show('slow');
				$('.textInputField').removeAttr('disabled');
				$('#contactList input[type=radio]:checked').removeAttr('checked');
				parseXMLToHtmlFormInput(xml);
				$('#todo').attr('value', 'update');
			  }
			});
        }
        })());
	$('#delete').click((function(){
        return function()
        {
           var selectedID = $('#contactList input[type=radio]:checked').val();
			$.ajax({
			  type: "POST",
			  url: "contactManagementProcess.php",
			  data: 'id='+selectedID+'&action=update',
			  dataType: "xml",
			  success: function(xml) {			
				$('#contactInputField').show('slow');
				$('.textInputField').attr('disabled', 'disabled');
				$('#contactList input[type=radio]:checked').removeAttr('checked');
				parseXMLToHtmlFormInput(xml);
				$('#todo').attr('value', 'delete');
			  }
			});
        }
        })());
	$('#list').click((function(){
        return function()
        {
           $('#contactInputField').hide();
        }
        })());
		
	// $('#ok').click((function(){
        // return function()
        // {
			// var selectedID = $('input#id').val();
			// var xml = convertHtmlFormToXML();
			// $.ajax({
			  // type: "POST",
			  // url: "contactManagementProcess.php",
			  // contentType: "text/xml"
			  // data: xml,
			  // dataType: "xml",
			  // success: function(xml) {
				// $('#contactList input[type=radio]:checked').removeAttr('checked');
				// parseXMLToHtmlFormInput(xml);
				// $('#todo').attr('value', 'delete');
			  // }
			// });
           // $('#contactInputField').hide();
        // }
        // })());
	
});

function parseXMLToHtmlFormInput(xml){
	$(xml).find('contact').each(function(){
		$('input#id').attr('value', $(this).find('>id').text());
		$('input#firstname').attr('value', $(this).find('firstname').text());
		$('input#lastname').attr('value', $(this).find('lastname').text());
		$('input#street').attr('value', $(this).find('street').text());
		$('input#town').attr('value', $(this).find('town').text());
		$('input#zip').attr('value', $(this).find('zip').text());
		$('input#phone').attr('value', $(this).find('phone').text());
		$('input#mobile').attr('value', $(this).find('mobile').text());
		$('input#email').attr('value', $(this).find('email').text());
  });
}


function convertHtmlFormInputToXML(){
	var xmlHeader = $('<?xml version="1.0"?><contacts></contacts>');
	var xmlContainer = $('<xml>');
	xmlContainer.append(xmlHeader);
	xmlContainer.find('contacts').append('<contact />');
	xmlContainer.find('contact').append('<id>'+$('input#id').val+'</id>');
	xmlContainer.find('contact').append('<firstname>'+$('input#firstname').val+'</firstname>');
	return xmlContainer;
}

