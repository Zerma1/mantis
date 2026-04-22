<?php 
require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'filter_api.php' );
require_api( 'filter_constants_inc.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'project_api.php' );
require_api( 'string_api.php' );
require_api( 'utility_api.php' );
require_api( 'form_api.php' );

auth_ensure_user_authenticated();

/*
Les trucs de notre plugin
*/
require_once('fvl_include.php');
require_once('fvl_print_functions.php');
require_once('vendor/PHPExcel.php');
?>

<?php

layout_page_header_begin( 'Export excel' );

if (access_has_project_level(55))
{
	layout_page_header_end();
	layout_page_begin( __FILE__ );
	 ?>
	<?php
	
	$filter_id = get_post_param('filterSelect', 'default'); //Code récupéré quelque part dans le code de mantis
	if($filter_id == 'default')
	{
		$xl_filter = fvl_default_xl_filter();
	} else {
		$xl_filter = filter_db_get_filter( $filter_id );
		$xl_filter_detail = explode( '#', $xl_filter, 2 );
		if( !isset( $xl_filter_detail[1] ) ) {
			return ApiObjectFactory::faultServerError( 'Invalid Filter' );
		}
		$xl_filter = json_decode( $xl_filter_detail[1], true );
	}
	
	$xl_project_id = helper_get_current_project();
	$xl_filter = filter_ensure_valid_filter( $xl_filter );
	$xl_filter['per_page'] = -1;
	$xl_filter['project_id'] = $xl_project_id;
	
	filter_init($xl_filter);

	//On récupère tout les bugs correspondant à notre projet et filtre
	$f_page_number = 1; //Obligés de définir des variables pour envoyer comme arguments car la fonction filter_get_bug_rows n'accepte que des références
	$t_per_page = -1;
	$t_bug_count = null;
	$t_page_count = null;
	$t_result = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $xl_filter, $xl_project_id);
	$t_row_count = count( $t_result );

	//Les couleurs des statuts
	$t_status_string = config_get( 'status_enum_string' );
	$t_statuses = MantisEnum::getAssocArrayIndexedByValues( $t_status_string );
	$t_colors = config_get( 'status_colors' );
	
	foreach($t_statuses as $i => $t_status)
	{
		$stat_colors[get_enum_element( 'status', $i)] = substr($t_colors[$t_statuses[$i]],1);
	}
	
	if(count($t_result)!=0)
	{	
	
	?>
	<?php
	
	$xl_data['nom_projet'] = project_get_name( $xl_project_id );
	$xl_data['date'] = date('d-m-Y');
	
	foreach( $xl_columns as $t_column )
	{
		$xl_data['titres'][] = column_get_title($t_column);
	}
	$i = 0;
	foreach($t_result as $t_row)
	{
		foreach( $xl_columns as $t_column ) {
			$xl_data['lignes'][$i][] = fvl_get_text($t_column, $t_row);
		}
		$i++;
	}
	


	
	$fileName = __DIR__ . '/../files/template.xlsx';
	//$exportName = $xl_data['nom_projet'].'_'.date('Y-m-d H_i_s').'.xlsx';
	$exportName = $xl_data['nom_projet'].'_'.date('Y-m').'.xlsx';
	//$exportName = 'lel.xlsx';
	$fileSaveName = __DIR__ . '/../../../custom/fvlplugin/download/'.$exportName;
	$fileType = PHPExcel_IOFactory::identify($fileName);
	$objReader = PHPExcel_IOFactory::createReader($fileType);
	$objPHPExcel = $objReader->load($fileName);

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("FVLPlugin")
								 ->setLastModifiedBy("FVLPlugin")
								 ->setTitle("Export Mantis")
								 ->setSubject("Extraction des FFT de Mantis")
								 ->setDescription("")
								 ->setKeywords("mantis fft extraction")
								 ->setCategory("");
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, $xl_data['nom_projet']);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, $xl_data['date']);
	
	foreach($xl_data['titres'] as $i => $titre)
	{
		$col = PHPExcel_Cell::stringFromColumnIndex($i);
		$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 4, $titre);
		$objPHPExcel->getActiveSheet()->getStyle($col.'4')->applyFromArray(
		array(
				'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array( 'rgb' => 'C0C0C0')),
				'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM))
			));
		//getBorders()->getAllBorders()->SetBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);		
		switch($titre)
		{
			case 'Statut':
				$stat_col = $i; //un entier
				break;
			case 'Catégorie':
				$cat_col = $i;
				break;
		}
		$col++;
	}
	
	$nb_lignes = count($xl_data['lignes']);
	$nb_colonnes = count($xl_data['titres']);
	$last_col = PHPExcel_Cell::stringFromColumnIndex($nb_colonnes-1);
	$cat_colors = array('Anomalie' =>  'FAD5B4',
						'Evolution' => 'D7E6BC');
						
	foreach($xl_data['lignes'] as $i => $ligne)
	{
		foreach($ligne as $j => $valeur)
		{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, $i + 5, $valeur);
		}
		$objPHPExcel->getActiveSheet()->getRowDimension($i + 5 )->setRowHeight(-1);
		/*var_dump( 'A'. $i .':'. $last_col . $i);
		var_dump($cat_colors[$ligne[$cat_col]]);
		var_dump($ligne[$cat_col]);*/
		$objPHPExcel->getActiveSheet()->getStyle( 'A'. ($i+5) .':'. $last_col . ($i+5))->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor()->setRGB($cat_colors[$ligne[$cat_col]]);
		$objPHPExcel->getActiveSheet()->getStyle( PHPExcel_Cell::stringFromColumnIndex($stat_col). ($i+5) )->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID )->getStartColor()->setRGB($stat_colors[$ligne[$stat_col]]);
	}
	$objPHPExcel->getActiveSheet()->getStyle('A5:'.$last_col.($nb_lignes+4))->applyFromArray(
	array(
			'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN))
		));
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
	$objWriter->save($fileSaveName);
	?>
	<a class="btn btn-primary" href="<?php echo 'custom/fvlplugin/download/'.$exportName; ?>">Télécharger</a>
<?php

	} else {
	echo '<h1 style="color:red">Aucun faits à exporter</h1>';
	}
} else {
	layout_page_header_end(); 
	layout_page_begin( __FILE__ );
	echo '<h1 style="color:red">Droits insuffisants</h1>';
} 
   layout_page_end(); 
?>