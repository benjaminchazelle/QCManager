$(".responsive #menucolumn").hover(function () {

	if($("#menucolumn").data("state") != "on") {
		$("#menucolumn").data("state", "on");
		
		$("#menucolumn, #logo").css("width", "199px");
		$("#menu_items").css("opacity", "1");
		
		$("#logo").css("background-image", "url(media/static/logo.png)");

		$("#questionscolumn, #onecolumn").css("left", "200px");
		$("#questionscolumn, #onecolumn").css("box-shadow", "none");
	}
	else {
		$("#menucolumn").data("state", "off");
		
		$("#menucolumn, #logo").css("width", "");
		$("#menu_items").css("opacity", "");
		
		$("#logo").css("background-image", "");

		$("#questionscolumn, #onecolumn").css("left", "");
		$("#questionscolumn, #onecolumn").css("box-shadow", "");

	}

});