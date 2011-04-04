<?php
/**
 * Joomla! 1.5 component Crosswords
 *
 * @version $Id: controller.php 2010-10-16 12:32:21 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Crosswords
 * @license GNU/GPL
 *
 * Crosswords is a Joomla component to generate crosswords with Community touch.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Crosswords Component Controller
 */
class CrosswordsController extends JController {
    function __construct() {
        parent::__construct();
        $this->registerDefaultTask('get_crossword_list');
        $this->registerTask('list','get_crossword_list');
        $this->registerTask('view','get_crossword_details');
        $this->registerTask("user", "get_user_crosswords");
        $this->registerTask("create", "create_new_crossword");
        $this->registerTask("keyword", "submit_keyword");
        $this->registerTask("check_result", "check_crossword_result");
    }

    function get_crossword_list() {
        $view = &$this->getView('crosswords', 'html');
        $model = &$this->getModel('crosswords');
        $view->setModel($model, true);
        $view->assign('action', 'crossword_list');
        $view->display();
    }

    function get_user_crosswords(){
        $view = &$this->getView('crosswords', 'html');
        $model = &$this->getModel('crosswords');
        $view->setModel($model, true);
        $view->assign('action', 'user_crosswords');
        $view->display();
    }
    
    function get_crossword_details() {
        $view = &$this->getView('crosswords', 'html');
        $model = &$this->getModel('crosswords');
        $view->setModel($model, true);
        $view->assign('action', 'crossword_details');
        $view->display();
    }

    function create_new_crossword() {
    	global $option;
    	if(!CWAuthorization::authorize($option,'create','crosswords','all')){
    		JError::raiseError( 500, 'Unauthorised usage. Error code: 10050.' );
    	}
        $view = &$this->getView('crosswords', 'html');
        $model = &$this->getModel('crosswords');
        $view->setModel($model, true);
        $view->assign('action', 'create_new_crossword');
        $view->display();
    }

    function submit_keyword() {
        global $option;
        if(!CWAuthorization::authorize($option,'words','crosswords','all')) {
            echo json_encode(array('error'=>JText::_('MSG_NOT_AUTHORIZED')));
        }else {
            $model = &$this->getModel('crosswords');
            $keyword = $model->save_keyword();
            if($keyword) {
            	$app = &JFactory::getApplication();
            	$cwConfig = $app->getUserState( SESSION_CONFIG );
            	if($cwConfig[STREAM_NEW_QUESTION] == '1'){
            		CrosswordsHelper::streamActivity(1, $obj);
            	}
                echo json_encode(array('message'=>JText::_('MSG_QUESTION_SUBMITTED')));
            }else {
                echo json_encode(array('error'=>$model->getError()));
            }
        }
        jexit();
    }
    
    function check_crossword_result(){
        global $option;
        if(!CWAuthorization::authorize($option,'access','crosswords','all')) {
            echo json_encode(array('error'=>JText::_('MSG_NOT_AUTHORIZED')));
        }else {
            $model = &$this->getModel('crosswords');
            $failed = $model->check_result();
            if($failed === false){
            	echo json_encode(array('error'=>$model->getError()));
            }else if(empty($failed)) {
            	$cid = JRequest::getVar("id", 0, "post", "int");
            	$crossword = &$model->get_crossword($cid, false);
            	CrosswordsHelper::awardPoints($user->id, 2, $crossword, JText::_('TXT_AWARD_POINTS_SOLVED_CROSSWORD'));
            	
            	$app = &JFactory::getApplication();
            	$cwConfig = $app->getUserState( SESSION_CONFIG );
            	if($cwConfig[STREAM_SOLVED_CROSSWORD] == '1'){
            		CrosswordsHelper::streamActivity(2, $crossword);
            	}
                echo json_encode(array('message'=>JText::_('MSG_CROSSWORD_SOLVED')));
            }else {
                echo json_encode(array('failed'=>$failed));
            }
        }
        jexit();
    }
}
?>