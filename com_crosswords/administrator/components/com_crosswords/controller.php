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

jimport( 'joomla.application.component.controller' );

/**
 * Crosswords Controller
 *
 * @package Joomla
 * @subpackage Crosswords
 */
class CrosswordsController extends JController {
    /**
     * Constructor
     * @access private
     * @subpackage Crosswords
     */
    function __construct() {
        parent::__construct();
        $this->registerDefaultTask('show_cpanel');
		$this->registerTask('crosswords','get_crosswords');
		$this->registerTask('keywords','get_keywords');
		$this->registerTask('categories','get_categories');
		$this->registerTask('config','get_config');
		$this->registerTask('about','show_about');
		$this->registerTask('edit_crossword','edit_crossword');
		$this->registerTask('edit_keyword','edit_keyword');
		$this->registerTask('edit_category','edit_category');
		$this->registerTask('save_crossword','save_crossword');
		$this->registerTask('save_keyword','save_keyword');
		$this->registerTask('save_category','save_category');
		$this->registerTask('save_config','save_config');
		$this->registerTask('publish_crosswords','publish_crosswords');
		$this->registerTask('unpublish_crosswords','unpublish_crosswords');
		$this->registerTask('publish_keywords','publish_keywords');
		$this->registerTask('unpublish_keywords','unpublish_keywords');
		$this->registerTask('publish_categories','publish_categories');
		$this->registerTask('unpublish_categories','unpublish_categories');
		$this->registerTask('delete_keywords','delete_keywords');
		$this->registerTask('delete_crosswords','delete_crosswords');
		$this->registerTask('keywords_uses','get_keywords_uses');
		$this->registerTask('cancel','cancel');
    }
    
    function show_cpanel(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('cpanel');
		$view->setModel($model, true);
		$view->display();
    }
    
    function get_crosswords(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('crosswords');
		$view->setModel($model, true);
		$view->display();
    }
    
    function get_keywords(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('keywords');
		$view->setModel($model, true);
		$view->display();
    }
    
    function get_categories(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('categories');
		$view->setModel($model, true);
		$view->display();
    }
    
    function get_config(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('config');
		$view->setModel($model, true);
		$view->display();
    }
    
    function show_about(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('about');
		$view->setModel($model, true);
		$view->display();
    }

    function edit_crossword(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('crossword_form');
		$view->setModel($model, true);
		$view->display();
    }
    
    function edit_keyword(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('keyword_form');
		$view->setModel($model, true);
		$view->display();
    }
    
    function edit_category(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('category_form');
		$view->setModel($model, true);
		$view->display();
    }

    function save_crossword(){
    	global $option;
		$model = &$this->getModel('crosswords');
		if(!$model->save_crossword()){
			$this->setRedirect('index.php?option='.$option.'&task=crosswords', JText::_('MSG_ERROR_PROCESSING'));
		}else{
			$this->setRedirect('index.php?option='.$option.'&task=crosswords', JText::_('MSG_COMPLETED'));
		}
    }
    
    function save_keyword(){
    	global $option;
		$model = &$this->getModel('crosswords');
		if(!$model->save_keyword()){
			$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_ERROR_PROCESSING'));
		}else{
			$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_COMPLETED'));
		}
    }
    
    function save_category(){
    	global $option;
		$model = &$this->getModel('crosswords');
		if(!$model->save_category()){
			$this->setRedirect('index.php?option='.$option.'&task=categories', JText::_('MSG_ERROR_PROCESSING'));
		}else{
			$this->setRedirect('index.php?option='.$option.'&task=categories', JText::_('MSG_COMPLETED'));
		}
    }
    
    function save_config(){
    	global $option;
		$model = &$this->getModel('crosswords');
		if(!$model->save_configuration()){
			$this->setRedirect('index.php?option='.$option.'&task=config', JText::_('MSG_ERROR_PROCESSING'));
		}else{
			$this->setRedirect('index.php?option='.$option.'&task=config', JText::_('MSG_CONFIG_SAVED'));
		}
    }
    
	function publish_crosswords(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=crosswords', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			$model->set_crosswords_status($id, "published", true);
			$this->setRedirect('index.php?option='.$option.'&task=crosswords', JText::_('MSG_COMPLETED'));
		}
	}

	function unpublish_crosswords(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=crosswords', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			$model->set_crosswords_status($id, "published", false);
			$this->setRedirect('index.php?option='.$option.'&task=crosswords', JText::_('MSG_COMPLETED'));
		}
	}

	function publish_keywords(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			if($model->set_keywords_status($id, "published", true)){
				$keywords = &$model->get_keyword_submitters($id);
				foreach ($keywords as $keyword){
					CrosswordsHelper::awardPoints($keyword->created_by, 1, $keyword->id, JText::_('TXT_AWARD_POINTS_NEW_QUESTION'));
				}
			}
			$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_COMPLETED'));
		}
	}

	function unpublish_keywords(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			$model->set_keywords_status($id, "published", false);
			$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_COMPLETED'));
		}
	}

	function publish_categories(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=categories', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			$model->set_categories_status($id, "published", true);
			$this->setRedirect('index.php?option='.$option.'&task=categories', JText::_('MSG_COMPLETED'));
		}
	}

	function unpublish_categories(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=categories', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			$model->set_categories_status($id, "published", false);
			$this->setRedirect('index.php?option='.$option.'&task=categories', JText::_('MSG_COMPLETED'));
		}
	}
	
	function delete_keywords(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			if(!$model->delete_keywords($id)){
				$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_ERROR_PROCESSING').$model->getError());
			}else{
				$this->setRedirect('index.php?option='.$option.'&task=keywords', JText::_('MSG_COMPLETED'));
			}
		}
	}
	
	function delete_crosswords(){
		global $option;
		$ids = JRequest::getVar("cid",array(),"","array");
		JArrayHelper::toInteger($ids);
		if(empty($ids)){
			$this->setRedirect('index.php?option='.$option.'&task=crosswords', JText::_('MSG_INVALID_ID'));
		}else{
			$id = implode(',', $ids);
			$model = &$this->getModel('crosswords');
			$model->delete_crosswords($id);
			$task = JRequest::getCmd('return');
			$keyid = JRequest::getInt('keyid');
			$this->setRedirect('index.php?option='.$option.'&task='.$task.($keyid ? '&keyid='.$keyid : ''), JText::_('MSG_COMPLETED'));
		}
	}
	
	function get_keywords_uses(){
		$view = &$this->getView('default', 'html');
		$model = &$this->getModel('crosswords');
		$view->setLayout('keyword_uses');
		$view->setModel($model, true);
		$view->display();
	}
	
	function cancel(){
    	global $option;
    	$task = JRequest::getCmd('task');
		$this->setRedirect('index.php?option='.$option.'&task='.$task, JText::_('MSG_OPERATION_CANCELLED'));
	}
}
?>