<?php
/**
 * Joomla! 1.5 component Community Crosswords
 *
 * @version $Id: install.crosswords.php 2009-08-10 03:45:15 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Community Crosswords
 * @license GNU/GPL
 *
 * The Community Crosswords allows the members of the Joomla website to create and play Crosswords from the front-end. The administrator has the powerful tools provided in the back-end to manage the Crosswords published by all users.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Initialize the database
$db =& JFactory::getDBO();
$update_queries = array ();

/*Default Configuration Properties */
$update_queries[] = 'INSERT INTO `#__crosswords_config` (`config_name`, `config_value`) VALUES ' .
    '("user_avatar","none"),' .
    '("user_name", "username"),'.
    '("comment_system", "none"),'.
	'("points_system", "none"),'.
    '("default_template", "default"),'.
    '("permission_access", "25"),'.
    '("permission_create", "25"),'.
	'("permission_guest_access", "1"),'.
    '("powered_by_enabled", "1"),'.
    '("list_limit", "20")';
// Perform all queries - we don't care if it fails
foreach( $update_queries as $query ) {
    $db->setQuery( $query );
    $db->query();
}
echo "<b><font color=\"red\">Database tables successfully updated to the latest version. Please check the configuration options once again.</font></b>";
?>