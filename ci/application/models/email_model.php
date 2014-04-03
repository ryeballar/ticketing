<?php

class Email_Model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function send($subject, $message, Array $emails) {
		$this->load->library('phpmailer');
		$mail = $this->phpmailer;

		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "softeng.ticketing@gmail.com";
		$mail->Password = "softeng123";
		$mail->SetFrom("softeng.ticketing@gmail.com");
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->SMTPDebug = 0;
		
		foreach($emails as $email)
			$mail->addAddress($email);

		if(!$mail->Send()) {
			$data['success'] = false;
			$data['error'] = $mail->ErrorInfo;
		} else {
			$data['success'] = true;
		}
		return $data;
	}
}