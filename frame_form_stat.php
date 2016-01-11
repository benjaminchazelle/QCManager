<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");
		
$auth = new Auth(true);

$error = true;

$data = array();

if(Validation::Query($_GET, array("id")) && is_numeric($_GET["id"])) {

	$questionnaire_result = $_MYSQLI->query('SELECT * FROM questionnaire WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["id"]).'" LIMIT 1');
		
	if($questionnaire_result->num_rows > 0)	{
		
		$error = false;
		
		$questionnaire = $questionnaire_result->fetch_object();
		
		$data["questionnaire"] = $questionnaire;
		
		$own = $questionnaire->questionnaire_user_id == Auth::getUserId();

		$data["questionnaire"]->own = $own;
		
	}
}




if($error) {
	header("Location: 404.php");
	exit;
}
			
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>
	</head>
	
	<body style="background:#fff;	text-align:left;">

		<div id="answer_framed" >
				<div class="padder">

						<img id="loader" src="media/static/loader.gif" alt="" style="display:none;margin-top:10px;" />
						<div id="answerForm">
							<form action="" method="post" id="statform">
							<fieldset>
								<legend>Règles de notations</legend>
								Pour chaque erreur sur la question :
								
								<ul id="rules">

								<li><label><input <?php if($questionnaire->questionnaire_notation_rule == 0) echo 'checked'; if(!$own) echo ' disabled'; ?> type="radio" name="rule" value="same" />Retirer autant de points qu'une réponse juste</label></li>								
								<li><label><input <?php if($questionnaire->questionnaire_notation_rule == 1) echo 'checked'; if(!$own) echo ' disabled'; ?> type="radio" name="rule" value="middle" />Retirer la moitié des points par rapport à une réponse juste</label></li>								
								<li><label><input <?php if($questionnaire->questionnaire_notation_rule == 2) echo 'checked'; if(!$own) echo ' disabled'; ?> type="radio" name="rule" value="zero" />Retirer tous les points à la question</label></li>
								<li><label><input <?php if($questionnaire->questionnaire_notation_rule == 3) echo 'checked'; if(!$own) echo ' disabled'; ?> type="radio" name="rule" value="all" />Ne retirer aucun point à la question</label></li>
								
								
								<ul>
							</fieldset>
							
							<fieldset>
								<legend>Résultats</legend>
								
								<table cellpadding="0" cellspacing="0" id="statresult">

								</table>
								
							</fieldset>
							
							</form>
						</div>
				</div>
			</div>
		<script>

			$("#rules input").click(function() {
				rule = $(this).val();
				
				$.getJSON("ajax/getForm.php?questionnaire_id=<?php echo $_GET["id"]; ?>&rule="+rule, function (json) {
					
					if(!json.error) {
						
						var data = json.data;
						
						$("#statresult").html("");
						// alert(data.answers);
						first = true;
						
						var avg = 0;
						var total = 0;
						
						for(user in data.answers) {
							total++;
							var score = 0;
							// user.identity;
							
							var max_score = 0;
							
							for(question in data.answers[user].score) {
								
								var weight = data.questions[question].weight;
								max_score += parseInt(weight);
								var total_correct = data.questions[question].total_correct;
								
								var sumup = data.answers[user].score[question].correct  / total_correct * weight;
								// $("#statresult").append(data.answers[user].score[question].correct  / total_correct*weight + "<br />");
								
								if(rule == "zero" && data.answers[user].score[question].uncorrect > 0) {
									sumdown = sumup;
								}
								else if(rule == "same") {
									sumdown = 1 / total_correct * data.answers[user].score[question].uncorrect * weight;
								}
								else if(rule == "middle") {
									sumdown = 1 / total_correct / 2 * data.answers[user].score[question].uncorrect * weight;
								}
								else { //all
									sumdown = 0;
								}
								
								score += sumup - sumdown;
								// $("#statresult").append(sumup + " - " + sumdown + " = " + (sumup-sumdown) + "<br />");
							}
							
							if(first) {
								first=false;
								
								$("#statresult").append("<tr><th>Nom</th><th>Note sur "+max_score+" </th><th>Note sur 20</tr></tr>");
								$("#statresult").append("<tr id=\"avgtr\"><td><b>Moyenne</b></td><td id=\"avgscore\"></td><td id=\"avgscore20\"></tr></tr>");
							}
							
							avg += score;
							
							mytr = (parseInt(user) == <?php echo Auth::getUserId(); ?>) ? 'id="mytr"' : '';
							
							$("#statresult").append("<tr "+mytr+"><td>" + data.answers[user].identity + "</td><td>" + parseFloat(score).toFixed(2) + "</td><td>" + parseFloat(score/max_score*20, 2).toFixed(2) + "</tr></tr>");
							
							
						}
						
						if(total > 0) {
						avg = parseFloat(avg / total);
						
						$("#avgscore").text(avg.toFixed(2)) ;
						$("#avgscore20").text( parseFloat(avg / max_score * 20).toFixed(2) );
						
						$("#mytr").insertAfter("#avgtr");
						}
						else {
							$("#statresult").html("<tr><td>Aucun utilisateur n'a encore répondu à ce QCM.</td></tr>");
						}
						
					}
				});
			});
			
			$("#rules input:checked").click();

		</script>
	</body>
	
</html>