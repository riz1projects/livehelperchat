<?php

class erLhcoreClassChatExport {

	public function chatExportXML(erLhcoreClassModelChat $chat) {
		$tpl = new erLhcoreClassTemplate('lhexport/xml.tpl.php');
		$tpl->set('chat', $chat);
		return $tpl->fetch();
	}

	public function chatExportJSON(erLhcoreClassModelChat $chat) {
		$tpl = new erLhcoreClassTemplate('lhexport/json.tpl.php');
		$tpl->set('chat', $chat);
		return $tpl->fetch();
	}
	
	public static function exportDepartmentStats($departments) {
	    include 'lib/core/lhform/PHPExcel.php';
			$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
			$cacheSettings = array( 'memoryCacheSize ' => '64MB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

	    $objPHPExcel = new PHPExcel();
	    $objPHPExcel->setActiveSheetIndex(0);
	    $objPHPExcel->getActiveSheet()->getStyle('A1:AW1')->getFont()->setBold(true);
	    $objPHPExcel->getActiveSheet()->setTitle('Report');
	    
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "ID");
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Department name'));
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Pending chats number'));
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Active chats number'));
	    
	    $attributes = array(
	        'id',
	        'name',
	        'pending_chats_counter',
	        'active_chats_counter',
	    );
	    
	    $i = 2;
	    foreach ($departments as $item) {
	        foreach ($attributes as $key => $attr) {
	            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($key, $i, (string)$item->{$attr});
	        }
	        $i++;
	    }
	    
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	    
	    // We'll be outputting an excel file
	    header('Content-type: application/vnd.ms-excel');
	    
	    // It will be called file.xls
	    header('Content-Disposition: attachment; filename="report.xlsx"');
	    
	    // Write file to the browser
	    $objWriter->save('php://output');
	}
	
