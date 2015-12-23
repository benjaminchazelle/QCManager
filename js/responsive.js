/*$.fn.slideFadeToggle  = function(speed, easing, callback) {
        this.animate({opacity: 'toggle', height: 'toggle'});
        return this.animate({opacity: 'toggle', height: 'toggle'}, speed, easing, callback);
}; */


$(".responsive #menucolumn").hover(function () {

	if($("#menucolumn").data("state") != "on") {
		$("#menucolumn").data("state", "on");
		
		$("#menucolumn, #logo").css("width", "199px");
		$("#menu_items").css("opacity", "1");
		
		$("#logo").css("background-image", "url(media/static/logo.png)");

		$("#questionscolumn, #searchBar").css("left", "200px");
		$("#questionscolumn").css("box-shadow", "none");
	}
	else {
		$("#menucolumn").data("state", "off");
		
		$("#menucolumn, #logo").css("width", "");
		$("#menu_items").css("opacity", "");
		
		$("#logo").css("background-image", "");

		$("#questionscolumn, #searchBar").css("left", "");
		$("#questionscolumn").css("box-shadow", "");

	}
	



});