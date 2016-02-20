<?php
require( "worklog_api.php" );
require( "css_worklog.php" );
html_page_top1();
if (OFF == plugin_config_get('worklog_view_window') ){
  html_page_top2();
}
?>

<?php
	access_ensure_project_level( DEVELOPER );

	# Add faq
	$f_log_type	  = gpc_get_string( 'log_type' );
	$f_log_begin	  = gpc_get_string( 'log_begin' );
	$f_log_end	  = gpc_get_string( 'log_end' );
	$f_ref_issue_ids	  = gpc_get_string( 'ref_issue_ids' );
	$f_ref_log_ids	  = gpc_get_string( 'ref_log_ids' );
	$f_subject	  = gpc_get_string( 'subject' );
	$f_content	  = gpc_get_string( 'content' );

	$f_project_id = gpc_get_string( 'project_id' );
	$f_poster_id  = auth_get_current_user_id();

	$f_view_level =plugin_config_get('worklog_view_threshold');

        $result = worklog_add_query($f_project_id, $f_poster_id, $f_content, $f_subject, $f_log_type
            , $f_log_begin, $f_log_end
            , $f_ref_issue_ids, $f_ref_log_ids,
            $f_view_level);
    $f_content = string_display( $f_content );
    $f_subject 	= string_display( $f_subject );
?>

<p>
<div align="center">
<?php
	if ( $result ) {			# SUCCESS
		PRINT lang_get( 'operation_successful' ) . '<p>';
?>
<table class="width75" cellspacing="1">
<tr>
	<td class="worklog-content">
		<span class="worklog-content"><?php echo $f_content ?></span>
	</td>
</tr>
<tr>
	<td class="worklog-body">
		<?php echo $f_body ?>
	</td>
</tr>
</table>
<p>
<?php
	} else {					# FAILURE
		print_sql_error( $query );
	}

	  if (ON == plugin_config_get('worklog_view_window') ){
	?>
	<a href="javascript:window.opener='x';window.close();">Close Window</a>
<?PHP

	  } else {
		print_bracket_link( $g_worklog_menu_page, lang_get( 'proceed' ) );
	}
?>
</div>
<?php
html_page_bottom1();
