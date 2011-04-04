<?php
/**
 * Joomla! 1.5 component Crosswords
 *
 * @version $Id: helper.php 2010-10-16 12:32:21 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Crosswords
 * @license GNU/GPL
 *
 * Crosswords is a Joomla component to generate crosswords with Community touch.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Crosswords Helper
 *
 * @package Joomla
 * @subpackage Crosswords
 * @since 1.5
 */
class CrosswordsHelper {
	function &get_configuration($rebuild=false){
        $app = &JFactory::getApplication();
        $cwConfig = $app->getUserState( SESSION_CONFIG );

        if(!isset($cwConfig) || $rebuild) {
            $db     =& JFactory::getDBO();
            $query = 'SELECT config_name, config_value FROM '. TABLE_CROSSWORDS_CONFIG;
            $db->setQuery($query);
            $configt = $db->loadObjectList();

            if($configt) {
                foreach($configt as $ct) {
                    $cwConfig[$ct->config_name] = $ct->config_value;
                }
            }else {
                JError::raiseError( 403, JText::_('Access Forbidden. Error code: 10001') );
                return;
            }
            $app->setUserState( SESSION_CONFIG, $cwConfig );
        }

        return $cwConfig;
	}

    function awardPoints($userid, $function, $referrence, $info){
        $app = &JFactory::getApplication();
        $cwConfig = &CrosswordsHelper::get_configuration();
        if(strcasecmp($cwConfig[POINTS_SYSTEM], COMPONENT_AUP) == 0) {
            $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
            if ( file_exists($api_AUP)){
                require_once ($api_AUP);
                $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $userid );
                if ( $aupid ){
                    switch ($function){
                        case 1: //New Question
                            AlphaUserPointsHelper::newpoints( AUP_NEW_QUESTION, $aupid, $referrence, $info );
                            break;
                        case 2: // Solved crossword
                            AlphaUserPointsHelper::newpoints( AUP_SOLVE_CROSSWORD, $aupid, $referrence, $info );
                            break;
                    }
                }
            }
        }else if(strcasecmp($cwConfig[POINTS_SYSTEM], COMPONENT_JOMSOCIAL) == 0) {
            include_once( JPATH_SITE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php');
            switch ($function){
                case 1: //New Question
                     CuserPoints::assignPoint(JSP_NEW_QUESTION, $userid);
                    break;
                case 2: // New Answer
                    CuserPoints::assignPoint(JSP_SOLVED_CROSSWORD, $userid);
                    break;
            }
        }else if(strcasecmp($cwConfig[POINTS_SYSTEM], COMPONENT_TOUCH) == 0) {
            $API = JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'api.php';
            if(file_exists($API)){
                require_once $API;
                switch ($function){
                    case 1: //New Question
                        JSCommunityApi::increaseKarma($userid, $cwConfig[TOUCH_POINTS_NEW_QUESTION]);
                        break;
                    case 2: // New Answer
                        JSCommunityApi::increaseKarma($userid, $cwConfig[TOUCH_POINTS_SOLVED_CROSSWORD]);
                        break;
                }
            }
        }

    }
	
	/**
	 * Loads the list of user groups in a select many box.
	 * 
	 * @param $id Id to be generated for the select box, which can be referred using javascript or style. 
	 * @param $name Name of the select box to be rendered through which values can be retrieved for saving groups selected.
	 * @param $value List of user groups which needs to be selected by default. 
	 */
    function usersGroups($id, $name, $value) {
        $acl	=& JFactory::getACL();
        $gtree	= $acl->get_group_children_tree( null, 'USERS', false );
        $attribs	= ' ';
        $attribs	.= 'size="'.count($gtree).'"';
        $attribs	.= 'class="inputbox"';
        $attribs	.= 'multiple="multiple"';

        return JHTML::_('select.genericlist', $gtree, $name, $attribs, 'value', 'text', $value, $id );
    }
}
?>