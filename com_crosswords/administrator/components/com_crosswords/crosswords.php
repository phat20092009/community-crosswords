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
global $option;

require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );
require_once( JPATH_ROOT.DS.'components'.DS.$option.DS.'helpers'.DS.'constants.php' );

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Require the base controller
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

// Initialize the controller
$controller = new CrosswordsController( );

$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::base(true)."/components/com_crosswords/assets/css/crosswords.css");

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>