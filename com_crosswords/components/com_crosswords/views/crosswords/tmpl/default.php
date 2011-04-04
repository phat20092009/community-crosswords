<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
global $option;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$cwConfig = &CrosswordsHelper::getConfig();
$tmpl = JRequest::getCmd('tmpl',($this->tmpl?$this->tmpl:$cwConfig[DEFAULT_TEMPLATE]));
$layoutPath = CWTemplateManager::getLayoutPath($tmpl, $this->layoutPath);
$templateUrlPath = CWTemplateManager::getTemplateUrlPath($tmpl);
$document = &JFactory::getDocument();
if($layoutPath) {
    $templatePath = CWTemplateManager::getTemplatePath($tmpl);
    if($templatePath){
        $scripts = JFolder::files($templatePath . 'scripts', '.js');
        $styles = JFolder::files($templatePath . 'css', '.css');
        if($scripts){
            foreach($scripts as $script){
                $document->addScript( $templateUrlPath . '/scripts/' . $script);
            }
        }
        if($styles){
            foreach($styles as $style){
                $document->addStyleSheet($templateUrlPath . '/css/' . $style);
            }
        }
    }
    include_once $layoutPath;
}else {
    JError::raiseError( 403, JText::_('Access Forbidden. Error code: 10001') );
    return;
}

?>