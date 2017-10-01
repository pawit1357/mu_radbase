<?php
return array (
		'name' => 'radbase',
		'defaultController' => 'site',
		'import' => array (
				'application.models.*',
				'application.components.*' 
		),
		'components' => array (
				'urlManager' => array (
						'urlFormat' => 'path' 
				),
				'db' => array (
						'class' => 'CDbConnection',
						'connectionString' => 'mysql:host=localhost;dbname=muraddb',
// 						'connectionString' => 'mysql:host=mdc.mahidol;dbname=radbasewww',
						'emulatePrepare' => true,
						'username' => 'root',
						'password' => 'P@ssw0rd',
// 						'username' => 'radbasewww',
// 						'password' => 'RadBase#2016#',
						'charset' => 'utf8' 
				),
				'Smtpmail' => array (
						'class' => 'application.extensions.smtpmail.PHPMailer',
						'Host' => "smtp.gmail.com",
						'Username' => 'radbasenoti@gmail.com',
						'Password' => 'radbasenotiP@ssw0rd',
						'Mailer' => 'smtp',
						'Port' => 465,
						'SMTPAuth' => true,
						'SMTPSecure' => 'ssl',
						'SMTPDebug' => 1 
				) 
		)
		 
);
?>