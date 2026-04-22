<?php
session_start();


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'Classes/PHPExcel.php';


DEFINE("ROWDUPREMIERPROJET",14);
DEFINE("NBTOTCOL",67);
DEFINE("NBSOUSCOL",16);

DEFINE("LARGCOL",5);
DEFINE("LARGCOLCRIT",4);

DEFINE("COULGRISE","C0C0C0");

$tabProj=$_SESSION["tabProj"];
$tabCat=$_SESSION["tabCat"];

/*echo "<pre>";
print_r($tabProj);
echo "</pre>";
exit;*/

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("MDSIO")
							 ->setLastModifiedBy("MDSIO")
							 ->setTitle("Indicateurs Mantis")
							 ->setSubject("Extraction des indicateurs sur les FFT de Mantis")
							 ->setDescription("")
							 ->setKeywords("mantis fft indicateurs")
							 ->setCategory("");

$col=1;
$row=1;



	$objPHPExcel->setActiveSheetIndex(0);

	
	//ecriture de la plage horaire
	$libelleDate="STATS FFT MANTIS : periode du ".$_POST['dateDeb']." au ".$_POST['dateFin'];
		
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $libelleDate);
	$row++;	
	$row++;
	
	//Ecriture de la l�gende de la feuille de stats
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "Ouv = encore ouverte au moment de la periode (comprend aussi les FFT ouvertes dans la periode et annulees en dehors de la periode)");
	
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "Clo P = cloturee pendant la periode");
	
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "Ouv P = ouverte pendant la periode (encore ouverte ou cloturee au moment de la periode)");
	
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "Ouv-Clo P = ouverte et cloturee pendant la periode");
	
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "t=total / nc=non critique / c=critique (critique si priorite=urgente OU impact = critique)");

	$row++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "T Pch = Temps moyen de prise en charge d'une FFT (pour les FFT ouvertes durant la periode cloturees ou non cloturees)");
	Italic($objPHPExcel, $col, $row);
	
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "T Clo P = Temps moyen entre la soumission d'une FFT et sa cloture");
	Italic($objPHPExcel, $col, $row);
	
	$row++;	$row++;
	
	//ecriture des cat�gories et du titre des indicateurs 
	$col=2;
	foreach ($tabCat as $cat) {
		
		$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+NBSOUSCOL-1,$row);
		ColorFondCell ($objPHPExcel,$col, $row,COULGRISE);
		AligneAuCentre($objPHPExcel,$col, $row,COULGRISE);
		Gras($objPHPExcel,$col, $row );
		
	
		for ($i=0; $i<=NBSOUSCOL-1; $i++) {
			DessineBordureTop($objPHPExcel,$col+$i,$row);
			DessineBordureBottom($objPHPExcel,$col+$i,$row);
			}

			DessineBordureLeft($objPHPExcel,$col,$row);
			DessineBordureRight($objPHPExcel,$col+NBSOUSCOL,$row);
			DessineBordureLeft($objPHPExcel,$col,$row+2);
			DessineBordureRight($objPHPExcel,$col+NBSOUSCOL-1,$row+2);
			
		
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cat);
				
		TitreIndic($objPHPExcel,$col, $row+1);
	
		$col=$col+NBSOUSCOL;
	}
	
	//titre FFT Annul�e
	$PosColAnnulee=NBSOUSCOL*count($tabCat)+2;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($PosColAnnulee, $row, "ANNULEE");
	ColorFondCell ($objPHPExcel,$col, $row,COULGRISE);
	AligneAuCentre($objPHPExcel,$col, $row,COULGRISE);
	Gras($objPHPExcel,$col, $row );
	
	DessineBordureRight($objPHPExcel,$PosColAnnulee,$row);
	DessineBorduretop($objPHPExcel,$PosColAnnulee,$row);
	DessineBordureLeft($objPHPExcel,$PosColAnnulee,$row);
	DessineBordureLeft($objPHPExcel,$PosColAnnulee,$row+1);
	DessineBordureRight($objPHPExcel,$PosColAnnulee,$row+1);
	DessineBordureLeft($objPHPExcel,$PosColAnnulee,$row+2);
	DessineBordureRight($objPHPExcel,$PosColAnnulee,$row+2);
	
	$row=ROWDUPREMIERPROJET;
	//$col=1;
		
	$col=0;

	
