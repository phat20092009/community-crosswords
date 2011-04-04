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

// Import Joomla! libraries
jimport('joomla.application.component.model');

class CrosswordsModelCrosswords extends JModel {
    function __construct() {
		parent::__construct();
    }
    
    function &get_crosswords($ids = 0){
    	global $option;
    	$app = &JFactory::getApplication();
    	$db = &JFactory::getDBO();
    	$cwConfig = &$app->getUserState( SESSION_CONFIG );
    	
    	$filter_order = $app->getUserStateFromRequest( "$option.crosswords.filter_order","filter_order","a.created","cmd" );
    	$filter_order_Dir = $app->getUserStateFromRequest( "$option.crosswords.filter_order_Dir","filter_order_Dir","DESC","word" );
    	$limitstart = $app->getUserStateFromRequest( "$option.crosswords.limitstart",'limitstart','','int' );
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$catid = JRequest::getVar("catid",0,"","int");
    	$userid = JRequest::getVar("uid",0,"","int");
    	$search = JRequest::getVar("search",null,"post","string");
    	
        if($catid){
        	$where[] = "a.catid=".$catid;
        }
        if($userid){
        	$where[] = "a.created_by=".$userid;
        }
        if($search){
        	$where[] = "(a.title like ".$db->quote( "%".$db->getEscaped( $search, true )."%", false ).")";
        }
        if($ids){
        	$where[] = "a.id in (".$ids.")";
        }
        
        $where = ((count($where) > 0) ? " where " . implode(" and ", $where):"");
        $order = " order by " . $filter_order . " " . $filter_order_Dir;
        
        $query = 'select count(*) from ' . TABLE_CROSSWORDS . ' a' . $where;
        $db->setQuery( $query );
        $total = $db->loadResult();
        
        jimport('joomla.html.pagination');
        $pagination = new JPagination( $total, $limitstart, $limit );
        
        $query = 'select a.id, a.title, a.alias, a.catid, a.created_by, a.created, a.questions, a.rows, a.columns, a.published, u.username, u.name, c.title as category'
    		. ' from '.TABLE_CROSSWORDS.' a'
    		. ' left join '.TABLE_CROSSWORDS_CATEGORIES .' c on a.catid=c.id'
    		. ' left join #__users u on a.created_by=u.id'
    		. $where . $order;
    	$db->setQuery($query, $limitstart, $limit);
    	$crosswords = $db->loadObjectList();
    	
    	$query = 'select distinct(a.created_by), u.username, u.name from '.TABLE_CROSSWORDS.' a left join #__users u on a.created_by=u.id where a.created_by>0 order by u.username';
    	$db->setQuery($query);
    	$users = $db->loadObjectList();
    	
        $lists['order'] = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['catid'] = $catid;
        $lists['uid'] = $userid;
        $lists['search'] = $search;
        
        $result->crosswords = $crosswords;
        $result->users = $users;
        $result->categories = &$this->get_categories();
        $result->lists = $lists;
        $result->pagination = $pagination;
        
        return $result;
    }
    
