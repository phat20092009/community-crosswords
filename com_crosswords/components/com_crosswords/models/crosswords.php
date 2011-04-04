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

jimport('joomla.application.component.model');

class CrosswordsModelCrosswords extends JModel {
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
    }
    
    function &get_categories(){
    	$db = &JFactory::getDBO();
    	
    	$query = "SELECT node.id, node.title, node.alias, (COUNT(parent.title) - 1) AS depth ".
    		"FROM ".TABLE_CROSSWORDS_CATEGORIES." AS node, ".TABLE_CROSSWORDS_CATEGORIES." AS parent ".
    		"WHERE (node.lft BETWEEN parent.lft AND parent.rgt) AND node.published=1 ".
    		"GROUP BY node.title ".
    		"ORDER BY node.lft";
    	$db->setQuery($query);
    	
    	return $db->loadObjectList();
    }
    
    function &get_crosswords_list($userid=0){
		$app = &JFactory::getApplication();
		$db = &JFactory::getDBO();
    	$cwConfig = &CrosswordsHelper::getConfig();
    	
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$limit = $cwConfig[LIST_LIMIT];
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    	
    	$where = array();
    	$where[] = "a.published=1";
    	if($userid){
    		$where[] = "a.created_by=".$userid;
    	}
    	$where = " where " . implode(" and ", $where);
		
		$query = "select count(*) from ".TABLE_CROSSWORDS." a".$where;
		$db->setQuery($query);
		$total = $db->loadResult();

		$result = new stdClass();
		jimport("joomla.html.pagination");
		$result->pagination = new JPagination($total, $limitstart, $limit);
		
    	$query = "select a.id, a.catid, a.title, a.alias, a.created_by, a.created, a.questions, a.rows, a.columns,"
    		. " u.".$cwConfig[USER_NAME]." as username, c.title as category, c.alias as calias, 0 as solved"
    		. " from ".TABLE_CROSSWORDS." a"
    		. " left join ".TABLE_CROSSWORDS_CATEGORIES." c on a.catid=c.id"
    		. " left join #__users u on a.created_by=u.id"
    		. $where
    		. " order by a.created desc";
    	$db->setQuery($query, $limitstart, $limit);
    	$crosswords = $db->loadObjectList();

    	$ids = array();
    	if(count($crosswords) > 0){
	    	foreach ($crosswords as $crossword){
	    		$ids[] = $crossword->id;
	    	}
    	}
    	$ids = implode(",", $ids);
    	$query = "select cid, count(*) as users from ".TABLE_CROSSWORDS_RESPONSES." r"
    		. " where r.solved=1 and r.cid in (".$ids.")"
    		. " group by r.cid";
    	$db->setQuery($query);
    	$solved_crosswords = $db->loadObjectList();
    	
    	if(count($solved_crosswords) > 0){
	    	foreach ($solved_crosswords as $solved){
	    		for($i=0; $i<count($crosswords); $i++){
	    			if($crosswords[$i]->id == $solved->cid){
	    				$crosswords[$i]->solved = $solved->users;
	    				break;
	    			}
	    		}
	    	}
    	}
    	$result->crosswords = $crosswords;
    	return $result;
    }
    
    function &get_crossword($cid, $details=true){
    	$db = &JFactory::getDBO();
    	$user = &JFactory::getUser();
    	$cwConfig = &CrosswordsHelper::getConfig();
    	$query = "select a.id, a.catid, a.title, a.alias, a.created_by, a.created, a.questions, a.rows, a.columns,"
    		. " u.".$cwConfig[USER_NAME].", c.title as category"
    		. " from ".TABLE_CROSSWORDS." a"
    		. " left join ".TABLE_CROSSWORDS_CATEGORIES." c on a.catid=c.id"
    		. " left join #__users u on a.created_by=u.id"
    		. " where a.id=".$cid;
    	$db->setQuery($query);
    	$crossword = $db->loadObject();
    	
    	if(!empty($crossword) && $details){
    		$crossword->response_id = 0;
    		$crossword->solved = 0;
    		if(!$user->guest){
    			$query = "select id, solved from ".TABLE_CROSSWORDS_RESPONSES." where cid=".$cid." and created_by=".$user->id;
    			$db->setQuery($query);
    			$result = $db->loadObject();
    			$crossword->solved = ( $result->solved ) ? $result->solved : 0;
    			$crossword->response_id = ( $result->id ) ? $result->id : 0;
    		}
    		
    		// Get questions
    		$query = "select q.id, k.keyword, r.answer, k.question, q.row, q.column, q.axis, q.position"
    			. " from ".TABLE_CROSSWORDS_QUESTIONS." q"
    			. " left join ".TABLE_CROSSWORDS_KEYWORDS." k on q.keyid=k.id"
    			. " left join ".TABLE_CROSSWORDS_RESPONSE_DETAILS." r on q.id=r.question_id and r.response_id=".$crossword->response_id
    			. " where q.cid=".$cid;
    		$db->setQuery($query);
    		$questions = $db->loadObjectList();
    		if(empty($questions)){
    			return false;
    		}
    		
    		// Form grid cells
    		$cells = array();
    		for($x=0; $x<$crossword->rows; $x++){
    			for($y=0; $y<$crossword->columns; $y++){
    				$cells[$x][$y]->letter = null;
    			}
    		}
    		
    		foreach ($questions as $question){
    			if($question->axis == 1){
    				$cells[$question->row][$question->column]->number = $question->position;
    				$cells[$question->row][$question->column]->axis = 2;
    				for($y=0; $y < strlen($question->keyword); $y++){
    					$cellnum = $question->column + $y;
    					$cells[$question->row][$cellnum]->letter = ($question->answer ? substr($question->answer, $y, 1) : " ");
    					$cells[$question->row][$cellnum]->class =  $cells[$question->row][$cellnum]->class 
    						? $cells[$question->row][$cellnum]->class . " letters-1-" . $question->position 
    						: "letters-1-" . $question->position;
    				}
    				
    			}else{
    				$cells[$question->row][$question->column]->number = $question->position;
    				$cells[$question->row][$question->column]->axis = 1;
    				for($x=0; $x < strlen($question->keyword); $x++){
    					$cellnum = $question->row + $x;
    					$cells[$cellnum][$question->column]->letter = ($question->answer ? substr($question->answer,$x,1) : " ");
    					$cells[$cellnum][$question->column]->class = $cells[$cellnum][$question->column]->class
    						? $cells[$cellnum][$question->column]->class . " letters-2-" . $question->position
    						: "letters-2-" . $question->position;
    				}
    			}
    		}
    		
    		// Get users who solved this
    		$query = "select r.created_by, u.".$cwConfig[USER_NAME] . " as username, r.created"
    			. " from ".TABLE_CROSSWORDS_RESPONSES." r "
    			. " left join #__users u on r.created_by = u.id "
    			. " where r.cid=".$cid." and r.solved=1"
    			. " order by r.created_by desc";
    		$db->setQuery($query, 0, 10);
    		$users = $db->loadObjectList();
    		
    		$query = "select count(*) from ".TABLE_CROSSWORDS_RESPONSES." where cid=".$cid." and solved=1";
    		$db->setQuery($query);
    		$user_count = (int)$db->loadResult();
    		
    		$crossword->questions = $questions;
    		$crossword->cells = $cells;
    		$crossword->users_solved = $users;
    		$crossword->user_count = $user_count - 15;
    	}
    	return $crossword;
    }
    
    function check_result(){
    	$db = &JFactory::getDBO();
    	$user = &JFactory::getUser();
    	$cid = JRequest::getVar("id", 0, "post", "int");
    	// Get questions
    	$query = "select q.id, k.keyword, q.row, q.column, q.axis, q.position"
    		. " from ".TABLE_CROSSWORDS_QUESTIONS." q"
    		. " left join ".TABLE_CROSSWORDS_KEYWORDS." k on q.keyid=k.id"
    		. " where q.cid=".$cid;
    	$db->setQuery($query);
    	$questions = $db->loadObjectList();
    	if(empty($questions)){
    		$this->setError(JText::_("MSG_ERROR_PROCESSING")." Error: 10021");
    		return false;
    	}
    	
    	// Form grid cells
    	$cells = array();
    	for($x=0; $x<$crossword->rows; $x++){
    		for($y=0; $y<$crossword->columns; $y++){
    			$cells[$x][$y]->letter = null;
    		}
    	}
    	
    	$failed = array();
    	$answers = array();
    	foreach ($questions as $question){
    		$answer = "";
    		$flag = false;
    		if($question->axis == 1){
    			for($y=0; $y < strlen($question->keyword); $y++){
    				$cellnum = $question->column + $y;
    				$letter = JRequest::getVar("cell_".$cellnum."_".$question->row, "", "post", "word");
    				$letter = ((empty($letter) || strlen($letter) != 1) ? " " : $letter);
    				$answer .= $letter;
    				if(!$flag && strcmp($letter, substr($question->keyword, $y, 1)) != 0){
    					$failed[] = "letters-1-" . $question->position;
    					$flag = true;
    				}
    			}
    		}else{
    			for($x=0; $x < strlen($question->keyword); $x++){
    				$cellnum = $question->row + $x;
    				$letter = JRequest::getVar("cell_".$question->column."_".$cellnum, "", "post", "word");
    				$letter = ((empty($letter) || strlen($letter) != 1) ? " " : $letter);
    				$answer .= $letter;
    				if(!$flag && strcmp($letter, substr($question->keyword, $x, 1)) != 0){
    					$failed[] = "letters-2-" . $question->position;
    					$flag = true; 
    				}
    			}
    		}
    		$response = new stdClass();
    		$response->answer = $answer;
    		$response->question_id = $question->id;
    		$response->valid = (int)!$flag;
    		$answers[] = $response;
    	}
    	
    	if(!$user->guest){
    		$query = "select id from ".TABLE_CROSSWORDS_RESPONSES." where cid=".$cid." and created_by=".$user->id;
    		$db->setQuery($query);
    		$response_id = $db->loadResult();
    		if($response_id){
	    		$query = "delete from ".TABLE_CROSSWORDS_RESPONSE_DETAILS." where response_id=".$response_id;
	    		$db->setQuery($query);
	    		if(!$db->query()){
	    			$this->setError(JText::_("MSG_ERROR_PROCESSING")." Error: 10022");
	    			return false;
	    		}
	    		if(count($failed) == 0){
	    			$query = "update ".TABLE_CROSSWORDS_RESPONSES." set solved=1 where id=".$response_id;
	    			$db->setQuery($query);
	    			$db->query();
	    		}
    		}else{
    			$query = "insert into ".TABLE_CROSSWORDS_RESPONSES."(cid, created_by, created, solved) values ("
    				. $cid . ","
    				. $user->id . ","
    				. $db->quote( gmdate ( 'Y-m-d H:i:s' ) ) . ","
    				. (( count($failed) > 0 ) ? "0" : "1")
    				. ")";
    			$db->setQuery($query);
    			if(!$db->query()){
    				$this->setError(JText::_("MSG_ERROR_PROCESSING")." Error: 10023");
    				return false;
    			}
    			$response_id = $db->insertid();
    		}
    		
    		$query = "insert into ".TABLE_CROSSWORDS_RESPONSE_DETAILS."(crossword_id, response_id, question_id, answer, valid) values ";
    		$records = array();
    		foreach ($answers as $answer){
    			$records[] = "(" . $cid . "," . $response_id . "," . $answer->question_id . "," . $db->quote($answer->answer) . "," . $answer->valid . ")"; 
    		}
    		$query .= implode(",", $records);
    		$db->setQuery($query);
    		if(!$db->query()){
    			$this->setError(JText::_("MSG_ERROR_PROCESSING")." Error: 10024".$query);
    			return false;
    		}
    	}
    	
    	return $failed;
    }
    
    function save_keyword(){
    	$question_title = JRequest::getVar("question-title",null,"post","string");
    	$question_keyword = JRequest::getVar("question-keyword", null, "post", "word");
    	$question_category = JRequest::getVar("question-category", 0, "post", "int");
    	
    	if(empty($question_title) || empty($question_keyword) || !$question_category){
    		$this->setError(JText::_("MSG_REQUIRED_FIELDS_MISSING"));
    		return false;
    	}
    	
    	$user = &JFactory::getUser();
    	$db = JFactory::getDBO();
    	$query = "insert into ".TABLE_CROSSWORDS_KEYWORDS."(question, keyword, created_by, created, catid) values (".
    		$db->quote($question_title) . "," .
    		strtoupper($db->quote($question_keyword)) . "," .
    		$user->id . "," .
    		$db->quote( gmdate ( 'Y-m-d H:i:s' ) ) . "," .
    		$question_category .
    		")";
    	$db->setQuery($query);
    	if($db->query()){
    		return $db->insertid();
    	}else{
    		return false;
    	}
    }
    
    function create_crossword(){
    	$user = &JFactory::getUser();
    	$db = &JFactory::getDBO();
    	
    	$crossword_title = JRequest::getVar("crossword-title", null, "post", "string");
    	$crossword_category = JRequest::getVar("crossword-category", 0, "post", "int");
    	$crossword_level = JRequest::getVar("crossword-level", 1, "post", "int");
    	$crossword_size = JRequest::getVar("crossword-size", 15, "post", "int");
    	
    	if(empty($crossword_title) || empty($crossword_category)){
    		$this->setError(JText::_("MSG_REQUIRED_FIELDS_MISSING"));
    	}
    	if($crossword_size < 15){
    		$crossword_size = 15;
    	}else if($crossword_size > 23){
    		$crossword_size = 23;
    	}
    	
    	$max_words = ($crossword_level == 1) ? $crossword_size - 10 : (($crossword_level == 2) ? $crossword_size - 5 : $crossword_size);
    	
    	require_once JPATH_COMPONENT.DS.'lib'.DS.'php_crossword.class.php';
        $client =& new PHP_Crossword($db, $crossword_size, $crossword_size, $crossword_category, $max_words, 5);
        $success = $client->generate();
        if($success){
        	$alias = JFilterOutput::stringURLSafe($crossword_title);
        	$query = "insert into ".TABLE_CROSSWORDS."("
        		. $db->nameQuote("title") . ","
        		. $db->nameQuote("alias") . ","
        		. $db->nameQuote("created") . ","
        		. $db->nameQuote("catid") . ","
        		. $db->nameQuote("created_by") . ","
        		. $db->nameQuote("questions") . ","
        		. $db->nameQuote("rows") . ","
        		. $db->nameQuote("columns") . ","
        		. $db->nameQuote("published") . ") values ("
        		. $db->quote($crossword_title). ","
        		. $db->quote($alias). ","
        		. $db->quote ( gmdate ( 'Y-m-d H:i' ) ) . ","
        		. $db->quote($crossword_category) . ","
        		. $user->id . ","
        		. $max_words . ","
        		. $crossword_size . ","
        		. $crossword_size . "," 
        		. "1)";
        	$db->setQuery($query);
        	if($db->query()){
        		$cid = $db->insertid();
        		$questions = array();
	        	$query = "insert into ".TABLE_CROSSWORDS_QUESTIONS."("
	        		. $db->nameQuote("cid") . ","
	        		. $db->nameQuote("keyid") . ","
	        		. $db->nameQuote("row") . ","
	        		. $db->nameQuote("column") . ","
	        		. $db->nameQuote("axis") . ","
	        		. $db->nameQuote("position") . ") values ";
	        		
	        	$words = $client->getWords();
	        	$row = 1;
	        	$col = 1;
	        	foreach($words as $word){
	        		if($word["axis"] == '1'){
	        			$questions[] = "(".$cid.",".$word["id"].",".$word["y"].",".$word["x"].",".$word["axis"].",".($row++).")";
	        		}else{
	        			$questions[] = "(".$cid.",".$word["id"].",".$word["y"].",".$word["x"].",".$word["axis"].",".($col++).")";
	        		}
	        	}
	        	$query = $query . implode(",", $questions);
	        	$db->setQuery($query);
	        	if($db->query()){
	        		return $this->get_crossword($cid);
	        	}else{
	        		$this->setError(JText::_("MSG_ERROR_PROCESSING")." Error: 10031");
	        		return false;
	        	}
        	}else{
        		$this->setError(JText::_("MSG_ERROR_PROCESSING")." Error: 10032". $client->getError());
        		return false;
        	}
        }else{
        	$this->setError(JText::_("MSG_ERROR_PROCESSING")." Error: 10033");
            return false;
        }
    }
    
    function save_crossword(){
    	$app = &JFactory::getApplication();
    	$cwConfig = &$app->getUserState(SESSION_CONFIG);
    	$db = &JFactory::getDBO();
    	
    	$id = JRequest::getVar("id", 0, "post", "int");
    	$query = "select q.id, q.keyid, q.row, q.column, q.axis, k.keyword from ".TABLE_CROSSWORDS_QUESTIONS." q ".
    		"left join ".TABLE_CROSSWORDS_KEYWORDS." k on q.keyid = k.id " .
    		"where q.cid=".$id;
    	$db->setQuery($query);
    	$questions = $db->loadObjectList();
    	
    }
}
?>