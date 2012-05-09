$(document).ready(function() {
	hideAllObjectsContentField($(this));
	$('div#all').show();
	//listObjects($('#listContent > ul'));
	
	$('#systemControl > input#startup').click((function(){
        return function()
        {						
			$.post("system_control.php", "action=startup",
					function(response){					
						$('#hostList').show();
						$('#hostList').empty();
						$.each(response, function(i, host){
							var hostElem = '<a href="http://' + host.ip + '">'
											+ host.id + '</a> ';
							$('#hostList').append(hostElem);
						});
						listObjects($('#listContent > ul'));
					}, "json");
        }
        })());
	
	$('#systemControl > input#shutdown').click((function(){
        return function()
        {			
			$.post("system_control.php", "action='shutdown'",
					function(response){					
						$('#hostList').show();
						$('#hostList').empty();
						$('#hostList').append("<strong>Server Shutdown.</strong>");
					}, "json");
        }
        })());
	
	/* $('li#all').click((function(){
        return function()
        {			
			$('#tabnav').find('li').each(function(){ $(this).removeClass('selectedTab');});
			$('li#all').addClass('selectedTab');  
			$('#listContent > ul > li').show();
			hideAllObjectsContentField($(document));
			$('div#all').show();
        }
        })()); */
	
	$('ul#tabnav > li').each(function(){ 
				var typ = $(this).attr('id');
				switch(typ){
					case 'all':
						$(this).click((function(){
								return function()
								{			
									$('#tabnav').find('li').each(function(){ $(this).removeClass('selectedTab');});
									$('ul#tabnav > li#all').addClass('selectedTab');  
									$('#listContent > ul > li').show();
									hideAllObjectsContentField($(document));
									$('div#all').show();
								}
								})());
						break;
					case 'search':
						break;
					default:
						$(this).click((function(){
								return function()
								{	
									hideAllObjectsContentField($(document));
									$('div#'+typ).show();
									$('#'+typ+'Form').find('#okButton').show();
									$('#'+typ+'Form').find('#updateDeleteButtons').hide();
									$('div#linkedObjects').hide();
									$('#tabnav').find('li').each(function(){ $(this).removeClass('selectedTab');});
									$('#tabnav > li#'+typ).addClass('selectedTab');
									$('#listContent > ul > li').hide();
									$('#listContent > ul > li#'+typ).each(function(){ $(this).show();});
									$('div#'+typ+'Form').find('input#objectTyp').attr('value', typ);
									buildContactEmptyForm($('div#'+typ+'Form'));			
								}
								})());
						break;
				} 
			}
		);
	
	/* $('li#contacts').click((function(){
        return function()
        {	
			hideAllObjectsContentField($(document));
			$('div#contact').show();
			$('#contactForm').find('#okButton').show();
			$('#contactForm').find('#updateDeleteButtons').hide();
			$('div#linkedObjects').hide();
			$('#tabnav').find('li').each(function(){ $(this).removeClass('selectedTab');});
			$('li#contacts').addClass('selectedTab');
			$('#listContent > ul > li').hide();
			$('#listContent > ul > li#Contact').each(function(){ $(this).show();});
			$('div#contactForm').find('input#object').attr('value', 'Contact');
			buildContactEmptyForm($('div#contactForm'));			
        }
        })()); */
		
	/* $('#contact').find('#new').click((function(){
        return function()
        {	
			$('#contactForm').find('#okButton').show();
			$('#contactForm').find('#updateDeleteButtons').hide();
			buildContactEmptyForm($('div#contactForm'));
			$('div#contactForm').find('input#id').attr('disabled', true);
        }
        })()); */
	
	$('div#contact').find('#new').click((function(){
        return function()
        {	
			var typ = 'contact';
			hideAllObjectsContentField($(document));
			var action = "action=create";
			var params = "request="+escape(convertFormToXML($('div#'+typ+'Form'), typ))
							+"&typ="+typ;
			var post_data = action + "&" + params;
			$.post("agent_process.php", post_data,
					function(response){					
						$('div#message').show();
						$('div#message').find('label').text(response.message);
						updateList($('#listContent > ul'), response.data, 'new');
					}, "json");
        }
        })());
		
	$('div#contact').find('#update').click((function(){
        return function()
        {	
			var typ = 'contact';
			hideAllObjectsContentField($(document));
			var action = "action=update";
			var params = "request="+escape(convertFormToXML($('div#'+typ+'Form'), typ));
			params = params + "&id=" + $('input#objectID').attr('value');
			var post_data = action + "&" + params;
			/*$.ajax({
			  type: "POST",
			  url: "agent_process.php",
			  data: post_data,
			  dataType: "xml",
			  success: function(response) {
				$('div#message').show();
						if($(response).find('OK'))
							$('div#message').find('label').text('update successful!');
						else
							$('div#message').find('label').text('update failed!');
						updateList($('#listContent > ul'), response.data, 'update');
			  }
			});*/
			
			$.post("agent_process.php", post_data,
					function(response){					
						$('div#message').show();
						$('div#message').find('label').text(response.message);
						//updateList($('#listContent > ul'), response.data, 'update');
					}, "json");
        }
        })());
		
	$('div#contact').find('#delete').click((function(){
        return function()
        {	
			hideAllObjectsContentField($(document));
			var action = "action=delete";
			var params = 'id='+$('input#objectID').attr('value');
			var post_data = action + "&" + params;				
			
			/*$.ajax({
			  type: "POST",
			  url: "agent_process.php",
			  data: post_data,
			  dataType: "xml",
			  success: function(response) {
				$('div#message').show();
						if($(response).find('OK'))
							$('div#message').find('label').text('Delete successful!');
						else
							$('div#message').find('label').text('Delete failed!');
						updateList($('#listContent > ul'), response.data, 'update');
			  }
			});*/
			$.post("agent_process.php", post_data,
					function(response){					
						$('div#message').show();
						$('div#message').find('label').text(response.message);
						updateList($('#listContent > ul'), response.data, 'delete');
					}, "json");
        }
        })());
		
	$('div#contact').find('input#links').click((function(){
        return function()
        {
			$('div#linkedObjects').show();
			listAllObjectsToSelect($('div#linkedObjects').find('div#objectsList'), $.trim($(this).attr('value')).split(','));
        }
        })());
		
	$('div#contact').find('div#objectsList').find('input').live('click', function(){
			var checkedObjects = '';
			$('div#contact').find('div#objectsList').find('input:checked').each(function(){
				checkedObjects = checkedObjects + $(this).attr('value') + ', ';});
			$('div#contact').find('input#links').attr('value', checkedObjects);
        });
	
	$('#listContent').find('li').live('click', function(){
			var objectTyp = $(this).attr('id');
			hideAllObjectsContentField($(document));
			$('div#'+objectTyp.toLowerCase()).show();
			$('div#'+objectTyp.toLowerCase()+'Form').find('input#objectTyp').attr('value', objectTyp);
			$('div#'+objectTyp.toLowerCase()+'Form').find('input#objectID').attr('value', $(this).attr('title'));
			
			var action = "action=show";
			var params = "id=" + $(this).attr('title');
			var post_data = action + "&" + params;
			$.post("agent_process.php", post_data,
					function(response){
						$('div#'+objectTyp.toLowerCase()+'Form').show();
						$('div#'+objectTyp.toLowerCase()+'Form').find('#okButton').hide();
						$('div#linkedObjects').hide();
						$('div#'+objectTyp.toLowerCase()+'Form').find('#updateDeleteButtons').show();
						//buildObjectForm($('div#'+objectTyp.toLowerCase()+'Form'), objectTyp, response);
						buildObjectForm($('div#'+objectTyp.toLowerCase()+'Form'), response);						
					}, "json");
        });

});

