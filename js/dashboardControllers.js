questionsFrame = null;
answerFrame = null;

function InitQuestionsFrameController(win) {
	questionsFrame = win;
}

function InitAnswerFrameController(win) {
	answerFrame = win;
}



function ViewStatController(id, title) {
	
	// if(answerFrame == null) return;
	
	document.getElementById("answerFrame").src = "frame_form_stat.php?id="+id;
	document.getElementById("answercolumn").style.visibility = "visible";
	document.getElementById("title").innerHTML = title;
}

function ViewFormController(id) {
	window.location.href = "form.php?id="+id;
}


window.onload = function () {
	
	$("#minebtn").click(function () {
		
		if(questionsFrame == null) return;
		
		$("#otherbtn").removeClass("selected");
		$(this).addClass("selected");
		
		questionsFrame.document.getElementById("mine").style.display = "";
		questionsFrame.document.getElementById("other").style.display = "none";
		
		
	});
	
	$("#otherbtn").click(function () {
		
		if(questionsFrame == null) return;
		
		$("#minebtn").removeClass("selected");
		$(this).addClass("selected");
		
		questionsFrame.document.getElementById("mine").style.display = "none";
		questionsFrame.document.getElementById("other").style.display = "";
		
		
	});
	
	
};
