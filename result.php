<!DOCTYPE html>
<html>
	<head>
		<!-- En-tête -->
		<meta charset="utf-8" >		 						<!-- Encodage -->
		<link rel="stylesheet" href="style.css" /> 	
		<title>Résultats de la recherche</title>
	<head>
	<body>
		<frameset rows="80,*" frameborder="NO" border="0" framespacing="5">
		<frame src="pcih/index_header.php" name="topFrame" scrolling="NO" noresize >
		</frameset>
		<?php
		header('Content-Type: text/html; charset=UTF-8');
		include "includes/connexion.php";
		include "includes/auth.php";
		
		if(isset($_GET['search'])) {
			$chainesearch = addslashes($_GET['search']);  
		} else {
			if(isset($_POST['search'])) {
				$chainesearch = addslashes($_POST['search']); 
			} else {
				$chainesearch = "";
			}
		}

		if ($chainesearch <> "") { 

			echo '<p>Vous avez recherché : ' . $chainesearch . '</p>';      

				include "includes/connexion.php";
				$requete = "SELECT * from trombinoscope WHERE una LIKE '". $chainesearch 
				."%' OR upre LIKE '". $chainesearch 
				."%' OR umar LIKE '". $chainesearch
				."%' OR Tpro LIKE '". $chainesearch
				."%' OR secteur LIKE '". $chainesearch ."%'"; 
					
				// Exécution de la requête SQL
				$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
				echo '<p>Les résultats de recherche sont : </p><br />';
			$req_fonc = $bdd->query("SELECT DISTINCT fonction from trombinoscope WHERE (datedinvalidite >= NOW() or datedinvalidite is null or datedinvalidite = '0000-00-00') ORDER BY fonction ASC");
			$nb_fonc = 0;
			while ($fonc[$nb_fonc] = $req_fonc->fetch()) {
				if ($nb_fonc == 0) {
					$nb_fonc++;
				}
				else {
					$nb_fonc++;
				}
				
			}
			echo "<div id='contenu'>\n";
			for ($i=0; $i < $nb_fonc; $i++) {
				$cmpt = 0;
				echo "<div class='item'>\n";
				// echo "<h2>onglet ".$i."</h2>";

				echo "<table class='sansbordure'>\n";
				while ($donnees = $resultat->fetch()) {
							
					if ($cmpt == 0) {
						echo "<tr>\n";
						$tropen = 1;
						$cmpt++;
					}

					echo "<td>\n";
					if ((isset($donnees["photo"])) && ($donnees["photo"] <> "")) {
						if (is_url_exist($photo_dir.iconv("ISO-8859-1", "UTF-8", $donnees["photo"])) ) {
							echo "<img src='".iconv("ISO-8859-1", "UTF-8", $photo_dir.$donnees["photo"])."' width=64 height=64 alt='' />\n";
						}
						else {
							echo "<img src='".$tronche."' width=64 height=64 alt='' />\n";
						}
					} else {
						echo "<img src='".$anonym."' width=64 height=64 alt='' />\n";
					}
					echo "</td>\n";
					echo "<td width=320>\n";
					echo "&nbsp;Nom: ".$donnees["una"].(($donnees["umar"] <> "") ? "&nbsp;-&nbsp;Nom marital: ".$donnees["umar"] : "")."<br>\n";
					echo "&nbsp;Prénom: ".iconv("ISO-8859-1", "UTF-8", $donnees["upre"])."<br>\n";
					
					if (isset($donnees["comment"]) &&  ($donnees["comment"] <> "")) {
						echo "&nbsp;<b><i>".iconv("ISO-8859-1", "UTF-8", $donnees["comment"])."</b></i><br>\n";
					}						
					if (isset($donnees["secteur"]) &&  ($donnees["secteur"] <> "")) {
						echo "&nbsp;<b><i>".iconv("ISO-8859-1", "UTF-8", $donnees["secteur"])."</b></i><br>\n";
					}
					if (isset($donnees["Epro"]) &&  ($donnees["Epro"] <> "")) {
						echo "&nbsp;Email Pro: <a href='mailto:".$donnees["Epro"]."'>".$donnees["Epro"]."</a><br>\n";
					}
					if (isset($donnees["persoShow"]) &&  ($donnees["persoShow"]== 1)) {
						echo "<i><span class='eperso'>&nbsp;Email Perso: <a href='mailto:".$donnees["Eperso"]."'>".$donnees["Eperso"]."</a></span></i><br>\n";
					}
					if (isset($donnees["Tpro"]) &&  ($donnees["Tpro"] <> "")) {
						echo "&nbsp;Poste: ".$donnees["Tpro"]."<br>\n";
					}
					echo "</td>\n";
					
					if ($cmpt == $nbutilperlig) {
						echo "</tr>\n";
						$tropen = 0;
						$cmpt = 0;
					} else {
						echo "<td class='sansbordure' width=15></td>\n";
						$cmpt++;
					}
				}
				if ($tropen == 1) {echo "</tr>\n";}
				echo "</table>\n";
				echo "</div>";
			}
			echo "</div>";
		}
		?>
	<body>
<html>