    function &get_keywords(){
    	global $option;
    	$app = &JFactory::getApplication();
    	$db = &JFactory::getDBO();
    	$cwConfig = &$app->getUserState( SESSION_CONFIG );
    	
    	$filter_order = $app->getUserStateFromRequest( "$option.keywords.filter_order","filter_order","a.created","cmd" );
    	$filter_order_Dir = $app->getUserStateFromRequest( "$option.keywords.filter_order_Dir","filter_order_Dir","DESC","word" );
    	$limitstart = $app->getUserStateFromRequest( "$option.keywords.limitstart",'limitstart','','int' );
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$catid = JRequest::getVar("catid",0,"","int");
    	$userid = JRequest::getVar("uid",0,"","int");
    	$search = JRequest::getVar("search",null,"post","string");
    	
        if($catid){
        	$where[] = "a.catid=".$catid;
        }
        if($userid){
        	$where[] = "a.created_by=".$userid;
        }
        if($search){
        	$where[] = "(a.keyword like ".$db->quote( "%".$db->getEscaped( $search, true )."%", false ).
        		" OR a.question like ".$db->quote( "%".$db->getEscaped( $search, true )."%", false ).")";
        }
        
        $where = ((count($where) > 0) ? " where " . implode(" and ", $where):"");
        $order = " order by " . $filter_order . " " . $filter_order_Dir;
        
        $query = 'select count(*) from ' . TABLE_CROSSWORDS_KEYWORDS . ' a' . $where;
        $db->setQuery( $query );
        $total = $db->loadResult();
        
        jimport('joomla.html.pagination');
        $pagination = new JPagination( $total, $limitstart, $limit );
        
        $query = 'select a.id, a.question, a.catid, a.created_by, a.created, a.keyword, a.published, u.username, u.name, c.title as category'
    		. ' from '.TABLE_CROSSWORDS_KEYWORDS.' a'
    		. ' left join '.TABLE_CROSSWORDS_CATEGORIES .' c on a.catid=c.id'
    		. ' left join #__users u on a.created_by=u.id'
    		. $where . $order;
    	$db->setQuery($query, $limitstart, $limit);
    	$keywords = $db->loadObjectList();
    	
    	$ids = array();
    	if($keywords){
    		foreach ($keywords as $keyword){
    			$ids[] = $keyword->id;
    		}
    		$ids = implode(",", $ids);
    		$counts = &$this->get_crossword_counts($ids);
    		if($counts){
    			foreach ($counts as $count){
    				foreach ($keywords as $i=>$keyword){
    					if($keyword->id == $count->keyid){
    						$keywords[$i]->crosswords = $count->crosswords;
    						break;
    					}
    				}
    			}
    		}
    	}
    	
    	$query = 'select distinct(a.created_by), u.username, u.name from '.TABLE_CROSSWORDS.' a left join #__users u on a.created_by=u.id where a.created_by>0 order by u.username';
    	$db->setQuery($query);
    	$users = $db->loadObjectList();
    	
        $lists['order'] = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['catid'] = $catid;
        $lists['uid'] = $userid;
        $lists['search'] = $search;
        
        $result->keywords = $keywords;
        $result->users = $users;
        $result->categories = &$this->get_categories();
        $result->lists = $lists;
        $result->pagination = $pagination;
        
        return $result;
    }
    
    function &get_categories(){
    	$db = &JFactory::getDBO();
    	
    	$query = "SELECT node.id, node.title, node.alias, (COUNT(parent.title) - 1) AS depth, node.published ".
    		"FROM ".TABLE_CROSSWORDS_CATEGORIES." AS node, ".TABLE_CROSSWORDS_CATEGORIES." AS parent ".
    		"WHERE (node.lft BETWEEN parent.lft AND parent.rgt) ".
    		"GROUP BY node.title ".
    		"ORDER BY node.lft";
    	$db->setQuery($query);
    	
    	return $db->loadObjectList();
    }
    
    function &get_crossword($id){
    	$db = &JFactory::getDBO();
    	$query = 'select id, title, alias, catid, published from '.TABLE_CROSSWORDS.' where id='.$id;
    	$db->setQuery($query);
    	return $db->loadObject();
    }
    
    function &get_keyword($id){
    	$db = &JFactory::getDBO();
    	$query = 'select id, question, catid, published from '.TABLE_CROSSWORDS_KEYWORDS.' where id='.$id;
    	$db->setQuery($query);
    	return $db->loadObject();
    }
    
    function &get_category($id){
    	$db = &JFactory::getDBO();
    	$query = 'select id, title, alias, published from '.TABLE_CROSSWORDS_CATEGORIES.' where id='.$id;
    	$db->setQuery($query);
    	return $db->loadObject();
    }
    
    function save_crossword(){
    	$db = &JFactory::getDBO();
    	$id = JRequest::getVar('cid',0,'post','int');
    	$title = JRequest::getVar('title',null,'post','string');
    	$alias = JRequest::getVar('alias',null,'post','string');
    	$category = JRequest::getVar('category',0,'post','int');
    	$published = JRequest::getVar('published',0,'post','int');
    	
    	if(!$id || !$title || !$category){
    		return false;
    	}
    	
    	if(empty($alias)){
    		$alias = JFilterOutput::stringURLSafe($title);
    	}
    	
    	$query = 'update '.TABLE_CROSSWORDS.' set'
    		. ' title='.$db->quote($title).','
    		. ' alias='.$db->quote($alias).','
    		. ' catid='.$category.','
    		. ' published='.$published
    		. ' where id='.$id;
    	$db->setQuery($query);
    	if(!$db->query()){
    		return false;
    	}
    	return true;
    }
    
    function save_keyword(){
    	$db = &JFactory::getDBO();
    	$id = JRequest::getVar('cid',0,'post','int');
    	$question = JRequest::getVar('question',null,'post','string');
    	$category = JRequest::getVar('category',0,'post','int');
    	$published = JRequest::getVar('published',0,'post','int');
    	if(!$id || !$question || !$category){
    		return false;
    	}
    	
    	$query = 'update '.TABLE_CROSSWORDS_KEYWORDS.' set'
    		. ' question='.$db->quote($question).','
    		. ' catid='.$category.','
    		. ' published='.$published
    		. ' where id='.$id;
    	$db->setQuery($query);
    	if(!$db->query()){
    		return false;
    	}
    	return true;
    }
    
