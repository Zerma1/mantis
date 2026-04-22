<?php
$t_num_of_columns = count( $t_columns ); //$t_columns défini dans core/fvl_config.php

// Ne pas hésiter à visualiser ce qui se passe avec var_dump(); !
$fvl_project_id = helper_get_current_project();
$fvl_versions = version_get_all_rows( $fvl_project_id, VERSION_RELEASED, null);

$fvl_filter = fvl_default_filter(); //Pour voir un dump de ce que retourne ceci, cf nimp/filtre.php
$fvl_filter['project_id']=$fvl_project_id;
filter_init($fvl_filter);

/* On récupère tout les bugs correspondant à notre projet et filtre */
$f_page_number = 1; //Obligés de définir des variables pour envoyer comme arguments car la fonction filter_get_bug_rows n'accepte que des références
$t_per_page = -1;
$t_bug_count = null;
$t_page_count = null;
$t_result = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $fvl_filter, $fvl_project_id);
$t_row_count = count( $t_result );

//Les couleurs des statuts
$t_status_string = config_get( 'status_enum_string' );
$t_statuses = MantisEnum::getAssocArrayIndexedByValues( $t_status_string );
$t_colors = config_get( 'status_colors' );
?>
