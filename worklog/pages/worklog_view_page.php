<?php
require( "worklog_api.php" );
require( "css_worklog.php" );
html_page_top1();
if (OFF == plugin_config_get('worklog_view_window') ){
	html_page_top2();
}

?>
<?php
$f_id = gpc_get_int( 'f_id' );

	# Select the faq posts
	$query = "SELECT *, UNIX_TIMESTAMP(date_posted) as date_posted
			FROM $g_mantis_worklog_table
			WHERE  id='$f_id'";
	$result = db_query_bound( $query );
    $worklog_count = db_num_rows( $result );

    # Loop through results
	for ($i=0;$i<$worklog_count;$i++) {
		$row = db_fetch_array($result);
		extract( $row, EXTR_PREFIX_ALL, "v" );

		$v_headline 	= string_display( $v_headline );
		$v_answere 		= string_display_links( $v_answere );
		$v_date_posted 	= date( $g_normal_date_format, $v_date_posted );

    	$t_poster_name	= user_get_name($v_poster_id );
		$t_poster_email	= user_get_email($v_poster_id );
        $t_project_name = "Sitewide";
		if( $v_project_id != 0 )
    		$t_project_name = project_get_field( $v_project_id, "name" );
?>
<p>
<div align="center">
<table class="width75" cellspacing="0">
<tr>
	<td class="worklog-heading">
		<span class="worklog-content"><?php echo $v_content ?></span> -
		<span class="worklog-date"><?php echo $v_date_posted ?></span> -
		<a class="worklog-email" href="mailto:<?php echo $t_poster_email ?>"><?php echo $t_poster_name ?></a> -
		<?php echo $t_project_name ?>
	</td>
</tr>
<tr>
	<td class="worklog-answere">
<?php
        echo $v_answere;
       	if ( access_has_project_level( DEVELOPER ) ) {
           global $g_worklog_edit_page, $g_worklog_delete_page;
           PRINT "<p align=\"right\"><span  class=\"small\">";
           print_bracket_link( $g_worklog_edit_page . "&f_id=$v_id", lang_get( 'bugnote_edit_link' ) );
           print_bracket_link( $g_worklog_delete_page . "&f_id=$v_id", lang_get( 'delete_link' ) );
           PRINT "</span></p>";
        }
?>
	</td>
</tr>
</table>
</div>
<?php
	}  # end for loop
?>

<p>
<div align="center">
	<?php
	      global $g_worklog_menu_page;
		  if (OFF == plugin_config_get('worklog_view_window') ){
			print_bracket_link( $g_worklog_menu_page, lang_get( 'back_link' ) );
		  }
    ?>
</div>

<?php
html_page_bottom1();
