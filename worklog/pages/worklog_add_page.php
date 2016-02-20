<?php
require( "worklog_api.php" );
require( "css_worklog.php" );
html_page_top1();
html_page_top2();
?>
<p>
<div align="center">
<form method="post" action="<?php echo $g_worklog_add2 ?>">
<input type="hidden" name="f_poster_id" value="<?php echo current_user_get_field( "id" ) ?>">
<table class="width75" cellspacing="1">
<tr>
	<td class="form-title" colspan="2">
		<?php echo plugin_lang_get( 'add_worklog_title' ) ?>
	</td>
</tr>
<tr class="row-1">
	<td class="category" width="25%">
		<?php echo plugin_lang_get( 'log_type' ) ?>
	</td>
	<td width="75%">
		<select name="log_type" size="80">
			<option value="0"><?php echo plugin_lang_get('log_type_day')?></option>
			<option value="1"><?php echo plugin_lang_get('log_type_week')?></option>
			<option value="2"><?php echo plugin_lang_get('log_type_month')?></option>
			<option value="3"><?php echo plugin_lang_get('log_type_year')?></option>
			</select>
	</td>
</tr>

<tr class="row-1">
	<td class="category" width="25%">
		<?php echo plugin_lang_get( 'log_begin_end' ) ?>
	</td>
	<td width="75%">
		<input type="text" name="log_begin"  > -  <input type="text" name="log_end" >
	</td>
</tr>

<tr class="row-1">
	<td class="category" width="25%">
		<?php echo plugin_lang_get( 'subject' ) ?>
	</td>
	<td width="75%">
		<input type="text" name="subject" size="80" maxlength="255">
	</td>
</tr>

<tr class="row-2">
	<td class="category">
		<?php echo plugin_lang_get( 'content' ) ?>
	</td>
	<td>
		<textarea name="content" cols="80" rows="10" wrap="virtual"></textarea>
	</td>
</tr>

	<tr class="row-1" style="display: none">
		<td class="category" width="25%">
			<?php echo plugin_lang_get( 'ref_log' ) ?>
		</td>
		<td width="75%">
			<input type="text" name="ref_log_ids" size="80" maxlength="255">
		</td>
	</tr>
	<tr class="row-1" style="display: none">
		<td class="category" width="25%">
			<?php echo plugin_lang_get( 'ref_issues' ) ?>
		</td>
		<td width="75%">
			<input type="text" name="ref_issue_ids" size="80" maxlength="255">
		</td>
	</tr>

<?php if (ON == plugin_config_get('worklog_view_check') ){ ?>

<tr class="row-1">
	<td class="category">
		<?php echo plugin_lang_get( 'worklog_view_threshold' ) ?>
	</td>
	<td>
			<select name="worklog_view_threshold">
			<?php print_enum_string_option_list( 'access_levels',  plugin_config_get( 'worklog_view_threshold') )?>
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
			print_project_option_list( helper_get_current_project(), $t_sitewide );
		?>
		</select>
	</td>
</tr>
<tr>
	<td class="center" colspan="2">
		<input type="submit" value="<?php echo plugin_lang_get( 'post_worklog_button' ) ?>">
	</td>
</tr>
</table>
</form>
</div>
<?php # Add faq Form END ?>

<?php
html_page_bottom1();