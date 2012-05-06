$(document).ready(function(){
	$('#login_button').button();
	styleForms();
	$('.btn_edit').button({
		icons: {
			primary: 'ui-icon-gear'
		},
		text: false
	});
	$('.btn_trash').button({
		icons: {
			primary: 'ui-icon-trash'
		},
		text: false
	});
	$('.btn_add').button({
		icons: {
			primary: 'ui-icon-plusthick'
		}
	})
});

function styleForms() {
	$('.form_element input, .form_element select').addClass('ui-widget-content ui-corner-all');
	//$('input[type=submit]').button();
}

