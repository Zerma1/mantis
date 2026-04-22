<?php
session_start();

/**
 * stats.php
 *
 * En fonction des dates saisies dans l'écran précédent
 * ce script initialise un tableau avec l'id des FFT Mantis et
 * toutes les statistiques requises
 *
 * @package stats
 * @copyright  CEPN 2013
 * @license    Marine
 */



DEFINE ("SAISIE",10);
DEFINE ("CLOTURE",90);

//on définit la criticité d'un fait en fonction des 2 paramètres saisis dans Mantis : l'impact et la priorité.
//un ticket est CRITIQUE quand sa priroritré est "urgente" (valeur=50) ou que son impact est "critique" (valeur =80)
DEFINE ("CRITPRIORITE", 50);
DEFINE ("CRITIMPACT", 80);

try {


	$options =array (PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);



	$pdo = new PDO('mysql:host=localhost;dbname=mantis', 'mantis', 'tsefisg00d',$options); //intradef
	//$pdo = new PDO('mysql:host=localhost;dbname=mantis', 'root', '***',$options); //poste dev

}
catch (Exception $e) {
	die('Erreur pour la connexion à la BDD : ' . $e->getMessage());
	exit;
}

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
/**
 *Affichage en tête commun (logo, version, titre...)
 */
include ("entete.php");


/**
*Vérification des dates dates saisies
 */
$errDate=false;
if (isset($_POST['mois'])) {
	if (isset($_POST['annee'])) {
		$mois=$_POST['mois'];
		$annee=$_POST['annee'];
		$dateDeb=mktime(0,0,0,$mois,1,$annee);
		$dateFin=mktime(23,59,59,$mois+1,0,$annee);
	}
	else {
		//pb sur l'année en ayant choisi un mois
		$msgerr= "Détermination impossible de l'année !";
		$errDate=true;
		
	}
}

if (!(isset($_POST['mois']))) {
	//test date de début;
	if ((!(isset($_POST['dateDeb'])) || trim($_POST['dateDeb'])==""  )) {
		$msgerr= "Veuillez saisir la date début des stats !";
		$errDate=true;
	}
	else 
	{
	
			if (!TestDate($_POST['dateDeb'])) {
				$msgerr= "Format de la date de début incorrecte !";
				$errDate=true;
			}
			else { //date début OK
				$date=$_POST['dateDeb'];
				$date_explosee = explode("/", $date);
				$jour = $date_explosee[0];
				$mois = $date_explosee[1];
				$annee = $date_explosee[2];
				$dateDeb=mktime(0,0,0,$date_explosee[1],$date_explosee[0],$date_explosee[2]);
			}
		
	
	}
	
	if (!$errDate) {
		//test date de fin
		if (!(isset($_POST['dateFin'])) || trim($_POST['dateFin'])==""  ) {
			$dateFin=time();
		}
		else {
	
				if (!TestDate($_POST['dateFin'])) {
					$msgerr= "Format de la date de fin incorrecte !";
					$errDate=true;
				}
				else { //date de fin OK
					$date=$_POST['dateFin'];
					$date_explosee = explode("/", $date);
					$jour = $date_explosee[0];
					$mois = $date_explosee[1];
					$annee = $date_explosee[2];
					//$dateFin=mktime(0,0,0,$date_explosee[1],$date_explosee[0],$date_explosee[2]);
					$dateFin=mktime(23,59,59,$date_explosee[1],$date_explosee[0],$date_explosee[2]);
				}
			
		
		}
	}
}

if ($errDate) {
	echo "<div id =\"banner\">";
	echo $msgerr;
	echo "<form action =\"index.php\">";
	echo "<p align=\"right\"><input type=submit value=\"Retour\"></p>";
	echo "</form>";
	echo "</div id>";
	exit;
}
		



