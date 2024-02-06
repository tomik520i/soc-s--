<?php

$seznamPohlavi = [
    "muz" => "Muž",
    "zena" => "Žena",
    "jine" => "Jiné",
];

if (array_key_exists("zaregistrovat", $_POST))
{
    $jmeno = $_POST["jmeno"];
    $vek = $_POST["vek"];
    $pohlavi = $_POST["pohlavi"];
	$email = $_POST["email"];
	$heslo = $_POST["heslo"];

    $hash = password_hash($heslo, PASSWORD_DEFAULT);

    $dotaz = $db->prepare("SELECT email FROM login WHERE email = ?");
    $dotaz->execute([$email]);
    $kontrolaEmailu = $dotaz->fetch();

    if ($kontrolaEmailu == false)
    {
        if ($email == "")
	    {
	    	$chyby["email"] = "Musí být vyplněno";
	    }
	    if(!preg_match("/.+@.+\\..+/" ,$email))
	    {
	    	$chyby["email"] = "Neplatný email";
	    }
    }
    else if ($kontrolaEmailu != false)
    {
        $chyby["email"] = "Email již existuje";
    }


    // kontrola jmena
    if ($jmeno == "")
    {
        $chyby["jmeno"] = "Musí být vyplněno";
    }
    else if (mb_strlen($jmeno) < 3)
    {
        $chyby["jmeno"] = "Příliš krátké jméno";
    }

	if ($heslo == "")
    {
        $chyby["heslo"] = "Musí být vyplněno";
    }
    else if (mb_strlen($heslo) < 5)
    {
        $chyby["heslo"] = "Příliš krátké heslo";
    }

    // kontrola veku
    if ($vek == "")
    {
        $chyby["vek"] = "Musí být vyplněno";
    }
    else if (is_numeric($vek) == false)
    {
        $chyby["vek"] = "Musí být číslo";
    }
    else if ($vek > 150)
    {
        $chyby["vek"] = "Nesprávná hodnota věku";
    }

    // kontrola pohlavi
    if ($pohlavi == "")
    {
        $chyby["pohlavi"] = "Musíte zvolit pohlaví";
    }

    // kontrola zdali je vse v poradku
    if (count($chyby) == 0)
    {
        $vseOK = true;

		$dotaz = $db->prepare("INSERT INTO login SET
		jmeno = ?, pohlavi = ?, vek = ?, email = ?, heslo = ? ");

		$dotaz->execute([$jmeno, $pohlavi, $vek, $email, $hash]);
    }
}