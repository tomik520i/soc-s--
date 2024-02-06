<?php
if (array_key_exists("prihlasenyUzivatel", $_SESSION))
						{
						?>
						<div class="pridavani">
							<form method="post" enctype="multipart/form-data">
								<textarea name="prispevek" cols="60" rows="3"></textarea>

								<div class="tlacitka2">
								<input type="file" name="fileToUpload[]" multiple>
								

								<button name="odeslat-prispevek">Odeslat</button><br>
								</div>
							</form>
							<?php if(array_key_exists("prazdnyPrispevek", $chyba2)) echo $chyba2["prazdnyPrispevek"] ?><br>
							<?php if(array_key_exists("souborNebylNahran", $chyba2)) echo $chyba2["souborNebylNahran"] ?><br>
							<?php if(array_key_exists("souborBylNahran", $chyba2)) echo $chyba2["souborBylNahran"] ?><br>
						</div>
						<?php
						}
						?>
							<?php
							$dotaz = $db->prepare("SELECT login.jmeno, obsah, datum, obsah.id FROM login
							JOIN obsah ON login.email = obsah.jmeno
							ORDER BY obsah.id DESC");

							$dotaz->execute();
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
								if (array_key_exists("prihlasenyUzivatel", $_SESSION))
								{
								?>
									<div class="komentar">
										<form method="post">
											<textarea name="komentar" cols="40" rows="2"></textarea>

											<?php if(array_key_exists("prazdnyKomentar", $chyba2)) echo $chyba2["prazdnyKomentar"] ?>

											<input type="hidden" name="id" value="<?php echo $prispevek["id"] ?>">
											<input type="hidden" name="pregmatch" value="okomentovat<?php echo $prispevek["id"] ?>">
											<div class="komentar">
											<button name="okomentovat<?php echo $prispevek["id"] ?>">okomentovat</button>
											</div>
										</form>
									
								</div>
								<?php
								}
								echo "</div>";
							}
								?>