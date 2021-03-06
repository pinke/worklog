<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
$f_promote_text = gpc_get_int( 'promote_text', ON );
$f_project_text = gpc_get_int( 'project_text', ON );
$f_promote_threshold = gpc_get_string( 'promote_threshold', DEVELOPER );
$f_worklog_view_check = gpc_get_int( 'worklog_view_check', OFF );
$f_worklog_view_threshold = gpc_get_string( 'worklog_view_threshold', VIEWER );
$f_worklog_view_window = gpc_get_int( 'worklog_view_window', OFF );

plugin_config_set( 'promote_text', $f_promote_text );
plugin_config_set( 'project_text', $f_project_text );
plugin_config_set( 'promote_threshold', $f_promote_threshold );
plugin_config_set( 'worklog_view_window', $f_worklog_view_window );
plugin_config_set( 'worklog_view_check', $f_worklog_view_check );
plugin_config_set( 'worklog_view_threshold', $f_worklog_view_threshold );

print_successful_redirect( plugin_page( 'config',TRUE ) );