<?php
defined('_JEXEC') or die('Restricted access');
global $option;
$app = &JFactory::getApplication();
$cwConfig = &$app->getUserState(SESSION_CONFIG);
?>
<form id="adminForm" action="index.php?option=<?php echo $option;?>" method="post" name="adminForm">
<table>
    <tr>
        <td align="left" width="100%">
            <?php echo JText::_( 'Filter' ); ?>:
            <input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
            <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
            <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
        </td>
        <td nowrap="nowrap">
            <select name="catid" id="catid" class="inputbox" onchange="this.form.submit();">
                <option value=""><?php echo JText::_("TXT_SELECT_CATEGORY")?></option>
                <?php
                if($this->categories){
	                foreach($this->categories as $category){
	                	if($category->id > 0){
	                    	echo '<option value="' . $category->id . '"' . (($this->lists['catid'] == $category->id)?'selected="selected"':'') . '>' . $category->title . '</option>';
	                	}
	                }
                }
                ?>
            </select>
            <select name="uid" id="uid" class="inputbox" onchange="this.form.submit();">
                <option value=""><?php echo JText::_("TXT_SELECT_USER");?></option>
                <?php
                if($this->users){
	                foreach($this->users as $author){
	                    echo '<option value="' . $author->created_by . '"' . (($this->lists['uid'] == $author->created_by)?'selected="selected"':'') . '>' . $author->username . ' ( ' . $author->name . ' ) ' . '</option>';
	                }
                }
                ?>
            </select>
            <?php echo $this->lists['state']; ?>
        </td>
    </tr>
</table>
<?php 
if($this->keywords){
?>
<table class="adminlist">
    <thead>
        <tr>
            <th width="20"><?php echo JText::_( '#' ); ?></th>
            <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->keywords ); ?>);" /></th>
            <th class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_KEYWORD' ), 'a.keyword', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_QUESTION' ), 'a.question', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="5%"><?php echo JText::_( 'LBL_CROSSWORDS' ); ?></th>
            <th width="10%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_CATEGORY' ), 'a.category', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="10%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_USERNAME' ), 'a.created_by', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="5%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_PUBLISHED' ), 'a.published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="11%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_CREATED_ON' ), 'a.created', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="20"><?php echo JText::_( 'ID' ); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $k = 0;
    $i=0;
    foreach($this->keywords as $row){
    $checked    = JHTML::_( 'grid.id', $i, $row->id );
    $link = JRoute::_('index.php?option='.$option.'&task=edit_keyword&cid='.$row->id);
    $link2 = JRoute::_('index.php?option='.$option.'&task=keywords_uses&keyid='.$row->id);
    ?>
    <tr class="<?php echo "row$k"; ?>">
        <td>
            <?php echo $this->pagination->getRowOffset( $i ); ?>
        </td>
        <td>
            <?php echo $checked; ?>
        </td>
        <td>
            <a href="<?php echo $link?>" onmouseover=""><?php echo $this->escape($row->keyword); ?></a>
        </td>
        <td>
            <a href="<?php echo $link?>" onmouseover=""><?php echo $this->escape($row->question); ?></a>
        </td>
        <td align="center">
            <a href="<?php echo $link2?>" onmouseover=""><?php echo $row->crosswords ? $row->crosswords : 0; ?></a>
        </td>        
        <td>
            <?php echo $this->escape($row->category); ?>
        </td>
        <td>
            <?php echo ($row->username)?$this->escape($row->username) : JText::_('GUEST_NAME'); ?>
        </td>
        <td align="center">
            <?php
                $publishUrl = "#";
                if($row->published == 1){
                    $img = 'published.png';
                    $alt = 'LBL_UNPUBLISH';
                    $publishUrl = JRoute::_( 'index.php?option='.$option.'&task=unpublish_keywords&cid[]='. $row->id );
                }else{
                    $img = 'unpublished.png';
                    $alt = 'LBL_PUBLISH';
                    $publishUrl = JRoute::_( 'index.php?option='.$option.'&task=publish_keywords&cid[]='. $row->id );
                }
             ?>
            <a href="<?php echo $publishUrl; ?>">
                <img src="components/<?php echo $option;?>/assets/images/<?php echo $img; ?>" border="0" title="<?php echo JText::_( $alt ); ?>" alt="<?php echo JText::_( $alt ); ?>" />
            </a>
        </td>
        <td>
            <?php echo JHTML::Date($this->escape($row->created), $cwConfig[DATE_FORMAT]); ?>
        </td>
        <td>
            <?php echo $row->id; ?>
        </td>
    </tr>
    <?php
    $k = 1 - $k;
    $i++;
}
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
        </tr>
    </tfoot>
</table>
<?php
}else{
    echo JText::_('MSG_NO_RESULTS');
}
?>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="task" value="keywords" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="Itemid" value="0" />
<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_Dir']) echo $this->lists['order_Dir']; ?>" />
</form>