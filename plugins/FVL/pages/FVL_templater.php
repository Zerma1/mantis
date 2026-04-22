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
?>

<?php

layout_page_header_begin( 'Génération par template' );
if(helper_get_current_project()==0)
{
	layout_page_header_end(); 
	layout_page_begin( __FILE__ );
	echo '<h1 style="color:red">Veuillez sélectionner un projet particulier</h1>';
}
else if (access_has_project_level(55))
{
	layout_page_header_end();
	layout_page_begin( __FILE__ );
	 ?>
	<h1>Fiche</h1>
	<div class='p2 m2 border' >
	<form id='verSelectForm'>
	<input id='slctAll' type='radio' name='verSelect' value='all' checked> Toutes les versions</input><br>
	<input id='slctFrom' type='radio' name='verSelect' value='from'> A partir de la version :</input><br>
	</form>
	<form id='genFvlForm'>
		<select id='vselect'>
		</select>
	</form>
	<input id='histoFilter' type='checkbox' checked> Afficher toutes les versions dans l'historique</input>
	</div>
	<div id='progressbar'></div>
	<button disabled id='submitBtn' class='btn btn-primary btn-important'>Télécharger la FVL</button>
	
	<?php
	//echo '<pre>'.json_encode($fvl_data, JSON_PRETTY_PRINT).'</pre>';

	echo '
		<script src="custom/fvlplugin/vendor/docxtemplater.min.js"></script>
		<script src="custom/fvlplugin/vendor/jszip.js"></script>
		<script src="custom/fvlplugin/vendor/file-saver.min.js"></script>
		<script src="custom/fvlplugin/vendor/jszip-utils.js"></script>
		<!--
		Mandatory in IE 6, 7, 8 and 9.
		-->
		<!--[if IE]>
		<script type="text/javascript" src="custom/fvlplugin/vendor/jszip-utils-ie.js"></script>
		<![endif]-->
		
		<script src="custom/fvlplugin/fvl_templater.js"></script>';
	?>
	<?php
	include('fvl_get_data.php');
	/* On remplit le tableau $fvldata qui sera utilisé tel quel pour le remplissage du template */
		
	$fvl_data['nom_projet'] = project_get_name( $fvl_project_id );
	/* Pour les champs où il manquait les retours à la ligne, on a recours à la solution donnée là :
	github.com/open-xml-templating/docxtemplater/issues/144 
	*/
	
	$fvl_data['description_projet_l'] = explode("\n",project_get_field( $fvl_project_id, 'description' ));

	$bugs_byver = sort_by_versions($fvl_versions, $t_result);
	$i = 0;

	//var_dump($bugs_byver);

	foreach( $fvl_versions as $fvl_version ) {
		if ($i==0) { 
		$fvl_data['version_courante'] = $fvl_version['version'];
		$fvl_data['date_version'] = string_attribute( date( 'd-m-Y', $fvl_version['date_order'] ));
		}
		/* On utilise un entier $i pour indexer les versions stockées : c'est un tableau simple, qui sera un tableau en json (pas un dictionnaire)*/
		$fvl_data['versions'][$i]['v_numero'] = $fvl_version['version'];
		$fvl_data['versions_histo'][$i]['numero'] = $fvl_version['version'];
		$fvl_data['versions'][$i]['date'] = string_attribute( date( 'd-m-Y', $fvl_version['date_order'] )); 
		$fvl_data['versions_histo'][$i]['date'] = string_attribute( date( 'd-m-Y', $fvl_version['date_order'] )); 
		$fvl_data['versions'][$i]['description_l'] = explode("\n",$fvl_version['description']);
		$fvl_data['versions_histo'][$i]['description_l'] = explode("\n",$fvl_version['description']);
		
		foreach($bugs_byver[$fvl_version['version']] as $bug)
		{
			$bug_data = array(
								'numero' => $bug->id,
								'impact' => get_enum_element( 'severity', $bug->severity ), //provient de helper_api
								'resume' => $bug->summary,
								'description_l' => explode("\n",$bug->description),
							);

			if($bug->category_id == 8)
			{
				$fvl_data['versions'][$i]['evolutions'][] = $bug_data;
			} else {
				$fvl_data['versions'][$i]['bugs'][] = $bug_data;
			}
			
		}	
		$i++;
	}
	?>
	<script>
	page_script(<?php echo json_encode( $fvl_data ); ?>);
	</script>

<?php
} else {
	layout_page_header_end(); 
	layout_page_begin( __FILE__ );
	echo '<h1 style="color:red">Droits insuffisants</h1>';
} 
   layout_page_end(); 
?>