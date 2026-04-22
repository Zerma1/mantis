<?php
/*
   Auteur Vianney Touchard
   Stage CEPN Septembre 2017
*/
//require_once( 'core/timetracking_api.php' ); 
class OutdatedBrowserPlugin extends MantisPlugin
{

	function register()
	{
		$this->name = 'Navigateurs dépassés';
		$this->description = 'Plugin pour avertir les utilisateurs ayant un navigateur non supporté';
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
			'EVENT_LAYOUT_BODY_END' => 'OB_script',
		);
	}

	function OB_script()
	{
		echo '
		<script>
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE");
		if( !sessionStorage["warned"] && (msie>0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)))
		{
			alert("Nous avons détecté que vous utilisez Internet Explorer. Il est possible que vous rencontriez des problèmes d\'affichage sur ce type de navigateurs.\nPour bénéficier d\'une meilleure compatibilité, veuillez utiliser un navigateur comme Mozilla Firefox");
			sessionStorage["warned"] = true;
		}
		</script>';
	}

} # class end
?>
