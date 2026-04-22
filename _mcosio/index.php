<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Statistiques Mantis</title>
		<link rel="stylesheet" href="stats.css" type="text/css" />

		
		<script language="Javascript">
		<!--
		function majDate() {
			window.document.getElementById("dateDeb").value="";
			window.document.getElementById("dateFin").value="";
			}

		function majRadio() {
			window.document.getElementById(["1"]).checked=false;
			window.document.getElementById(["2"]).checked=false;
			window.document.getElementById(["3"]).checked=false;
			window.document.getElementById(["4"]).checked=false;
			window.document.getElementById(["5"]).checked=false;
			window.document.getElementById(["6"]).checked=false;
			window.document.getElementById(["7"]).checked=false;
			window.document.getElementById(["8"]).checked=false;
			window.document.getElementById(["9"]).checked=false;
			window.document.getElementById(["10"]).checked=false;
			window.document.getElementById(["11"]).checked=false;
			window.document.getElementById(["12"]).checked=false;
		
		}


		function PlusAnnee (){
			a=window.document.getElementById("annee").value;
			a++;
			window.document.getElementById("annee").value=a;
			window.document.getElementById("anneevisi").value=a;
		
		}
		
		function MoinsAnnee (){
			a=window.document.getElementById("annee").value;
			a--;
			if (a<2012) a=2012;
			window.document.getElementById("annee").value=a;
			window.document.getElementById("anneevisi").value=a;
		
		}
		-->
		</script>
		
	</head>
	<body>
	<?php 
	include ("entete.php");
	
	
	//Gestion du fichier qui mémorise la date d'exécution du script cloture.php
	if (!(file_exists("datemaj.txt"))) { //le fichier n'existe pas il est créé
		$fp=fopen("datemaj.txt", "w+");
		$dateMaj=0;
		@fclose($fp);
	}
	else { //le fichier existe, on récupère la date de maj
		$fp=fopen("datemaj.txt", "r+");
		$dateMaj=fgets($fp);
		$dateMaj=(int)$dateMaj;
		if (!(is_int($dateMaj))) $dateMaj=0;
		@fclose($fp);
	}
	
	
	//echo "==".time()."==".$dateMaj;
	if (time()-$dateMaj > (int)600) $MsgErrMaj="La base n'est plus à jour depuis au moins 10mn. Veuillez forcer sa mise à jour !"; 
	else $MsgErrMaj="";
	

	
	
	function NbMails () {		
			
		try {


		$options =array (PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
		$pdo = new PDO('mysql:host=localhost;dbname=mantis', 'mantis', 'mantis',$options); //intradef
		

		}
		catch (Exception $e) {
			
		return -1;
		}
	
		
		$sql="SELECT count( * ) as nb FROM `mantis_email_table`";
			
		$result = $pdo->query($sql);
		$row = $result->fetch(PDO::FETCH_ASSOC) ;
		

		
		
		if (isset($row['nb'])) {
			$nb=$row['nb'];
			$result->closeCursor();	
			return $nb;
			
		}
		else {
			$result->closeCursor();	
			return -1;
		}
		
	}
		
		
		
	
	$mois[1]="jan";
	$mois[2]="fev";
	$mois[3]="mars";
	$mois[4]="avr";
	$mois[5]="mai";
	$mois[6]="juin";
	$mois[7]="juil";
	$mois[8]="aout";
	$mois[9]="sept";
	$mois[10]="oct";
	$mois[11]="nov";
	$mois[12]="dec";

	function CalculDateInit ()
	{
		if(isset($_POST["annee_retour"]) and $_POST["annee_retour"]!="") {
			return $_POST["annee_retour"];
		}
		else 
		{
			if (date("m")=="01") return(date("Y")-1); else  return date("Y");
		}
	}
	
	
	?>
	
	<div id="banner">
		<div align="left" class="cadre1">
			<form action="stats.php" method="POST">
			
			<table class="tableDate">
			<tr>
			<td>Date de début </td><td> <input type="text" onclick="javascript:majRadio();" class="mCalendar" value="<?php  if(isset($_POST["dateDeb_retour"])) { echo $_POST["dateDeb_retour"]; } ?>"  id="dateDeb" name="dateDeb"></td>
			<tr>
			<td>Date de fin  </td><td> <input type="text" onclick="javascript:majRadio();" class="mCalendar" value="<?php  if(isset($_POST["dateFin_retour"])) { echo $_POST["dateFin_retour"]; } ?>"   id="dateFin" name="dateFin"></td>
			<!-- <input type="text" class="mCalendarFR" value="19/10/2009">
			<input type="text" class="mCalendarEN" value="10/19/2009">-->
			</table>
		</div>
			
		<div class="separ">
		ou
		</div>	
			
		<div align="right" class="cadre2">
				<table class="tableMois">
				<tr>
					<td align="center">
					<div id="image_moins" onclick="MoinsAnnee();"></div></td><td align="center">
					<input name="annee" id="annee" style="text-align:center;" size="4" type="hidden" value="<?php 
						echo CalculDateInit(); ?>">
					<input disabled="disabled" name="anneevisi" id="anneevisi" style="text-align:center;" size="4" type="text" value="<?php echo CalculDateInit(); ?>">
					</td>
					<td align="center">
					<div id="image_plus" onclick="PlusAnnee();"></div></td>
			<?php for ($i=1;$i<13;$i=$i+3) {
				
				?>
				
				
				<tr>
			
			<td><input <?php if(isset($_POST["mois_retour"]) and $i==$_POST["mois_retour"]) { echo "checked='checked'"; } ?> class="checked" type="radio" onchange="javascript:majDate();" id="<?php echo $i ?>" name="mois" value=<?php echo $i ?>><label style="margin-left:4px;" for="<?php echo $i ?>" id="l<?php echo $i ?>"><?php echo $mois[$i]; ?></label></td>
			<td><input <?php if(isset($_POST["mois_retour"]) and ($i+1)==$_POST["mois_retour"]) { echo "checked='checked'"; } ?>  class="checked" type="radio" onchange="javascript:majDate();" id="<?php echo $i +1 ?>" name="mois" value=<?php echo $i +1 ?>><label style="margin-left:4px;" for="<?php echo $i+1 ?>" id="l<?php echo $i+1 ?>"><?php echo $mois[$i+1]; ?></label></td>
			<td><input <?php if(isset($_POST["mois_retour"]) and ($i+2)==$_POST["mois_retour"]) { echo "checked='checked'"; } ?>  class="checked" type="radio" onchange="javascript:majDate();" id="<?php echo $i +2 ?>" name="mois" value=<?php echo $i +2 ?>><label style="margin-left:4px;" for="<?php echo $i+2 ?>" id="l<?php echo $i+2 ?>"><?php echo $mois[$i+2]; ?></label></td>	
				<?php 
			}
				
				?>
			
			</table>
		
	

		
	
		<p style="margin-top:10px;"><input type=submit value="Calculer"></p>
		<script type="text/javascript" src="mCalandar.js"></script>

		</form>
		
			</div>
			
			<hr  style="visibility:hidden;clear:both;">
		
		
		</div>
		
		
		
		
		
		
		
	
	
		<div id="banner">
		<div align="left" class="cadre3">
						
			<table style="none">
			<tr>
			<td>Mise en forme des extractions de Mantis. </br>
			Depuis Mantis : "Afficher les faits techniques", puis choisir "Export Excel". Copiez/collez l'ensemble des lignes dans le fichier Excel : </br> 
			<a href="Modele liste FFT MANTIS.xlsm">Mise en forme des extractions v1.3 fév.2014</a>.</td>

			</table>

			
				
	

		
		
		
				</div>
				<hr  style="visibility:hidden;clear:both;">
				
				
	</div>
	
	
	
	
	
	<div id="banner">
		<div align="left" class="cadre3">
						
			<table style="none">
			<tr>
			<td>Dans l'affichage des FFT, la colonne DATE CLOTURE a été ajoutée. 
			Cette colonne est actualisée toutes les 5 minutes. La non mise à jour de cette colonne a une incidence sur le calcul des temps moyens de résolution. 
			La mise à jour permet aussi un affichage conforme à la réalité (quand on sélectionne 'Afficher les faits techniques').</td>

			</table>

			
				
	

		
		<form action="cloture.php?affiche=oui" method="POST">
		
		<p align="right"><input type=submit value="Forcer la MAJ des dates de cl&ocirc;ture"></p>
		<?php if ($MsgErrMaj!="") 
			echo "<p align\"right\" style=\"color:red;\">".$MsgErrMaj."</p>";
			
			?>
	
		</form>
		
				</div>
				<hr  style="visibility:hidden;clear:both;">
				
				
	</div>
			



	<div id="banner">
		<div align="left" class="cadre3">
						
			<table style="none">
			<tr>
			<td><?php 
			$nbr=NbMails();
			//echo $nbr;
			if ($nbr==-1) echo "Probleme pour determiner le nb de mails en attente.";
			else echo "Mails en attente : ".$nbr;
			?>
			</td>

			</table>

			
				
	

		
		<form action="execmail.php" method="POST">
		
		<p align="right"><input type=submit value="Forcer l'envoi des mails"></p>
	
		</form>
		
				</div>
				<hr  style="visibility:hidden;clear:both;">
				
				
	</div>




			


		
		
		
		
	</body>
</html>
