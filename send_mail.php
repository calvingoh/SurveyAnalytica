<?php
//send_mail.php

if(isset($_POST['email_data']))
{
	require 'class/class.phpmailer.php';
	$output = '';
	$email = $_POST['email_data'];
	$arr_length = count($email);
	for ($i=1; $i <= $arr_length ;$i++)
	{
		$mail = new PHPMailer;
		$mail->IsSMTP();								//Sets Mailer to send message using SMTP
		$mail->Host = 'smtp.gmail.com';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
									//Sets the default SMTP server port
		$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
		$mail->Username = 'surveyanalytica2019@gmail.com';					//Sets SMTP username
		$mail->Password = 'gaywayland';					//Sets SMTP password
		$mail->SMTPSecure = 'tls';							//Sets connection prefix. Options are "", "ssl" or "tls"

		$mail->setFrom('surveyanalytica2019@gmail.com', 'Survey Analytica');
		$mail->addAddress($email[$i][0]);   // Add a recipient

		$mail->isHTML(true);  // Set email format to HTML$mail->Subject = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'; //Sets the Subject of the message
		//An HTML or plain text message body
		$mail->Subject = $email[$i][1];
		$mail->Body = $email[$i][2];

		$result = $mail->Send();						//Send an Email. Return true on success or false on error

		if($result["code"] == '400')
		{
			$output .= html_entity_decode($result['full_error']);
		}

	}
	if($output == '')
	{
		echo 'ok';
	}
	else
	{
		echo $output;
	}
}

?>
