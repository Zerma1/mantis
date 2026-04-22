<?php
// Ouverture du fichier
$handle=fopen('unites.txt','r');
$tab=array();
if ($handle){
	while (!feof($handle))
	{
		//on lit la ligne courante
		$buffer = trim(fgets($handle));
		$buffer=str_replace('"','',$buffer);
		if ($buffer!="" and $buffer!=null) $tab[]= $buffer;
	}
	//fermeture du fichier
	fclose($handle);
	echo "<pre>";
	//print_r($tab);
	echo "</pre>";
	echo implode('|',$tab);
}