<?php

////////////////////////////////////////////////////////////////////////////
//
// CONFIG
//
////////////////////////////////////////////////////////////////////////////
$email_from 			= 'sales@jaegerlumber.com';
$email_to 				= 'sales@jaegerlumber.com';

$thank_you_page 		= 'thankyou.html';
$error_page 			= 'contacterror.html';

$subject_field_name 	= 'subject';

// SMTP Settings
define('SEND_EMAILS_USING_SMTP',  	'1');
define('SMTP_HOST',  				'mail.jaegerlumber.com');
define('USE_SMTP_AUTHENTICATION',	'1');
define('SMTP_EMAIL',  				'sales@jaegerlumber.com');
define('SMTP_PASSWORD',  			'jaeger0073');
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
// CODE/////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
$path_to_root = '';
while(!file_exists($path_to_root . 'includes/send_mail.php')) $path_to_root .= '../';
require_once($path_to_root . 'includes/send_mail.php');

$ignore_fields_for_message = array('encrypted_security_string', 'security_string', $subject_field_name);

$message = '<table>';

foreach($_POST as $key => $value)
{
	if( ! in_array($key, $ignore_fields_for_message) )
	{
		if (is_array($value))
		{	
			$key = str_replace('_', ' ', $key);
			$message .= "<tr><td align='left'>{$key}:&nbsp;</td>";
			$N = count($value);
			
			for($i=0; $i < $N; $i++)
			{					
				$message .= "<tr><td><td>{$value[$i]}</td></tr>";
			}			
			$message .= "<tr><td><hr /></td><td><hr /></td></tr>";
		}	
		else
		{
			$key = str_replace('_', ' ', $key);
			$message .= "<tr><td align='left'>{$key}:&nbsp;</td>";
			$message .= "<td>{$value}</td></tr>";	
		}		
	}
}

$message .= '</table>';

if( isset($_POST[$subject_field_name]) )
{
	$subject = $_POST[$subject_field_name];
}
else
{
	if (isset($_SERVER['HTTP_HOST'])) 
	{
		$domain = $_SERVER['HTTP_HOST'];
	} 
	else 
	{
		$domain = $_SERVER['SERVER_NAME'];
	}
	
	$subject = "Message from $domain";
}

$error = '';

if( send_mail( 
	$subject,
	$email_from, // From
	'', // From Name
	$email_to, 
	'', 
	'', 
	$message, // Message
	'', // attachment
	$error
) )
{
	header("Location: $thank_you_page");
} 
else
{
	header("Location: $error_page");
}
	
?>