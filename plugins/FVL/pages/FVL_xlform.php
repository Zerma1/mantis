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

layout_page_header_begin( 'Export excel' );
if (access_has_project_level(55))
{
	layout_page_header_end();
	layout_page_begin( __FILE__ ); ?>
	<?php	
	$t_stored_queries_arr = filter_db_get_available_queries();?>
	<form name='filterForm' method='POST' action='<?php echo plugin_page('FVL_xlgen.php'); ?>'>
	<h3 class="h3">Filtre pour l'export :</h3>
	<select name='filterSelect'>
		<option value='default'>[EXPORT PAR DEFAUT]</option>
		<?php
		foreach($t_stored_queries_arr as $query_id => $query_name)
		{ 
			echo '<option value="'.$query_id.'">'.$query_name.'</option>';
		} ?>
	</select><br><br>
	<button type='submit' class="btn btn-primary">Générer l'export</button>
	</form>
<?php
} else {
	layout_page_header_end(); 
	layout_page_begin( __FILE__ );
	echo '<h1 style="color:red">Droits insuffisants</h1>';
} 
   layout_page_end(); 
?>