<?php
$hlaskaResetu = null;

if (array_key_exists("resetovat", $_POST))
{
	$email = $_POST["resetovatEmail"];

	//var_dump($email);

	$dotaz = $db->prepare("SELECT email FROM login WHERE email = ?");
    $dotaz->execute([$email]);
    $kontrolaEmailu = $dotaz->fetch();

	if ($kontrolaEmailu != false)
	{
		$noveHeslo = rand(1000000, 9999999);

		require "vendor/autoload.php";

		$mail = new PHPMailer\PHPMailer\PHPMailer(true);

		$mail->CharSet = "utf-8";

		$mail->setFrom('resethesla@nevim.com', 'Nevim');

		$mail->addAddress($email);

		$mail->isHTML(true);
		$mail->Subject = 'Resetované Heslo';

		$mail->Body = "
		<h1>Vaše nové heslo</h1>
		<div><b>Vaše nové heslo:</b> $noveHeslo</div>
		";

		$mail->send();

		$hash = password_hash($noveHeslo, PASSWORD_DEFAULT);

		$dotaz = $db->prepare("UPDATE login SET heslo = ? WHERE email = ?");
		$dotaz->execute([$hash, $email]);

		$hlaskaResetu = "Email s novým heslem byl úspěšně odeslán";
	}
	else
	{
		$hlaskaResetu = "Email nebyl nalezen";
	}
}