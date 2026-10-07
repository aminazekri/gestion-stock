<?php
$p = new Produit('P001', 'Clavier', 150, 10);
verifier($p->getQuantite() === 10, 'La quantité initiale est 10');
$p->retirerQuantite(3);
verifier($p->getQuantite() === 7, 'Après retrait de 3, il en reste 7');
verifier(abs($p->valeurStock() - 1050) < 0.001, 'La valeur du stock vaut 7 x 150');

$p->ajouterQuantite(5);
verifier($p->getQuantite() === 12, 'Après ajout de 5, il y en a 12');

$ok = false;
try { new Produit('P002', 'Souris', -10); } catch (InvalidArgumentException $e) { $ok = true; }
verifier($ok, 'Un prix négatif lève une InvalidArgumentException');

$ok = false;
try { new Produit('P003', 'Écran', 900, -1); } catch (InvalidArgumentException $e) { $ok = true; }
verifier($ok, 'Une quantité négative lève une InvalidArgumentException');

$ok = false;
try { $p->ajouterQuantite(0); } catch (Exception $e) { $ok = true; }
verifier($ok, 'Ajouter 0 lève une exception');

$ok = false;
try { $p->retirerQuantite(100); } catch (Exception $e) { $ok = true; }
verifier($ok, 'Retirer plus que le stock lève une exception');
verifier((new Produit('P004', 'Câble', 20))->getQuantite() === 0, 'La quantité par défaut est 0');