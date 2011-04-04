<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
global $option;
$imagePath = JURI::base(true) . '/components/'.$option.'/assets/images';
$images	= array();

$crosswords->path		= $imagePath . "/crosswords2.png";
$crosswords->title		= JText::_('LBL_CROSSWORDS');
$crosswords->href		= "index.php?option=".$option."&task=crosswords";
$images[]				= $crosswords;

$keywords->path			= $imagePath . "/keywords.png";
$keywords->title		= JText::_('LBL_KEYWORDS');
$keywords->href			= "index.php?option=".$option."&task=keywords";
$images[]				= $keywords;

$categories->path		= $imagePath . "/categories.png";
$categories->title		= JText::_('LBL_CATEGORIES');
$categories->href		= "index.php?option=".$option."&task=categories";
$images[]				= $categories;

$configuration->path    = $imagePath . "/configuration.png";
$configuration->title   = JText::_('LBL_CONFIG');
$configuration->href    = "index.php?option=".$option."&task=config";
$images[]               = $configuration;
?>
<table class="contentpaneopen" width="100%">
    <tr>
        <td width="100%" valign="top">
            <div id="cpanel">
                <?php
                foreach($images as $image) { ?>
                <div class="icon">
                    <a href="<?php echo $image->href; ?>">
                        <img src="<?php echo $image->path; ?>" alt="<?php echo $image->title; ?>" align="top" border="0" />
                        <span><?php echo JText::_( $image->title ); ?></span>
                    </a>
                </div>
				<?php } ?>
            </div>
        </td>
    </tr>
</table>