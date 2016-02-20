<?php
require( "worklog_api.php" );
require( "css_worklog.php" );
html_page_top1();
if (OFF == plugin_config_get('worklog_view_window') ){
  html_page_top2();
}
$f_id = gpc_get_int( 'f_id' );
?>
<p>
<div align="center">
	<?php print_hr( $g_hr_size, $g_hr_width ) ?>
	<?php echo plugin_lang_get( 'delete_worklog_sure_msg' ) . "<br>" ?>

	<form method="post" action="<?php echo $g_worklog_delete ?>">
		<input type="hidden" name="f_id" value="<?php echo $f_id ?>">
		<input type="submit" value="<?php echo plugin_lang_get( 'delete_worklog_item_button' ) ?>">
	</form>

	<?php print_hr( $g_hr_size, $g_hr_width ) ?>
</div>

<?php
html_page_bottom1();