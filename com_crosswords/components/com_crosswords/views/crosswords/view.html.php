<?php
/**
 * Joomla! 1.5 component Crosswords
 *
 * @version $Id: view.html.php 2010-10-16 12:32:21 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Crosswords
 * @license GNU/GPL
 *
 * Crosswords is a Joomla component to generate crosswords with Community touch.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Crosswords component
 */
class CrosswordsViewCrosswords extends JView {
    function display($tpl = null) {
        global $option;
        $app = & JFactory::getApplication ();
        $cwConfig = &CrosswordsHelper::getConfig ();
        $user = & JFactory::getUser ();
        $menu = &JSite::getMenu ();
        $document = &JFactory::getDocument();

        $mnuitems = $menu->getItems ( 'link', 'index.php?option=' . $option . '&view=crosswords' );
        $itemid = isset ( $mnuitems [0]->id ) ? $mnuitems [0]->id : JRequest::getVar ( 'itemid' );
        $itemid = ($itemid) ? '&amp;Itemid=' . $itemid : 0;
        
        //$document->addScript('https://getfirebug.com/firebug-lite.js');
        
        JPluginHelper::importPlugin( 'corejoomla' );
        $dispatcher =& JDispatcher::getInstance();
        $dispatcher->trigger('onCallIncludeJQuery', array(array("jquery","jqueryui","jqueryform")));

        $model = &$this->getModel("crosswords");
        switch ($this->action){
            case "crossword_list":
            	$result = &$model->get_crosswords_list();
                $categories = &$model->get_categories();
                $this->assignRef("crosswords", $result->crosswords);
                $this->assignRef("pagination", $result->pagination);
                $this->assignRef("categories", $categories);
                break;
            case "user_crosswords":
            	$result = &$model->get_crosswords_list($user->id);
                $categories = &$model->get_categories();
                $this->assignRef("crosswords", $result->crosswords);
                $this->assignRef("pagination", $result->pagination);
                $this->assignRef("categories", $categories);
                $this->action = "crossword_list";
                break;
			case "create_new_crossword":
                $crossword = &$model->create_crossword();
                if($model->getError()){
                	JFactory::getApplication()->enqueueMessage( $model->getError() );
                }
                $this->assignRef("crossword", $crossword);
                $this->action = "crossword_details";
                break;
            case "crossword_details":
            	$cid = JRequest::getInt("id");
            	$crossword = &$model->get_crossword($cid);
            	$this->assignRef("crossword", $crossword);
                break;
        }
        $this->assignRef("layoutPath", $this->action);
        parent::display($tpl);
    }
}
?>