						<?php
						if (array_key_exists("profil", $_GET) && array_key_exists("prihlasenyUzivatel", $_SESSION))
						{
							if ($_SESSION["prihlasenyUzivatel"] == $_GET["profil"])
							{
								$dotaz = $db->prepare("SELECT jmeno, vek, email, pohlavi FROM login
								WHERE email = ?");
	
								$dotaz->execute([$_SESSION["prihlasenyUzivatel"]]);
								$uprava = $dotaz->fetch();

								//var_dump($uprava);

								?>
								<details>
									<summary>Upravit profil</summary>

								<form method="post">
			            Jméno: <input type="text" name="jmeno" value="<?php echo $uprava["jmeno"]; ?>">
			            <?php
			            if (array_key_exists("jmeno", $chyby))
			            {
			                echo $chyby["jmeno"];
			            }
			            ?>
			            <br>
					
			            Věk: <input type="text" name="vek" value="<?php echo $uprava["vek"]; ?>">
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
			                    if ($identifikatorPohlavi == $uprava["pohlavi"])
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
					
			            <button name="upravit">upravit</button>
			        </form>

					<form method="post">
					Email: <input type="email" name="email" value="<?php echo $uprava["email"]; ?>">
			            <?php
			            if (array_key_exists("email", $chyby))
			            {
			                echo $chyby["email"];
			            }
			            ?>
			            <br>

						<button name="zmenitemail">Změnit email</button>
					</form>

					<form method="post">
					Heslo: <input type="password" name="heslo">
			            <?php
			            if (array_key_exists("heslo", $chyby))
			            {
			                echo $chyby["heslo"];
			            }
			            ?>
			            <br>
						
						<button name="zmenitheslo">Změnit heslo</button>
					</form>
					</details>
					<?php

							$dotaz = $db->prepare("SELECT login.jmeno, obsah, datum, obsah.id FROM login
							JOIN obsah ON login.email = obsah.jmeno
							WHERE login.email = ?
							ORDER BY obsah.id DESC");

							$dotaz->execute([$_SESSION["prihlasenyUzivatel"]]);
							$prispevky = $dotaz->fetchAll();

							//var_dump($prispevky);

							$dotaz = $db->prepare("SELECT fotky.cesta_fotky, fotky.id2, obsah.id FROM fotky
							JOIN obsah on obsah.id = fotky.id2
							ORDER BY fotky.id2 DESC");

							$dotaz->execute();
							$fotky = $dotaz->fetchAll();

							//var_dump($fotky);

							$dotaz = $db->prepare("SELECT login.jmeno, komentare.obsah, komentare.datum, komentare.id2 FROM komentare
							JOIN obsah ON obsah.id = komentare.id2
							JOIN login ON login.email = komentare.jmeno
							ORDER BY komentare.id2 DESC");

							$dotaz->execute();
							$vypsaniKomentare = $dotaz->fetchAll();
							
							foreach ($prispevky as $key => $prispevek)
							{
								echo "<div class='celyprispevek'>";

								//var_dump($prispevek);
								?>
								<div class="prispevek">
									<div class="infoPrispevku">
									<div class="jmeno">
										<?php echo "Příspěvek od: ".$prispevek["jmeno"] ?>
									</div>
									<div class="cas">
										<?php echo $prispevek["datum"] ?>
									</div>
									</div>
									<div class="obsahPrispevku">
										<?php echo $prispevek["obsah"] ?>
									</div>
									<div class="fotkyPrispevku">
										<?php
											foreach($fotky as $fotka)
											{
												if($prispevek["id"] == $fotka["id"])
												{
													//var_dump($fotka);
													$fotkaurl = $fotka["cesta_fotky"];
													echo "<a data-fslightbox='gallery' href='uploads/$fotkaurl'><img src='uploads/$fotkaurl' width=200px></a>";

												}
											}
										?>
									</div>
									<div class="mazani">
										<form method="post">
											<input type="hidden" name="idprispevku" value="<?php echo $prispevek["id"] ?>">
											<button name="smazat">Smazat</button>
										</form>
									</div>
								</div>

								<div class="komentare">
									<?php
									foreach($vypsaniKomentare as $komentar)
									if ($komentar["id2"] == $prispevek["id"])
									{
									?>
										<div class="komentar2">
										<div class="infokomentare">
										<div class="jmeno">
											<?php echo "Okomentoval: ".$komentar["jmeno"] ?>
										</div>
										<div class="cas">
											<?php echo $komentar["datum"] ?>
										</div>
										</div>
										<div class="obsahKomentare">
											<?php echo $komentar["obsah"] ?>
										</div>
										</div>
										<?php
									}
									?>
								</div>
								</div>
								<?php
							}
							}
						}