function listObjects(selector){
	var action = "action=listObjects";
	var params = 'request=<?xml version="1.0" encoding="utf-8" ?><request><filter></filter></request>';
	var post_data = action + '&' + params;
	$.post("list_process.php", post_data,
			function(response){
				selector.empty();
				$.each(response, function(index, hosts){
					$.each(hosts, function(i, object){
						selector.append('<li id="' + object.type.toLowerCase() + '" title="' + object.id + '">'
											+ '(' + object.type + ')'
											+ ' ' + object.id
											+ ' : ' +object.content
											+ '</li>');
					});
				});
			}, "json");
}

function listAllObjectsToSelect(selector, selected){
	var action = "action=listObjects";
	var params = 'request=<?xml version="1.0" encoding="utf-8" ?><request><filter></filter></request>';
	var post_data = action + '&' + params;
	$.post("list_process.php", post_data,
			function(response){
				selector.empty();
				$.each(response, function(index, hosts){
					$.each(hosts, function(i, object){
						checked = '';
						if($.inArray(object.id, selected) != -1)
							checked = ' checked="checked" ';
							
						selector.append('<input type="checkbox" class="objects" name="objects"'
											+ checked
											+ 'id="' + object.type.toLowerCase() + '" value="' + object.id + '">'
											+ '(' + object.type + ')'
											+ ' ' + object.id
											+ ' : ' +object.content
											+ '<br />');
					});
				});
			}, "json");
}

function updateList(selector, object, action){
	var found = selector.find('li[title="'+object.id+'"]');
	switch(action){
		case 'new':
			selector.append('<li id="' + object.type + '" title="' + object.id + '">'
						+ '(' + object.type + ')'
						+ ' ' + object.id
						+ ' : ' +object.content
						+ '</li>');
			break;
		case 'update':
			selector.find('li[value="'+object.id+'"]').remove();
			selector.append('<li id="' + object.type + '" title="' + object.id + '">'
						+ '(' + object.type + ')'
						+ ' ' + object.id
						+ ' : ' +object.content
						+ '</li>');
			break;
		case 'delete':
			selector.find('li[title="'+object.id+'"]').remove();
			break;
	}
}

