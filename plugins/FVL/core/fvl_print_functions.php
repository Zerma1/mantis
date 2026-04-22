<?php
/*
Version modifiée de 'columns_api.php'
Ensemble de fonctions qui permettent de récupérer les caractéristiques d'un bug en texte pur (pas des echo de html tout dégueulasses comme Mantis fait habituellement)
Pallie la NULLITÉ de MantisBT, codé avec le c*l dans le respect de la tradition PHP
*/

require_api( 'access_api.php' );
require_api( 'bug_api.php' );
require_api( 'category_api.php' );
require_api( 'columns_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'custom_field_api.php' );
require_api( 'date_api.php' );
require_api( 'error_api.php' );
require_api( 'event_api.php' );
require_api( 'file_api.php' );
require_api( 'helper_api.php' );
require_api( 'icon_api.php' );
require_api( 'lang_api.php' );
require_api( 'prepare_api.php' );
require_api( 'print_api.php' );
require_api( 'project_api.php' );
require_api( 'sponsorship_api.php' );
require_api( 'string_api.php' );

require_once('fvl_config.php');

function fvl_get_text($t_column, $t_bug)
{
	$text_function = 'fvl_text_column_'.$t_column;
		if(function_exists($text_function))
		{
			return $text_function( $t_bug, COLUMNS_TARGET_PRINT_PAGE );
		} else {
			$t_custom_field = column_get_custom_field_name( $t_column );
			if( $t_custom_field !== null )
			{
				$t_field_id = custom_field_get_id_from_name( $t_custom_field );

				if( $t_field_id === false )
				{
					return '';
				} else {
					return custom_field_get_value($t_field_id, $t_bug->id);
				}
			}
		}
}

/**
 * Print column content for column edit
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_edit( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {

	if( !bug_is_readonly( $p_bug->id ) && access_has_bug_level( config_get( 'update_bug_threshold' ), $p_bug->id ) ) {
		return string_get_bug_update_url( $p_bug->id );
	} else {
		return ';';
	}
}

/**
 * Print column content for column priority
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_priority( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return get_enum_element( 'priority', $p_bug->priority, auth_get_current_user_id(), $p_bug->project_id );
}

/**
 * Print column content for column id
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_id( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return $p_bug->id;
}

/**
 * Print column content for column sponsorship total
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_sponsorship_total( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	if( $p_bug->sponsorship_total > 0 ) {
		$t_sponsorship_amount = sponsorship_format_amount( $p_bug->sponsorship_total );
		return string_no_break( $t_sponsorship_amount );
	}
}

/**
 * Print column content for column bugnotes count
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_bugnotes_count( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	global $g_filter;

	# grab the bugnote count
	$t_bugnote_stats = bug_get_bugnote_stats( $p_bug->id );
	if( null !== $t_bugnote_stats ) {
		$t_bugnote_count = $t_bugnote_stats['count'];
		$v_bugnote_updated = $t_bugnote_stats['last_modified'];
	} else {
		$t_bugnote_count = 0;
	}

	if( $t_bugnote_count > 0 ) {
		return $t_bugnote_count;
	} else {
		return ';';
	}
}

/**
 * Print column content for column attachment count
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_attachment_count( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {

	# Check for attachments
	$t_attachment_count = 0;
	if( file_can_view_bug_attachments( $p_bug->id, null ) ) {
		$t_attachment_count = file_bug_attachment_count( $p_bug->id );
	}

	if( $t_attachment_count > 0 ) {
		return $t_attachment_count;
	} else {
		return ' ; ';
	}
}

/**
 * Print column content for column category id
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_category_id( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {

	return string_display_line( category_full_name( $p_bug->category_id, false ) );
}

/**
 * Print column content for column severity
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_severity( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return get_enum_element( 'severity', $p_bug->severity, auth_get_current_user_id(), $p_bug->project_id );
}

/**
 * Print column content for column eta
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_eta( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return get_enum_element( 'eta', $p_bug->eta, auth_get_current_user_id(), $p_bug->project_id );
}

/**
 * Print column content for column projection
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_projection( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return get_enum_element( 'projection', $p_bug->projection, auth_get_current_user_id(), $p_bug->project_id );
}

/**
 * Print column content for column reproducibility
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_reproducibility( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return get_enum_element( 'reproducibility', $p_bug->reproducibility, auth_get_current_user_id(), $p_bug->project_id );
}

/**
 * Print column content for column resolution
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_resolution( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return get_enum_element( 'resolution', $p_bug->resolution, auth_get_current_user_id(), $p_bug->project_id );
}

/**
 * Print column content for column status
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_status( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	
	return get_enum_element( 'status', $p_bug->status, auth_get_current_user_id(), $p_bug->project_id );
}

function fvl_text_user($p_user_id)
{
	if( NO_USER == $p_user_id ) {
		return '';
	}

	$t_username = user_get_name( $p_user_id );
	$t_username = string_display_line( $t_username );
	return $t_username ;
}

/**
 * Print column content for column handler id
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_handler_id( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	
	# In case of a specific project, if the current user has no access to the field, then it would have been excluded from the
	# list of columns to view.  In case of ALL_PROJECTS, then we need to check the access per row.
	if( $p_bug->handler_id > 0 && ( helper_get_current_project() != ALL_PROJECTS || access_has_project_level( config_get( 'view_handler_threshold' ), $p_bug->project_id ) ) ) {
		return fvl_text_user( $p_bug->handler_id );
	}
}

/**
 * Print column content for column reporter id
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_reporter_id( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$p_user_id = $p_bug->reporter_id;
	
	return fvl_text_user( $p_bug->reporter_id );
}

/**
 * Print column content for column project id
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_project_id( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return string_display_line( project_get_name( $p_bug->project_id ) );
}

/**
 * Print column content for column last updated
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_last_updated( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	global $g_filter;

	$t_last_updated = string_display_line( date( config_get( 'short_date_format' ), $p_bug->last_updated ) );

	return $t_last_updated;
}

/**
 * Print column content for column date submitted
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_date_submitted( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$t_date_submitted = string_display_line( date( config_get( 'short_date_format' ), $p_bug->date_submitted ) );

	return $t_date_submitted;
}

/**
 * Print column content for column summary
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_summary( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$t_summary = $p_bug->summary;

	return $t_summary;
}

/**
 * Print column content for column description
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_description( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$t_description = $p_bug->description;

	return $t_description;
}

/**
 * Print column content for notes column
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_notes( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$t_notes = bugnote_get_all_visible_as_string( $p_bug->id, /* user_bugnote_order */ 'DESC', /* user_bugnote_limit */ 0 );

	return $t_notes;
}

