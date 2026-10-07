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