foreach ($tabProj as $proj){

	//ecriture du nom des projets
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $proj["name"]);
	for ($i=0; $i<NBTOTCOL; $i++) {
		DessineBordureTop($objPHPExcel,$col+$i,$row);
		DessineBordureBottom($objPHPExcel,$col+$i,$row);
		
	}
	//ecriture du nbr de FFT annul�es par projet
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($PosColAnnulee, $row, SupZero($proj["annulee"]));
	
	//ecriture du temps moyen de r�solution
	/*if (isset($proj['Perf']["cloture"]) && $proj['Perf']["cloture"]<>0) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(NBTOTCOL, $row, AfficheTemps($proj['Perf']["TempsCloture"]/$proj['Perf']["cloture"]));
	}*/

	AligneAuCentre ($objPHPExcel, NBTOTCOL-1,$row);
	DessineBordureRight($objPHPExcel,NBTOTCOL-1,$row);
	
// 	echo "<pre>";
// 	print_r($proj);
// 	echo "</pre>";
// 	exit;
	
	
	foreach ($proj as $key => $Cat) { 
		if ((string)$key!="name" AND (string)$key!="annulee" AND (string)$key!="Perf") {
			IndicParCategorie  ($objPHPExcel, $proj[$key], $col +2 ,$row); 
			$col=$col+NBSOUSCOL;
		}
	}

	$row++;
	$col=0;

}

//calcule les sommes
For ($k=2;$k<NBTOTCOL;$k++) { //d�part de la colonne C
	$a=0;
	
	//pour le calcul du temps de prise en charge moyen par cat�gorie
	$moyTpc=0;
	$Tpc=0;
	$nbPc=0;
	
	//pour le calcul du temps de cloture moyen par cat�gorie
	$nbTclo=0;
	$Tclo=0;
	$moyTclo=0;
	
	
	For ($i=ROWDUPREMIERPROJET;$i<$row;$i++) {
		$tmp=0;
		if ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($k,$i)->getValue()=="") $tmp=0;
		else {
			$tmp=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($k,$i)->getValue();
			
			//on affiche erreur si les temps sont < 0
			//si valeur n�gative dans la colonne des temps de prise en charge alors la somme de la colonne "T Pch" = erreur
			if ($k % NBSOUSCOL == (NBSOUSCOL-2) && $tmp<0  || $k % NBSOUSCOL == (NBSOUSCOL-1) && $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($k-1,$i)->getValue()<0) {
				$a="erreur";
			}
			//si valeur n�gative dans la colonne des temps de cl�ture alors la somme de la colonne "T clo P" = erreur
			if ($k % NBSOUSCOL == 0 && $tmp<0 || $k % NBSOUSCOL == 1 && $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($k-1,$i)->getValue()<0) {
				$a="erreur";
			}	
			
			if ((string)$a!="erreur") {
				if 	($k % NBSOUSCOL == (NBSOUSCOL-2)) {//cas de la colonne des temps de prise en charge. On calcule la moyenne
					$nbPc++;
					$Tpc=$Tpc+$tmp;
					$moyTpc=$Tpc/$nbPc;
				}
				if ($k % NBSOUSCOL == 0) {//cas de la colonne des temps de cloture. On calcule la moyenne
					$nbTclo++;
					$Tclo=$Tclo+$tmp;
					$moyTclo=$Tclo/$nbTclo;					
				}
				else $a=$a+(int)$tmp;
			}
	
		}

		
		if ($k % NBSOUSCOL ==1 || $k % NBSOUSCOL ==(NBSOUSCOL-1)) Italic($objPHPExcel, $k, $i);

	}
	
	//sommes -> derni�re ligne
	switch ($k % NBSOUSCOL) {
		case (NBSOUSCOL-2) : //si k correspond � la colonne masqu�e pour le temps moyen de prise en charge sans formatage
			if ((string)$a <>"erreur") {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k,$row,$moyTpc);
			}
			break;
		case (NBSOUSCOL-1) : //si k correspond � la colonne visible du temps moyen de prise en charge  avec formatage
			if ((string)$a <>"erreur") {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k,$row,AfficheTemps($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($k-1,$row)->getValue()));
			}
			break;
		case 0 : //si k correspond � la colonne masqu�e pour le temps moyen de cloture sans formatage
			if ((string)$a <>"erreur") {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k,$row,$moyTclo);
			}
			break;
		case 1 : //si k correspond � la colonne visible du temps moyen de cloture avec formatage
			if ((string)$a <>"erreur") {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k,$row,AfficheTemps($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($k-1,$row)->getValue()));
			}
			break;
		default :
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k,$row,(string)$a);
			break;
	}
	
	if ($k % NBSOUSCOL ==1 || $k % NBSOUSCOL == (NBSOUSCOL-1)) Italic($objPHPExcel, $k, $row);
	AligneAuCentre ($objPHPExcel, $k, $row);
	
	//cache les colonnes "Temps"
	if ($k % NBSOUSCOL ==0 || $k % NBSOUSCOL == (NBSOUSCOL-2)) $objPHPExcel->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($k))->setWidth(0);
}



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Export');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);