/**
 * Print column content for column steps to reproduce
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_steps_to_reproduce( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$t_steps_to_reproduce = $p_bug->steps_to_reproduce;

	return $t_steps_to_reproduce;
}

/**
 * Print column content for column additional information
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_additional_information( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$t_additional_information = $p_bug->additional_information;

	return $t_additional_information;
}

/**
 * Print column content for column target version
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_target_version( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	# In case of a specific project, if the current user has no access to the field, then it would have been excluded from the
	# list of columns to view.  In case of ALL_PROJECTS, then we need to check the access per row.
	if( helper_get_current_project() != ALL_PROJECTS || access_has_project_level( config_get( 'roadmap_view_threshold' ), $p_bug->project_id ) ) {
		return string_display_line( $p_bug->target_version );
	}
}
function fvl_text_column_fixed_in_version( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return string_display_line( $p_bug->fixed_in_version );
}
function fvl_text_column_version( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	return string_display_line( $p_bug->version );
}


/**
 * Print column content for view state column
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_view_state( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {

	if( VS_PRIVATE == $p_bug->view_state ) {
		$t_view_state_text = lang_get( 'private' );
		return $t_view_state_text;
	} else {
		return ';';
	}
}

/**
 * Print column content for column tags
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_tags( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {

	if( access_has_bug_level( config_get( 'tag_view_threshold' ), $p_bug->id ) ) {
		return string_display_line( tag_bug_get_all( $p_bug->id ) );
	}
}

/**
 * Print column content for column due date
 *
 * @param BugData $p_bug            BugData object.
 * @param integer $p_columns_target See COLUMNS_TARGET_* in constant_inc.php.
 * @return void
 * @access public
 */
function fvl_text_column_due_date( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {
	$t_overdue = '';

	if( !access_has_bug_level( config_get( 'due_date_view_threshold' ), $p_bug->id ) ||
		date_is_null( $p_bug->due_date )
	) {
		$t_value = ';';
	} else {
		if( bug_is_overdue( $p_bug->id ) ) {
			$t_overdue = ' overdue';
		}
		$t_value = string_display_line( date( config_get( 'short_date_format' ), $p_bug->due_date ) );
	}

	return $t_value;
}

function fvl_text_column_overdue( BugData $p_bug, $p_columns_target = COLUMNS_TARGET_VIEW_PAGE ) {

	if( access_has_bug_level( config_get( 'due_date_view_threshold' ), $p_bug->id ) &&
		!date_is_null( $p_bug->due_date ) &&
		bug_is_overdue( $p_bug->id ) ) {
		$t_overdue_text = lang_get( 'overdue' );
		$t_overdue_text_hover = sprintf( lang_get( 'overdue_since' ), date( config_get( 'short_date_format' ), $p_bug->due_date ) );
		return $t_overdue_text_hover;
	} else {
		return ';';
	}
}
