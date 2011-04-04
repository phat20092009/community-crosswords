<?php
defined('_JEXEC') or die('Restricted access');
global $option;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Details' ); ?></legend>
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <label for="category">
                    <?php echo JText::_( 'LBL_QUESTION' ); ?>:
                </label>
            </td>
            <td>
                <input class="text_area" type="text" name="question" id="question" size="32" maxlength="250" value="<?php echo $this->escape($this->keyword->question);?>" />
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="alias">
                    <?php echo JText::_( 'LBL_CATEGORY' ); ?>:
                </label>
            </td>
            <td>
            	<select name="category" id="category">
            		<?php foreach ($this->categories as $cat):?>
            		<option value="<?php echo $cat->id;?>" <?php echo ($this->keyword->catid == $cat->id) ? 'selected="selected"':''?>><?php echo $cat->title;?></option>
            		<?php endforeach;?>
            	</select>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="published">
                    <?php echo JText::_( 'LBL_PUBLISHED' ); ?>:
                </label>
            </td>
            <td>
                <input id="published-no" name="published" type="radio" value="0" <?php echo ($this->keyword->published)?'':'checked="checked"';?>/>
                <label for="published-no"><?php echo JText::_('LBL_NO');?></label>&nbsp;&nbsp;&nbsp;
                <input id="published-yes" name="published" type="radio" value="1" <?php echo ($this->keyword->published)?'checked="checked"':'';?>/>
                <label for="published-yes"><?php echo JText::_('LBL_YES');?></label>
            </td>
        </tr>
    </table>
    </fieldset>
</div>
<input type="hidden" name="cid" value="<?php echo $this->keyword->id; ?>" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="task" value="save_keyword" />
</form>