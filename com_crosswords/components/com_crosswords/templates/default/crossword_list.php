<?php 
defined('_JEXEC') or die('Restricted access');
global $option;
$user = &JFactory::getUser();
$menu = &JSite::getMenu();
$mnuitem = $menu->getItems('link', 'index.php?option='.$option.'&view=crosswords', true);
$itemid = isset($mnuitem) ? '&Itemid='.$mnuitem->id : '';
$citemid = ($this->catid)? '&catid='.$this->catid : '';
?>
<script type="text/javascript">
<!--
jQuery(document).ready(function($){
	CrossWordFactory.init_list_page();
});
//-->
</script>
<div id="crossword-list">
	<div id="menu-wrapper">
		<div id="crossword-menu" class="ui-widget-header ui-corner-all">
			<span id="menuitems">
				<a id="menu-home" href="<?php echo JRoute::_('index.php?option='.$option.'&view=crosswords&task=home'.$itemid);?>"><?php echo JText::_("LBL_HOME");?></a>
				<?php if(!$user->guest && CWAuthorization::authorize($option,'create','crosswords','all')): ?>
				<a id="menu-user" href="<?php echo JRoute::_('index.php?option='.$option.'&view=crosswords&task=user'.$itemid);?>"><?php echo JText::_("LBL_MY_CROSSWORDS");?></a>
				<?php endif; ?>
				<?php if(CWAuthorization::authorize($option,'words','crosswords','all')): ?>
				<a id="menu-submit_question" href="#"><?php echo JText::_("LBL_SUBMIT_QUESTION");?></a>
				<?php endif; ?>
				<?php if(CWAuthorization::authorize($option,'create','crosswords','all')): ?>
				<a id="menu-create_crossword" href="#"><?php echo JText::_("LBL_CREATE_CROSSWORD");?></a>
				<?php endif; ?>
			</span>
		</div>
	</div>
	<?php if(!empty($this->crosswords)):?>
	<div class="page-title" style="margin-top: 10px;"><?php echo JText::_("LBL_CROSSWORD_LIST");?></div>
	<ul class="crosswords">
	<?php
	$i = 0;
	foreach ($this->crosswords as $crossword){
		$href = JRoute::_("index.php?option=".$option."&view=crosswords&task=view&id=".$crossword->id.":".$crossword->alias.$citemid.$itemid);
		$chref = JRoute::_( 'index.php?option='.$option.'&view=crosswords&task=list&catid='.$crossword->catid.(!empty ($crossword->calias)?':'.$crossword->calias:'').$itemid );
		$crossword_date = CrosswordsHelper::getFormattedDate($crossword->created);
		$user_avatar = CrosswordsHelper::getUserAvatar($crossword->created_by, 36);
		$styleClass = $i ? "alt" : "noalt";
		?>
		<li class="<?php echo $styleClass;?>">
	        <div class="crossword-avatar"><?php echo $user_avatar;?></div>
	        <div class="crossword-title">
	            <a href="<?php echo $href; ?>"><?php echo $this->escape($crossword->title);?></a>
	        </div>
	        <div class="crossword-meta">
	            <span class="meta-category"><a href="<?php echo $chref; ?>"><?php echo $this->escape($crossword->category);?></a></span>
	            <span class="meta-user"><?php echo JText::_('TXT_BY');?>&nbsp;<?php echo CrosswordsHelper::getUserProfileUrl($crossword->created_by, $crossword->username);?></span>
	            <span class="meta-date"><?php echo $crossword_date;?></span>
	            <span class="meta-users"><?php echo $crossword->solved . ' ' . JText::_('LBL_PEOPLE_SOLVED');?></span>
	        </div>
	        <div class="clear"></div>
		</li>
		<?php
		$i = 1 - $i;
	}
	?>
	</ul>
	<?php if(!empty($this->pagination)): ?>
	<table width="100%">
	    <tr>
	        <td colspan="<?php echo $cols; ?>">
	            <div style="float: left;">
	                <?php echo $this->pagination->getPagesLinks(); ?>
	            </div>
	            <div style="float: right;">
	                <?php echo $this->pagination->getResultsCounter(); ?>
	            </div>
	            <div style="clear:both;"></div>
	        </td>
	    </tr>
	</table>
	<?php endif;?>
	<?php else :?>
		<?php echo JText::_('MSG_NO_RESULTS_FOUND');?>
	<?php endif;?>
