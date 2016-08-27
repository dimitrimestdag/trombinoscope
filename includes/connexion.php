<?php
// connexion bdd    
		$BDD_hote = 'ws-hur-radm1wl';
		$BDD_bd = 'linda_users';
		$BDD_utilisateur = 'root';
		$BDD_mot_passe = '59PCIHAdmin';
	
	try{
				$bdd = new PDO('mysql:host='.$BDD_hote.';dbname='.$BDD_bd, $BDD_utilisateur, $BDD_mot_passe, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			}
				catch(PDOException $e){
				echo 'Erreur : '.$e->getMessage();
				echo 'N° : '.$e->getCode();
			}      
?>