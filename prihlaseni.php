<?php
$chybaPrihlaseni = null;
$heslo2 = null;

	if (array_key_exists("prihlasit", $_POST))
	{
		$jmeno = $_POST["uzivatel"];
		$heslo = $_POST["heslo"];

		$dotaz = $db->prepare("SELECT email, heslo FROM login WHERE email = ?");
		$dotaz->execute([$jmeno]);
		$uzivatele = $dotaz->fetch();

		if ($uzivatele != false)
		{
			$heslo2 = password_verify($heslo, $uzivatele["heslo"]);
		}

		if ($uzivatele != false && $heslo2 == true)
		{
			$uzivatel = [$uzivatele["email"] => $uzivatele["heslo"]];

			$uzivatelExistuje = array_key_exists($jmeno, $uzivatel);

			if ($uzivatelExistuje && $heslo2 == true)
			{
				$_SESSION["prihlasenyUzivatel"] = $jmeno;
			}

			else
			{
				$chybaPrihlaseni = "Nesprávné přihlašovací údaje";
			}
		}

		if ($heslo2 == false)
		{
			$chybaPrihlaseni = "Nesprávné heslo";
		}

		if ($uzivatele == false)
		{
			$chybaPrihlaseni = "Přihlašovací email neexistuje";
		}
	}


if (array_key_exists("odhlasit", $_POST))
{
	unset($_SESSION["prihlasenyUzivatel"]);
}