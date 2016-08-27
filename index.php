<!DOCTYPE html>
<html>
	<head>
		<!-- En-tête -->
		<meta charset="utf-8" >		 						<!-- Encodage -->
		<link rel="stylesheet" href="style.css" /> 			<!-- Lien vers feuille de style .css -->
		<link href="/Tools/css/magnific-popup.css" rel="stylesheet" type="text/css" />


		<title>Tombinoscope</title> 
	
	<script src="http://code.jquery.com/jquery-1.12.1.min.js"></script>
	<script src="/Tools/js/jquery.magnific-popup.min.js" type="text/javascript"></script> 
	
	<script>
		$(function() {
			$('#onglets').css('display', 'block');
			$('#onglets').click(function(event) {
				var actuel = event.target;
				if (!/li/i.test(actuel.nodeName) || actuel.className.indexOf('actif') > -1) {
					alert(actuel.nodeName)
					return;
				}
				$(actuel).addClass('actif').siblings().removeClass('actif');
				setDisplay();
			});
			function setDisplay() {
				var modeAffichage;
				$('#onglets li').each(function(rang) {
					modeAffichage = $(this).hasClass('actif') ? '' : 'none';
					$('.item').eq(rang).css('display', modeAffichage);
				});
			}
			setDisplay();
		});
	</script>
	
	</head>
	<body>
		<!-- Corps / Contenu -->

		<p class="intro">
			Les photographies placées ici sont fournies afin de proposer un "trombinoscope" de l'équipe facilitant l'intégration des nouveaux arrivants.<br />
			Elles ont été obtenues avec l'accord de chacun. Leur utilisation en dehors de ce site est interdite, sauf accord explicite des intéressés.
		</p>
		<br/>
		<?php
			include "includes/connexion.php";
			include "includes/auth.php";

			echo "<div style='position:absolute; margin:0px; padding:0px; left:10px; top: 10px;'><img src='images/PCIH.png' width=64 height=64></div>\n";
				$cmpt = 0; $tropen = 0;
				
			echo "<div style='position:absolute; margin:0px; padding:0px; right:10px; top: 10px;'>\n";	
			echo "<link rel='stylesheet' href='style.css' />\n";
			echo "<form action ='result.php' method='post' target=_blanck>\n";
			// echo "<form name=reqform id=reqform action ='#1?w=50%' rel='popup_name' class='poplight' method='get'>\n";
			echo "<span class='encadrement'>Recherche par nom :</span><br>\n";
			echo "<input type='text' id='search' name='search'/><br>\n";
			echo "<input type='submit' value='Envoyer' class='bouton'>\n";
			echo "<input type='reset' value='Annuler' class='bouton'>\n";
			echo "</form>\n";
			echo "</div>\n";

			// echo "<div id='popup_name' class='popup_block'>";
				// echo "<a href='#1' id='closepop' onclick='document.getElementById(\"popup_name\").style.display = \"none\"; $(\"#fade , .popup_block\").fadeOut();'>";
				// echo "<img src='/Tools/img/close.png' class='btn_close' title='Fermer' alt='Fermer' />";
				// echo "</a><br>";

				// echo '<iframe src="result.php?search=\'<script type="text/javascript"> document.reqform.search.value</script>\'" style="width:100%; height:100%">';
				// echo "<p>Votre navigateur ne supporte pas l\"élément iframe</p>";
				// echo "</iframe>";
			// echo "</div>";		
				
				// On récupère la liste des fonctions décrites dans la base de données, classées par ordre alphabétique.
				
				// Requête pour identifier les fonctions disponibles

				$req_fonc = $bdd->query("SELECT DISTINCT fonction from trombinoscope WHERE (datedinvalidite >= NOW() or datedinvalidite is null or datedinvalidite = '0000-00-00') ORDER BY fonction ASC");
				
				echo "<ul id='onglets'>\n";
				$nb_fonc = 0;
				
				while ($fonc[$nb_fonc] = $req_fonc->fetch()) {
					if ($nb_fonc == 0) {
						echo "<li class='actif'>\n".iconv("ISO-8859-1", "UTF-8", $fonc[$nb_fonc]["fonction"])."s</li>\n";
						$nb_fonc++;
					}
					else {
						echo "<li>\n".iconv("ISO-8859-1", "UTF-8", $fonc[$nb_fonc]["fonction"])."s</li>\n";
						$nb_fonc++;
					}
					
				}
				echo "</ul>\n";
				echo "<div id='contenu'>\n";
				
				
				
				for ($i=0; $i < $nb_fonc; $i++) {
					$cmpt = 0;
					$req_user = $bdd->query("SELECT * FROM trombinoscope WHERE (fonction like '".remove_accent($fonc[$i]["fonction"])."') and (datedinvalidite >= NOW() or datedinvalidite is null or datedinvalidite = '0000-00-00') ORDER BY UNA, UPRE ASC");	// On récupère tout le contenu de la table trombinoscope
					echo "<div class='item'>\n";
					// echo "<h2>onglet ".$i."</h2>";

					echo "<table class='sansbordure'>\n";
					while ($donnees = $req_user->fetch()) {
								
						if ($cmpt == 0) {
							echo "<tr>\n";
							$tropen = 1;
							$cmpt++;
						}

						echo "<td>\n";
						if ((isset($donnees["photo"])) && ($donnees["photo"] <> "")) {
							// if (@getimagesize($donnees["photo"]) ) {
								// echo $photo_dir.iconv("ISO-8859-1", "UTF-8", $donnees["photo"]);
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
					$req_user->closeCursor(); 
				}

				echo "</div>";
				echo "</ul>";
				$req_fonc->closeCursor(); 
				

		?>
		<script>
			$(document).ready(function() {
				//Lorsque vous cliquez sur un lien de la classe poplight et que le href commence par #1
				$('a.poplight[href^=#1]').click(function() {
					var popID = $(this).attr('rel'); //Trouver la pop-up correspondante
					var popURL = $(this).attr('href'); //Retrouver la largeur dans le href

					//Récupérer les variables depuis le lien
					var query= popURL.split('?');
					var dim= query[1].split('&amp;');
					var popWidth = dim[0].split('=')[1]; //La première valeur du lien
			// alert (Number(popWidth));
					//Faire apparaitre la pop-up et ajouter le bouton de fermeture
					$('#' + popID).fadeIn().css({
						'width': '95%',
						'height': '75%'
					})
					// .prepend('<a href="#" id="close" onclick="$(\'#fade, a.close\').remove(); $(\'#fade , .popup_block\').fadeOut();"><img src="/icon/close.png" class="btn_close" title="Fermer" alt="Fermer" /></a>');

					//Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
					// var popMargTop = ($('#' + popID).height() + 80)/2 ;
					// var popMargLeft = ($('#' + popID).width() + 80)/2 ;
					var popMargTop = 5 ;
					var popMargLeft = 5 ;
					//On affecte le margin
					$('#' + popID).css({
						'margin-top' : -popMargTop,
						'margin-left' : -popMargLeft
					});

					//Effet fade-in du fond opaque
					$('body').append('<div id="fade"></div>'); //Ajout du fond opaque noir
					//Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
					$('#fade').css({'filter' : 'alpha(opacity=30)'}).fadeIn();

					return false;
				});

				//Lorsque vous cliquez sur un lien de la classe poplight et que le href commence par #2
				$('a.poplight[href^=#2]').click(function() {
					var popID = $(this).attr('rel'); //Trouver la pop-up correspondante
					var popURL = $(this).attr('href'); //Retrouver la largeur dans le href

					//Récupérer les variables depuis le lien
					var query= popURL.split('?');
					var dim= query[1].split('&amp;');
					var popWidth = dim[0].split('=')[1]; //La première valeur du lien
					// alert (Number(popWidth));
					//Faire apparaitre la pop-up et ajouter le bouton de fermeture
					$('#' + popID).fadeIn().css({
						'width': '95%',
						'height': '75%'
					})
					// .prepend('<a href="#" id="close" onclick="$(\'#fade, a.close\').remove(); $(\'#fade , .popup_block\').fadeOut();"><img src="/icon/close.png" class="btn_close" title="Fermer" alt="Fermer" /></a>');

					//Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
					// var popMargTop = ($('#' + popID).height() + 80)/2 ;
					// var popMargLeft = ($('#' + popID).width() + 80)/2 ;
					var popMargTop = 5 ;
					var popMargLeft = 5 ;
					//On affecte le margin
					$('#' + popID).css({
						'margin-top' : -popMargTop,
						'margin-left' : -popMargLeft
					});

					//Effet fade-in du fond opaque
					$('body').append('<div id="fade"></div>'); //Ajout du fond opaque noir
					//Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
					$('#fade').css({'filter' : 'alpha(opacity=30)'}).fadeIn();

					return false;
				});

				//Fermeture de la pop-up et du fond
				$('a.close, #fade').live('click', function() { //Au clic sur le bouton ou sur le calque...
					$('#fade , .popup_block').fadeOut(function() {
						alert("remove"); $('#fade, a.close').remove();  //...ils disparaissent ensemble
					});
					return false;
				});

			});
		</script>
	</body>
</html>