function hideAllObjectsContentField(selection){
	if(selection.find('div#all').is(':visible')) selection.find('div#all').hide();
	if(selection.find('div#message').is(':visible')) selection.find('div#message').hide();
	if(selection.find('div#contact').is(':visible')) selection.find('div#contact').hide();
	if(selection.find('div#task').is(':visible')) selection.find('div#task').hide();
	if(selection.find('div#note').is(':visible')) selection.find('div#note').hide();
	if(selection.find('div#appointment').is(':visible')) selection.find('div#appointment').hide();
	if(selection.find('div#project').is(':visible')) selection.find('div#project').hide();
}

/* function buildObjectForm(div, typ, data){
	switch(typ){
		case 'Contact':
			buildContactForm(div, data);
			break;
		case 'Project':
			// buildProjectForm(div, data);
			break;
	}
} */

function buildObjectForm(div, data){
	div.find('input[class!="button"]').each(function(){
								var inputID = $(this).attr('id');
								$(this).attr('value', data[inputID]);
							});
}

function buildContactForm(div, response){
	div.find('input[class!="button"]').each(function(){
								var inputID = $(this).attr('id');
								$(this).attr('value', response[inputID]);
							});

	/* div.find('input#id').attr('value', response.id);
	div.find('input#firstname').attr('value', response.firstname);
	div.find('input#lastname').attr('value', response.lastname);
	div.find('input#street').attr('value', response.street);
	div.find('input#town').attr('value', response.town);
	div.find('input#zip').attr('value', response.zip);
	div.find('input#phone').attr('value', response.phone);
	div.find('input#email').attr('value', response.email);
	var linkStr = '';
	$.each(response.links, function(index, link){
								linkStr = linkStr + link + '; ';
		});
	div.find('input#links').attr('value', linkStr); */
}

function buildContactEmptyForm(div){
	div.find('input[class!="button"]').each(function(){
								$(this).attr('value', '');});
}



function parseXMLToHtmlFormInput(xml){
	$(xml).find('contact').each(function(){
		$('input#id').attr('value', $(this).find('>id').val());
		$('input#firstname').attr('value', $(this).find('firstname').val());
		$('input#lastname').attr('value', $(this).find('lastname').val());
		$('input#street').attr('value', $(this).find('street').val());
		$('input#town').attr('value', $(this).find('town').val());
		$('input#zip').attr('value', $(this).find('zip').val());
		$('input#phone').attr('value', $(this).find('phone').val());
		$('input#mobile').attr('value', $(this).find('mobile').val());
		$('input#email').attr('value', $(this).find('email').val());
  });
}


function convertFormToXML(div, typ){
	//var xmlHeader = '<?xml version="1.0" encoding="utf-8" ?>';
	
/* 	var xml = xmlHeader 
				+'<contact>'
				+'<id>'+div.find('input#id').val()+'</id>'
				+'<firstname>'+div.find('input#firstname').val()+'</firstname>'
				+'<lastname>'+div.find('input#lastname').val()+'</lastname>'
				+'<street>'+div.find('input#street').val()+'</street>'
				+'<town>'+div.find('input#town').val()+'</town>'
				+'<zip>'+div.find('input#zip').val()+'</zip>'
				+'<phone>'+div.find('input#phone').val()+'</phone>'
				+'<mobile>'+div.find('input#mobile').val()+'</mobile>'
				+'<email>'+div.find('input#email').val()+'</email>'
				+'</contact>'; */

	var xml = '<' + typ + '>';
	div.find('input[class!="button"]').each(function(){
								var inputID = $(this).attr('id');
								if(inputID != 'links')
									xml = xml + '<' + inputID + '>' + $(this).attr('value') + '</' + inputID + '>';
								else{
									if($.trim($(this).attr('value')) == '')
										xml = xml + '<links/>';
									else{
										xml = xml + '<links>';
										var idArray = ($.trim($(this).attr('value'))).split(',');
										$.each(idArray, function(index, id){
														if($.trim(id) != '')
															xml = xml + '<id>' + $.trim(id) + '</id>';
														});
										xml = xml + '</links>';
									}
								}
							});
	xml = xml + '</' + typ + '>';
	return xml;
}

function xmlToObjectForm(div, xml){
	var object = xml.find('contact');	
	div.find('input[class!="button"]').each(function(){
								var inputID = $(this).attr('id');
								$(this).attr('value', object.find(inputID).val());
							});
	/* 
	div.find('input#id').attr('value', object.find('id').val());
	div.find('input#firstname').attr('value', object.find('firstname').val());
	div.find('input#lastname').attr('value', object.find('lastname').val());
	div.find('input#street').attr('value', object.find('street').val());
	div.find('input#town').attr('value', object.find('town').val());
	div.find('input#zip').attr('value', object.find('zip').val());
	div.find('input#phone').attr('value', object.find('phone').val());
	div.find('input#mobile').attr('value', object.find('mobile').val());
	div.find('input#email').attr('value', object.find('email').val()); */
}
