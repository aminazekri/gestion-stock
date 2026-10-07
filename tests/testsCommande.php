<?php
$clavier = new Produit('C001', 'Clavier', 150, 10);
$souris  = new Produit('C002', 'Souris', 80, 5);

$cmd = new Commande(1);
$cmd->ajouterLigne($clavier, 2);
$cmd->ajouterLigne($souris, 3);
verifier(abs($cmd->total() - 540) < 0.001, 'Le total vaut 2x150 + 3x80 = 540');
verifier(!$cmd->estValidee(), 'Une nouvelle commande n\'est pas validée');

$cmd->valider();
verifier($cmd->estValidee(), 'La commande est validée');
verifier($clavier->getQuantite() === 8 && $souris->getQuantite() === 2, 'La validation retire les quantités du stock');
verifier(str_contains($cmd->afficher(), 'TOTAL'), 'La facture contient le total');

$ok = false;
try { $cmd->valider(); } catch (Exception $e) { $ok = true; }
verifier($ok, 'Valider deux fois lève une exception');

$ok = false;
try { (new Commande(2))->valider(); } catch (Exception $e) { $ok = true; }
verifier($ok, 'Valider une commande vide lève une exception');

$ok = false;
try { (new Commande(3))->ajouterLigne($souris, 50); } catch (Exception $e) { $ok = true; }
verifier($ok, 'Commander plus que le stock lève une exception');

$ok = false;
try { (new Commande(4))->ajouterLigne($souris, 0); } catch (Exception $e) { $ok = true; }
verifier($ok, 'Une quantité nulle lève une exception');