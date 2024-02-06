<?php
$to = "tomik520i@seznam.cz";
$subject = "Test mail";
$message = "Hello! This is a simple email message.";
$from = "my_email@example.com";
$headers = "From: $from";
mail($to,$subject,$message,$headers);
if (mail($to,$subject,$message,$headers)) echo "Mail Sent.";
else echo "Not sent";