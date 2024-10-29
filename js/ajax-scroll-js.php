var speed = 500;
var opacity_loading = 0.2;
var is_loading = false;


function ajax_scroll_success(data, direction, container) {
	var container_id = "#" + container;
	if (direction == "older")
		jQuery(container_id).hide("slide", {direction: "right"}, speed);
	else
		jQuery(container_id).hide("slide", {direction: "left"}, speed);

	var newHTML = jQuery(container_id, data).html();

	if ( newHTML == null )
    	alert('Unable to locate container: ' + container);
	else
		jQuery(container_id).html(newHTML);
	
	if (direction == "older")
		jQuery(container_id).show("slide", {direction: "left"}, speed);
	else
		jQuery(container_id).show("slide", {direction: "right"}, speed);
	is_loading = false;
	if ( !(jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7 ) )
		jQuery(container_id).fadeTo(speed, 1);
	if (jQuery(container_id + "_load").length)
		jQuery(container_id + "_load").hide();
}

function ajax_scroll(direction, url, container)
{
	if (!is_loading)
    {
		is_loading = true;
		if ( !(jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7 ) )
			jQuery("#" + container).fadeTo(speed, opacity_loading);
		if (jQuery("#" + container + "_load").length)
			jQuery("#" + container + "_load").show();
        
		jQuery.ajax({type: "POST", url: url, cache: true, success: function(data) {ajax_scroll_success(data, direction, container);} });
	}
}