<?php //session_start();

$affiche=$_GET["affiche"];
	
DEFINE ("CLOTURE",90);
DEFINE ("ID_CHAMPDATECLOTURE",11);

$tabHist=array(); //tableau de l'historique de changement de status
$tabClo=array(); //tableau des dates de cloture par FFT

$message="";

try {


	$options =array (PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
	$pdo = new PDO('mysql:host=localhost;dbname=mantis', 'mantis', 'DiPvFk9wD.E@*5zgU',$options); //intradef
	//$pdo = new PDO('mysql:host=localhost;dbname=mantis', 'root', '*****',$options); //poste dev

}
catch (Exception $e) {
	die('Erreur pour la connexion à la BDD : ' . $e->getMessage());
	exit;
}
	
	//init tableau du tableau de l'historique pour chaque bug
	$sql="SELECT bug_id as ID, date_modified as D, field_name as F, new_value as N FROM mantis_bug_history_table WHERE field_name='status'
	ORDER BY ID, date_modified ASC";

	$result = $pdo->query($sql);
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		//echo $row['ID']."--".date('d/m/Y', $row['D'])."--".$row['F']."--".$row['N']."</br>";
		$tabHist[$row['ID']]['status']=$row['N'];
		$tabHist[$row['ID']]['date']=$row['D'];
		//echo $row['id'];
	}
	$result->closeCursor();
	
	/* exemple $tabHist
	[1] => Array
	(
			[status] => 90
			[date] => 1355753611
	)
	
	[2] => Array
	(
			[status] => 40
			[date] => 1373447307
			
	echo "<pre>";
	print_r($tabHist);
	echo "</pre>";	)*/
	

	
	
	//init tableau du tableau des dates de clôture
	$sql="SELECT field_id, bug_id, value FROM `mantis_custom_field_string_table` WHERE field_id='".ID_CHAMPDATECLOTURE."'";
	

	$result = $pdo->query($sql);
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$tabClo[$row['bug_id']]=$row['value'];
	}
	$result->closeCursor();
	
	/*echo "<pre>";
	print_r($tabClo);
	echo "</pre>";*/
	
	/*	 exemple $tabClo
    [677] => 2013-11-28
    [678] => 2013-11-28
    [679] => 2013-12-02
    [682] => 2013-11-29
    [683] => 2013-12-02
    [684] => 2013-12-02
    [685] => 2013-12-02 */

		
	try {
		
	
	//préparation des requêtes
	$sqlDel="DELETE FROM mantis_custom_field_string_table WHERE field_id='".ID_CHAMPDATECLOTURE."' AND bug_id=:id";
	$sqlDelPrepa=$pdo->prepare($sqlDel);
	$sqlInsert="INSERT INTO mantis_custom_field_string_table (field_id, bug_id, value) VALUES (:field_id, :bug_id, :value)";
	$sqlInsertPrepa=$pdo->prepare($sqlInsert);
	$sqlUpdate="UPDATE mantis_custom_field_string_table SET value = :value WHERE field_id= :field_id and bug_id = :bug_id";
	$sqlUpdatePrepa=$pdo->prepare($sqlUpdate);

	foreach ($tabHist as $id => $tab) {
		if ($tab['status']==CLOTURE) {
			//on vérifie la présence de la date de clôture
			if (isset($tabClo[$id])) { //une date de clôture est déjà présente	
				//on vérifie si c'est la dernière => cas d'une FFT dont la date de cloture a évoluée
				if (!($tabClo[$id]==date("Y-m-d",$tab['date']))) {
					//echo "La date de cloture de la FFT $id a evoluee </br>";
					//MAJ
					$params=array(
					":field_id" => ID_CHAMPDATECLOTURE,
					":bug_id" => $id,
					":value" => date("Y-m-d",$tab['date'])
				);
				$result=$sqlUpdatePrepa->execute($params);
				$message.="La date de cloture de la FFT $id a ete modifiee.</br>";
				}
			}	
			else { //on ajoute la date de cloture
				//AJOUT
				
				$params=array(
					":field_id" => ID_CHAMPDATECLOTURE,
					":bug_id" => $id,
					":value" => date("Y-m-d",$tab['date'])
				);
				//print_r($params);
				$result=$sqlInsertPrepa->execute($params);
				$message.="La FFT $id a ete cloturee.</br>";
				
			}	
		}
		else {
		//la FFT n'est pas cloturée, on vérifie si une date de cloture n'est pas mentionnée dans le champ perso => cas d'une FFT réouverte
			if (isset($tabClo[$id])) {
			//DELETE
			$sqlDelPrepa->bindParam(":id", $id,PDO::PARAM_INT);
			$sqlDelPrepa->execute();
			$message.="La FFT $id a ete reouverte.</br>";
			}
		}
		
		

	 
	}
	}catch (PDOException $e) {
			$message.="Erreur PDO</br>"; 
		
	}
			

	/*if (!(file_exists("datemaj.txt"))) {
		$fp=fopen("datemaj.txt", "w+");
		@fclose($fp);
	}*/
	


	$fp=fopen("datemaj.txt", "a");
		
		
	if (ftruncate($fp, 0)) {
		$contenu=time();
		fwrite($fp,$contenu);
	}
	
	@fclose($fp);
	
	
	if ($affiche=="oui") {
		?>

		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=Latin1">
		<title>Statistiques Mantis</title>
		<link rel="stylesheet" href="stats.css" type="text/css" />
		</head>
		<body>
		
		<?php
		include ("entete.php");
		
		?>
		<div id="banner">
		<?php 
		if ($message =="") echo "</br>Les dates de cloture sont &agrave; jour.";
				else echo $message."</br>";
	

	?>
	<form action="index.php" method="POST">
	
	<p align="right"><input type=submit value="Retour"></p>
	
	</form>
	</div>
	</body>
	</html>
	
	<?php 
}
	?>
	
