<?php
require __DIR__ . '/autoload.php';

$stock = new Stock();
$numeroCommande = 1;

function lire(string $question): string
{
    echo $question;
    $ligne = fgets(STDIN);
    return $ligne === false ? '0' : trim($ligne);
}

function afficherProduit(Produit $p): string
{
    return sprintf("%-8s %-20s %8.2f DH  qte: %4d  valeur: %10.2f DH",
        $p->getReference(), $p->getNom(), $p->getPrix(), $p->getQuantite(), $p->valeurStock());
}

while (true) {
    echo "\n===== GESTION DE STOCK =====\n";
    echo "1. Ajouter un produit\n";
    echo "2. Lister le stock\n";
    echo "3. Réapprovisionner\n";
    echo "4. Nouvelle commande\n";
    echo "5. Produits en rupture ou sous seuil\n";
    echo "0. Quitter\n";
    $choix = lire("Votre choix : ");

    try {
        switch ($choix) {
            case '1':
                $ref = lire("Référence : ");
                $nom = lire("Nom : ");
                $prix = (float) lire("Prix unitaire : ");
                $qte = (int) lire("Quantité initiale : ");
                $stock->ajouter(new Produit($ref, $nom, $prix, $qte));
                echo "Produit ajouté.\n";
                break;

            case '2':
                if ($stock->compter() === 0) {
                    echo "Le stock est vide.\n";
                    break;
                }
                foreach ($stock->tous() as $p) {
                    echo afficherProduit($p) . "\n";
                }
                printf("%d produit(s) - valeur totale : %.2f DH\n", $stock->compter(), $stock->valeurTotale());
                break;

            case '3':
                $p = $stock->trouver(lire("Référence : "));
                if ($p === null) { echo "Produit introuvable.\n"; break; }
                $p->ajouterQuantite((int) lire("Quantité à ajouter : "));
                echo "Nouveau stock : {$p->getQuantite()}\n";
                break;

            case '4':
                $commande = new Commande($numeroCommande);
                while (true) {
                    $ref = lire("Référence (vide pour terminer) : ");
                    if ($ref === '') break;
                    $p = $stock->trouver($ref);
                    if ($p === null) { echo "Produit introuvable.\n"; continue; }
                    try {
                        $commande->ajouterLigne($p, (int) lire("Quantité : "));
                        echo "Ligne ajoutée.\n";
                    } catch (Exception $e) {
                        echo "Erreur : {$e->getMessage()}\n";
                    }
                }
                $commande->valider();
                $numeroCommande++;
                echo $commande->afficher();
                break;

            case '5':
                $seuil = (int) lire("Seuil d'alerte : ");
                echo "-- En rupture --\n";
                foreach ($stock->produitsEnRupture() as $p) echo afficherProduit($p) . "\n";
                echo "-- Sous le seuil de $seuil --\n";
                foreach ($stock->produitsSousSeuil($seuil) as $p) echo afficherProduit($p) . "\n";
                break;

            case '0':
                echo "Au revoir.\n";
                exit(0);

            default:
                echo "Choix invalide.\n";
        }
    } catch (Exception $e) {
        echo "Erreur : {$e->getMessage()}\n";
    }
}
