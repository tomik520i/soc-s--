<?php
class existujeChyba extends Exception
{

}

class velikostChyba extends Exception
{

}

class formatChyba extends Exception
{

}

class notimgChyba extends Exception
{

}

class chyba extends Exception
{

}

class prazdnyChyba extends Exception
{

}

class prispevek2
{
	function vlozitPrispevek()
	{
		$prispevek = $_POST["prispevek"];

		$prispevek = htmlspecialchars($prispevek);
	
	//var_dump($_FILES);
	
	$velikostPole = count($_FILES["fileToUpload"]["name"]);
	$cislo = 0;
	
	//var_dump($velikostPole);
	if ($prispevek == "")
	{
		$chyba = new prazdnyChyba();
		throw $chyba;
	}
	else
	{
	
	for ($cislo; $cislo < $velikostPole; $cislo++)
	{
		if (array_key_exists("fileToUpload", $_FILES))
		{
	
			//var_dump($_FILES["fileToUpload"]["name"][0]);
	
	
			if ($_FILES["fileToUpload"]["name"][0] != "")
			{
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$cislo]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
			// Check if image file is a actual image or fake image
			if(isset($_POST["fileToUpload"])) {
			  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$cislo]);
			  if($check !== false) {
				$chyba["souborNebylNahran"] = "File is an image - " . $check["mime"][$cislo] . ".";
				$uploadOk = 1;
			  } else {

				$chyba = new notimgChyba();
				throw $chyba;

				$uploadOk = 0;
			  }
			}
			
			// Check if file already exists
			if (file_exists($target_file)) {

				$chyba = new existujeChyba();
				throw $chyba;

			  $uploadOk = 0;
			}
			
			// Check file size
			if ($_FILES["fileToUpload"]["size"][$cislo] > 5000000) {

				$chyba = new velikostChyba();
				throw $chyba;

			  $uploadOk = 0;
			}
			
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {

				$chyba = new formatChyba();
				throw $chyba;

			  $uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$chyba = new chyba();
				throw $chyba;
			// if everything is ok, try to upload file
			} else {
			  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$cislo], $target_file)) {
				//return $chyba2["souborBylNahran"] = "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"][$cislo])). " has been uploaded.";

			  } else {
				$chyba = new chyba();
				throw $chyba;
			  }
			}
			}
		}
	}
	}
	}
}

$prispevek2 = new prispevek2();

if (array_key_exists("odeslat-prispevek", $_POST))
{
	try
	{
		$prispevek2->vlozitPrispevek();

		$prispevek = $_POST["prispevek"];

		$prispevek = htmlspecialchars($prispevek);

		$datumCas = date("Y-m-d H:i:s");

		$dotaz = $db->prepare("INSERT INTO obsah SET
		jmeno = ?, obsah = ?, datum = ?");
		
		$dotaz->execute([$_SESSION["prihlasenyUzivatel"], $prispevek, $datumCas]);

		$dotaz = $db->prepare("SELECT MAX(id) FROM obsah");
		$dotaz->execute();
		$lastId = $dotaz->fetch();

		$cislo = 0;
		$velikostPole = count($_FILES["fileToUpload"]["name"]);

		for ($cislo; $cislo < $velikostPole; $cislo++)
		{
			if ($_FILES["fileToUpload"]["name"][0] != "")
			{
				$dotaz = $db->prepare("INSERT INTO fotky
				SET jmeno = ?, cesta_fotky = ?, id2 = ?");

				$dotaz->execute([$_SESSION["prihlasenyUzivatel"], $_FILES["fileToUpload"]["name"][$cislo], $lastId["MAX(id)"]]);

				$chyba2["souborBylNahran"] = "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"][$cislo])). " has been uploaded.";
			}
		}
	}

	catch (existujeChyba $chyba)
	{
		$chyba2["souborNebylNahran"] = "Sorry, file already exists.";

	}
	
	catch (velikostChyba $chyba)
	{
		$chyba2["souborNebylNahran"] = "Sorry, your file is too large.";

	}
	
	catch (formatChyba $chyba)
	{
		$chyba2["souborNebylNahran"] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

	}

	catch (notimgChyba $chyba)
	{
		$chyba2["souborNebylNahran"] = "File is not an image.";
	}

	catch (chyba $chyba)
	{
		$chyba2["souborNebylNahran"] = "Sorry, there was an error uploading your file.";
	}

	catch (prazdnyChyba $chyba)
	{
		$chyba2["prazdnyPrispevek"] = "Příspěvek nesmí být prázdný";
	}

	catch (Exception $chyba)
	{
		$chyba2["souborNebylNahran"] = "Nepodařilo se přidat příspěvek";
	}
}


// komentář
//var_dump($_POST);
if (array_key_exists("pregmatch", $_POST))
{
	preg_match("/^okomentovat.*$/", $_POST["pregmatch"], $matches);


if (array_key_exists($matches[0], $_POST))
{
	if ($_POST["komentar"] == "")
	{
		$chyba2["prazdnyKomentar"] = "Komentář nesmí být prázdný";
	}
	else
	{

$dotaz = $db->prepare("SELECT obsah.id FROM obsah WHERE obsah.id = ?");
$dotaz->execute([$_POST["id"]]);
$idPrispevku = $dotaz->fetchAll();

//var_dump($idPrispevku);

foreach ($idPrispevku as $key => $idPrispevku)
{
	$idPrispevku2 = "okomentovat".$idPrispevku['id'];
	//var_dump($idPrispevku);

	if (array_key_exists($idPrispevku2, $_POST))
	{
		$komentar = $_POST["komentar"];
		$id = $_POST["id"];

		$komentar = htmlspecialchars($komentar);

		//var_dump($id);
		//var_dump($komentar);
		
		
		$datumCas = date("Y-m-d H:i:s");
	
		$dotaz = $db->prepare("INSERT INTO komentare SET
		jmeno = ?, obsah = ?, datum = ?, id2 = ?");

		$dotaz->execute([$_SESSION["prihlasenyUzivatel"], $komentar, $datumCas, $id]);
		
	}
}
	}
}
}

if (array_key_exists("smazat", $_POST))
{
	$id = $_POST["idprispevku"];

	//var_dump($_POST);
	//var_dump($id);

	$dotaz = $db->prepare("DELETE FROM obsah WHERE id = ?");

	$dotaz->execute([$id]);
}
