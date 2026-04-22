<?php
/*
   Auteur Vianney Touchard
   Stage CEPN Juillet 2017
*/
//require_once( 'core/timetracking_api.php' ); 
class FVLPlugin extends MantisPlugin
{

	function register()
	{
		$this->name = 'CEPN';
		$this->description = 'Plugin pour le CEPN';
		$this->page = 'FVL_form';

		$this->version = '1.0';
		$this->requires = array(
			'MantisCore' => '2.0.0'
		);

		$this->author = 'Vianney Touchard';
		$this->contact = 'vianneytouchard@hotmail.fr';
		$this->url = '';
	}

	/* Pour les 'hooks' j'ai pris exemple sur les autres plugins, resources et */
	function hooks()
	{
		return array(
			'EVENT_MENU_MAIN'      => 'FVL_menu_entry',
			'EVENT_CORE_HEADERS' => 'csp_headers'
		);
	}

	function init()
	{
		$t_path = config_get_global('plugin_path' ). plugin_get_current() . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR;
		set_include_path(get_include_path() . PATH_SEPARATOR . $t_path);
	}
	
	function FVL_menu_entry()
	{
	    return array(
			array(
				'title' => 'CEPN',
				'url' => plugin_page( 'FVL_menu' ),
				'icon' => 'fa-file-text',
				'access_level' => 55,
			)
		);
	}

	/**
	 * Add Content-Security-Policy directives that are needed to load scripts for CDN.
	 * @return void
	 */
	function csp_headers() {
		http_csp_add( 'script-src', "'unsafe-inline'" );
	}
} # class end
?>