try {

	$tabCat=array();  //tableau des catégories
	$tabProj=array(); //tableau des projets;
	$tabHist=array(); //tableau de l'historique de changement de status. Pour une FFT : possède le dernier statut avec sa date
	$tabPriseEnCharge=array(); //tableau contenant la date du premier changement de statut => correspond à la date ou le statut 'saisie' a été remplacé
	//par un statut prise en charge, qualifiée, analysée... 
	$tabSaisie=array(); //tableau comprenant pour chaque FFT sa date de saisie (date submitted)

	
	$EncoreOuverte=0; //Encore ouverte au moment de la période 
	$CloturePeriode=0; //clôturée pendant la période 
	$OuvertePeriode=0; //ouverte pendant la période (encore ouverte ou fermée au moment de la période)
	$OuverteCloturePeriode=0; //ouverte et clôturee pendant la période 
	$AnnuleePeriode=0; //Annulée pendant la période. Une FFT annulée signifie qu'elle a été clôturée et que le champ personnalisé 'annulee' a été positionné à 'oui'
	


	//init tableau des catégories
	$tabCat[0]="Non definie";
	$sql="SELECT * FROM mantis_category_table ORDER BY name";
	$result = $pdo->query($sql);
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$tabCat[$row['id']]=$row['name'];
	}

	

	
	
	//init tableau des projets
	$sql="SELECT * FROM mantis_project_table ORDER BY name";
	$result = $pdo->query($sql);
	
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			foreach ($tabCat as $idCat => $cat) {
				$tabProj[$row['id']]['name']=$row['name'];
				$tabProj[$row['id']][$idCat]['EncoreOuverte']=0;
				$tabProj[$row['id']][$idCat]['EncoreOuverteCrit']=0;
				$tabProj[$row['id']][$idCat]['EncoreOuverteNonCrit']=0;
				
				$tabProj[$row['id']][$idCat]['CloturePeriode']=0;
				$tabProj[$row['id']][$idCat]['CloturePeriodeCrit']=0;
				$tabProj[$row['id']][$idCat]['CloturePeriodeNonCrit']=0;
				
				$tabProj[$row['id']][$idCat]['OuvertePeriode']=0;
				$tabProj[$row['id']][$idCat]['OuvertePeriodeCrit']=0;
				$tabProj[$row['id']][$idCat]['OuvertePeriodeNonCrit']=0;
				
				$tabProj[$row['id']][$idCat]['OuverteCloturePeriode']=0;
				$tabProj[$row['id']][$idCat]['OuverteCloturePeriodeCrit']=0;
				$tabProj[$row['id']][$idCat]['OuverteCloturePeriodeNonCrit']=0;
				
				
				$tabProj[$row['id']][$idCat]['Perf']['TempsCloture']=0;	
				$tabProj[$row['id']][$idCat]['Perf']['erreur']=false; //erreur, donc calcul impossible pour la catégorie de ce projet
				$tabProj[$row['id']][$idCat]['Perf']['cloture']=0; //nb de fft comptabilisée par projet et par catégorie pour le calcul du temps moyen de cloture
				$tabProj[$row['id']][$idCat]['Perf']['MoyTcloture']=0; //temps moyen pour la cloture
				
				$tabProj[$row['id']][$idCat]['Perf']['TempsPrisEnCharge']=0; //temps de pris en charge par projet et par catégorie
				$tabProj[$row['id']][$idCat]['Perf']['erreurTpc']=false; //erreur pour le calcul du temps de prise en charge, donc calcul impossible pour la catégorie de ce projet
				$tabProj[$row['id']][$idCat]['Perf']['prisEnCharge']=0; //nb de fft comptabilisée par projet et par catégorie pour le calcul du temps moyen de prise en charge 
				$tabProj[$row['id']][$idCat]['Perf']['MoyTpc']=0; //temps moyen de prise en charge 
				$tabProj[$row['id']]['annulee']=0;
				


				
				
				//$tabProj[$row['id']]['Perf']['TempsCloture']=0; //temps total entre la saisie et la cloture
				//$tabProj[$row['id']]['Perf']['cloture']=0; //nb de fft cloturee pour un projet
				//$tabProj[$row['id']]['Perf']['TempsPrisEnCharge']=0; //temps de prise en charge pour un projet
				//$tabProj[$row['id']]['Perf']['prisEnCharge']=0; //nb de fft comptabilisée par projet pour le calcul du temps de prise en charge 
				//$tabProj[$row['id']]['Perf']['MoyTpc']=0; //moyenne du temps de prise en charge				
			}

		}
	$result->closeCursor();

	//init tableau de l'historique pour chaque bug et du tableau de prise en charge des FFT
	$sql="SELECT bug_id as ID, date_modified as D, field_name as F, new_value as N FROM mantis_bug_history_table WHERE field_name='status'
	ORDER BY ID, date_modified ASC";
		
	$result = $pdo->query($sql);
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$tabHist[$row['ID']]['status']=$row['N'];
		$tabHist[$row['ID']]['date']=$row['D'];
		
		if ($row['N']>SAISIE) { // on considère que si le statut est sup à 10 alors la FFT a été prise en charge
			if (!(isset($tabPriseEnCharge[$row['ID']]))) $tabPriseEnCharge[$row['ID']]['date']=$row['D'];
		}
		
	}
	$result->closeCursor();
	
	
	//init tableau du tableau des bug annulés
	//9 = id du champ personnalisé 'annulé'
	$sql="SELECT bug_id as ID FROM mantis_custom_field_string_table WHERE field_id='9' AND value='oui'";
		
	$result = $pdo->query($sql);
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		//echo $row['ID']."--".date('d/m/Y', $row['D'])."--".$row['F']."--".$row['N']."</br>";
		$tabSup[]=$row['ID'];
	}
	$result->closeCursor();
	

		//$sql="SELECT * FROM mantis_bug_table WHERE project_id=4 and date_submitted <= ".$dateFin; //A VOIR < ou <=
		
	$sql="SELECT * FROM mantis_bug_table WHERE date_submitted <= ".$dateFin; //A VOIR < ou <=
	//echo $sql;
	$result = $pdo->query($sql);
	$MsgErr ="";
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	
	/* echo $row['id'];
		echo "<pre>";
	print_r($tabHist[$row['id']]);
		echo "</pre>";*/
		
	
		/* echo "<pre>";
		print_r($row);
		echo "</pre>";*/
	
	
			if($row['date_submitted']<$dateDeb) { //soumise avant datedeb
				if (isset($tabHist[$row['id']]['status'])) { //détection d'un changement de statut
					if ($tabHist[$row['id']]['status']==CLOTURE) {
						if ($tabHist[$row['id']]['date']<$dateDeb) { //clôturée avant la datedeb
							//on ne fait rien
						}
						else { //clôturée après la datedeb
							if ($tabHist[$row['id']]['date']<=$dateFin) { // clôturée pendant la période
								//$CloturePeriode ++;
								//$tabProj[$row['project_id']][$row['category_id']]['CloturePeriode']++;
								
								$CloturePeriode=$CloturePeriode +1 -BugAnnule($row['id'],$tabSup);
								$tabProj[$row['project_id']][$row['category_id']]['CloturePeriode']=$tabProj[$row['project_id']][$row['category_id']]['CloturePeriode'] +1 -BugAnnule($row['id'],$tabSup);

								//bug critique ?
								$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeCrit']=$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeCrit'] + VerifCrit($row['priority'],$row['severity'],2) - BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],2));
								$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeNonCrit'] + VerifCrit($row['priority'],$row['severity'],3) - BugAnnuleCrit($row['id'], $tabSup, VerifCrit($row['priority'],$row['severity'],3) );

								
								$AnnuleePeriode=$AnnuleePeriode+BugAnnule($row['id'],$tabSup);
								$tabProj[$row['project_id']]['annulee']=$tabProj[$row['project_id']]['annulee'] + BugAnnule($row['id'],$tabSup);
								
								//calcul du temps de résolution (date_cloture moins date_saisie)
								if (BugAnnule($row['id'],$tabSup) <> 1) { //si le bug n'est pas annulé on comptabilise le temps passé (en heures) entre la clôture et la saisie
									if ($tabHist[$row['id']]['date']-$row['date_submitted'] < 0) {
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['erreur']=true;
										$MsgErr .=  "FFT ".$row['id']." : date de soumission > date de cloture ! <br />";
									}
									else {
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture'] = $tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture'] + ($tabHist[$row['id']]['date']-$row['date_submitted'])/3600;
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['cloture']++;
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['MoyTcloture'] = $tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture'] / $tabProj[$row['project_id']][$row['category_id']]['Perf']['cloture'];
										//$tabProj[$row['project_id']]['TempsCloture'] = $tabProj[$row['project_id']]['Perf']['TempsCloture'] + ($tabHist[$row['id']]['date']-$row['date_submitted'])/3600;
										//$tabProj[$row['project_id']]['Perf']['cloture']++;										
									}
								}
								
							}
							else { //clôturée après la période
								$EncoreOuverte++;
								$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverte']++;
								
								//bug critique ?
								$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit'] + VerifCrit($row['priority'],$row['severity'],2);
								$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit'] + VerifCrit($row['priority'],$row['severity'],3);
									
							}
						}
					}
					else //toujours ouverte
					{
						$EncoreOuverte ++;
						$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverte']++;
						//bug critique ?
						$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit'] + VerifCrit($row['priority'],$row['severity'] , 2);
						$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit'] + VerifCrit($row['priority'],$row['severity'] , 3);
							
					}
				}
				else  //pas de changement de statut = toujours ouverte
				{
					$EncoreOuverte ++;
					$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverte']++;
					
					//bug critique ?
					$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit'] + VerifCrit($row['priority'],$row['severity'] , 2);
					$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit'] + VerifCrit($row['priority'],$row['severity'] , 3);
						
				}
			
			}
			else { //soumise après datedeb
				if ($row['date_submitted']<=$dateFin) { //soumise dans la période
					if (isset($tabHist[$row['id']]['status'])) { //détection d'un changement de statut
						if ($tabHist[$row['id']]['status']==CLOTURE) { //elle est clôturée
							if ($tabHist[$row['id']]['date']<=$dateFin) { // ouverte et clôturée pendant la période
								/*$CloturePeriode ++;
								$OuvertePeriode++;
								$OuverteCloturePeriode++;
								$tabProj[$row['project_id']][$row['category_id']]['CloturePeriode']++;
								$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriode']++;
								$tabProj[$row['project_id']][$row['category_id']]['OuverteCloturePeriode']++;*/
								
								$CloturePeriode = $CloturePeriode +1 -BugAnnule($row['id'],$tabSup);
								$OuvertePeriode = $OuvertePeriode +1 -BugAnnule($row['id'],$tabSup);
								$OuverteCloturePeriode = $OuverteCloturePeriode +1 -BugAnnule($row['id'],$tabSup);
								$tabProj[$row['project_id']][$row['category_id']]['CloturePeriode']=$tabProj[$row['project_id']][$row['category_id']]['CloturePeriode'] +1 -BugAnnule($row['id'],$tabSup);
								$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriode']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriode'] +1 -BugAnnule($row['id'],$tabSup);
								$tabProj[$row['project_id']][$row['category_id']]['OuverteCloturePeriode']=$tabProj[$row['project_id']][$row['category_id']]['OuverteCloturePeriode']+1 -BugAnnule($row['id'],$tabSup);
								
								//bug critique ?
								$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeCrit']=$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeCrit'] + VerifCrit($row['priority'],$row['severity'],2) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],2));
								$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['CloturePeriodeNonCrit'] + VerifCrit($row['priority'],$row['severity'],3) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],3));
								$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit'] + VerifCrit($row['priority'],$row['severity'],2) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],2));
								$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit'] + VerifCrit($row['priority'],$row['severity'],3) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],3));
								$tabProj[$row['project_id']][$row['category_id']]['OuverteCloturePeriodeCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuverteCloturePeriodeCrit'] + VerifCrit($row['priority'],$row['severity'],2) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],2));
								$tabProj[$row['project_id']][$row['category_id']]['OuverteCloturePeriodeNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuverteCloturePeriodeNonCrit'] + VerifCrit($row['priority'],$row['severity'],3) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],3));
								
								
								$AnnuleePeriode=$AnnuleePeriode+BugAnnule($row['id'],$tabSup);
								$tabProj[$row['project_id']]['annulee']=$tabProj[$row['project_id']]['annulee'] + BugAnnule($row['id'],$tabSup);
								
								//calcul du temps de résolution (date_cloture moins date_saisie)
								if (BugAnnule($row['id'],$tabSup) <> 1) { //si le bug n'est pas annulé on comptabilise le temps passé entre la clôture et la saisie
									if ($tabHist[$row['id']]['date']-$row['date_submitted'] < 0) {
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['erreur']=true;
										$MsgErr .=  "FFT ".$row['id']." : date de soumission > date de cloture ! <br />";
									}
									else {
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture'] = $tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture'] + ($tabHist[$row['id']]['date']-$row['date_submitted'])/3600;
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['cloture']++;
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['MoyTcloture'] = $tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture'] / $tabProj[$row['project_id']][$row['category_id']]['Perf']['cloture'];
										/*
										$tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture'] = $tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsCloture']+($tabHist[$row['id']]['date']-$row['date_submitted'])/3600;
										$tabProj[$row['project_id']]['Perf']['TempsCloture'] = $tabProj[$row['project_id']]['Perf']['TempsCloture'] + ($tabHist[$row['id']]['date']-$row['date_submitted'])/3600;
										$tabProj[$row['project_id']]['Perf']['cloture']++;*/
									}
								}
								
							}
							else { //ouverte pendant la periode et clôturée après la période
								$EncoreOuverte++;
								$OuvertePeriode++;
								$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverte']++;
								
								$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriode']++;
								
								//bug critique ?
								$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit'] + VerifCrit($row['priority'],$row['severity'] , 2);
								$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit'] + VerifCrit($row['priority'],$row['severity'] , 3);
								$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit'] + VerifCrit($row['priority'],$row['severity'],2) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],2));
								$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit'] + VerifCrit($row['priority'],$row['severity'],3) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],3));
		
		
								
							}
						}
						else { //elle n'est pas clôturée et elle est soumise dans la période
							$EncoreOuverte ++;
							$OuvertePeriode++;
							$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverte']++;
							$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriode']++;
							
							//bug critique ?
							$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit'] + VerifCrit($row['priority'],$row['severity'] , 2);
							$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit'] + VerifCrit($row['priority'],$row['severity'] , 3);
							$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit'] + VerifCrit($row['priority'],$row['severity'],2) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],2));
							$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit'] + VerifCrit($row['priority'],$row['severity'],3) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],3));
								
						}
						
						// FFT soumise dans la période, et un changement de statut a eu lieu. On mémorise 
						// la date du premier changement de statut : cela correspond à la date de prise en charge
						// par le CEPN de cette FFT 
						//calcul du temps de prise en charge
						if (BugAnnule($row['id'],$tabSup) <> 1) { //si le bug n'est pas annulé on comptabilise le temps passé pour sa prise en charge
							if ($tabPriseEnCharge[$row['id']]['date']-$row['date_submitted'] < 0) {
								$tabProj[$row['project_id']][$row['category_id']]['Perf']['erreurTpc']=true;
								$MsgErr .=  "FFT ".$row['id']." : date de soumission > date de prise en charge ! <br />";
								$tabProj[$row['project_id']][$row['category_id']]['Perf']['MoyTpc']=-1;
							}
							else {
								if ($tabProj[$row['project_id']][$row['category_id']]['Perf']['MoyTpc'] <>-1) {
								//$tabProj[$row['project_id']]['Perf']['TempsPrisEnCharge'] = $tabProj[$row['project_id']]['Perf']['TempsPrisEnCharge'] + ($tabPriseEnCharge[$row['id']]['date']-$row['date_submitted'])/3600;
								//$tabProj[$row['project_id']]['Perf']['prisEnCharge']++;
								//$tabProj[$row['project_id']]['Perf']['MoyTpc']=$tabProj[$row['project_id']]['Perf']['TempsPrisEnCharge']/$tabProj[$row['project_id']]['Perf']['prisEnCharge'];
								$tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsPrisEnCharge'] = $tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsPrisEnCharge']+($tabPriseEnCharge[$row['id']]['date']-$row['date_submitted'])/3600;
								$tabProj[$row['project_id']][$row['category_id']]['Perf']['prisEnCharge']++;
								$tabProj[$row['project_id']][$row['category_id']]['Perf']['MoyTpc']=$tabProj[$row['project_id']][$row['category_id']]['Perf']['TempsPrisEnCharge']/$tabProj[$row['project_id']][$row['category_id']]['Perf']['prisEnCharge'];
								}
							}
						}
						
				
						
					}
					else { //pas de changement de statut = toujours ouverte
						$EncoreOuverte ++;
						$OuvertePeriode++;
						$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverte']++;
						$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriode']++;
						
						//bug critique ?
						$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteCrit'] + VerifCrit($row['priority'],$row['severity'] , 2);
						$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['EncoreOuverteNonCrit'] + VerifCrit($row['priority'],$row['severity'] , 3);
						$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeCrit'] + VerifCrit($row['priority'],$row['severity'],2) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],2));
						$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit']=$tabProj[$row['project_id']][$row['category_id']]['OuvertePeriodeNonCrit'] + VerifCrit($row['priority'],$row['severity'],3) -BugAnnuleCrit($row['id'],$tabSup,VerifCrit($row['priority'],$row['severity'],3));
						
					}
				}
			}
	}
	
	
	$result->closeCursor();	
	
	
	$_SESSION["tabProj"]= $tabProj;
	$_SESSION["tabCat"]= $tabCat;

	
	/*echo "<pre>";
	print_r($tabProj);
	echo "</pre>";*/
	/*print_r ($tabHist);
	*/
	
	?>
	
		
		<div id="banner">
		
		<div >
	<table>
	<tr>
	<td colspan="3" style="font-weight:bold;">	<?php echo "<div>du ".date('d/m/Y',$dateDeb)." au ".date('d/m/Y',$dateFin)." : </div>";?></td>
	<tr>
	<td colspan="3" style="color:red;"><?php echo $MsgErr ?></td>
	<tr>
	<tr>
		<td style="width:50px;"><?php echo $EncoreOuverte; ?></td><td style="width:100px;">OUV</td><td>FFT encore ouvertes</td>
	<tr>
		<td style="width:50px;"><?php echo $CloturePeriode; ?></td><td style="width:100px;">CLO P</td><td>FFT clôturées pendant la période</td>
	<tr>	
		<td style="width:50px;"><?php echo $OuvertePeriode; ?></td><td style="width:100px;">OUV P</td><td>FFT ouvertes pendant la période (clôturée ou pas)</td>
	<tr>
		<td style="width:50px;"><?php echo $OuverteCloturePeriode; ?></td><td style="width:100px;">OUV-CLO P</td><td>FFT ouvertes et clôturées pendant la période</td>
	<tr>	
		<td style="width:50px;"><?php echo $AnnuleePeriode; ?></td><td style="width:100px;">ANNULEE</td><td>FFT annulées pendant la période</td>
	
	</table>
	
	</div>
	
	
	
	<?php 

	

	
	
	

	/*echo "<div>".$EncoreOuverte." FFT encore ouvertes</div>";
	echo "<div>".$CloturePeriode." FFT clôturées pendant la période</div>";
	echo "<div>".$OuvertePeriode." FFT ouvertes pendant la période (clôturée ou pas)</div>";
	echo "<div>".$OuverteCloturePeriode." FFT ouvertes et clôturées pendant la période</div>";
	echo "<div>".$AnnuleePeriode." FFT annulées pendant la période</div>";*/
	
