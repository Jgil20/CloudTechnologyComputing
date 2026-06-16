<?php
	//Sender information (Change here according to your form field name)
	$senderemail = $email;
	$fstname = (isset($_POST['fname'])) ? ' '.htmlspecialchars($_POST['fname'],ENT_QUOTES) : '';
	$lstname = (isset($_POST['lname'])) ? ' '.htmlspecialchars($_POST['lname'],ENT_QUOTES) : '';
	$sendername = $fstname.$lstname;
	
	$mail->clearAddresses();
	$mail->clearCCs();
	$mail->clearBCCs();
	$mail->clearAttachments();
	
	//Recipient address and name
	$mail->addAddress($senderemail, $sendername);
	
	//Subject (Change here)
	$subject = "mgscoder.com - We have received your Request";
	$mail->Subject = htmlspecialchars($subject, ENT_QUOTES);
	
	$mgsetemple = true;		//Boolean true/false	true: email template	false: plain text email
	$body_message = "";
	if(!$mgsetemple) {
		//prepare email body [for Plain email use this] (Change here)
		$body_message .= "Hi". $sendername .",<br><br>";
		$body_message .= "Thank you for getting in touch!<br>
We appreciate you contacting us. One of our customer happiness members will be getting back to you shortly.<br>
Thanks in advance for your patience.<br>
Have a great day!<br><br>";
		$body_message .= "Regards,<br>";
		$body_message .= "Cloud Technology Computing Team ";
	}
	else{			
		//prepare email body [Using email template]
		$body_message = file_get_contents('mgsc-email-template/mgsc-confirmation-et.php');
		$mgssemailshorttag = array("[mgs-sender-name]", "[mgs-year]");
		$mgssemailshorttagvalue   = array($sendername, date("Y"));
		$body_message = str_replace($mgssemailshorttag, $mgssemailshorttagvalue, $body_message);
	}
	
	$mail->Body = $body_message;
	
	//send mail
	if(!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
?>
