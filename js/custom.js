$(document).ready(function() {
	hideAllObjectsContentField($(this));
	//listObjects($('#listContent > ul'));
	
	$('#systemControl > input#startup').click((function(){
        return function()
        {	
			$('#hostList').empty(); $('#hostList').hide();
			showLoading($('div#hosts').find('#loading'));
			$.post("system_control.php", "action=startup",
					function(response){
						$.each(response, function(i, host){
							var hostElem = '<a href="http://' + host.ip + '">'
											+ host.id + '</a> ';
							$('#hostList').append(hostElem);
						});
						hideLoading($('div#hosts').find('#loading'));
						$('#hostList').show();
						$('#systemControl > input#startup').attr('value', 'Refresh');
						showLoading($('div#objectList').find('#loading'));
						listObjects($('#listContent > ul'));
						hideLoading($('div#objectList').find('#loading'));
					}, "json");
        }
        })());
	
	$('#systemControl > input#shutdown').click((function(){
        return function()
        {	
			$('#hostList').empty(); $('#hostList').hide();
			showLoading($('div#hosts').find('#loading'));
			$.post("system_control.php", "action=shutdown",
					function(response){
						$('#systemControl > input#startup').attr('value', 'Startup');
						hideAllObjectsContentField($(document));
						$('#listContent > ul').empty();
						hideLoading($('div#hosts').find('#loading'));
						$('#hostList').append("<strong>Server is down.</strong>");
						$('#hostList').show();							
					}, "json");
        }
        })());
	
	$('ul#tabnav > li').each(function(){ 
				var typ = $(this).attr('id');
				switch(typ){
					case 'all':
						$(this).click((function(){
								return function()
								{			
									$('#tabnav').find('li').each(function(){ $(this).removeClass('selectedTab');});
									$('ul#tabnav > li#all').addClass('selectedTab'); 
									if($('div#searchContent').is(':visible')) $('div#searchContent').hide();
									$('#listContent > ul > li').show();
									hideAllObjectsContentField($(document));
								}
								})());
						break;
					case 'search':
						$(this).click((function(){
								return function()
								{			
									$('#tabnav').find('li').each(function(){ $(this).removeClass('selectedTab');});
									$('ul#tabnav > li#search').addClass('selectedTab');  
									$('#listContent > ul > li').hide();
									hideAllObjectsContentField($(document));
									if(!$('div#searchContent').is(':visible')) $('div#searchContent').show();
									$('div#searchResultsContent > ul').empty();
								}
								})());
						break;
					default:
						$(this).click((function(){
								return function()
								{	
									hideAllObjectsContentField($(document));
									if($('div#searchContent').is(':visible')) $('div#searchContent').hide();
									$('div#objectContent').show();
									$('div#objectContent').find('fieldset#objectTyp > legend > strong').text(typ.toUpperCase() + " :");
									showObjectContent(typ);
									$('#objectForm').find('#newButton').show();
									$('#objectForm').find('#updateDeleteButtons').hide();
									$('div#linkedObjects').hide();
									$('#tabnav').find('li').each(function(){ $(this).removeClass('selectedTab');});
									$('#tabnav > li#'+typ).addClass('selectedTab');
									$('#listContent > ul > li').hide();
									$('#listContent > ul > li#'+typ).each(function(){ $(this).show();});
									$('#objectForm').find('input#objectTyp').attr('value', typ);
									emptyObjectForm($('div#objectForm'));			
								}
								})());
						break;
				} 
			}
		);
	
	$('input#new').click((function(){
        return function()
        {	
			var typ = $('#objectForm').find('input#objectTyp').attr('value');
			hideAllObjectsContentField($(document));
			var action = "action=create";
			var request = convertFormToXML($('div#objectForm > #formInputField[title='+typ+']'), typ);
			var params = "request="+escape(request)+"&typ="+typ;
			//alert(request + '----->' + params);
			var post_data = action + "&" + params;
			$.post("agent_process.php", post_data,
					function(response){					
						$('div#messageContent').show();
						$('div#messageContent').find('label').text(response.message);
						if(response.status == 'OK')
							updateList($('#listContent > ul'), response.data, 'new');
					}, "json");
        }
        })());
		
	$('input#update').click((function(){
        return function()
        {	
			var typ = $('#objectForm').find('input#objectTyp').attr('value');
			hideAllObjectsContentField($(document));
			var action = "action=update";
			var request = convertFormToXML($('div#objectForm > #formInputField[title='+typ+']'), typ);
			var params = "request="+escape(request);
			params = params + "&id=" + $('input#objectID').attr('value');
			//alert(request + ' -----> ' + params);
			var post_data = action + "&" + params;			
			$.post("agent_process.php", post_data,
					function(response){					
						$('div#messageContent').show();
						$('div#messageContent').find('label').text(response.message);
						//if(response.status == 'OK')
						//	updateList($('#listContent > ul'), response.data, 'update');
					}, "json");
        }
        })());
		
	$('input#delete').click((function(){
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
						$('div#messageContent').show();
						$('div#messageContent').find('label').text(response.message);
						if(response.status == 'OK')
							updateList($('#listContent > ul'), response.data, 'delete');
					}, "json");
        }
        })());
		
	$('input#search').click((function(){
        return function()
        {				
			showLoading($('div#objectList').find('#loading'));
			var filter = $('input#searchFilter').attr('value');
			hideAllObjectsContentField($(document));
			var action = "action=search";	
			var post_data = action;
			if(filter != '')
				post_data = post_data + '&request='+filter;
			//alert(post_data);
			if(!$('div#searchContent').is(':visible')) $('div#searchContent').show();
			$('#searchResultsContent > ul').empty(); $('#searchResultsContent').hide();
			$.post("agent_process.php", post_data,
				function(response){
					if(response != '')
						$.each(response, function(index, hosts){
							$.each(hosts, function(i, object){
								$('div#searchResultsContent > ul').append('<li id="' + object.type.toLowerCase() + '" title="' + object.id + '">'
																			+ '(' + object.type + ')'
																			+ ' ' + object.id
																			+ ' : ' +object.content
																			+ '</li>');
							});
						
						});
					hideLoading($('div#objectList').find('#loading'));
					$('#searchResultsContent').show();
			}, "json");
			
        }
        })());
		
	$('input#links').click((function(){
        return function()
        {
			$('div#linkedObjects').show();
			listAllObjectsToSelect($('div#linkedObjects').find('div#objectsList'), $.trim($(this).attr('value')).split(','));
        }
        })());
		
	$('div#objectsList').find('input').live('click', function(){
			var checkedObjects = '';
			$('div#objectContent').find('div#objectsList').find('input:checked').each(function(){
				checkedObjects = checkedObjects + $(this).attr('value') + ', ';});
			$('div#objectForm').find('input#links').attr('value', checkedObjects);
        });
	
	$('#listContent').find('li').live('click', function(){
			var typ = $(this).attr('id');			
			hideAllObjectsContentField($(document));
			$('div#objectContent').show();
			$('div#objectContent').find('fieldset#objectTyp > legend > strong').text(typ.toUpperCase() + " :");			
			showObjectContent(typ);
			$('div#objectForm').find('input#objectTyp').attr('value', typ);
			$('div#objectForm').find('input#objectID').attr('value', $(this).attr('title'));			
			var action = "action=show";
			var params = "id=" + $(this).attr('title');
			var post_data = action + "&" + params;
			//alert(typ+' clicked!'+' post_data'+post_data);
			$.post("agent_process.php", post_data,
					function(response){
						$('div#objectForm').find('#newButton').hide();
						$('div#linkedObjects').hide();
						$('div#objectForm').find('#updateDeleteButtons').show();
						//buildObjectForm($('div#'+typ.toLowerCase()+'Form'), typ, response);
						buildObjectForm($('div#objectForm'), response);						
					}, "json");
        });
	
	$('#searchResultsContent').find('li').live('click', function(){
			var typ = $(this).attr('id');			
			hideAllObjectsContentField($(document));
			$('div#searchContent').show();
			$('div#objectContent').show();
			$('div#objectContent').find('fieldset#objectTyp > legend > strong').text(typ.toUpperCase() + " :");			
			showObjectContent(typ);
			$('div#objectForm').find('input#objectTyp').attr('value', typ);
			$('div#objectForm').find('input#objectID').attr('value', $(this).attr('title'));			
			var action = "action=show";
			var params = "id=" + $(this).attr('title');
			var post_data = action + "&" + params;
			//alert(typ+' clicked!'+' post_data'+post_data);
			$.post("agent_process.php", post_data,
					function(response){
						$('div#objectForm').find('#newButton').hide();
						$('div#linkedObjects').hide();
						$('div#objectForm').find('#updateDeleteButtons').show();
						//buildObjectForm($('div#'+typ.toLowerCase()+'Form'), typ, response);
						buildObjectForm($('div#objectForm'), response);						
					}, "json");
        });

});

