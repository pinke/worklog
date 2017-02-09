<?php
require( "worklog_api.php" );

access_ensure_global_level( plugin_config_get( 'worklog_view_threshold' ) );
html_page_top1();
html_page_top2();
# Select the faq posts

$minimum_level = access_get_global_level();
$t_where_clausole = "view_access <= $minimum_level";
if (!isset($_POST['f_all_user']) || !isset($_GET['f_all_user'])) { //allow show all youser
	$t_where_clausole .= " and poster_id = " . current_user_get_field("id");
} else if (!isset($_POST['f_user_id'])) { //show by userId
	$t_where_clausole .= " and poster_id = " . gpc_get_int("poster_id");
}
$p_project_id = helper_get_current_project();

if( $p_project_id != 0 ) {//pk remove filter by project
    $t_where_clausole .= " and ((project_id='".$p_project_id."' OR project_id=0)";
	$t_project_ids = project_hierarchy_get_subprojects( $p_project_id );
	foreach ($t_project_ids as $value) {
		$t_where_clausole .= " or project_id='".$value."'";
	}
	$t_where_clausole .= ")";
}
$f_search = "";
if( !isset($_POST["f_search"] ) ) {
	$f_search = "";
	$f_search3 = "";
	$f_search2 = "";
} else {
    $f_search = $_POST["f_search"];
	$f_search3 = "";
	$f_search2 = "";
    if( $t_where_clausole != "" ){
        $t_where_clausole = $t_where_clausole . " AND ";
	}

	$f_search=trim($f_search);
	$what = " ";
	$pos = strpos($f_search, $what);

	$search_string = $_POST["search_string"];
	if (($pos === false) or (isset( $search_string ))){
		$t_where_clausole = $t_where_clausole . " ( (subject LIKE '%".addslashes($f_search)."%')
				OR (content LIKE '%".addslashes($f_search)."%') ) ";
	} else {
		$pos1 = strpos($f_search, $what, $pos+1);
		if ($pos1 === false) {
			$f_search2 = substr($f_search, $pos);
		} else {
			$len1=$pos1-$pos;
			$f_search2 = substr($f_search, $pos1,$len1);
		}
		$f_search3 = substr($f_search,0, $pos);
		$f_search3=trim($f_search3);
		$f_search2=trim($f_search2);
		$t_where_clausole = $t_where_clausole . " ((subject LIKE '%".addslashes($f_search3)."%') and (subject LIKE '%".addslashes($f_search2)."%'))
					OR ((content LIKE '%".addslashes($f_search3)."%') and (content LIKE '%".addslashes($f_search2)."%')) ";
	}
}

$query = "SELECT id, poster_id, project_id, UNIX_TIMESTAMP(date_posted) as date_posted, subject, content FROM $g_mantis_worklog_table";
if( $t_where_clausole != "" ){
    $query = $query . " WHERE $t_where_clausole";
}

$query = $query . " ORDER BY UPPER(subject) ASC";
$result = db_query_bound( $query );
$worklog_count = db_num_rows( $result );
?>
<p>
<table class="width100" cellspacing="0">
<form method="post" action="<?php echo $g_worklog_menu_page ?>">
<tr class="row-category2">
<td class="small-caption">
<?php PRINT lang_get( 'search'); ?>
</td>
<td class="small-caption">
<?php PRINT ""; ?>
</td>
</tr>
<tr>
<td class="small-caption">
<input type="text" size="25" name="f_search" value="<?php echo $f_search; ?>">
<input  type="checkbox" name="search_string" id="search_string" > <label for="search_string"><?php echo plugin_lang_get( 'search_string' ) ?></label>
</td>
<td class="right">
   <input type="submit" name="f_filter" value="<?php echo lang_get( 'filter_button') ?>">

	<?php
	if ( access_has_project_level( DEVELOPER ) ) {
		global $g_worklog_add_page;
		print_bracket_link( $g_worklog_add_page, plugin_lang_get( 'add_worklog') );
	}
	?>