// Redirect output to a clients web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="mantis.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;


//pour un projet donn� et une activit� donn�e, la fonction inscrit 
//dans Excel le titre des indicateurs (OUV, CLO P...)
function TitreIndic ($objPHPExcel, $col, $row ) {
	DessineBordureLeft($objPHPExcel,$col,$row);
	//ecriture des titres des indicateurs
	$col=DessineTitreColIndic($objPHPExcel,$col,$row,"Ouv");
	$col=DessineTitreColIndic($objPHPExcel,$col,$row,"Clo P");
	$col=DessineTitreColIndic($objPHPExcel,$col,$row,"Ouv P");
	$col=DessineTitreColIndic($objPHPExcel,$col,$row,"Ouv-Clo P");
	

	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "MoyTempsPch");
	AligneAuCentre ($objPHPExcel, $col,$row);
	
	$col++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "T Pch");
	AligneAuCentre ($objPHPExcel, $col,$row);
	Italic($objPHPExcel, $col,$row);
	DessineBordureRightPointillee($objPHPExcel,$col ,$row);
	DessineBordureRightPointillee($objPHPExcel,$col ,$row+1);
	DessineBordureLeft($objPHPExcel,$col,$row);
	DessineBordureLeft($objPHPExcel,$col,$row+1);
	
	$col++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "MoyTempsClo");
	AligneAuCentre ($objPHPExcel, $col,$row);
	
	$col++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, "T Clo P");
	AligneAuCentre ($objPHPExcel, $col,$row);
	Italic($objPHPExcel, $col,$row);
	
	DessineBordureRight($objPHPExcel,$col,$row);
	DessineBordureRightPointillee($objPHPExcel,$col ,$row+1);
	
}


//Pour un projet et une cat�gorie donn�e (ticket, Evolution...) inscrit dans le fic excel la veleur des indicateurs issus du tableau  proj
//pass� en argument
//L'inscription commence � la ligne row (du projet) et � la colonne col. Les indicateurs sont �crits dans les colonnes qui suivent
//Exemple de tableau proj
// 		[EncoreOuverte] => 0
// 		[EncoreOuverteCrit] => 0
// 		[EncoreOuverteNonCrit] => 0
// 		[CloturePeriode] => 9
// 		[CloturePeriodeCrit] => 1
// 		[CloturePeriodeNonCrit] => 8
// 		[OuvertePeriode] => 9
// 		[OuvertePeriodeCrit] => 1
// 		[OuvertePeriodeNonCrit] => 8
// 		[OuverteCloturePeriode] => 9
// 		[OuverteCloturePeriodeCrit] => 1
// 		[OuverteCloturePeriodeNonCrit] => 8
// 		[Perf] => Array
// 		(
// 				[TempsCloture] => 1.2083333333333
// 				[erreur] =>
// 				[cloture] => 9
// 				[MoyTcloture] => 0.13425925925926
// 				[TempsPrisEnCharge] => 1.0694444444444
// 				[erreurTpc] =>
// 				[prisEnCharge] => 9
// 				[MoyTpc] => 0.11882716049383
// 		)
function IndicParCategorie ($objPHPExcel, $proj = array(), $col ,$row) {

		DessineBordureLeft($objPHPExcel,$col,$row);
		if (($col + NBSOUSCOL - 2) < NBTOTCOL )  		DessineBordureRight($objPHPExcel,($col + NBSOUSCOL - 1),$row);
		
		//Traitement de l'indicateur OUV
		$col=DessineColIndic ($objPHPExcel, $col, $row, $proj["EncoreOuverte"], $proj["EncoreOuverteNonCrit"], $proj["EncoreOuverteCrit"]);
		
		//Traitement de l'indicateur Clo P
		$col=DessineColIndic ($objPHPExcel, $col, $row, $proj["CloturePeriode"], $proj["CloturePeriodeNonCrit"], $proj["CloturePeriodeCrit"]);
		
		//Traitement de l'indicateur Ouv P
		$col=DessineColIndic ($objPHPExcel, $col, $row, $proj["OuvertePeriode"], $proj["OuvertePeriodeNonCrit"], $proj["OuvertePeriodeCrit"]);
		
		//Traitement de l'indicateur Ouv-Clo P
		$col=DessineColIndic ($objPHPExcel, $col, $row, $proj["OuverteCloturePeriode"], $proj["OuverteCloturePeriodeNonCrit"], $proj["OuverteCloturePeriodeCrit"]);
	

		//Traitement de l'indicateur T Pch (temps moyen de prise en charge)
		if ($proj["Perf"]["erreurTpc"]==false) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, SupZero($proj["Perf"]["MoyTpc"]));
			if ($proj["Perf"]["MoyTpc"]!=0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, AfficheTemps($proj["Perf"]["MoyTpc"]));

		}
		else {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, -1);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, "erreur");
		}
		AligneauCentre ($objPHPExcel, $col,$row);
		AligneauCentre ($objPHPExcel, $col+1,$row);
		$col++;
		DessineBordureRightPointillee($objPHPExcel,$col ,$row);
		DessineBordureLeft($objPHPExcel,$col,$row);
		$col++;

		//Traitement de l'indicateur T clo P (temps moyen de cloture)
		if ($proj["Perf"]["erreur"]==false) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, SupZero($proj["Perf"]["MoyTcloture"]));
			if ($proj["Perf"]["MoyTcloture"]!=0) $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, AfficheTemps($proj["Perf"]["MoyTcloture"]));
		}
		else {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, -1);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, "erreur");
		}
		AligneauCentre ($objPHPExcel, $col,$row);
		AligneauCentre ($objPHPExcel, $col+1,$row);
		$col++;
}