?>

	<div style="float:right;">
	<form name="retour" action="index.php" method="POST" style="float:left;">

		
		<p  align="right"><input type=submit value="Modifier période"></p>
		<?php  
		//pour retenir les dates choisies lors du retour
		if ((isset($_POST['mois']))) {
		?>
		<input type="hidden"  value="<?php echo $_POST['mois']; ?>" name="mois_retour">
		<input type="hidden"  value="<?php echo $_POST['annee']; ?>" name="annee_retour">
		
		<?php 
		} else 
		{
			if (isset($_POST['dateDeb'])) {?>
				<input type="hidden"  value="<?php echo $_POST['dateDeb']; ?>" name="dateDeb_retour">
		<?php  }
			if (isset($_POST['dateFin'])) {?>
						<input type="hidden"  value="<?php echo $_POST['dateFin']; ?>" name="dateFin_retour">
				<?php  }
		}
	    ?>
	</form>
	
	<form name="export" action="excel.php" method="POST" style="float:left;">
		<input type="hidden"  value="<?php echo date('d/m/Y',$dateDeb); ?>" name="dateDeb">
		<input type="hidden"  value="<?php echo date('d/m/Y',$dateFin); ?>" name="dateFin">
		<input type="hidden"  value="<?php echo $mois; ?>" name="mois">
		<p align="right"><input type=submit value="Export Excel"></p>

	
	</form>
	
	</br>

	</div>
		</br>	
	
	</div>

	
