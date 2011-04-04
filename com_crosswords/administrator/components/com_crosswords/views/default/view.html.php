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

// Import Joomla! libraries
jimport( 'joomla.application.component.view');
class CrosswordsViewDefault extends JView {
	function display($tpl = null) {
		$model = &$this->getModel();
		switch ($this->getLayout()){
			case 'cpanel':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_CPANEL').']</small></small>', 'crosswords.png');
				break;
			case 'crosswords':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_CROSSWORDS').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'publish_crosswords', 'publish.png', 'publish.png', JText::_('LBL_PUBLISH'), true, false );
				JToolBarHelper::custom( 'unpublish_crosswords', 'unpublish.png', 'unpublish.png', JText::_('LBL_UNPUBLISH'), true, false );
				JToolBarHelper::custom( 'delete_crosswords', 'delete.png', 'delete.png', JText::_('LBL_DELETE'), true, false );
				$result = &$model->get_crosswords();
				$this->assignRef('crosswords', $result->crosswords);
				$this->assignRef('users', $result->users);
				$this->assignRef('categories', $result->categories);
				$this->assignRef('lists', $result->lists);
				$this->assignRef('pagination', $result->pagination);
				break;
			case 'keyword_uses':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_CROSSWORDS').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'publish_crosswords', 'publish.png', 'publish.png', JText::_('LBL_PUBLISH'), true, false );
				JToolBarHelper::custom( 'unpublish_crosswords', 'unpublish.png', 'unpublish.png', JText::_('LBL_UNPUBLISH'), true, false );
				JToolBarHelper::custom( 'delete_crosswords', 'delete.png', 'delete.png', JText::_('LBL_DELETE'), true, false );
				$keyid = JRequest::getVar('keyid', 0, '', 'int');
				$ids = &$model->get_keyword_uses($keyid);
				if($ids){
					$ids = implode(",", $ids);
					$result = &$model->get_crosswords($ids);
					$this->assignRef('crosswords', $result->crosswords);
					$this->assignRef('users', $result->users);
					$this->assignRef('categories', $result->categories);
					$this->assignRef('lists', $result->lists);
					$this->assignRef('pagination', $result->pagination);
				}
				$this->setLayout('crosswords');
				break;
			case 'keywords':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_KEYWORDS').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'publish_keywords', 'publish.png', 'publish.png', JText::_('LBL_PUBLISH'), true, false );
				JToolBarHelper::custom( 'unpublish_keywords', 'unpublish.png', 'unpublish.png', JText::_('LBL_UNPUBLISH'), true, false );
				JToolBarHelper::custom( 'delete_keywords', 'delete.png', 'delete.png', JText::_('LBL_DELETE'), true, false );
				$result = &$model->get_keywords();
				$this->assignRef('keywords', $result->keywords);
				$this->assignRef('users', $result->users);
				$this->assignRef('categories', $result->categories);
				$this->assignRef('lists', $result->lists);
				$this->assignRef('pagination', $result->pagination);
				break;
			case 'categories':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_CATEGORIES').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'publish_categories', 'publish.png', 'publish.png', JText::_('LBL_PUBLISH'), true, false );
				JToolBarHelper::custom( 'unpublish_categories', 'unpublish.png', 'unpublish.png', JText::_('LBL_UNPUBLISH'), true, false );
				JToolBarHelper::custom( 'edit_category', 'new.png', 'new.png', JText::_('LBL_NEW'), false, false );
				$categories = &$model->get_categories();
				$this->assignRef('categories', $categories);
				break;
			case 'config':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_CONFIG').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'save_config', 'save.png', 'save.png', JText::_('LBL_SAVE'), false, false );
				$config = &CrosswordsHelper::get_configuration(true);
				$this->assignRef('config', $config);
				break;
			case 'about':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_ABOUT').']</small></small>', 'crosswords.png');
				break;
			case 'crossword_form':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_EDIT_CROSSWORD').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'save_crossword', 'save.png', 'save.png', JText::_('LBL_SAVE'), false, false );
				JToolBarHelper::custom( 'crosswords', 'cancel.png', 'cancel.png', JText::_('LBL_CANCEL'), false, false );
				$id = JRequest::getVar('cid',0,'','int');
				$crossword = new stdClass();
				if($id){
					$crossword = $model->get_crossword($id);
				}
				$categories = &$model->get_categories();
				$this->assignRef('crossword', $crossword);
				$this->assignRef('categories', $categories);
				break;
			case 'keyword_form':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_EDIT_KEYWORD').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'save_keyword', 'save.png', 'save.png', JText::_('LBL_SAVE'), false, false );
				JToolBarHelper::custom( 'keywords', 'cancel.png', 'cancel.png', JText::_('LBL_CANCEL'), false, false );
				$id = JRequest::getVar('cid',0,'','int');
				$keyword = new stdClass();
				if($id){
					$keyword = $model->get_keyword($id);
				}
				$categories = &$model->get_categories(); 
				$this->assignRef('keyword', $keyword);
				$this->assignRef('categories', $categories);
				break;
			case 'category_form':
				JToolBarHelper::title(JText::_('TITLE_COMMUNITY_CROSSWORDS').':&nbsp;<small><small>['.JText::_('LBL_EDIT_CATEGORY').']</small></small>', 'crosswords.png');
				JToolBarHelper::custom( 'save_category', 'save.png', 'save.png', JText::_('LBL_SAVE'), false, false );
				JToolBarHelper::custom( 'categories', 'cancel.png', 'cancel.png', JText::_('LBL_CANCEL'), false, false );
				$id = JRequest::getVar('cid',0,'','int');
				$category = new stdClass();
				if($id){
					$category = $model->get_category($id);
				}
				$this->assignRef('category', $category);
				break;
		}
		parent::display($tpl);
	}
}
?>