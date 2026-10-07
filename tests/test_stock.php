<?php
// Tests de Stock. Variables préfixées par $stk pour éviter les collisions
// avec les autres fichiers de test (tous inclus dans le même scope).

$stk = new Stock();
verifier($stk->compter() === 0, 'Un nouveau stock est vide');

$stkClavier = new Produit('P001', 'Clavier', 150, 10);
$stkSouris  = new Produit('P002', 'Souris', 50, 5);
$stk->ajouter($stkClavier);
$stk->ajouter($stkSouris);

verifier($stk->compter() === 2, 'Après deux ajouts, le stock compte 2 produits');
verifier($stk->trouver('P001') === $stkClavier, 'trouver(P001) retourne le clavier');
verifier($stk->trouver('P999') === null, 'trouver() retourne null pour une référence inconnue');
verifier(count($stk->tous()) === 2, 'tous() retourne les 2 produits');

$stkException = false;
try {
    $stk->ajouter(new Produit('P001', 'Autre clavier', 99, 1));
} catch (InvalidArgumentException $e) {
    $stkException = true;
}
verifier($stkException, 'Ajouter une référence déjà existante lève une exception');
verifier($stk->compter() === 2, 'Le doublon n\'a pas été ajouté');

// --- valeurTotale et filtres ---
$stkEcran = new Produit('P003', 'Ecran', 300, 0);
$stk->ajouter($stkEcran);

verifier(abs($stk->valeurTotale() - 1750) < 0.001, 'valeurTotale = 10 x 150 + 5 x 50 + 0 x 300 = 1750');

$stkRupture = $stk->produitsEnRupture();
verifier(count($stkRupture) === 1 && $stkRupture[0] === $stkEcran, 'produitsEnRupture retourne uniquement l\'écran');

verifier(count($stk->produitsSousSeuil(6)) === 2, 'produitsSousSeuil(6) retourne la souris (5) et l\'écran (0)');
verifier(count($stk->produitsSousSeuil(5)) === 1, 'Le seuil est strict : la souris (5) est exclue pour le seuil 5');
verifier(count($stk->produitsSousSeuil(0)) === 0, 'produitsSousSeuil(0) ne retourne aucun produit');