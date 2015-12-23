<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>
	</head>
	
	<body style="background: #fff;">
			<div id="answer_framed" >
				<div class="padder">
					<div id="questiontitle">On considère les deux unités de pression : le Bar et le Pascal. Laquelle des propositions suivantes est correcte ?</div>
					<form action="" method="POST" id= "questionchoices" >
						<input type="radio" name="group1" value="Choice1"> 1 bar = 105 N.m².s² <br>
						<input type="radio" name="group1" value="Choice2" checked> 1 bar = 145 N.m².s² <br>
						<input type="radio" name="group1" value="Choice3"> 1 bar = 108 N.m².s² <br>
					</form>
					<div id="indice">► Indice</div>
					<div id="indice_content" style="">
					Coucou
					</div>
					<input type="submit" form="QuestionChoices" value="Sauvegarder" class="btn"></button>
				</div>
			</div>
			
		<script>
		hint=false;

		$('#indice').click( function() { // Au clic sur un élément

		// $("#indice_content").animate({opacity: 'toggle', height: 'toggle'}, 4000);


		if(!hint) {
		$("#indice_content").animate({height: 'toggle'}, 300).animate({opacity: '1'}, 300);
		$('#indice').html("▼ Indice");				
		}

		else {
		$("#indice_content").animate({opacity: '0'}, 300).animate({height: 'toggle'}, 300);
		$('#indice').html("► Indice");				
		}


		hint = !hint;

		});
		</script>
	</body>
	
	
</html>