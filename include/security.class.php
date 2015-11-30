<?php

class Security {
	
	static function Randomize () {
		// initialiser la variable $mdp
		$mdp = "";
		
		$longueur = 5;
	 
		// Définir tout les caractères possibles dans le mot de passe, 
		// Il est possible de rajouter des voyelles ou bien des caractères spéciaux
		$possible = "xQRK67YdfBCLTGH34wzbcghjDZy012FAJMk89avWXmnpqrtVNP";
	 
		// obtenir le nombre de caractères dans la chaîne précédente
		// cette valeur sera utilisé plus tard
		$longueurMax = strlen($possible);
	 
		if ($longueur > $longueurMax) {
			$longueur = $longueurMax;
		}
	 
		// initialiser le compteur
		$i = 0;
	 
		// ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
		while ($i < $longueur) {
			// prendre un caractère aléatoire
			$caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
	 
			// vérifier si le caractère est déjà utilisé dans $mdp
			if (!strstr($mdp, $caractere)) {
				// Si non, ajouter le caractère à $mdp et augmenter le compteur
				$mdp .= $caractere;
				$i++;
			}
		}
	 
		// retourner le résultat final
		return $mdp;
	}
	
	static Hash ($str) {
		return sha1($str);
	}
	
};


?>