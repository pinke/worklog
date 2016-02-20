<?php
require( "worklog_api.php" );
require( "css_worklog.php" );
html_page_top1();
if (OFF == plugin_config_get('worklog_view_window') ){
	html_page_top2();
}
?>
<p>
<div align="center">
<?php
	access_ensure_project_level( DEVELOPER );

	# Update faq
	$f_content	  = gpc_get_string( 'content' );
	$f_answere	  = gpc_get_string( 'answere' );
	$f_project_id = gpc_get_int( 'project_id' );
	$f_poster_id  = gpc_get_int( 'f_id' );
	if (plugin_config_get('worklog_view_check') )
		$f_view_access = gpc_get_int( 'worklog_view_threshold' );
	else
		$f_view_access = 0;

    $result = worklog_update_query( $f_poster_id, $f_content, $f_answere, $f_project_id,$f_view_access );

    $f_content 	= string_display( $f_content );
    $f_answere 		= string_display( $f_answere );

	if ( $result ) {				# SUCCESS
		PRINT lang_get( 'operation_successful' ) . '<p>';
?>
<table class="width75" cellspacing="1">
<tr>
	<td class="worklog-heading">
		<span class="worklog-content"><?php echo $f_content ?></span>
	</td>
</tr>
<tr>
	<td class="worklog-answere">
		<?php echo $f_answere ?>
	</td>
</tr>
</table>
<p>
<?php
	} else {						# FAILURE
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