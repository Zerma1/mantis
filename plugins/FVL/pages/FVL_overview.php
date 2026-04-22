<?php
/*
	Version modifiée de la page print_all_bug_page.php
*/

/*
Les trucs de Mantis dont on se sert
*/
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

/*
Les trucs de notre plugin
*/
require_once('fvl_include.php');
include('fvl_get_data.php');
?>

<?php 
auth_ensure_user_authenticated();
layout_page_header_begin( 'Vue globale du projet' );
layout_page_header_end();
layout_page_begin( __FILE__ );

if (access_has_project_level(55))
{
?>

	<div>
		<div class="center">
		<h1 class="h1"><?php echo string_display( project_get_name( $fvl_project_id ) ); ?> | <a href='<?php echo "manage_proj_edit_page.php?project_id=" . $fvl_project_id; ?>'>Editer</a></h1>
		</div>
		<div>
			<p><?php echo string_display( project_get_field( $fvl_project_id, 'description' ) ); ?></p>
		</div>
		<div>
		<p><a class='btn btn-primary mb1 green' href="manage_config_columns_page.php">Éditer les colonnes d'export Excel</a></p>
		<h2 class="h2">Versions</h2>
		<div>
		<?php
		foreach ($fvl_versions as $fvl_version)
		{ ?>
		<div>
		<h3 class="h3"><?php echo string_display($fvl_version['version']); ?> | <a href='<?php echo "manage_proj_ver_edit_page.php?version_id=" . $fvl_version['id']; ?>'>Editer</a></h3>
			<h4 class="h4"><?php echo string_attribute( date( 'Y-m-d', $fvl_version['date_order'] )) ; ?></h4>
		<p><?php echo string_display($fvl_version['description']); ?></p>
		<div>
		<?php } ?>
		</div>
		
		<div>
	</div>
<?php
} else { 
	echo '<h1 style="color:red">Droits insuffisants</h1>';
} 
   layout_page_end(); 
?>
