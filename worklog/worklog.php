<?php
class worklogPlugin extends MantisPlugin {

  function register() {
    $this->name        = 'worklog';
    $this->description = 'Write day,ork log MantisBT installation.';
    $this->version     = '0.1';
    $this->requires    = array('MantisCore' => '1.3.0',);
    $this->author      = 'pinke';
    $this->contact     = 'xpinke@qq.com';
    $this->url         = 'https://github.com/pinke/worklog';
    $this->page        = 'config';
  }

  /**
   * Default plugin configuration.
   */
  function config() {
    return array(
      'promote_text'       => ON,
      'promote_threshold'  => 55,
      'project_text'       => ON,
      'worklog_view_window'    => OFF,
      'worklog_view_check'     => OFF,
      'worklog_view_threshold' => 10,
    );
  }

  function init() {
    plugin_event_hook( 'EVENT_MENU_MAIN', 'mainmenu' );
    plugin_event_hook( 'EVENT_MENU_ISSUE', 'worklogmenu' );
  }

  function mainmenu() {
    return array( '<a href="'. plugin_page( 'worklog_menu_page.php' ) . '">' . lang_get( 'menu_worklog_link' ) . '</a>' );
  }

  function worklogmenu() {
    if (ON == plugin_config_get( 'promote_text' ) ){
      $bugid = gpc_get_int( 'id' );
      if ( access_has_bug_level( plugin_config_get( 'promote_threshold' ), $bugid ) ){
        $t_bug_p = bug_get( $bugid, true );
        if ( OFF == plugin_config_get( 'project_text' ) ) {
          $proj_id = 0;
        } else {
          $proj_id = $t_bug_p->project_id;
        }

        $subject = urlencode( $t_bug_p->description );
        $subject .= " ";
        $subject .= urlencode( $t_bug_p->additional_information );

        $content = category_full_name( $t_bug_p->category_id );
        $content .= " -> ";
        $content .= urlencode( $t_bug_p->summary );

        if ( ON == plugin_config_get( 'worklog_view_check') ){
          $import_page = 'worklog_add_page2.php';
        } else {
          $import_page = 'worklog_add.php';
        }
        $import_page .= '&content=';
        $import_page .= $content;
        $import_page .= '&subject=';
        $import_page .= $subject;
        $import_page .= '&project_id=';
        $import_page .= $proj_id;

        if (ON == plugin_config_get('worklog_view_check') ){
          return array( plugin_lang_get( 'import_worklog' ) => plugin_page( $import_page ). '" target=_new>' );
        } else {
          return array( plugin_lang_get( 'import_worklog' ) => plugin_page( $import_page ) );
        }
      }
    }
  }

  function schema() {
    return array(
      array( 'CreateTableSQL', array( plugin_table( 'results' ), "
        id				I		NOTNULL UNSIGNED ZEROFILL AUTOINCREMENT PRIMARY,
        log_type		I		NOTNULL UNSIGNED ZEROFILL ,
        project_id		I		NOTNULL UNSIGNED ZEROFILL ,
        poster_id		I		NOTNULL UNSIGNED ZEROFILL ,
        log_begin		T		NOTNULL,
        log_end		T		NOTNULL,
        date_posted		T		NOTNULL,
        last_modified	T		NOTNULL,
        ref_issue_ids		C(150)	DEFAULT \" '' \",
        ref_log_ids		C(150)	DEFAULT \" '' \",
        subject		C(50)	DEFAULT \" '' \",
        content		XL	DEFAULT \" '' \",
        view_access		I		NOTNULL UNSIGNED ZEROFILL DEFAULT \" '10' \"
        visits_count		I		NOTNULL UNSIGNED ZEROFILL DEFAULT \" '0' \"
        good_count		I		NOTNULL UNSIGNED ZEROFILL DEFAULT \" '0' \"
        bad_count		I		NOTNULL UNSIGNED ZEROFILL DEFAULT \" '0' \"
        " )
      ),
    );
  }
}