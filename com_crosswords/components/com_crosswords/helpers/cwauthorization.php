<?php
/**
 * Joomla! 1.5 component Community Crosswords
 *
 * @version $Id: CWAuthorization.php 2010-08-10 03:45:15 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Community Crosswords
 * @license GNU/GPL
 *
 * Crosswords is a Joomla component to generate crosswords with Community touch.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CWAuthorization {

	function CWAuthorization($config) {
		global $option;
		$auth =& JFactory::getACL();
		$app = &JFactory::getApplication();
		$db = &JFactory::getDBO();

		// Component access
		if(!empty($config[PERMISSION_ACCESS])){
			$query = "select name from #__core_acl_aro_groups where id in(". $config[PERMISSION_ACCESS] . ")";
			$db->setQuery($query);
			$groups = $db->loadObjectList();
			if($groups){
				foreach($groups as $group){
					$auth->addACL( $option, 'access', 'users', $group->name, 'crosswords', 'all' );
				}
			}
		}

		// Create crosswords
		if(!empty($config[PERMISSION_CREATE])){
			$query = "select name from #__core_acl_aro_groups where id in(". $config[PERMISSION_CREATE] . ")";
			$db->setQuery($query);
			$groups = $db->loadObjectList();
			if($groups){
				foreach($groups as $group){
					$auth->addACL( $option, 'create', 'users', $group->name, 'crosswords', 'all' );
				}
			}
		}

		// Create crosswords
		if(!empty($config[PERMISSION_SUBMIT_WORDS])){
			$query = "select name from #__core_acl_aro_groups where id in(". $config[PERMISSION_SUBMIT_WORDS] . ")";
			$db->setQuery($query);
			$groups = $db->loadObjectList();
			if($groups){
				foreach($groups as $group){
					$auth->addACL( $option, 'words', 'users', $group->name, 'crosswords', 'all' );
				}
			}
		}
		
		// Manage crosswords
        $auth->addACL( $option, 'manage', 'users', 'super administrator', 'crosswords', 'all' );
        $auth->addACL( $option, 'manage', 'users', 'administrator', 'crosswords', 'all' );
        $auth->addACL( $option, 'manage', 'users', 'manager', 'crosswords', 'all' );
	}

	function authorize($component,$action,$section,$scope){
		$app = &JFactory::getApplication();
		$user = &JFactory::getUser();
		$cwConfig = $app->getUserState(SESSION_CONFIG);
		if($user->guest){
			if(strcmp($action, 'access') == 0 && $cwConfig[PERMISSION_GUEST_ACCESS]){
				return true;
			}else{
				return false;
			}
		}else{
			return $user->authorize($component,$action,$section,$scope);
		}
	}
}