//retourne une cha�ne vide si l'argument val = 0
function SupZero ($val) {
	if ($val==0) return "";
	else return $val;
}


//Retourne une ch�ine vide si l'argument val = "(0|0)"
function SupDblZero ($val) {

	if ($val=="(0|0)") return "";
	else return $val;
}



//Retourne un nombre d'heures au format XXjYYh
//XX des jours
//YY des heures
function AfficheTemps ($nbHeures) {
	if ($nbHeures<0) return ("erreur");
	$nbJours=$nbHeures/24;
	if ($nbJours > 1) return (string) (round($nbJours,0))."j";
	else {
			return "0j ".(ceil($nbHeures))."h";//arrondi sup
	}
}



//Dessine une bordure haute (trait plein) pour la cellule col,row (colonne, ligne)
function DessineBordureTop ($objPHPExcel,$col, $row ) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(
					'borders' => array(
							'top'     => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
							)
					),
	
			)
	);
}


//Dessine une bordure basse (trait plein) pour la cellule col,row (colonne, ligne)
function DessineBordureBottom ($objPHPExcel,$col, $row ) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(
					'borders' => array(
							'bottom'     => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
							)
					),

			)
	);
}

//Dessine une bordure gauche (trait plein) pour la cellule col,row (colonne, ligne)
function DessineBordureLeft ($objPHPExcel,$col, $row ) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(
					'borders' => array(
							'left'     => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
							)
					),

			)
	);
}

//Dessine une bordure droite (trait plein) pour la cellule col,row (colonne, ligne)
function DessineBordureRight ($objPHPExcel,$col, $row ) {

	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(
					'borders' => array(
							'right'     => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
							)
					),

			)
	);
}

//Dessine une bordure droite en pointill�s pour la cellule col,row (colonne, ligne)
function DessineBordureRightPointillee ($objPHPExcel,$col, $row ) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(
					'borders' => array(
							'right'     => array(
									'style' => PHPExcel_Style_Border::BORDER_HAIR
							)
					),

			)
	);
}


//Aligne au centre le contenu d'une cellule col,row (colonne, ligne)
function AligneAuCentre ($objPHPExcel, $col, $row) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(

					'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					),
			)
	);
}

//Met en italic le contenu d'une cellule col,row (colonne, ligne)
function Italic ($objPHPExcel, $col, $row) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(

					'font' => array(
							'italic' => true,
					),
			)
	);
}


//Met en gras le contenu d'une cellule col,row (colonne, ligne)
function Gras ($objPHPExcel, $col, $row) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
	array(
			'font'    => array(
					'bold'      => true
			),
				)
		);
}

		

//D�finit la largeur larg d'une colonne col
function LargeurCel ($objPHPExcel, $col, $larg) {
	$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth($larg);

}


