<?php
/**
 * Joomla! 1.5 component Crosswords
 *
 * @version $Id: helper.php 2010-10-16 12:32:21 svn $
 * @author Maverick
 * @package Joomla
 * @subpackage Crosswords
 * @license GNU/GPL
 *
 * Crosswords is a Joomla component to generate crosswords with Community touch.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Crosswords Helper
 *
 * @package Joomla
 * @subpackage Crosswords
 * @since 1.5
 */
class CrosswordsHelper {

    function &getConfig($rebuild=false) {
        $app = &JFactory::getApplication();
        $cwConfig = $app->getUserState( SESSION_CONFIG );

        if(!isset($cwConfig) || $rebuild) {
            $db     =& JFactory::getDBO();
            $query = 'SELECT config_name, config_value FROM '. TABLE_CROSSWORDS_CONFIG;
            $db->setQuery($query);
            $configt = $db->loadObjectList();

            if($configt) {
                foreach($configt as $ct) {
                    $cwConfig[$ct->config_name] = $ct->config_value;
                }
            }else {
                JError::raiseError( 403, JText::_('Access Forbidden. Error Code: 10000.') );
                return;
            }
            $app->setUserState( SESSION_CONFIG, $cwConfig );
        }

        return $cwConfig;
    }

    function getMessage($message, $args) {
        $msg = $message;
        if($args && count($args) > 0) {
            for($i=0;$i<count($args);$i++) {
                $msg = str_replace($message, "{".$i."}", $args[$i]);
            }
        }
        return $msg;
    }

