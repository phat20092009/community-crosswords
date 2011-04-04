<?php
defined('_JEXEC') or die('Restricted access');
global $option;
?>
<form id="adminForm" action="index.php?option=<?php echo $option;?>" method="post" name="adminForm">
<?php if($this->categories):?>
<table class="adminlist">
    <thead>
        <tr>
            <th width="20"><?php echo JText::_( '#' ); ?></th>
            <th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->categories ); ?>);" /></th>
            <th width="20"><?php echo JText::_( 'ID' ); ?></th>
            <th width="30%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_TITLE' ), 'a.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="4%" class="title"><?php echo JHTML::_( 'grid.sort', JText::_( 'LBL_PUBLISHED' ), 'a.published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $k = 0;
    $i=0;
    foreach($this->categories as $row){
    $checked    = JHTML::_( 'grid.id', $i, $row->id );
    $link = 'index.php?option='.$option.'&task=edit_category&cid='.$row->id;
    ?>
    <tr class="<?php echo "row$k"; ?>">
        <td align="center"><?php echo $i+1; ?></td>
        <td align="center"><?php echo $checked; ?></td>
        <td align="center"><?php echo $row->id; ?></td>
        <td><a href="<?php echo $link?>" onmouseover=""><?php echo $this->escape($row->title); ?></a></td>
        <td align="center">
            <?php
                $publishUrl = "#";
                if($row->published == 1){
                    $img = 'published.png';
                    $alt = 'LBL_UNPUBLISH';
                    $publishUrl = JRoute::_( 'index.php?option='.$option.'&task=unpublish_categories&cid[]='. $row->id );
                }else{
                    $img = 'unpublished.png';
                    $alt = 'LBL_PUBLISH';
                    $publishUrl = JRoute::_( 'index.php?option='.$option.'&task=publish_categories&cid[]='. $row->id );
                }
             ?>
            <a href="<?php echo $publishUrl; ?>">
                <img src="components/<?php echo $option;?>/assets/images/<?php echo $img; ?>" border="0" title="<?php echo JText::_( $alt ); ?>" alt="<?php echo JText::_( $alt ); ?>" />
            </a>
        </td>
        <td></td>
    </tr>
    <?php
    $k = 1 - $k;
    $i++;
	}
	?>
    </tbody>
</table>
<?php else:?>
<?php echo JText::_('MSG_NO_RESULTS');?>
<?php endif;?>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="task" value="crosswords" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="Itemid" value="0" />
</form>