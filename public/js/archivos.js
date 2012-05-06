$(document).ready(function(){
	$('#btn_up').button({
		icons: {
			primary: 'ui-icon-arrowthick-1-n'
		},
		text: true
	});
	$('#btn_add_file').button({
		icons: {
			primary: 'ui-icon-script'
		},
		text: true
	});
	$('#btn_add_directory').button({
		icons: {
			primary: 'ui-icon-folder-collapsed'
		},
		text: true
	});
	$('.btn_permissions').button({
		icons: {
			primary: 'ui-icon-gear'
		},
		text: false
	});
	$('.btn_delete').button({
		icons: {
			primary: 'ui-icon-trash'
		},
		text: false
	});
	
	$('.toolbar_btn').click(function(event){
		event.preventDefault();
		var target_url = $(this).attr('href');
		var dialog_title = $(this).attr('title');
		
		$('#dialog').load(target_url, function(){styleForms();});
		
		$('#dialog').dialog({
			width: 350,
			height: 200,
			title: dialog_title,
			show: 'blind',
			hide: 'explode'
		});
	});
	
	// $('#saveDocument').click(function(){
		// event.preventDefault();
		// $
	// })
	
	$('.btn_permissions').click(function(event){
		var options = {
			target_url: $(this).attr('href'),
			title: $(this).attr('title'),
			width: 350,
			height: 400
		};
		optionsButton(event,options);
	});
	
	$('.btn_delete').click(function(event){
		var options = {
			target_url: $(this).attr('href'),
			title: $(this).attr('title'),
			width: 350,
			height: 100
		};
		optionsButton(event,options);
	});
	
	$('.item_display .item_options').each(function(){
		$(this).position({
			of: $(this).parent(),
			my: 'left top',
			at: 'left top',
			offset: '5',
			collision: 'flip'
		});
	});
});

function optionsButton(event, options){
	event.stopPropagation();
	event.preventDefault();
	
	$('#dialog').load(options.target_url, function(){styleForms();});
	
	$('#dialog').dialog({
		width: options.width,
		height: options.height,
		title: options.title,
		show: 'blind',
		hide: 'explode'
	});
}