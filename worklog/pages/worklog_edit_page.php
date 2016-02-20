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

$f_id = gpc_get_int( 'f_id' );
	# Retrieve faq item data and prefix with v_
	$row = worklog_select_query( $f_id );
	if ( $row ) {
    	extract( $row, EXTR_PREFIX_ALL, "v" );
    }

   	$v_subject = string_attribute( $v_subject );
   	$v_content 	= string_textarea( $v_content );
?>

<?php # Edit faq Form BEGIN ?>
<p>
<div align="center">
<form method="post" action="<?php global $g_worklog_update; echo $g_worklog_update; ?>">
<input type="hidden" name="f_id" value="<?php echo $v_id ?>">
<table class="width75" cellspacing="1">
<tr>
	<td class="form-title">
		<?php echo plugin_lang_get( 'edit_worklog_title' ) ?>
	</td>
	<td class="right">
		<?php
		if (OFF == plugin_config_get('worklog_view_window') ){
			print_bracket_link( $g_worklog_menu_page, lang_get( 'go_back' ) );
		}
		?>
	</td>
</tr>

	<tr class="row-1">
		<td class="category" width="25%">
			<?php echo plugin_lang_get( 'log_type' ) ?>
		</td>
		<td width="75%">
			<select name="log_type">
				<option value="0" <?php echo $v_log_type==0?' selected=selectd ':'' ?>><?php echo plugin_lang_get('log_type_day')?></option>
				<option value="1" <?php echo $v_log_type==1?' selected=selectd ':'' ?> ><?php echo plugin_lang_get('log_type_week')?></option>
				<option value="2" <?php echo $v_log_type==2?' selected=selectd ':'' ?> ><?php echo plugin_lang_get('log_type_month')?></option>
				<option value="3" <?php echo $v_log_type==3?' selected=selectd ':'' ?> ><?php echo plugin_lang_get('log_type_year')?></option>
			</select>
		</td>
	</tr>

	<tr class="row-1">
		<td class="category" width="25%">
			<?php echo plugin_lang_get( 'log_begin_end' ) ?>
		</td>
		<td width="75%">
			<input type="text" name="log_begin"  value="<?php echo $v_log_begin ?>"> -  <input type="text" name="log_end" value="<?php echo $v_log_end ?>" >
		</td>
	</tr>

	<tr class="row-1">
	<td class="category" width="25%">
		<?php echo plugin_lang_get( 'subject' ) ?>
	</td>
	<td width="75%">
		<input type="text" name="subject" size="80" maxlength="255" value="<?php echo $v_subject ?>">
	</td>
</tr>
<tr class="row-2">
	<td class="category">
		<?php echo plugin_lang_get( 'content' ) ?>
	</td>
	<td>
		<textarea name="content" cols="80" rows="10" wrap="virtual"><?php echo $v_content ?></textarea>
	</td>
</tr>


	<tr class="row-1" style="display: none">
		<td class="category" width="25%">
			<?php echo plugin_lang_get( 'ref_log' ) ?>
		</td>
		<td width="75%">
			<input type="text" name="ref_log_ids" value="<?php echo $v_ref_log_ids ?>" size="80" maxlength="255">
		</td>
	</tr>
	<tr class="row-1" style="display: none">
		<td class="category" width="25%">
			<?php echo plugin_lang_get( 'ref_issues' ) ?>
		</td>
		<td width="75%">
			<input type="text" name="ref_issue_ids"  value="<?php echo $v_ref_issue_ids ?>" size="80" maxlength="255">
		</td>
	</tr>
<?php if (ON == plugin_config_get('worklog_view_check') ){ ?>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo plugin_lang_get( 'worklog_view_threshold' ) ?>
	</td>
	<td >

			<select name="worklog_view_threshold">
			<?php print_enum_string_option_list( 'access_levels', $v_view_access ) ?>;
			</select>

	</td>
</tr>
<?php } ?>





<tr class="row-1">
	<td class="category">
		<?php echo lang_get( 'post_to' ) ?>
	</td>
	<td>
		<select name="project_id">
		<?php
			$t_sitewide = false;
			if ( access_has_project_level( MANAGER ) ) {
				$t_sitewide = true;
			}
			print_project_option_list( $v_project_id, $t_sitewide );
		?>
		</select>
	</td>
</tr>
<tr>
	<td class="center" colspan="2">
		<input type="submit" value="<?php echo plugin_lang_get( 'update_worklog_button' ) ?>">
	</td>
</tr>
</table>
</form>
</div>
<?php # Edit faq Form END ?>

<?php
html_page_bottom1();