// D�finit le titre des colonnes des indicateurs (OUV, CLOP, OUVP, OUV-CloP) avec leur subdivision (t, nc, c) 
// Pour une cat�gorie donn�e (ticket, Evolution...), inscrit le nom (titre) de l'indicateur pass� en param�tre, 
// et inscrit ses 3 sous param�tres (t, nc, c).  
// L'indicateur est inscrit dans la premi�re colonne col de la ligne row (exemple : OUV)
// Puis toujours dans la colonne col mais dans la ligne row+1 on inscrit "t" puis col+1 on inscrit "nc" et col +2 "c"
//Arguments :
// - col = 1ere colonne
// - row = ligne
// - Titre = indicateur  (OUV, ...) 
//Valeur retourn�e :
// - la colonne qui suit la colonne "c")
// EXEMPLE DANS LE FICHIER EXCEL :
//     OUV       => r�alis� par cette fonction
//  t  nc  c     => r�alis� par cette fonction
 
function DessineTitreColIndic ($objPHPExcel, $col, $row, $titre) {
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $titre);
	AligneAuCentre ($objPHPExcel, $col,$row);
	$objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+2,$row); //merge
	ColorFondCell ($objPHPExcel, $col,$row,COULGRISE);
	
	LargeurCel($objPHPExcel,$col,LARGCOL);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row+1, "t");
	AligneAuCentre ($objPHPExcel, $col,$row+1);
	ColorFondCell ($objPHPExcel, $col,$row,COULGRISE);
	ColorFondCell ($objPHPExcel, $col,$row+1,COULGRISE);
	
	$col++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row+1, "nc");
	AligneAuCentre ($objPHPExcel, $col,$row+1);
	LargeurCel($objPHPExcel,$col,LARGCOL);
	
	$col++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row+1, "c");
	AligneAuCentre ($objPHPExcel, $col,$row+1);
	LargeurCel($objPHPExcel,$col,LARGCOL);
	DessineBordureRightPointillee($objPHPExcel,$col ,$row);
	DessineBordureRightPointillee($objPHPExcel,$col ,$row+1);

	$col++;
	return $col;
}



// Pour une cat�gorie donn�e et un indicateur donn� (OUV, CLOP, OUVP, OUV-CloP), inscrit dans la premi�re colonne col d'une ligne row le nombre total de FFT, puis
// dans la colonne suivante (col+1) de la m�me ligne row  on inscrit le nombre de fft "non critique", puis
// dans la colonne suivante (col+1+1) de la m�me ligne row  on inscrit le nombre de fft "critique"
//Arguments : 
// - col = 1ere colonne 
// - row = ligne
// - ColtTot = nombre total de FFT
// - ColNonCrit = nombre de FFT non critique
// - ColCrit = nombre de FFT critique
//Valeur retourn�e :
// - la colonne qui suit la colonne "critique")
// EXEMPLE DANS LE FICHIER EXCEL :
//     OUV     
//  t  nc  c 
//  3  2   1  => r�alis� par cette fonction
function DessineColIndic ($objPHPExcel, $col, $row, $ColTot, $ColNonCrit, $ColCrit) {
	
	//colonne "total"
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, SupZero($ColTot));
	AligneAuCentre ($objPHPExcel, $col,$row);
	ColorFondCell ($objPHPExcel, $col,$row,COULGRISE);
	
	//colonne "non-critique"
	$col++;
	LargeurCel ($objPHPExcel, $col, LARGCOLCRIT);
	//on affiche une valeur dans la colonne non critique ssi une valeur existe pour cette colonne ET la colonne critique
	if (SupDblZero("(".($ColNonCrit)."|".($ColCrit).")")) { 
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, ($ColNonCrit));
		AligneAuCentre ($objPHPExcel, $col,$row);
	}
	
	//colonne "critique"
	$col++;
	LargeurCel ($objPHPExcel, $col, LARGCOLCRIT);
	//on affiche une valeur dans la colonne critique ssi une valeur existe pour cette colonne ET la colonne non-critique
	if (SupDblZero("(".($ColNonCrit)."|".($ColCrit).")")) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, ($ColCrit));
		AligneAuCentre ($objPHPExcel, $col,$row);
		if ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,$row)->getValue()!="0") Gras ($objPHPExcel, $col, $row);
	}
	DessineBordureRightPointillee($objPHPExcel,$col ,$row);
	
	$col++; 
	return $col;//retourne la prochaine colonne � traiter
	
	
}

//Collorie le fond d'une cellule avec la couleur "coul"
function ColorFondCell ($objPHPExcel,$col, $row, $coul ) {
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray(
			array(
					
					'fill' => array(
							'type'       => PHPExcel_Style_Fill::FILL_SOLID,
							/*'rotation'   => 90,*/
							'startcolor' => array(
									'argb' => $coul
							),
							/*'endcolor'   => array(
							 'argb' => 'FFFFFFFF'
							)*/
					)
			)
	);
	
	
	
}