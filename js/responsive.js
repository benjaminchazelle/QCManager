$(".responsive #menucolumn").hover(function () {

	if($("#menucolumn").data("state") != "on") {
		$("#menucolumn").data("state", "on");
		
		$("#menucolumn, #logo").css("width", "199px");
		$("#menu_items").css("opacity", "1");
		$("#menu_items").css("overflow-y", "auto");

		$("#questionscolumn, #searchBar").css("left", "200px");
		$("#questionscolumn").css("box-shadow", "none");
	}
	else {
		$("#menucolumn").data("state", "off");
		
		$("#menucolumn, #logo").css("width", "");
		$("#menu_items").css("opacity", "");
		$("#menu_items").css("overflow-y", "");

		$("#questionscolumn, #searchBar").css("left", "");
		$("#questionscolumn").css("box-shadow", "");

	}
	



});