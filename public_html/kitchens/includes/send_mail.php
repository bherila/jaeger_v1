<?php
/**
 * Copyright (c) 2008 Asidus.com. All Rights Reserved.
 * http://asidus.com
 */ 

require("phpmailer/class.phpmailer.php");

function send_mail($subject, $from, $from_name, $to, $cc, $bcc, $message, $attachment, &$error){
    
    $mail = new phpmailer;

	if(SEND_EMAILS_USING_SMTP){
    	$mail->IsSMTP();
		$mail->Host = SMTP_HOST;
		
	    if(USE_SMTP_AUTHENTICATION){
			$mail->SMTPAuth = true;     
			$mail->Username = SMTP_EMAIL;  
			$mail->Password = SMTP_PASSWORD; 
		}
	} else{
    	$mail->IsMail();
	}

    $mail->CharSet = 'UTF-8';

    $mail->IsHTML(true);                                  
    //$mail->WordWrap = 75;
    
    if(!is_array($attachment)){
    	if($attachment != ''){
    		$attachment = array($attachment);
    	}
    	else{
    		$attachment = array();	
    	}
    	
    }
    if(!is_array($to)){
        if($to != '') $to = array($to);
        else          $to = array();
    }
    if(!is_array($cc)){
        if($cc != '') $cc = array($cc);
        else          $cc = array();
    }
    if(!is_array($bcc)){
        if($bcc != '') $bcc = array($bcc);
        else           $bcc = array();
    }
    
    foreach($to as $to_mail){
        if($to_mail != '') 
            $mail->AddAddress($to_mail);
    }
    foreach($cc as $cc_mail){
        if($cc_mail != '') 
            $mail->AddCC($cc_mail);
    }
    foreach($bcc as $bcc_mail){
        if($bcc_mail != '') 
            $mail->AddBCC($bcc_mail);
    }
    foreach ($attachment as $mail_attachment){
    	if($mail_attachment != ''){
    		$path = $mail_attachment;
			if(strrpos($mail_attachment, '/') !== false){
				$mail_attachment = substr($mail_attachment, strrpos($mail_attachment, '/') + 1);
			}
    		$mail->AddAttachment($path, $mail_attachment);
    	}
    }
    
    $mail->From     = $from;
    $mail->FromName = $from_name;
    $mail->Subject  = $subject;
    $mail->Body     = $message;

    $mail->AltBody = strip_tags( preg_replace('[<\/tr>|<br>|<br\/>|<br \/>]', chr(13), $message));

    if(!$mail->Send())
    {
       $error = $mail->ErrorInfo;
       return 0;
    }

    return 1;
}
   
?>