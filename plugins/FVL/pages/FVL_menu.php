<?php
auth_ensure_user_authenticated();

layout_page_header_begin( 'FVL' );
layout_page_header_end();
layout_page_begin( __FILE__ );
if (access_has_project_level(55)) //55 c'est le niveau requis pour accéder aux fonctionnalités du plugin (expert fonctionnel)
{
?>
<div class='m4 border-box'>
	<h1 class="h1">Options de projet</h1>
    <p><a class='btn btn-primary mb1 green' href="<?php echo plugin_page( 'FVL_templater.php' ) ?>">Génération de FVL docx</a></p>
    <p><a class='btn btn-primary mb1 green' href="<?php echo plugin_page( 'FVL_xlform.php' ) ?>">Génération d'export Excel</a></p>
    <p><a class='btn btn-primary mb1 green' href="<?php echo plugin_page( 'FVL_overview.php' ) ?>">Vue globale du projet</a></p>
    <p><a class='btn btn-primary mb1 green' href="_mcosio/">Stats MCOSIO</a></p>
    <p><a class='btn btn-primary mb1 green' href="http://antemmtln.marine.defense.gouv.fr:8080/kanboard">KanBoard</a></p>
</div>
<?php
} else { 
	echo '<h1 style="color:red">Droits insuffisants</h1>';
} 
   layout_page_end(); 
?>