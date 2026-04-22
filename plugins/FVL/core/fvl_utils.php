<?php 
/* Fonction qui sépare en plusieurs sous-tableaux les bugs (par version) */
function sort_by_versions( $versions, $bugs )
{
	foreach($versions as $version)
	{
		$vname = $version['version'];
		$result[$vname] = array_filter($bugs, function($bug) use($vname) {
			return $bug->fixed_in_version == $vname;
		});
	}
	return  $result;
}

function status_color($status_code)
{
	/*<tr style="<?php echo "background-color: {$t_colors[$t_statuses[$t_row->status]]};"; ?>">*/
}
function get_get_param($pkey, $pdefaultvalue)
{
	return (isset($_GET[$pkey]) ? htmlspecialchars($_GET[$pkey]) : $pdefaultvalue); //Condition inline
}
function get_post_param($pkey, $pdefaultvalue)
{
	return (isset($_POST[$pkey]) ? htmlspecialchars($_POST[$pkey]) : $pdefaultvalue); //Condition inline
}

/*	
	echo "
	<button data-toggle='collapse' data-target='#Lel'>V</button>
	<div id='Lel' class='collapse'>
	";
	//var_dump($fvl_filter);
	var_dump($fvl_versions);
	var_dump($t_result[0]);
	var_dump($t_statuses);
	var_dump($t_colors);
	echo "</div>";*/
	
function fvl_default_filter()
{
	$filter_ = array (
		'_version' => 'v9',
		'_view_type' => 'simple',
		'category_id' => ['Anomalie', 'Evolution'],	
		'status' => [90],
		'highlight_changed' => 6,
		'reporter_id' => [0],
		'handler_id' => [0],
		'project_id' => 0,
		'resolution' => [0],
		'build' => [0],
		'version' => [0],
		'hide_status' => [-2], //90 c'est pour cacher les cloturés (pas ce que l'on veut)
		'monitor_user_id' => [0],
		'sort' => 'last_updated',
		'dir' => 'DESC',
		'per_page' => -1,
		'match_type' => 0,
		'platform' => [0],
		'os' => [0],
		'os_build' => [0],
		'fixed_in_version' => [0],
		'target_version' => [0],
		'profile_id' => [0],
		'priority' => [0],
		'note_user_id' => [0],
		'sticky' => true,
		'filter_by_date' => false,
		'start_month' => '06',
		'end_month' => '06',
		'start_day' => 1,
		'end_day' => '28',
		'start_year' => '2017',
		'end_year' => '2017',
		'filter_by_last_updated_date' => false,
		'last_updated_start_month' => '06',
		'last_updated_end_month' => '06',
		'last_updated_start_day' => 1,
		'last_updated_end_day' => '28',
		'last_updated_start_year' => '2017',
		'last_updated_end_year' => '2017',
		'search' => '',
		'view_state' => 0,
		'tag_string' => '',
		'tag_select' => 0,
		'relationship_type' => -1,
		'relationship_bug' => 0,
		'custom_fields' => array(
			9 => [0],
			5 => [0],
			11 => [0],
			12 => [0],
			13 => [0],
			4 => [0],
			6 => [0],
			8 => [0],
			3 => [0])
		);
	return $filter_;
}

function fvl_default_xl_filter()
{
	$newfilter = fvl_default_filter();
	$newfilter['status'] = [0];
	return $newfilter;
}

?>