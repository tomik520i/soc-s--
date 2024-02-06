<?php
session_start();

//var_dump($_GET);

if (count($_GET) == 0 || array_key_exists("home", $_GET))
{
	$stranka = "home.php";
}
else if (array_key_exists("profil", $_GET))
{
	$stranka = "muj-profil.php";
}
else if (array_key_exists("hledat", $_GET) || array_key_exists("jmeno", $_GET))
{
	$stranka = "hledat.php";
}
else
{
	$stranka = "home.php";
}

// připojení na databázi
require "databaze.php";

$jmeno = null;
$vek = null;
$pohlavi = null;
$chyby = [];
$chyba = [];
$chyba2 = [];
$vseOK = false;
$email = null;
$heslo = null;

// registrace
require "registrace.php";


// přihlášení
require "prihlaseni.php";

// prispevek-komentar
require "prispevek-komentar.php";

// zapomenute heslo
require "zapomenute-heslo.php";

//var_dump($_POST);

require "uprava.php";

?>
<!DOCTYPE html>
<html lang="cs">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nevim</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/header.css">
	<link rel="stylesheet" href="css/section.css">
	<link rel="stylesheet" href="css/side.css">


</head>
<body>
<div class="grid">
	<header>
		<div class="container">

			<div class="nadpis-hledani">
			<h1><a href="?stranka=home">nevim</h1></a>

			<div class="hledani">
			<form method="get">
				<input type="text" name="uzivatel">
				<button name="hledat">Vyhledat</button>
			</form>
			</div>
			</div>

		<div class="formulare">
			<?php
		if(array_key_exists("prihlasenyUzivatel", $_SESSION) == false)
		{
		?>
			<div class="tlacitka">
				<form method="get">
					<button name="login">Login</button>
					<button name="registrace">Registrace</button>
					<button name="zapomenuteHeslo">Zapomenuté heslo</button>
				</form>
			</div>
		<?php
		}
		?>

		<?php
			if (array_key_exists("registrace", $_GET))
			{
			?>
			<div class="registrace">
			<?php
			if(array_key_exists("prihlasenyUzivatel", $_SESSION) == false)
			{
			    if ($vseOK == false)
			    {
			        ?>
			        <form method="post">
			            Jméno: <input type="text" name="jmeno" value="<?php echo $jmeno; ?>">
			            <?php
			            if (array_key_exists("jmeno", $chyby))
			            {
			                echo $chyby["jmeno"];
			            }
			            ?>
			            <br>

						Heslo: <input type="password" name="heslo">
			            <?php
			            if (array_key_exists("heslo", $chyby))
			            {
			                echo $chyby["heslo"];
			            }
			            ?>
			            <br>

						Email: <input type="email" name="email" value="<?php echo $email; ?>">
			            <?php
			            if (array_key_exists("email", $chyby))
			            {
			                echo $chyby["email"];
			            }
			            ?>
			            <br>
					
			            Věk: <input type="text" name="vek" value="<?php echo $vek; ?>">
			            <?php
			            if (array_key_exists("vek", $chyby))
			            {
			                echo $chyby["vek"];
			            }
			            ?>
			            <br>
					
			            Pohlaví: <select name="pohlavi">
			                <option value="">Vyberte</option>
			                <?php
			                foreach ($seznamPohlavi as $identifikatorPohlavi => $nazevPohlavi)
			                {
			                    $selected = '';
			                    if ($identifikatorPohlavi == $pohlavi)
			                    {
			                        $selected = 'selected';
			                    }
			                    echo "<option value='$identifikatorPohlavi' $selected>$nazevPohlavi</option>";
			                }
			                ?>
			            </select>
			            <?php
			            if (array_key_exists("pohlavi", $chyby))
			            {
			                echo $chyby["pohlavi"];
			            }
			            ?>
			            <br>
					
			            <button name="zaregistrovat">Zaregistrovat</button>
			        </form>
			        <?php
			    }
			    else
			    {
			        // vse ok - vypsat rekapitulaci
			        echo "<h1>Registrace proběhla úspěšně</h1>";
			    }

			echo "</div>";
			}
			}
			?>
			<?php
		if(array_key_exists("prihlasenyUzivatel", $_SESSION) == false)
		{
			if (array_key_exists("login", $_GET))
			{
			?>
			<div class="login">
				<form method="post">
					Email:<input type="email" name="uzivatel"> <br>
					Heslo:<input type="password" name="heslo"> <br>
					<button name="prihlasit">Přihlásit</button>
				</form>
				<?php
					echo $chybaPrihlaseni;

			echo "</div>";
			}
		}
			?>

		<?php
		if(array_key_exists("prihlasenyUzivatel", $_SESSION) == false)
		{
			if (array_key_exists("zapomenuteHeslo", $_GET))
			{
			?>
			<div class="resetHesla">
				<form method="post">
					Email:<input type="email" name="resetovatEmail"> <br>
					<button name="resetovat">Resetovat</button>
				</form>
				<?php

			echo "</div>";

			echo $hlaskaResetu;
			}
		}
			?>

			<?php
			if (array_key_exists("prihlasenyUzivatel", $_SESSION))
			{
			?>
					<div class="prihlaseny">
						<form method="post">
							<button name="odhlasit">Odhlásit</button>
						</form>
					</div>
			<?php
			}
			?>
			</div>
		
		</div>
	</header>
	
	<main>
		<div class="container">
			<div class="grid2">
				<aside>
					<div class="profil">
					<?php
					if (array_key_exists("prihlasenyUzivatel", $_SESSION))
					{
						$dotaz = $db->prepare("SELECT jmeno FROM login WHERE email = ?");
						$dotaz->execute([$_SESSION["prihlasenyUzivatel"]]);
						$prihlaseniUzivatel = $dotaz->fetch();

						$encode = urlencode($_SESSION['prihlasenyUzivatel']);

						echo "<a href='?profil=$encode'>{$prihlaseniUzivatel['jmeno']}</a>";
					}
					?>
					</div>
					
				</aside>
				<section>
					<div class="obsah">
						<div class="obsahfora">
						<?php
							require $stranka;
						?>

						<script src='fslightbox-basic-3.4.1/fslightbox.js'></script>

						</div>
					</div>
				</section>
			</div>
		</div>
	</main>

	<footer>
		<div class="container">

		</div>
	</footer>
</div>
</body>
</html>