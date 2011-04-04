<?php
/**
 * Joomla! 1.5 component Community Crosswords
 *
 * @version $Id: helper.php 2010-10-02 03:45:15 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Community Surveys
 * @license GNU/GPL
 *
 * Crosswords is a Joomla component to generate crosswords with Community touch.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CWTemplateManager {

    function getLayoutPath($template, $layout) {
        if(strlen($layout) <= 0) {
            return false;
        }
        $templatePath = CWTemplateManager::getTemplatePath($template);
        if(JFile::exists($templatePath . $layout . '.php')){
            return $templatePath . $layout . '.php';
        }else{
            return false;
        }
    }

    function getTemplatePath($template){
        if(strlen($template) <= 0) {
            $templatePath =  DEFAULT_TEMPLATE_PATH . DS . "default" . DS;
        }else if(JFolder::exists(TEMPLATE_OVERRIDES_PATH . DS . $template )) {
            $templatePath =  TEMPLATE_OVERRIDES_PATH . DS . $template . DS;
        }else if(JFolder::exists(DEFAULT_TEMPLATE_PATH . DS . $template )) {
            $templatePath =  DEFAULT_TEMPLATE_PATH . DS . $template . DS;
        }else {
            $templatePath =  DEFAULT_TEMPLATE_PATH . DS . "default" . DS;
        }

        return $templatePath;
    }

    function getTemplateUrlPath($template){
        if(strlen($template) <= 0) {
            $templatePath =  DEFAULT_TEMPLATE_URL . "default";
        }else if(JFolder::exists(TEMPLATE_OVERRIDES_PATH . DS . $template )) {
            $templatePath =  TEMPLATE_OVERRIDES_URL . $template;
        }else if(JFolder::exists(DEFAULT_TEMPLATE_PATH . DS . $template )) {
            $templatePath =  DEFAULT_TEMPLATE_URL . $template;
        }else {
            $templatePath =  DEFAULT_TEMPLATE_URL . "default";
        }

        return $templatePath;
    }

    function renderModules($position, $attribs = array()) {
        jimport( 'joomla.application.module.helper' );
        $modules = JModuleHelper::getModules( $position );
        $modulehtml = '';

        foreach($modules as $module) {
            $params = new JParameter( $module->params );
            $moduleClassSuffix 	= $params->get('moduleclass_sfx', '');

            $modulehtml .= '<div class="moduletable'.$moduleClassSuffix.'">';
            $modulehtml .= JModuleHelper::renderModule($module, $attribs);
            $modulehtml .= '</div>';
        }

        echo $modulehtml;
    }
}