</td>
</form>
</table>
<table width="100%" cellspacing="0" border="0" cellpadding="0">
<tr>
<td class="small-caption">
<?php
echo $worklog_count . " ";
?>
</td>
<td class="right">
</td>
</tr>
</table>
<ul>
<?php

# Loop through results
if( $f_search == "" ){
    $worklog_count1=15;
	if ($worklog_count==0){
		$worklog_count1=0;
	}
	if ($worklog_count1 > $worklog_count){
		$worklog_count1=$worklog_count;
	}
} else {
    $worklog_count1=$worklog_count;
}

//$v_log_type = plugin_config_get('worklog_type');
$v_log_type = isset($v_log_type) ? $v_log_type : 0;
for ($i=0;$i<$worklog_count1;$i++) {
	$row = db_fetch_array($result);
	extract( $row, EXTR_PREFIX_ALL, "v" );
    $pos = isset($pos) ? $pos : false;
    if(( isset( $search_string )) or ($pos === false)) {
        if($f_search !="" ) {
            $v_subject = str_replace($f_search, "<b>" . $f_search . "</b>", $v_subject);
            $v_content = str_replace($f_search, "<b>" . $f_search . "</b>", $v_content);
        }
    }
    if( $f_search2 != "" )  {
   		$v_subject = str_replace ( $f_search2, "<b>".$f_search2."</b>", $v_subject );
    	$v_content 	= str_replace ( $f_search2, "<b>".$f_search2."</b>", $v_content );
    }
    if( $f_search3 != "" )  {
   		$v_subject = str_replace ( $f_search3, "<b>".$f_search3."</b>", $v_subject );
    	$v_content 	= str_replace ( $f_search3, "<b>".$f_search3."</b>", $v_content );
    }
	$v_subject = string_display( $v_subject );
	$v_content 	= string_display_links( $v_content );
	$v_date_posted = date( $g_complete_date_format, $v_date_posted );

	# grab the username and email of the poster
   	$t_poster_name	= user_get_name($v_poster_id );
	$t_poster_email	= user_get_email($v_poster_id );

    $t_project_name = " ";
	if( $v_project_id != 0 ) {
   		$t_project_name = project_get_field( $v_project_id, "name" );
	}
	$v_content = trim(substr($v_content, 0, 25));
	$v_content .="...";
	if (ON == plugin_config_get('worklog_view_window') ){
		if( helper_get_current_project() == '0000000' ){
			PRINT "<li><span class=\"worklog-subject\">[".worklog_type_display($v_log_type)."]<a href=\"$g_worklog_view_page&f_id=$v_id\" target=_new>$v_subject</a> [$t_project_name] </span><br><span>$v_content</span><br>";
		}else{
			PRINT "<li><span class=\"worklog-subject\">[".worklog_type_display($v_log_type)."]<a href=\"$g_worklog_view_page&f_id=$v_id\" target=_new>$v_subject</a></span><br><span>$v_content</span><br>";
		}
	} else{
		if( helper_get_current_project() == '0000000' ){
			PRINT "<li><span class=\"worklog-subject\">[".worklog_type_display($v_log_type)."]<a href=\"$g_worklog_view_page&f_id=$v_id\" >$v_subject</a> [$t_project_name] </span><br><span>$v_content</span><br>";
		}else{
			PRINT "<li><span class=\"worklog-subject\">[".worklog_type_display($v_log_type)."]<a href=\"$g_worklog_view_page&f_id=$v_id\" >$v_subject</a></span><br><span>$v_content</span><br>";
		}
	}

    PRINT "<span  class=\"small\">$v_date_posted - <a class=\"worklog-email\" href=\"mailto:$t_poster_email\">$t_poster_name</a></span><br><br>";
}  # end for loop
?>
</ul>
<?php
html_page_bottom1();