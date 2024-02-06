					<?php
							if (array_key_exists("uzivatel", $_GET))
							{
								if ($_GET['uzivatel'] == "")
								{
									echo "Nic nenalezeno";
									
								}

								else
								{
									$dotaz = $db->prepare("SELECT jmeno FROM login
									WHERE login.jmeno LIKE ?");

									$vyhledat = "%{$_GET['uzivatel']}%";

									$dotaz->execute([$vyhledat]);
									$jmena = $dotaz->fetchAll();
								

							//var_dump($jmena);
							if (count($jmena) == 0)
							{
								echo "Nic nenalezeno";
							}
							else
							{
							foreach ($jmena as $jmeno)
							{
								//var_dump($jmeno);
								echo "<a href='?jmeno={$jmeno['jmeno']}'>{$jmeno['jmeno']}</a><br>";
							}
							}
							}
							}

							if (array_key_exists("jmeno", $_GET))
							{

							$dotaz = $db->prepare("SELECT jmeno, vek FROM login
							WHERE login.jmeno LIKE ?");
	
							$vyhledat = "%{$_GET['jmeno']}%";
	
							$dotaz->execute([$vyhledat]);
							$info = $dotaz->fetch();

							//var_dump($info);
							
							echo "<div class='info'>";
								echo "Profil Uživatele: {$info['jmeno']}<br>";
								echo "Věk uzivatele: {$info['vek']}";
							echo "</div>";

							$dotaz = $db->prepare("SELECT login.jmeno, obsah, datum, obsah.id FROM login
							JOIN obsah ON login.email = obsah.jmeno
							WHERE login.jmeno LIKE ?
							ORDER BY obsah.id DESC");

							$vyhledat = "%{$_GET['jmeno']}%";

							$dotaz->execute([$vyhledat]);
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
								<?php
								echo "</div>";
							}
						}