function listObjects(selector){
	var action = "action=search";
	var post_data = action;
	$.post("agent_process.php", post_data,
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
	var action = "action=search";
	var post_data = action;
	$.post("agent_process.php", post_data,
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
			selector.append('<li id="' + object.type.toLowerCase() + '" title="' + object.id + '">'
						+ '(' + object.type + ')'
						+ ' ' + object.id
						+ ' : ' +object.content
						+ '</li>');
			break;
		case 'update':
			selector.find('li[value="'+object.id+'"]').remove();
			selector.append('<li id="' + object.type.toLowerCase() + '" title="' + object.id + '">'
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
	if(selection.find('div#messageContent').is(':visible')) selection.find('div#messageContent').hide();
	if(selection.find('div#searchContent').is(':visible')) selection.find('div#searchContent').hide();
	if(selection.find('div#objectContent').is(':visible')) selection.find('div#objectContent').hide();
}

function showObjectContent(typ){
	$('div#formInputField[title='+typ+']').show();
	$('div#formInputField:not([title='+typ+'])').hide();
}

function buildObjectForm(div, data){
	div.find('input[class!="button"]').each(function(){
								var inputID = $(this).attr('id');
								$(this).attr('value', data[inputID]);
							});
}

function emptyObjectForm(div){
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

// Function for loading pinner
function showLoading(selector){
	selector.show();
}

function hideLoading(selector){
	selector.hide();
}