<?php 
	//$result->closeCursor(); 


} catch (PDOException $e){
	echo "SQL : Erreur".$e->getMessage();
	return "erreur";

}


function TestDate ($value) {

	list($dd,$mm,$yy)=explode("/",$value);
	if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd) )	
	{
		return checkdate($mm,$dd,$yy);
	}
	else return false;

	//return preg_match("^\d{1,2}/\d{1,2}/\d(4)$",$value);
}


//arguments :
// - id : id de la FFT
// - tab : tableau des FFT annulées
//valeurs retournées :
// - "1" si la FFT id est présente dans le tableau tab
// - "0" si la FFT id n'est pas présente dans le tableau tab 
function BugAnnule ($id, $tab) {
//vérifie si le bug $id est présent dans le tableau $tab des bug annulés
if (in_array($id,$tab)) return 1; 
else return 0;
}

//arguments :
// - id : id de la FFT
// - tab : tableau des FFT annulées
// - crit = 0 ou 1
//valeurs retournées :
// - "1" si la FFT id est présente dans le tableau tab et que crit = 1
// - "0" si la FFT id n'est pas présente dans le tableau tab ou lorsque que crit = 0 (même si la FFT est présente dans tab)
function BugAnnuleCrit ($id, $tab, $crit) {
	//vérifie si le bug $id est présent dans le tableau $tab des bug annulés
	if ((in_array($id,$tab)) and $crit==1) return 1;
	else return 0;
}


//on définit la criticité d'un fait (ou fiche de fait technique FFT) en fonction des 2 paramètres saisis dans Mantis : l'impact et la priorité.
//un fait est CRITIQUE quand sa priroritré est "urgente" (valeur=50) ou que son impact est "critique" (valeur =80)
//arguments de la fonction:
// - priotité de la FFT
// - impact de la FFT
// - mod : pour définir le modulo (valeurs possibles : 2 ou 3)  : voir explication ci-dessous
//valeurs retournées par la fonction :
// - "1" si la FFT est critique et que $mod=2
// - "0" si la FFT est non critique et que $mod=2
// - "0" si la FFT est critique et que $mod=3
// - "1" si la FFT est non critique et que $mod=3
// pour résumer : mod=2 pour déterminer si un fait est critique et mod=3 pour déterminer si le fait est non critique
function VerifCrit ($priority, $severity, $mod) {
	//
	if ((int) $priority>= (int) CRITPRIORITE OR (int) $severity>=(int) CRITIMPACT) return (3 % $mod);
	else return (4 % $mod);
}

?>



	</body>

</html>
