<?php
	$g_mantis_worklog_table              = plugin_table('results');

	#----------------------------------
	# faq page definitions

	$g_worklog_menu_page                 = plugin_page( 'worklog_menu_page.php' );
	$g_worklog_view_page                 = plugin_page( 'worklog_view_page.php' );

	$g_worklog_edit_page                 = plugin_page( 'worklog_edit_page.php' );
	$g_worklog_add_page                  = plugin_page( 'worklog_add_page.php' );
	$g_worklog_add                       = plugin_page( 'worklog_add.php' );
	$g_worklog_add2                       = plugin_page( 'worklog_add2.php' );
	$g_worklog_delete_page               = plugin_page( 'worklog_delete_page.php' );
	$g_worklog_delete                    = plugin_page( 'worklog_delete.php' );
	$g_worklog_update                    = plugin_page( 'worklog_update.php' );

		#----------------------------------

	###########################################################################
	# faq API
	###########################################################################

	# function worklog_add   ( $p_project_id, $p_poster_id, $p_content, $p_subject, $p_log_type, $p_log_begin, $p_log_end , $p_ref_issue_ids,$p_ref_log_ids );
	# function worklog_delete( $p_id );
	# function worklog_update( $p_id, $p_content,  $p_subject , $p_log_type, $p_log_begin, $p_log_end , $p_ref_issue_ids,$p_ref_log_ids);
	# function worklog_select( $p_id );

	# --------------------
	function worklog_add_query( $p_project_id, $p_poster_id, $p_content, $p_subject, $p_log_type, $p_log_begin, $p_log_end , $p_ref_issue_ids,$p_ref_log_ids, $p_view_level= 10) {
		global $g_mantis_worklog_table;

		# " character poses problem when editting so let's just convert them
		$p_content	= db_prepare_string( $p_content );
		$p_subject	= db_prepare_string( $p_subject );

		# Add item
		$query = "INSERT
				INTO $g_mantis_worklog_table
	    		( id, project_id, poster_id, date_posted, last_modified, content, subject, log_type,log_begin,log_end,ref_issue_ids,ref_log_ids, view_access )
				VALUES
				( null, '$p_project_id', '$p_poster_id', NOW(), NOW(), '$p_content', '$p_subject','$p_log_type','$p_log_begin','$p_log_end',
				 '$p_ref_issue_ids','$p_ref_log_ids',
				'$p_view_level' )";
	    return db_query_bound( $query );
	}
	# --------------------
	# Delete the faq entry
	function worklog_delete_query( $p_id ) {
		global $g_mantis_worklog_table;

		$query = "DELETE
				FROM $g_mantis_worklog_table
	    		WHERE id='$p_id'";
	    return db_query_bound( $query );
	}
	# --------------------
	# Update faq item
	function worklog_update_query( $p_id, $p_content, $p_subject, $p_log_type, $p_log_begin, $p_log_end, $p_project_id ,$p_ref_issue_ids,$p_ref_log_ids,$p_view_level) {
		global $g_mantis_worklog_table;

		# " character poses problem when editting so let's just convert them to '
		$p_content	= db_prepare_string( $p_content );
		$p_subject		= db_prepare_string( $p_subject );

		# Update entry
		$query = "UPDATE $g_mantis_worklog_table
				SET content='$p_content',
				subject='$p_subject',
				log_type='$p_log_type',
				log_begin='$p_log_begin',
				log_end='$p_log_end',
				ref_issue_ids='$p_ref_issue_ids',
				ref_log_ids='$p_ref_log_ids',
					project_id='$p_project_id', view_access='$p_view_level', last_modified=NOW()
	    		WHERE id='$p_id'";
	    return db_query_bound( $query );
	}
	# --------------------
	# Selects the faq item associated with the specified id
	function worklog_select_query( $p_id ) {
		global $g_mantis_worklog_table;

		$query = "SELECT *
			FROM $g_mantis_worklog_table
			WHERE id='$p_id'";
	    $result = db_query_bound( $query );
		return db_fetch_array( $result );
	}
	# --------------------
	# get faq count (selected project plus sitewide posts)
	function worklog_count_query( $p_project_id ) {
		global $g_mantis_worklog_table;

		$query = "SELECT COUNT(*)
				FROM $g_mantis_worklog_table";
//				;WHERE project_id='$p_project_id' OR project_id='0000000'";
		$result = db_query_bound( $query );
	    return db_result( $result, 0, 0 );
	}

    function worklog_type_display($p_log_type) {
		switch($p_log_type){
			case 0:return plugin_lang_get('log_type_day');
			case 1:return plugin_lang_get('log_type_week');
			case 2:return plugin_lang_get('log_type_month');
			case 3:return plugin_lang_get('log_type_year');
		}
		return '';
    }