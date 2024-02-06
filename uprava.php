<?php
if (array_key_exists("upravit", $_POST))
{
	$jmeno = $_POST["jmeno"];
	$pohlavi = $_POST["pohlavi"];
	$vek = $_POST["vek"];

    // kontrola jmena
    if ($jmeno == "")
    {
        $chyby["jmeno"] = "Musí být vyplněno";
    }
    else if (mb_strlen($jmeno) < 3)
    {
        $chyby["jmeno"] = "Příliš krátké jméno";
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
	$dotaz = $db->prepare("UPDATE login SET jmeno = ?, vek = ?, pohlavi = ? WHERE email = ?");
	$dotaz->execute([$jmeno, $vek, $pohlavi, $_SESSION["prihlasenyUzivatel"]]);
	}
}

if (array_key_exists("zmenitemail", $_POST))
{
	$email = $_POST["email"];

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
    if ($kontrolaEmailu != false)
    {
        $chyby["email"] = "Email již existuje";
    }

	if (count($chyby) == 0)
	{
	$dotaz = $db->prepare("UPDATE login SET email = ? WHERE email = ?");
	$dotaz->execute([$email, $_SESSION["prihlasenyUzivatel"]]);

	$dotaz = $db->prepare("UPDATE fotky SET jmeno = ? WHERE jmeno = ?");
	$dotaz->execute([$email, $_SESSION["prihlasenyUzivatel"]]);

	$dotaz = $db->prepare("UPDATE komentare SET jmeno = ? WHERE jmeno = ?");
	$dotaz->execute([$email, $_SESSION["prihlasenyUzivatel"]]);

	$dotaz = $db->prepare("UPDATE obsah SET jmeno = ? WHERE jmeno = ?");
	$dotaz->execute([$email, $_SESSION["prihlasenyUzivatel"]]);

	unset($_SESSION["prihlasenyUzivatel"]);
	}
}

if (array_key_exists("zmenitheslo", $_POST))
{
	$heslo = $_POST["heslo"];

	if ($heslo == "")
    {
        $chyby["heslo"] = "Musí být vyplněno";
    }
    else if (mb_strlen($heslo) < 5)
    {
        $chyby["heslo"] = "Příliš krátké heslo";
    }
    if (count($chyby) == 0)
	{
	$hash = password_hash($_POST["heslo"], PASSWORD_DEFAULT);

	$dotaz = $db->prepare("UPDATE login SET heslo = ? WHERE email = ?");
	$dotaz->execute([$hash, $_SESSION["prihlasenyUzivatel"]]);
	}
}
