<?php
/**
 * Joomla! 1.5 component Crosswords
 *
 * @version $Id: crosswords.php 2010-10-16 12:32:21 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Crosswords
 * @license GNU/GPL
 *
 * Crosswords is a Joomla component to generate crosswords with Community touch.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
global $option;

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'constants.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'template.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'cwauthorization.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'Date_Difference.php';

JFactory::getApplication()->set('jquery', true);

if( !JRequest::getVar( 'view' )) {
	JRequest::setVar('view', 'crosswords' );
}

$cwConfig = &CrosswordsHelper::getConfig(true);
$auth = new CWAuthorization($cwConfig);
if(!CWAuthorization::authorize($option, "access", "crosswords", "all")){
	JError::raiseError( 403, JText::_('Access Forbidden. Error Code: 10003.') );
}
// Initialize the controller
$controller = new CrosswordsController();
$controller->execute( JRequest::getCmd("task") );

// Redirect if set by the controller
$controller->redirect();
if($cwConfig[ENABLE_POWERED_BY]){
    echo CrosswordsHelper::getPoweredByLink();
}
?>