    function save_category(){
    	$db = &JFactory::getDBO();
    	$id = JRequest::getVar('cid',0,'post','int');
    	$title = JRequest::getVar('category',null,'post','string');
    	$alias = JRequest::getVar('alias',null,'post','string');
    	$published = JRequest::getVar('published',0,'post','int');
    	
    	if(!$title){
    		return false;
    	}
    	
    	if(!$alias){
    		jimport('joomla.filter.output');
    		$alias = JFilterOutput::stringURLSafe($title);
    	}
    	
    	$query = '';
    	if($id){
	    	$query = 'update '.TABLE_CROSSWORDS_CATEGORIES.' set'
	    		. ' title='.$db->quote($title).','
	    		. ' alias='.$db->quote($alias).','
	    		. ' published='.$published
	    		. ' where id='.$id;
    	}else{
    		$query = 'insert into '.TABLE_CROSSWORDS_CATEGORIES.'(title, alias, published) values ('
    			. $db->quote($title) . ','
    			. $db->quote($alias) . ','
    			. $published . ')';
    	}
    	$db->setQuery($query);
    	if(!$db->query()){
    		$this->setError($db->getErrorMsg());
    		return false;
    	}
    	return true;
    }
    
    function set_crosswords_status($id, $column, $status){
    	$db = &JFactory::getDBO();
    	$query = "update ".TABLE_CROSSWORDS.
    		" set ".$db->nameQuote($column)." = ".($status?1:0).
    		" where ".$db->nameQuote('id')." in ( ".$id." )";
    	$db->setQuery($query);
    	if(!$db->query()){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    function set_keywords_status($id, $column, $status){
    	$db = &JFactory::getDBO();
    	$query = "update ".TABLE_CROSSWORDS_KEYWORDS.
    		" set ".$db->nameQuote($column)." = ".($status?1:0).
    		" where ".$db->nameQuote('id')." in ( ".$id." )";
    	$db->setQuery($query);
    	if(!$db->query()){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    function set_categories_status($id, $column, $status){
    	$db = &JFactory::getDBO();
    	$query = "update ".TABLE_CROSSWORDS_CATEGORIES.
    		" set ".$db->nameQuote($column)." = ".($status?1:0).
    		" where ".$db->nameQuote('id')." in ( ".$id." )";
    	$db->setQuery($query);
    	if(!$db->query()){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    function delete_keywords($ids){
    	$db = &JFactory::getDBO();
    	$query = "select count(*) from ".TABLE_CROSSWORDS_QUESTIONS." where keyid in (".$ids.")";
    	$db->setQuery($query);
    	$count = (int)$db->loadResult();
    	
    	if($count == 0){
    		$query = "delete from ".TABLE_CROSSWORDS_KEYWORDS." where id in (".$ids.")";
    		$db->setQuery($query);
    		if($db->query()){
    			return true;
    		}else{
    			return false;
    		}
    	}else{
    		$this->setError(JTExt::_('MSG_CASCADE_DELETE'));
    		return false;
    	}
    }
    
    function delete_crosswords($ids){
    	$db = &JFactory::getDBO();
    	$queries = array();
    	$queries[] = "delete from ".TABLE_CROSSWORDS_RESPONSES." where cid in (".$ids.")";
    	$queries[] = "delete from ".TABLE_CROSSWORDS_RESPONSE_DETAILS." where crossword_id in (".$ids.")";
    	$queries[] = "delete from ".TABLE_CROSSWORDS_QUESTIONS." where cid in (".$ids.")";
    	$queries[] = "delete from ".TABLE_CROSSWORDS." where id in (".$ids.")";
    	
    	foreach ($queries as $query){
    		$db->setQuery($query);
    		$db->query();
    	}
    }
    
    function save_configuration(){
		$db =& JFactory::getDBO();
		$user_name                  = JRequest::getVar(USER_NAME, 'username', 'post','STRING');
		$user_avtar                 = JRequest::getVar(USER_AVTAR, 'none', 'post','CMD');
		$list_limit					= JRequest::getVar(LIST_LIMIT, 20, 'post','INT');
		$points_system              = JRequest::getVar(POINTS_SYSTEM, 'none', 'post','CMD');
		$touch_pts_new_question		= JRequest::getVar(TOUCH_POINTS_CW_QUESTION, '0', 'post','INT');
		$touch_pts_solved_crossword	= JRequest::getVar(TOUCH_POINTS_SOLVED_CROSSWORD, '0', 'post','INT');
		$comment_system				= JRequest::getVar(COMMENT_SYSTEM, 'none', 'post','CMD');
		$activity_stream_type		= JRequest::getVar(ACTIVITY_STREAM_TYPE, 'none', 'post','CMD');
		$stream_new_question		= JRequest::getVar(STREAM_NEW_QUESTION, '0', 'post','INT');
		$stream_solved_crossword	= JRequest::getVar(STREAM_SOLVED_CROSSWORD, '0', 'post','INT');
		$enable_powered_by			= JRequest::getVar(ENABLE_POWERED_BY, 1, 'post','INT');
		$permission_guest_access	= JRequest::getVar(PERMISSION_GUEST_ACCESS, 0, 'post','INT');
		$permission_access			= JRequest::getVar(PERMISSION_ACCESS, array(), 'post','ARRAY');
		$permission_create			= JRequest::getVar(PERMISSION_CREATE, array(), 'post','ARRAY');
		$permission_submit_words	= JRequest::getVar(PERMISSION_SUBMIT_WORDS, array(), 'post','ARRAY');
		
		JArrayHelper::toInteger( $permission_access );
		JArrayHelper::toInteger( $permission_create );
		JArrayHelper::toInteger( $permission_submit_words );
		$permission_access			= implode(",",$permission_access);
		$permission_create			= implode(",",$permission_create);
		$permission_submit_words	= implode(",",$permission_submit_words);
		
		/*Default Configuration Properties */
		$query = 'INSERT INTO '.TABLE_CROSSWORDS_CONFIG.' (`config_name`, `config_value`) VALUES' .
			'("' . DEFAULT_TEMPLATE . '",' . $db->quote("default") . '),' .
            '("' . USER_NAME . '",' . $db->quote($user_name) . '),' .
            '("' . USER_AVTAR . '",' . $db->quote($user_avtar) . '),' .
			'("' . LIST_LIMIT . '",' . $db->quote($list_limit) . '),' .
            '("' . POINTS_SYSTEM . '",' . $db->quote($points_system) . '),' .
			'("' . TOUCH_POINTS_CW_QUESTION . '",' . $db->quote($touch_pts_new_question) . '),' .
			'("' . TOUCH_POINTS_SOLVED_CROSSWORD . '",' . $db->quote($touch_pts_solved_crossword) . '),' .
			'("' . COMMENT_SYSTEM . '",' . $db->quote($comment_system) . '),' .
			'("' . ACTIVITY_STREAM_TYPE . '",' . $db->quote($activity_stream_type) . '),' .
			'("' . STREAM_NEW_QUESTION . '",' . $db->quote($stream_new_question) . '),' .
			'("' . STREAM_SOLVED_CROSSWORD . '",' . $db->quote($stream_solved_crossword) . '),' .
			'("' . PERMISSION_GUEST_ACCESS . '",' . $db->quote($permission_guest_access) . '),' .
			'("' . PERMISSION_ACCESS . '",' . $db->quote($permission_access) . '),' .
			'("' . PERMISSION_SUBMIT_WORDS . '",' . $db->quote($permission_submit_words) . '),' .
			'("' . ENABLE_POWERED_BY . '",' . $db->quote($enable_powered_by) . '),' .
            '("' . PERMISSION_CREATE . '",' . $db->quote($permission_create) . ')' .
            ' ON DUPLICATE KEY UPDATE config_value=VALUES(config_value)';

		$db->setQuery( $query );

		if(!$db->query()) {
			$this->setError($query);
			return false;
		}

		return true;
    }
    
    function &get_keyword_submitters($ids){
    	$db = &JFactory::getDBO();
    	$query = "select created_by, id from ".TABLE_CROSSWORDS_KEYWORDS." where id in (".$ids.")";
    	$db->setQuery($query);
    	return $db->loadObjectList();
    }
    
    function &get_crossword_counts($ids){
    	$db = &JFactory::getDBO();
    	$query = "select count(*) as crosswords, keyid from ".TABLE_CROSSWORDS_QUESTIONS." where keyid in (".$ids.") group by keyid";
    	$db->setQuery($query);
    	return $db->loadObjectList();
    }
    
    function &get_keyword_uses($id){
    	$db = &JFactory::getDBO();
    	$query = "select cid from ".TABLE_CROSSWORDS_QUESTIONS." where keyid=".$id;
    	$db->setQuery($query);
    	return $db->loadResultArray();
    }
}
?>