<?php 
defined('_JEXEC') or die('Restricted access');
global $option;
$cwConfig = &CrosswordsHelper::getConfig();
$user = &JFactory::getUser();
$menu = &JSite::getMenu();
$mnuitem = $menu->getItems('link', 'index.php?option='.$option.'&view=crosswords', true);
$itemid = isset($mnuitem) ? '&Itemid='.$mnuitem->id : '';
$citemid = ($this->catid)? '&catid='.$this->catid : '';
?>
<script type="text/javascript">
<!--
jQuery(document).ready(function($){
	CrossWordFactory.init_details_page();
});
//-->
</script>
<div id="crossword-details">
	<?php if($this->crossword):?>
	<div class="toolbar">
		<ul class="submenu">
			<li><a href="<?php echo JRoute::_('index.php?option='.$option.'&view=crosswords&task=list'.$itemid);?>" class="active icon-home"><?php echo JText::_('LBL_HOME')?></a></li>
			<?php if(!$user->guest):?>
			<li><a href="<?php echo JRoute::_('index.php?option='.$option.'&view=crosswords&task=user'.$itemid);?>" class="active icon-user"><?php echo JText::_('LBL_MY_CROSSWORDS')?></a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="page-title"><?php echo $this->escape($this->crossword->title);?></div>
	<table cellpadding="0" cellspacing="0" width="99.5%">
		<tbody>
			<tr>
				<td valign="top" style="vertical-align: top;">
					<form id="crossword-form" action="<?php echo JRoute::_('index.php?option='.$option.'&view=crosswords&task=check_result');?>" method="post">
					    <table id="crossword-grid">
						    <tbody>
						    <?php if($this->crossword->rows):?>
						    <?php for($y=0; $y<=$this->crossword->rows+1; $y++): ?>
						        <tr>
						        <?php for($x=0; $x<=$this->crossword->columns+1; $x++) : ?>
						            <?php if (!empty($this->crossword->cells[$x][$y]->number)) :?>
						            <td id="pos-<?php echo $this->crossword->cells[$x][$y]->axis . '-' . $this->crossword->cells[$x][$y]->number;?>" class="<?php echo $this->crossword->cells[$x][$y]->class;?> keyword-<?php echo $this->crossword->cells[$x][$y]->number;?>">
						            	<input 
						            		name="cell_<?php echo $y.'_'.$x?>" 
						            		id="cell_<?php echo $y.'_'.$x?>" 
						            		type="text" 
						            		size="1" 
						            		maxlength="1" 
						            		value="<?php echo $this->crossword->cells[$x][$y]->letter;?>" 
						            		class="cells cell-<?php echo $this->crossword->cells[$x][$y]->number?>"
						            		<?php echo $this->crossword->solved == '1' ? 'readonly="readonly"' : '';?>/>
					            	</td>
						            <?php elseif (!empty($this->crossword->cells[$x][$y]->letter)): ?>
						            <td>
						            	<input 
						            		name="cell_<?php echo $y.'_'.$x?>" 
						            		id="cell_<?php echo $y.'_'.$x?>" 
						            		type="text" 
						            		size="1" 
						            		maxlength="1" 
						            		value="<?php echo $this->crossword->cells[$x][$y]->letter;?>" 
						            		class="<?php echo $this->crossword->cells[$x][$y]->class;?>"
						            		<?php echo $this->crossword->solved == '1' ? 'readonly="readonly"' : '';?>/>
				            		</td>
						            <?php else: ?>
						            <td>&nbsp;</td>
						            <?php endif;?>
						        <?php endfor;?>
						        </tr>
						    <?php endfor; ?>
						    <?php endif;?>
						    </tbody>
					    </table>
					    <input type="hidden" name="id" value="<?php echo $this->crossword->id;?>"/>
				    </form>
			    </td>
			    <td valign="top" width="100%">
					<?php if($this->crossword->solved == '1'):?>
					<div class="ui-state-highlight ui-corner-all" style="margin: 0 0 10px 10px; padding: 0 .7em;"><?php echo JText::_("TXT_YOU_HAVE_SOLVED");?></div>
					<?php else: ?>
			    	<div class="navigation ui-widget-header ui-corner-all">
			    		<a href="#" id="btn-check-result" onclick="return false;"><?php echo JText::_("LBL_CHECK_RESULT");?></a>
			    	</div>
					<?php endif;?>
			    	<table id="solved-users" align="left" width="100%">
			    		<thead><tr><th><?php echo JText::_('TXT_WHO_SOLVED');?></th></tr></thead>
			    		<tbody>
						<?php if($this->crossword->users_solved):?>
							<?php foreach ($this->crossword->users_solved as $user_solved):?>
							<tr>
								<td nowrap="nowrap">
									<div style="float: left; margin-right: 5px;"><?php echo CrosswordsHelper::getUserAvatar($user_solved->created_by, 32);?></div>
									<div><?php echo CrosswordsHelper::getUserProfileUrl($user_solved->created_by, $this->escape($user_solved->username));?></div>
									<div><?php echo CrosswordsHelper::getFormattedDate($user_solved->created);?></div>
									<div class="clear"></div>
								</td>
							</tr>
							<?php endforeach;?>
							<?php if($this->crossword->user_count > 0):?>
							<tr><td><?php echo $this->crossword->user_count . " " . JText::_("TXT_MORE_USERS_SOLVED");?></td></tr>
							<?php endif;?>
			    		<?php else :?>
			    			<tr><td><?php echo JText::_("TXT_NONE_SOLVED");?></td></tr>
						<?php endif;?>
			    		</tbody>
			    	</table>
			    </td>
		    </tr>
	    </tbody>
    </table>
    <table class="questions">
    	<thead>
	    	<tr>
	    		<td><?php echo JText::_("LBL_ACROSS");?></td>
	    		<td><?php echo JText::_("LBL_DOWN");?></td>
	    	</tr>
    	</thead>
    	<tbody>
    		<?php 
    			$h_questions = "";
    			$v_questions = "";
    			foreach ($this->crossword->questions as $question){
    				if($question->axis == '2'){
    					$h_questions = $h_questions . '<tr class="question-title" id="'.$question->axis.'-'.$question->position.'"><td width="12px">'.$question->position.'</td><td>' . $this->escape($question->question) . '</td></tr>';
    				}else{
    					$v_questions = $v_questions . '<tr class="question-title" id="'.$question->axis.'-'.$question->position.'"><td width="12px">'.$question->position.'</td><td>' . $this->escape($question->question) . '</td></tr>';
    				}
    			}
    		?>
	    	<tr>
	    		<td width="50%" valign="top"><table width="100%"><tbody><?php echo $h_questions;?></tbody></table></td>
	    		<td width="50%" valign="top"><table width="100%"><tbody><?php echo $v_questions;?></tbody></table></td>
	    	</tr>
    	</tbody>
    </table>
    <div id="cw-comments">
	<?php
	if($cwConfig[COMMENT_SYSTEM] == "jomcomment") {
		$jomcommentbot = JPATH_PLUGINS . DS . 'content' . DS . 'jom_comment_bot.php';
	    if(file_exists($jomcommentbot)) {
	    	include_once( $jomcommentbot );
	        echo jomcomment($this->crossword->id, $option);
        }
    }else if($cwConfig[COMMENT_SYSTEM] == "jcomment") {
    	$app = &JFactory::getApplication();
		$comments = $app->getCfg('absolute_path') . '/components/com_jcomments/jcomments.php';
	    if (file_exists($comments)) {
	    	require_once($comments);
	        echo JComments::showComments($this->crossword->id, $option, $this->crossword->title);
        }
    }else if($cwConfig[COMMENT_SYSTEM] == "jacomment"){
		if(!JRequest::getInt('print') && file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php') && file_exists(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jacomment.php')){
	    	$_jacCode = "#{jacomment(.*?) contentid=(.*?) option=(.*?) contenttitle=(.*?)}#i";
	    	$_jacCodeDisableid = "#{jacomment(\s)off.*}#i";
	        $_jacCodeDisable = "#{jacomment(\s)off}#i";
	        if(!preg_match($_jacCode, $this->crossword->title) && !preg_match($_jacCodeDisable, $this->crossword->title) && !preg_match($_jacCodeDisableid, $this->crossword->title)) {
	        	echo '{jacomment contentid='.$this->poll->id.' option='.$option.' contenttitle='.$this->crossword->title.'}';
            }
        }
    }
	?>
	</div>
    <?php else:?>
    <?php echo JText::_("MSG_ERROR_PROCESSING")." Error: 10020";?>
    <?php endif;?>
</div>
<span id="lbl_cancel" style="display: none;"><?php echo JText::_("LBL_CANCEL");?></span>
<span id="lbl_info" style="display: none;"><?php echo JText::_("LBL_INFO");?></span>
<span id="msg_failed_answers" style="display: none;"><?php echo JText::_("MSG_CROSSWORD_UNSOLVED");?></span>
<img id="progress-confirm" alt="..." src="<?php echo $templateUrlPath;?>/images/ui-anim_basic_16x16.gif" style="display: none;"/>