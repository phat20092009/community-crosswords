<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
global $option;
?>
<script type="text/javascript">
    function resetPermissionOptions(select){
        selectBox = document.getElementById(select);
        selectBox.selectedIndex = -1;
    }
</script>
<form action="index.php?option=<?php echo $option;?>" method="post" name="adminForm">
<div id="config-document">
<table class="noshow">
    <tbody>
        <tr>
            <td valign="top" width="100%">
                <?php
                jimport('joomla.html.pane');
                $pane =& JPane::getInstance('tabs', array('startOffset'=>0));
                echo $pane->startPane( 'pane' );
                    echo $pane->startPanel( JText::_('TAB_GENERAL'), 'tabGeneral' );
                    ?>
                    <fieldset>
                        <legend><?php echo JText::_('GENERAL_SETTINGS');?></legend>
                        <table class="admintable">
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_USER_NAME_DESC'); ?>">
                                        <?php echo JText::_('LBL_USER_NAME'); ?>
                                    </span>
                                </td>
                                <td>
                                    <input
                                        type="radio"
                                        name="<?php echo USER_NAME;?>"
                                        id="<?php echo USER_NAME;?>"
                                        value="name"
                                        <?php echo ($this->config[USER_NAME] == 'name') ? 'checked':'';?>>
                                        <label for="<?php echo USER_NAME;?>"><?php echo JText::_('LBL_NAME');?></label>
                                    <input
                                        type="radio"
                                        name="<?php echo USER_NAME;?>"
                                        id="<?php echo USER_NAME;?>"
                                        value="username"
                                        <?php echo ($this->config[USER_NAME] == 'username') ? 'checked':'';?>>
                                        <label for="<?php echo USER_NAME;?>"><?php echo JText::_('LBL_USERNAME');?></label>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_USER_AVTAR_DESC'); ?>">
                                        <?php echo JText::_('LBL_USER_AVTAR'); ?>
                                    </span>
                                </td>
                                <td>
                                    <select
                                        class="inputbox"
                                        name="<?php echo USER_AVTAR ?>"
                                        id="<?php echo USER_AVTAR ?>"
                                        size="1">
                                            <option value="none" <?php if($this->config[USER_AVTAR]=='none') echo 'selected="selected"';?>><?php echo JText::_('OPTION_NONE'); ?></option>
                                            <option value="cb" <?php if($this->config[USER_AVTAR]=='cb') echo 'selected="selected"';?>><?php echo JText::_('OPTION_CB'); ?></option>
                                            <option value="jomsocial" <?php if($this->config[USER_AVTAR]==COMPONENT_JOMSOCIAL) echo 'selected="selected"';?>><?php echo JText::_('OPTION_JOMSOCIAL'); ?></option>
                                            <option value="touch" <?php if($this->config[USER_AVTAR]==COMPONENT_TOUCH) echo 'selected="selected"';?>><?php echo JText::_('OPTION_MIGHTY_TOUCH'); ?></option>
                                            <option value="kunena" <?php if($this->config[USER_AVTAR]=='kunena') echo 'selected="selected"';?>><?php echo JText::_('OPTION_KUNENA'); ?></option>
                                            <option value="aup" <?php if($this->config[USER_AVTAR]==COMPONENT_AUP) echo 'selected="selected"';?>><?php echo JText::_('OPTION_AUP'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_LIST_LENGTH_DESC'); ?>">
                                        <?php echo JText::_('LBL_LIST_LENGTH'); ?>
                                    </span>
                                </td>
                                <td>
                                    <input
                                        class="inputbox"
                                        name="<?php echo LIST_LIMIT ?>"
                                        id="<?php echo LIST_LIMIT ?>"
                                        type="text"
                                        size="25"
                                        value="<?php echo $this->config[LIST_LIMIT]; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_ENABLE_CREDITS_DESC'); ?>">
                                        <?php echo JText::_('LBL_ENABLE_CREDITS'); ?>
                                    </span>
                                </td>
                                <td>
                                    <input
                                        type="radio"
                                        name="<?php echo ENABLE_POWERED_BY;?>"
                                        id="<?php echo ENABLE_POWERED_BY;?>"
                                        value="1"
                                        <?php echo ($this->config[ENABLE_POWERED_BY] == '1') ? 'checked':'';?>>
                                        <label for="<?php echo ENABLE_POWERED_BY;?>"><?php echo JText::_('LBL_YES');?></label>
                                    <input
                                        type="radio"
                                        name="<?php echo ENABLE_POWERED_BY;?>"
                                        id="<?php echo ENABLE_POWERED_BY;?>"
                                        value="0"
                                        <?php echo ($this->config[ENABLE_POWERED_BY] == '0') ? 'checked':'';?>>
                                        <label for="<?php echo ENABLE_POWERED_BY;?>"><?php echo JText::_('LBL_NO');?></label>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <?php
                    echo $pane->endPanel();
                    echo $pane->startPanel( JText::_('TAB_THIRD_PARTY'), 'tabGeneral' );
                    ?>
                    <fieldset>
                        <legend><?php echo JText::_('THIRD_PARTY_SETTINGS');?></legend>
                        <table class="admintable">
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_POINTS_SYSTEM_DESC'); ?>">
                                        <?php echo JText::_('LBL_POINTS_SYSTEM'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $date =& JFactory::getDate();
                                    ?>
                                    <select
                                        class="inputbox"
                                        name="<?php echo POINTS_SYSTEM ?>"
                                        id="<?php echo POINTS_SYSTEM ?>"
                                        size="1">
                                        <option value="none" <?php if($this->config[POINTS_SYSTEM]=='none') echo 'selected="selected"';?>><?php echo JText::_('OPTION_NONE'); ?></option>
                                        <option value="jomsocial" <?php if($this->config[POINTS_SYSTEM]==COMPONENT_JOMSOCIAL) echo 'selected="selected"';?>><?php echo JText::_('OPTION_JOMSOCIAL'); ?></option>
                                        <!-- <option value="touch" <?php if($this->config[POINTS_SYSTEM]==COMPONENT_TOUCH) echo 'selected="selected"';?>><?php echo JText::_('OPTION_MIGHTY_TOUCH'); ?></option> -->
                                        <option value="aup" <?php if($this->config[POINTS_SYSTEM]==COMPONENT_AUP) echo 'selected="selected"';?>><?php echo JText::_('OPTION_AUP'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_TOUCH_PTS_NEW_QUESTION_DESC'); ?>">
                                        <?php echo JText::_('LBL_TOUCH_PTS_NEW_QUESTION'); ?>
                                    </span>
                                </td>
                                <td>
                                    <input
                                        class="inputbox"
                                        name="<?php echo TOUCH_POINTS_CW_QUESTION ?>"
                                        id="<?php echo TOUCH_POINTS_CW_QUESTION ?>"
                                        type="text"
                                        size="25"
                                        value="<?php echo $this->config[TOUCH_POINTS_CW_QUESTION]; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_TOUCH_PTS_SOLVEDCW_DESC'); ?>">
                                        <?php echo JText::_('LBL_TOUCH_PTS_SOLVEDCW'); ?>
                                    </span>
                                </td>
                                <td>
                                    <input
                                        class="inputbox"
                                        name="<?php echo TOUCH_POINTS_SOLVED_CROSSWORD ?>"
                                        id="<?php echo TOUCH_POINTS_SOLVED_CROSSWORD ?>"
                                        type="text"
                                        size="25"
                                        value="<?php echo $this->config[TOUCH_POINTS_SOLVED_CROSSWORD]; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_COMMENT_SYSTEM_DESC'); ?>">
                                        <?php echo JText::_('LBL_COMMENT_SYSTEM'); ?>
                                    </span>
                                </td>
                                <td>
                                    <select
                                        class="inputbox"
                                        name="<?php echo COMMENT_SYSTEM ?>"
                                        id="<?php echo COMMENT_SYSTEM ?>"
                                        size="1">
                                            <option value="none" <?php if($this->config[COMMENT_SYSTEM]=='none') echo 'selected="selected"';?>><?php echo JText::_('OPTION_NONE'); ?></option>
                                            <option value="jomcomment" <?php if($this->config[COMMENT_SYSTEM]=='jomcomment') echo 'selected="selected"';?>><?php echo JText::_('OPTION_JOMCOMMENT'); ?></option>
                                            <option value="jcomment" <?php if($this->config[COMMENT_SYSTEM]=='jcomment') echo 'selected="selected"';?>><?php echo JText::_('OPTION_JCOMMENT'); ?></option>
                                            <option value="jacomment" <?php if($this->config[COMMENT_SYSTEM]=='jacomment') echo 'selected="selected"';?>><?php echo JText::_('OPTION_JACOMMENT'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_ACTIVITY_STREAM_TYPE_DESC'); ?>">
                                        <?php echo JText::_('LBL_ACTIVITY_STREAM_TYPE'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $date =& JFactory::getDate();
                                    ?>
                                    <select
                                        class="inputbox"
                                        name="<?php echo ACTIVITY_STREAM_TYPE ?>"
                                        id="<?php echo ACTIVITY_STREAM_TYPE ?>"
                                        size="1">
                                        <option value="none" <?php if($this->config[ACTIVITY_STREAM_TYPE]=='none') echo 'selected="selected"';?>><?php echo JText::_('OPTION_NONE'); ?></option>
                                        <option value="jomsocial" <?php if($this->config[ACTIVITY_STREAM_TYPE]==COMPONENT_JOMSOCIAL) echo 'selected="selected"';?>><?php echo JText::_('OPTION_JOMSOCIAL'); ?></option>
                                        <option value="touch" <?php if($this->config[ACTIVITY_STREAM_TYPE]==COMPONENT_TOUCH) echo 'selected="selected"';?>><?php echo JText::_('OPTION_MIGHTY_TOUCH'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_STREAM_NEW_QUESTION_DESC'); ?>">
                                        <?php echo JText::_('LBL_STREAM_NEW_QUESTION'); ?>
                                    </span>
                                </td>
                                <td>
                                    <input
                                        type="radio"
                                        name="<?php echo STREAM_NEW_QUESTION;?>"
                                        id="<?php echo STREAM_NEW_QUESTION;?>"
                                        value="1"
                                        <?php echo ($this->config[STREAM_NEW_QUESTION] == '1') ? 'checked="checked"':'';?>><?php echo JText::_('LBL_YES');?>
                                    <input
                                        type="radio"
                                        name="<?php echo STREAM_NEW_QUESTION;?>"
                                        id="<?php echo STREAM_NEW_QUESTION;?>"
                                        value="0"
                                        <?php echo ($this->config[STREAM_NEW_QUESTION] == '0') ? 'checked="checked"':'';?>><?php echo JText::_('LBL_NO');?>
                                </td>
                            </tr>
                            <tr>
                                <td class="key">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_STREAM_SOLVED_CROSSWORD_DESC'); ?>">
                                        <?php echo JText::_('LBL_STREAM_SOLVED_CROSSWORD'); ?>
                                    </span>
                                </td>
                                <td>
                                    <input
                                        type="radio"
                                        name="<?php echo STREAM_SOLVED_CROSSWORD;?>"
                                        id="<?php echo STREAM_SOLVED_CROSSWORD;?>"
                                        value="1"
                                        <?php echo ($this->config[STREAM_SOLVED_CROSSWORD] == '1') ? 'checked':'';?>><?php echo JText::_('LBL_YES');?>
                                    <input
                                        type="radio"
                                        name="<?php echo STREAM_SOLVED_CROSSWORD;?>"
                                        id="<?php echo STREAM_SOLVED_CROSSWORD;?>"
                                        value="0"
                                        <?php echo ($this->config[STREAM_SOLVED_CROSSWORD] == '0') ? 'checked':'';?>><?php echo JText::_('LBL_NO');?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <?php
                    echo $pane->endPanel();
                    echo $pane->startPanel( JText::_('TAB_PERMISSIONS'), 'tabPermissions' );
                    ?>
                    <table class="admintable">
                    	<tr>
                            <td class="key">
                                <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_PERMISSION_GUEST_ACCESS_DESC'); ?>">
                                    <?php echo JText::_('LBL_PERMISSION_GUEST_ACCESS'); ?>
                                </span>
                            </td>
                            <td>
                                <input
                                    type="checkbox"
                                    name="<?php echo PERMISSION_GUEST_ACCESS;?>"
                                    id="<?php echo PERMISSION_GUEST_ACCESS;?>"
                                    value="1"
                                    <?php echo ($this->config[PERMISSION_GUEST_ACCESS] == '1') ? 'checked':'';?>><?php echo JText::_('LBL_PERMISSION_GUEST_ACCESS');?>
                            </td>
                            <td colspan="3">&nbsp;&nbsp;</td>
                    	</tr>
                        <tr>
                            <td class="key">
                                <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_PERMISSION_ACCESS_DESC'); ?>">
                                    <?php echo JText::_('LBL_PERMISSION_ACCESS'); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo CrosswordsHelper::usersGroups(PERMISSION_ACCESS,PERMISSION_ACCESS.'[]',explode(",",$this->config[PERMISSION_ACCESS]));?>
                            </td>
                            <td>&nbsp;&nbsp;</td>
                            <td class="key">
                                <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_PERMISSION_CREATE_DESC'); ?>">
                                    <?php echo JText::_('LBL_PERMISSION_CREATE'); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo CrosswordsHelper::usersGroups(PERMISSION_CREATE,PERMISSION_CREATE.'[]',explode(",",$this->config[PERMISSION_CREATE]));?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <span class="editlinktip hasTip" title="<?php echo JText::_('LBL_PERMISSION_SUBMIT_WORDS_DESC'); ?>">
                                    <?php echo JText::_('LBL_PERMISSION_SUBMIT_WORDS'); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo CrosswordsHelper::usersGroups(PERMISSION_SUBMIT_WORDS,PERMISSION_SUBMIT_WORDS.'[]',explode(",",$this->config[PERMISSION_SUBMIT_WORDS]));?>
                            </td>
                            <td colspan="3">&nbsp;&nbsp;</td>
                        </tr>
                    </table>
                    <?php
                    echo $pane->endPanel();
                echo $pane->endPane();
                ?>
            </td>
        </tr>
    </tbody>
</table>
</div>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="task" value="save_config" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>