</div>
<form id="crossword-form" title="<?php echo JText::_("TITLE_CREATE_CROSSWORD");?>" method="post" action="<?php echo JRoute::_('index.php?option='.$option.'&view=crosswords&task=create'.$itemid);?>" style="display: none;">
	<fieldset>
		<label for="crossword-title"><?php echo JText::_("LBL_CROSSWORD_TITLE");?></label>
		<input type="text" name="crossword-title" id="crossword-title" size="40" class="text ui-widget-content ui-corner-all required" />
		<label for="crossword-category"><?php echo JText::_("LBL_CATEGORY");?></label>
		<select name="crossword-category" id="crossword-category">
			<option><?php echo JText::_("TXT_SELECT_AN_OPTION");?></option>
			<?php 
			if($this->categories){
				foreach($this->categories as $category){
				?>
				<option value="<?php echo $category->id?>"><?php echo $this->escape($category->title);?></option>
				<?php
				}
			}
			?>
		</select>
		<label for="crossword-size"><?php echo JText::_("LBL_CROSSWORD_SIZE");?></label>
		<select id="crossword-size" name="crossword-size">
			<option value="23">23</option>
			<option value="20">20</option>
			<option value="15">15</option>
		</select>
		<label for="crossword-level"><?php echo JText::_("LBL_DIFFICULTY_LEVEL");?></label>
		<select id="crossword-level" name="crossword-level">
			<option value="1"><?php echo JText::_("TXT_LEVEL_EASY");?></option>
			<option value="2"><?php echo JText::_("TXT_LEVEL_MODERATE");?></option>
			<option value="3"><?php echo JText::_("TXT_LEVEL_HARD");?></option>
		</select>
	</fieldset>	
	<input type="hidden" name="view" value="crossword"/>
	<input type="hidden" name="task" value="create"/>
</form>
<form id="question-form" title="<?php echo JText::_("LBL_SUBMIT_QUESTION");?>" method="post" action="<?php echo JRoute::_('index.php?option='.$option.'&view=crosswords&task=keyword'.$itemid);?>" style="display: none;">
	<div class=".ui-icon-info ui-state-highlight"><?php echo JText::_("TXT_SUBMIT_QUESTION_HELP");?></div>
	<fieldset>
		<label for="question-keyword"><?php echo JText::_("LBL_QUESTION_KEYWORD");?></label>
		<input type="text" name="question-keyword" id="question-keyword" size="40" class="text ui-widget-content ui-corner-all required" />
		<label for="question-title"><?php echo JText::_("LBL_QUESTION_TITLE");?></label>
		<input type="text" name="question-title" id="question-title" size="40" class="text ui-widget-content ui-corner-all required" />
		<label for="question-category"><?php echo JText::_("LBL_CATEGORY");?></label>
		<select name="question-category" id="question-category" class="text ui-widget-content ui-corner-all required">
			<option><?php echo JText::_("TXT_SELECT_AN_OPTION");?></option>
			<?php 
			if($this->categories){
				foreach($this->categories as $category){
				?>
				<option value="<?php echo $category->id?>"><?php echo $this->escape($category->title);?></option>
				<?php
				}
			}
			?>
		</select>
	</fieldset>	
	<input type="hidden" name="view" value="crossword"/>
	<input type="hidden" name="task" value="keyword"/>
</form>
<span id="lbl_cancel" style="display: none;"><?php echo JText::_("LBL_CANCEL");?></span>
<span id="lbl_submit" style="display: none;"><?php echo JText::_("LBL_SUBMIT");?></span>
<span id="lbl_error" style="display: none;"><?php echo JText::_("LBL_ERROR");?></span>
<span id="lbl_info" style="display: none;"><?php echo JText::_("LBL_INFO");?></span>
<span id="lbl_ok" style="display: none;"><?php echo JText::_("LBL_OK");?></span>
<img id="progress-confirm" alt="..." src="<?php echo $templateUrlPath;?>/images/ui-anim_basic_16x16.gif" style="display: none;"/>