	public static function chatListExportXLS($chats, $params = array()) {

		include 'lib/core/lhform/PHPExcel.php';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		$cacheSettings = array( 'memoryCacheSize ' => '64MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		
		$chatArray = array();
		
		$id = "ID";
		$name = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Visitor Name');
		$email = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','E-mail');
		$phone = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Phone');
		$wait = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Wait time');
		$country = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Country');
		$city = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','City');
		$ip = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','IP');
		$operator = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Operator');
		$dept = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Department');
		$date = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Date');
		$minutes = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Minutes');
		$vote = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Vote status');
		$mail = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Mail send');
		$page = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Page');
		$from = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Came from');
		$link = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Link');
		$remarks = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Remarks');
		$additionalDataPlain = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Additional plain');
		$additionalData = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Additional data');
        $survey = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Survey data');

		if (isset($params['type']) && ($params['type'] == 2 || $params['type'] == 4)) {
		    $content = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/chatexport','Chat content');
		} else {
			$content = null;
		}

        if (isset($params['type']) && $params['type'] == 2) {
            $chatArray[] = array($id, $name, $email, $phone, $wait, $country, $city, $ip, $operator, $dept, $date, $minutes, $vote, $mail, $page, $from, $link, $remarks, $content, $additionalDataPlain, $additionalData);
        } elseif (isset($params['type']) && $params['type'] == 3) {
            $chatArray[] = array($id, $name, $email, $phone, $wait, $country, $city, $ip, $operator, $dept, $date, $minutes, $vote, $mail, $page, $from, $link, $remarks, $survey, $additionalDataPlain, $additionalData);
            $surveyData = erLhAbstractModelSurveyItem::getList(array_merge(array('filterin' => array('chat_id' => array_keys($chats)), 'offset' => 0, 'limit' => 100000)));
        } elseif (isset($params['type']) && $params['type'] == 4) {
            $chatArray[] = array($id, $name, $email, $phone, $wait, $country, $city, $ip, $operator, $dept, $date, $minutes, $vote, $mail, $page, $from, $link, $remarks, $survey, $content, $additionalDataPlain, $additionalData);
            $surveyData = erLhAbstractModelSurveyItem::getList(array_merge(array('filterin' => array('chat_id' => array_keys($chats)), 'offset' => 0, 'limit' => 100000)));
        } else {
            $chatArray[] = array($id, $name, $email, $phone, $wait, $country, $city, $ip, $operator, $dept, $date, $minutes, $vote, $mail, $page, $from, $link, $remarks, $additionalDataPlain, $additionalData);
        }

        $exportChatData = array();
        foreach ($surveyData as $surveyItem)
        {
            $survey = erLhAbstractModelSurvey::fetch($surveyItem->survey_id);
            $exported = erLhcoreClassSurveyExporter::exportRAW(array($surveyItem),$survey);

            $pairs = array();

            foreach ($exported['value'] as $chatId => $valueItems) {
                foreach ($exported['title'] as $indexColumn => $columnName) {
                    $pairs[] = $columnName . ' - ' . $valueItems[$indexColumn];
                }
            }

            $exportChatData[$surveyItem->chat_id] = implode(', ',$pairs);
        }

        foreach ($chats as $item) {
                $id = (string)$item->{'id'};
                $nick = (string)$item->{'nick'};
                $email = (string)$item->{'email'};
                $phone = (string)$item->{'phone'};
                $wait = (string)$item->{'wait_time'};
                $country = (string)$item->{'country_name'};
                $city = (string)$item->{'city'};
                $ip = (string)$item->{'ip'};
                $user = (string)$item->{'user'};
                $dept = (string)$item->{'department'};
                $remarks = (string)$item->{'remarks'};

                $date = date(erLhcoreClassModule::$dateFormat,$item->time);
                $minutes = date('H:i:s',$item->time);
                $vote = ($item->fbst == 1 ? 'UP' : ($item->fbst == 2 ? 'DOWN' : 'NONE'));
                $mail = $item->mail_send == 1 ? 'Yes' : 'No';
                $page = $item->referrer;
                $additionalDataContent = $item->additional_data;

                $additionalDataPlain = '';

                $additionalPairs = array();

                if (!empty($additionalDataContent)){
                    foreach (json_decode($additionalDataContent,true) as $additionalItem) {
                        $additionalPairs[] = $additionalItem['key'] . ' - ' . $additionalItem['value'];
                    }
                }

                if ($item->session_referrer != '') {
                        $referer = parse_url($item->session_referrer);                    
                        if (isset($referer['host'])) {
                            $from = $referer['host'];
                        } else {
                        	$from = null;
                        }
                } else {
                	$from = null;
                }

                $url = erLhcoreClassXMP::getBaseHost() . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('user/login').'/(r)/'.rawurlencode(base64_encode('chat/single/'.$item->id));

                // Print chat content to last column
                if (isset($params['type']) && ($params['type'] == 2 || $params['type'] == 4)) {

                    $messages = erLhcoreClassModelmsg::getList(array('limit' => 10000,'sort' => 'id ASC','filter' => array('chat_id' => $item->id)));                       
                    $messagesContent = '';

                    foreach ($messages as $msg ) {
                        if ($msg->user_id == -1) {
                                $messagesContent .= date(erLhcoreClassModule::$dateDateHourFormat,$msg->time).' '. erTranslationClassLhTranslation::getInstance()->getTranslation('chat/syncadmin','System assistant').': '.htmlspecialchars($msg->msg)."\n";
                        } else {
                                $messagesContent .= date(erLhcoreClassModule::$dateDateHourFormat,$msg->time).' '. ($msg->user_id == 0 ? htmlspecialchars($item->nick) : htmlspecialchars($msg->name_support)).': '.htmlspecialchars($msg->msg)."\n";
                        }
                    }

                    if ($params['type'] == 2) {
                        $chatArray[] = array($id, $nick, $email, $phone, $wait, $country, $city, $ip, $user, $dept, $date, $minutes, $vote, $mail, $page, $from, $url, $remarks, trim($messagesContent),implode(', ',$additionalPairs), $additionalDataContent);
                    } else {
                        $chatArray[] = array($id, $nick, $email, $phone, $wait, $country, $city, $ip, $user, $dept, $date, $minutes, $vote, $mail, $page, $from, $url, $remarks, (isset($exportChatData[$item->id]) ? $exportChatData[$item->id] : ''), trim($messagesContent),implode(', ',$additionalPairs), $additionalDataContent);
                    }

                } elseif ($params['type'] == 3) {
                    $chatArray[] = array($id, $nick, $email, $phone, $wait, $country, $city, $ip, $user, $dept, $date, $minutes, $vote, $mail, $page, $from, $url, $remarks, (isset($exportChatData[$item->id]) ? $exportChatData[$item->id] : ''), implode(', ',$additionalPairs), $additionalDataContent);
                } else {
                	$chatArray[] = array($id, $nick, $email, $phone, $wait, $country, $city, $ip, $user, $dept, $date, $minutes, $vote, $mail, $page, $from, $url, $remarks, implode(', ',$additionalPairs), $additionalDataContent);
                }
        }

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		// Set the starting point and array of data
		$objPHPExcel->getActiveSheet()->fromArray($chatArray, null, 'A1');

		// Set style for top row
		$objPHPExcel->getActiveSheet()->getStyle('A1:AW1')->getFont()->setBold(true);

		// Set file type and name of file
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="report.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		$writer->save('php://output');
	}
}

?>