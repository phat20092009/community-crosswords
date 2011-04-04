<?php
/**
 * @version		$Id: date.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla16.Crosswords
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
/*
 * JavaScript Pretty Date
 * Copyright (c) 2008 John Resig (jquery.com)
 * Licensed under the MIT license.
 */

defined('_JEXEC') or die('Restricted access');

// Ported to PHP >= 5.1 by Zach Leatherman (zachleat.com)
class Date_Difference
{
	public static function getStringResolved($date, $compareTo = NULL){
		if(!is_null($compareTo)) {
			$compareTo = new DateTime($compareTo);
		}
		return self::getString(new DateTime($date), $compareTo);
	}

	public static function getString(DateTime $date, DateTime $compareTo = NULL){
		if(is_null($compareTo)) {
			$compareTo = new DateTime('now');
		}
		$diff = $compareTo->format('U') - $date->format('U');
		$dayDiff = floor($diff / 86400);

		if(is_nan($dayDiff) || $dayDiff < 0) {
			return '';
		}

		if($dayDiff == 0) {
			if($diff < 60) {
				return JText::_('TXT_JUST_NOW');
			} elseif($diff < 120) {
				return JText::_('TXT_ONE_MINUTE_AGO');
			} elseif($diff < 3600) {
				return floor($diff/60) . ' ' . JText::_('TXT_N_MINUTES_AGO');
			} elseif($diff < 7200) {
				return JText::_('TXT_ONE_HOUR_AGO');
			} elseif($diff < 86400) {
				return floor($diff/3600) . ' ' . JText::_('TXT_N_HOURS_AGO');
			}
		} elseif($dayDiff == 1) {
			return JText::_('TXT_YESTERDAY');
		} elseif($dayDiff < 7) {
			return $dayDiff . ' ' . JText::_('TXT_N_DAYS_AGO');
		} elseif($dayDiff == 7) {
			return JText::_('TXT_ONE_WEEK_AGO');
		} elseif($dayDiff < (7*6)) { // Modifications Start Here
			// 6 weeks at most
			return ceil($dayDiff/7) . ' ' . JText::_('TXT_N_WEEKS_AGO');
		} elseif($dayDiff < 365) {
			return ceil($dayDiff/(365/12)) . ' ' . JText::_('TXT_N_MONTHS_AGO');
		} else {
			$years = round($dayDiff/365);
			return $years . ' ' . JText::_('TXT_YEAR'). ($years != 1 ? 's' : '') . ' ' . JText::_('TXT_AGO');
		}
	}
}