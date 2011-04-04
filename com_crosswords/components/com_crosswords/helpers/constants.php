<?php
/**
 * @version		$Id: constants.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla16.Rewards
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');

// Please do not touch these until and unless you know what you are doing.
global $option;
define("TABLE_CROSSWORDS",                      "#__crosswords");
define("TABLE_CROSSWORDS_CONFIG",				"#__crosswords_config");
define("TABLE_CROSSWORDS_KEYWORDS",				"#__crosswords_keywords");
define("TABLE_CROSSWORDS_QUESTIONS",			"#__crosswords_questions");
define("TABLE_CROSSWORDS_RESPONSES",			"#__crosswords_responses");
define("TABLE_CROSSWORDS_RESPONSE_DETAILS",		"#__crosswords_response_details");
define("TABLE_CROSSWORDS_CATEGORIES",			"#__crosswords_categories");

define("DEFAULT_TEMPLATE",              		"default_template");
define("COOKIE_TIME_TO_LIVE",           		"cookie_time_to_live");
define("USER_NAME",                     		"user_name" );
define("USER_AVTAR",                			"user_avatar" );
define("LIST_LIMIT",							"list_limit" );
define("PERMISSION_GUEST_ACCESS",				"permission_guest_access" );
define("PERMISSION_ACCESS",						"permission_access" );
define("PERMISSION_CREATE",						"permission_create" );
define("PERMISSION_SUBMIT_WORDS",				"permission_submit_words" );
define("SENDER_NAME",							"email_sender_name" );
define("SENDER_EMAIL",							"email_sender_email" );
define("POINTS_SYSTEM",							"points_system" );
define("COMMENT_SYSTEM",						"comment_system" );
define("ENABLE_POWERED_BY",						"powered_by_enabled" );
define("TOUCH_POINTS_CW_QUESTION",				"touch_points_cw_newquestion");
define("TOUCH_POINTS_SOLVED_CROSSWORD",			"touch_points_solved_crossword");
define("ACTIVITY_STREAM_TYPE",					"activity_stream_type");
define("STREAM_NEW_QUESTION",					"stream_new_question");
define("STREAM_SOLVED_CROSSWORD",				"stream_solved_crossword");

define("SESSION_CONFIG",                		"crosswords_session_config");
define("DEFAULT_TEMPLATE_PATH",         		JPATH_COMPONENT . DS . "templates" );
define("DEFAULT_TEMPLATE_URL",          		JURI::base(true). "/components/".$option."/templates/" );
define("TEMPLATE_OVERRIDES_PATH",       		JPATH_ROOT . DS . "templates" . DS . "crosswords" );
define("TEMPLATE_OVERRIDES_URL",        		JURI::base(true)."/templates/crosswords/" );

define("COOKIE_PREFIX",							"CWCKRSPNCE");
define("AUP_CREDITS",							"sysplgaup_crosswords_credits");
define("JOMSOCIAL_CREDITS",						"com_crosswords.credits");
define("COMPONENT_JOMSOCIAL",					"jomsocial");
define("COMPONENT_TOUCH",						"touch");
define("COMPONENT_AUP",							"aup");
define("AUP_NEW_QUESTION",						"sysplgaup_submitcwquestion");
define("AUP_SOLVE_CROSSWORD",					"sysplgaup_solvecrossword");
define("JSP_NEW_QUESTION",						"com_crosswords.newquestion");
define("JSP_SOLVED_CROSSWORD",					"com_crosswords.solvedcrossword");
?>