    /**
     * Gets the IP address of the currently visiting user.
     *
     * @return <type>
     */
    function getUserIP() {
		$ip = '';
		if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) AND strlen($_SERVER['HTTP_X_FORWARDED_FOR'])>6 ){
	        $ip = strip_tags($_SERVER['HTTP_X_FORWARDED_FOR']);
	    }elseif( !empty($_SERVER['HTTP_CLIENT_IP']) AND strlen($_SERVER['HTTP_CLIENT_IP'])>6 ){
			 $ip = strip_tags($_SERVER['HTTP_CLIENT_IP']);
		}elseif(!empty($_SERVER['REMOTE_ADDR']) AND strlen($_SERVER['REMOTE_ADDR'])>6){
			 $ip = strip_tags($_SERVER['REMOTE_ADDR']);
	    }
		return trim($ip);
    }

    /**
     * Gets the avatar of the user. Currently supporting JomSocial, Kunena, AUP and CB.
     *
     * @global <type> $cwConfig
     * @param <type> $userid
     * @param <type> $height
     * @return <type>
     */
    function getUserAvatar($userid=0, $height=48) {
        $app = &JFactory::getApplication();
        $cwConfig = $app->getUserState( SESSION_CONFIG );
        $db = JFactory::getDBO();
        $avatar = '';
        $username = $cwConfig[USER_NAME];
        switch ( $cwConfig[USER_AVTAR] ) {
            case 'jomsocial':
                $jspath = JPATH_BASE.DS.'components'.DS.'com_community';
                include_once($jspath.DS.'libraries'.DS.'core.php');

                // Get CUser object
                $jsuser =& CFactory::getUser($userid);
                $usrname = $jsuser->$username;
                $avatarLoc = $jsuser->getThumbAvatar();
                $link = CRoute::_('index.php? option=com_community&view=profile&userid='.$userid.'&Itemid='.JRequest::getInt('Itemid'));
                $avatar = '<a href="'.$link.'"><img class="hasTip" style="border: 1px solid #cccccc; height: '.$height.'px;" src="'.$avatarLoc.'" alt="'.$usrname.'"  title="'.$usrname.'"/></a>';
                break;
            case 'cb':
                $strSql = "SELECT `avatar`, firstname FROM #__comprofiler WHERE `user_id`='{$userid}' AND `avatarapproved`='1'";
                $db->setQuery($strSql);
                $result = $db->loadObject();
                $link = JRoute::_( 'index.php?option=com_comprofiler&amp;task=userProfile&amp;user=' . $userid);
                if($result && !empty($result->avatar)) {
                    $avatarLoc = JURI::base(true)."/images/comprofiler/".$result->avatar;
                } else {
                    $avatarLoc = JURI::base(true)."/components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png";
                }
                $avatar = '<a href="'.$link.'"><img src="'.$avatarLoc.'" class="hasTip" style="border: 1px solid #cccccc; height: '.$height.'px;" alt="'.$result->firstname.'" title="'.$result->firstname.'"/></a>';
                break;
            case 'touch':
                $avatarLoc = JURI::base(true) . '/index2.php?option=com_community&amp;controller=profile&amp;task=avatar&amp;width=' . $height . '&amp;height=' . $height . '&amp;user_id=' . $userid . '&amp;no_ajax=1';
                $avatar = '<img src="' . $avatarLoc . '" style="border: 1px solid #cccccc; height: ' . $height . 'px;" alt=""/>';
                $link = JRoute::_("index.php?option=com_community&view=profile&user_id={$userid}&Itemid=".JRequest::getInt('Itemid'));
                $avatar = '<a href="' . $link . '">' . $avatar . '</a>';
                break;
            case 'gravatar':
                $strSql = 'SELECT email FROM #__users WHERE id=' . $userid;
                $db->setQuery($strSql);
                $email = $db->loadResult();
				$url = 'http://www.gravatar.com/avatar/'.md5( strtolower( trim( $email ) ) );
				$url .= "?s=$height&d=mm&r=g";
				$avatar = '<img src="' . $url . '"/>';				
                break;
            case 'kunena':
                $query = "SELECT a.avatar, b." . $username . " FROM #__fb_users as a" .
                        " LEFT JOIN #__users as b on b.id=a.userid where a.userid=".$userid;
                $db->setQuery( $query );
                $kunenaUser = $db->loadObject();
                $kunena_avtar = @$kunenaUser->avatar;
                $kunena_avtars = JURI::base(true) . '/images/fbfiles';
                if ($kunena_avtar != '') {
                    if(!file_exists( JPATH_ROOT .'/images/fbfiles/avatars/l_' . $kunena_avtar)) {
                        $avatarLoc = $kunena_avtars . '/avatars/' . $kunena_avtar;
                    }
                    else {
                        $avatarLoc = $kunena_avtars . '/avatars/l_' . $kunena_avtar;
                    }
                }
                else $avatarLoc = $kunena_avtars . '/avatars/nophoto.jpg';
                //URL
                $usrname = ($kunenaUser->$username)?$kunenaUser->$username:JText::_('GUEST');
                $link = JRoute::_( 'index.php?option=com_kunena&amp;func=fbprofile&amp;userid=' . $userid);
                $avatar = '<a href="'.$link.'" title="'.$usrname.'"><img src="'.$avatarLoc.'" class="hasTip" style="border: 1px solid #cccccc; height: '.$height.'px;" alt="'.$usrname.'"/></a>';
                break;
            case 'aup':
                $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
                if ( file_exists($api_AUP)) {
                    require_once ($api_AUP);
                    $avatar = AlphaUserPointsHelper::getAupAvatar($userid, 1, $height, $height);
                }
                break;
        }
        return $avatar;
    }

    function getUserProfileUrl($userid=0, $username='Guest') {
        $app = &JFactory::getApplication();
        $cwConfig = $app->getUserState( SESSION_CONFIG );
        $link = null;
        
        switch ( $cwConfig[USER_AVTAR] ) {
            case 'jomsocial':
                $jspath = JPATH_BASE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php';
                if(file_exists($jspath)) {
                    include_once($jspath);
                    $link = '<a href="' . CRoute::_('index.php? option=com_community&view=profile&userid='.$userid) . '">' . $username . '</a>';
                }
                break;
            case 'cb':
                $link = '<a href="' . JRoute::_( 'index.php?option=com_comprofiler&amp;task=userProfile&amp;user=' . $userid) . '">' . $username . '</a>';
                break;
            case 'touch':
                $link = CommunityAnswersHelper::getTouchPopup($userid, $username);
                break;
            case 'kunena':
                $link = '<a href="' . JRoute::_( 'index.php?option=com_kunena&amp;func=fbprofile&amp;userid=' . $userid) . '">' . $username . '</a>';
                break;
            case 'aup':
                $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
                if ( file_exists($api_AUP)) {
                    require_once ($api_AUP);
                    $link = '<a href="' . AlphaUserPointsHelper::getAupLinkToProfil($userid) . '">' . $username . '</a>';
                }
                break;
        }
        return (!$link)?$username:$link;
    }

    function getTouchPopup($user_id, $user_name) {
    	global $option;
        Static $capi = false;
        Static $api_enabled = true;

        if($api_enabled == false) return $user_name;

        if(!$capi) {
            $API = JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'api.php';
            if(file_exists($API)) {
                require_once $API;
                $capi = new JSCommunityApi();
            }
            else {
                $api_enabled = false;
                return $user_name;
            }
        }

        $params['width'] = 400;

        $links[0]->link = JRoute::_( 'index.php?option='.$option.'&view=crosswords&task=user&uid='.$user_id.'&Itemid='.JRequest::getInt('Itemid'));
        $links[0]->alt = JText::_('LBL_ALL_CROSSWORDS_BY') . ' this user';
        $links[0]->icon = 'components/'.$option.'/assets/images/crosswords.png';

        $user_name = $capi->getUserSlideMenu($user_id, $user_name, $links, $params);

        return $user_name;
    }

    function getFormattedDate($strdate, $format='d m Y') {
        jimport('joomla.utilities.date');
        $user =& JFactory::getUser();
        if($user->get('id')) {
            $tz = $user->getParam('timezone');
        } else {
            $conf =& JFactory::getConfig();
            $tz = $conf->getValue('config.offset');
        }

        // Given time
        $jdate = new JDate($strdate);
        $jdate->setOffset($tz);
        $date = $jdate->toISO8601();
        
        return Date_Difference::getStringResolved($date);
    }

    function awardPoints($userid, $function, $referrence, $info){
        $app = &JFactory::getApplication();
        $cwConfig = &$app->getUserState(SESSION_CONFIG);
        if(strcasecmp($cwConfig[POINTS_SYSTEM], COMPONENT_AUP) == 0) {
            $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
            if ( file_exists($api_AUP)){
                require_once ($api_AUP);
                $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $userid );
                if ( $aupid ){
                    switch ($function){
                        case 1: //New Question
                            AlphaUserPointsHelper::newpoints( AUP_NEW_QUESTION, $aupid, $referrence, $info );
                            break;
                        case 2: // Solved crossword
                            AlphaUserPointsHelper::newpoints( AUP_SOLVE_CROSSWORD, $aupid, $referrence, $info );
                            break;
                    }
                }
            }
        }else if(strcasecmp($cwConfig[POINTS_SYSTEM], COMPONENT_JOMSOCIAL) == 0) {
            include_once( JPATH_SITE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php');
            switch ($function){
                case 1: //New Question
                     CuserPoints::assignPoint(JSP_NEW_QUESTION, $userid);
                    break;
                case 2: // New Answer
                    CuserPoints::assignPoint(JSP_SOLVED_CROSSWORD, $userid);
                    break;
            }
        }else if(strcasecmp($cwConfig[POINTS_SYSTEM], COMPONENT_TOUCH) == 0) {
            $API = JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'api.php';
            if(file_exists($API)){
                require_once $API;
                switch ($function){
                    case 1: //New Question
                        JSCommunityApi::increaseKarma($userid, $cwConfig[TOUCH_POINTS_NEW_QUESTION]);
                        break;
                    case 2: // New Answer
                        JSCommunityApi::increaseKarma($userid, $cwConfig[TOUCH_POINTS_SOLVED_CROSSWORD]);
                        break;
                }
            }
        }

    }

    function streamActivity($action, $obj) {
    	global $option;
        $app = &JFactory::getApplication();
        $cwConfig = $app->getUserState( SESSION_CONFIG );
        $user =& JFactory::getUser();
        $menu = &JSite::getMenu();
        $mnuitems	= $menu->getItems('link', 'index.php?option='.$option.'&view=crosswords');
        $itemid = isset($mnuitems[0]) ? '&amp;Itemid='.$mnuitems[0]->id : '';

        if(strcasecmp($cwConfig[ACTIVITY_STREAM_TYPE], COMPONENT_JOMSOCIAL) == 0) {
            $API = JPATH_SITE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php';
            if(file_exists($API)) {
                include_once( $API );
                $act = new stdClass();
                $act->cmd       = 'wall.write';
                $act->actor 	= $user->id;
                $act->target 	= 0; // no target
                $act->content 	= '';
                $act->app       = 'wall';
                $act->cid       = 0;
                
                switch ($action){
                    case 1: // New question
                        $act->title = JText::_('{actor} ' . JText::_('TXT_SUBMITTED_CROSSWORD_QUESTION'));
                        break;
                    case 2: // Solved crossword
                        $text = JText::_('TXT_SOLVED_CROSSWORD');
                        $link = JRoute::_('index.php?option='.$option.'&view=crosswords&task=view&id='.$obj->id.':'.$obj->alias. $itemid);
                        $act->title 	= JText::_('{actor} ' . $text . ' <a href="'.$link.'">'.$obj->title.'</a>');
                        break;
                }

                CFactory::load('libraries', 'activities');
                CActivityStream::add($act);
            }
        } else if(strcasecmp($cwConfig[ACTIVITY_STREAM_TYPE], 'touch') == 0){
            $API = JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'api.php';
            if(file_exists($API)) {
                require_once $API;
                $capi = new JSCommunityApi();
                if($user->id) {
                    $link = JRoute::_('index.php?option='.$option.'&view=crosswords&task=view&id='.$obj->id.":".$obj->alias.$itemid);
                    $icon = JURI::base() . 'components/'.$option.'/assets/images/logo.png';
                    $text = '';
                    switch ($action){
                        case 1: // New question
                            $text = $user->$cwConfig[USER_NAME] . ' '. JText::_('TXT_SUBMITTED_CROSSWORD_QUESTION');
                            break;
                        case 2: // solved crossword
                            $text = $user->$cwConfig[USER_NAME] . ' ' . JText::_('TXT_SOLVED_CROSSWORD') . ' <a href="'.$link.'">' . $obj->title . '</a>';
                            break;
                    }
                    $capi->registerActivity(0, $text, $user->get('id'), $icon, 'user', null, $option, '', 'Crosswords');
                }
            }
        }
    }

    function getReferenceLinks($reference){
        if(!$reference){
            return false;
        }
        $urls = array($reference);
        if(strpos($reference, '\n\r')){
            $urls = explode('\n\r', $reference);
        }else if(strpos('\n', $reference)){
            $urls = explode('\n', $reference);
        }
        $result = array();
        foreach($urls as $url){
            if (preg_match('~(?:www|ftp|http)[\.\:]+[\/\w]+~', $url)){
                $result[] = '<a href="' . $url . '">' . $url . "</a>";
            }
        }
        return implode('<br>',$result);
    }

    function printSpaces($n){
        for($i=0;$i<$n-1;$i++){
            echo "....";
        }
    }
    
    function sendMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null){
        // Get a JMail instance
        $mail = &JFactory::getMailer();

        $mail->setSender(array($from, $fromname));
        $mail->setSubject($subject);
        $mail->setBody($body);

        // Are we sending the email as HTML?
        if ($mode) {
            $mail->IsHTML(true);
        }

        $mail->addRecipient($recipient);
        $mail->addCC($cc);
        $mail->addBCC($bcc);
        $mail->addAttachment($attachment);

        // Take care of reply email addresses
        if (is_array($replyto)) {
            $numReplyTo = count($replyto);
            for ($i=0; $i < $numReplyTo; $i++){
                    $mail->addReplyTo(array($replyto[$i], $replytoname[$i]));
            }
        } elseif (isset($replyto)) {
            $mail->addReplyTo(array($replyto, $replytoname));
        }

        return  $mail->Send();
    }
    
    function nl2a($string) {
        $class_attr = ($class!='') ? ' class="'.$class.'"' : '';
        $strings = explode("\n", $string);
        $html = '';
        foreach($strings as $str){
            $str = trim($str);
            if((strncmp($str, 'http://', 7) == 0) || (strncmp($str, 'www.', 4) == 0)){
                $html .= '<a href="' . $str . '">' . $str . '</a>' . '<br>';
            }
        }
        return $html;
    }

    function getEmailText($question, $text){
        global $option;
        $menu = &JSite::getMenu();
        $mnuitem = $menu->getItems('link', 'index.php?option='.$option.'&view=answers', true);
        $itemid = isset($mnuitem) ? '&Itemid='.$mnuitem->id : '';
        jimport( 'joomla.environment.uri' );
        $url = JURI::root() . 'index.php?option='.$option.'&view=crosswords&task=view&id='.$question->id.$itemid;
        $url = '<a href="'.$url.'">'.$url.'</a>';
        $text = str_replace("{user}", $question->username, $text);
        $text = str_replace("{question}", $question->title, $text);
        $text = str_replace("{url}", $url, $text);
        return $text;
    }

    /**
     * Loads the modules published in the position name passed.
     *
     * @param <type> $position
     * @return <type>
     */
    function loadModulePosition($position) {
        if(JDocumentHTML::countModules($position)) {
            $document	= &JFactory::getDocument();
            $renderer	= $document->loadRenderer('modules');
            $options	= array('style' => 'xhtml');
            return $renderer->render($position, $options, null);
        }else {
            return '';
        }
    }
    
    function getPoweredByLink() {
        $poweredby = '<div style="text-align: center; width: 100%; font-family: arial; font-size:9px; font-color: #ccc;">' . JText::_('POWERED_BY') . ' <a href="http://www.corejoomla.com" alt="CoreJoomla">Community Crosswords</a></div>';
        return $poweredby;
    }
    
    function getItemId(){
        global $option;
        $menu = &JSite::getMenu();
        $mnuitem = $menu->getItems('link', 'index.php?option='.$option.'&view=crosswords', true);
        $ritemid = JRequest::getInt('Itemid');
        $catid = JRequest::getInt('catid');
        $catparam = isset($catid)? '&catid='.$catid : '';
        $itemid = isset($mnuitem) ? '&Itemid='.$mnuitem->id : (isset($ritemid)?'&Itemid='.$ritemid:'');
        return $itemid.$catparam;
    }
}
?>