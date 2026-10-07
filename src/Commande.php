<?php

class Commande
{
    private int $numero;
    private array $lignes = [];
    private bool $validee = false;

    public function __construct(int $numero)
    {
        $this->numero = $numero;
    }

    public function ajouterLigne(Produit $p, int $quantite): void
    {
        if ($quantite <= 0) {
            throw new InvalidArgumentException("La quantité commandée doit être positive.");
        }
        if ($quantite > $p->getQuantite()) {
            throw new Exception("Stock insuffisant pour {$p->getReference()} : {$p->getQuantite()} disponible(s).");
        }
        $this->lignes[] = ['produit' => $p, 'quantite' => $quantite];
    }
    
    public function total(): float
    {
        $total = 0.0;
        foreach ($this->lignes as $l) {
            $total += $l['produit']->getPrix() * $l['quantite'];
        }
        return $total;
    }

    public function valider(): void
    {
        if ($this->validee) {
            throw new Exception("La commande n°{$this->numero} est déjà validée.");
        }
        if (empty($this->lignes)) {
            throw new Exception("Impossible de valider une commande vide.");
        }
        foreach ($this->lignes as $l) {
            $l['produit']->retirerQuantite($l['quantite']);
        }
        $this->validee = true;
    }

    public function estValidee(): bool
    {
        return $this->validee;
    }
    
    public function afficher(): string
    {
        $texte = "=== Facture - Commande n°{$this->numero} ===\n";
        foreach ($this->lignes as $l) {
            $p = $l['produit'];
            $texte .= sprintf("%-8s %-20s %3d x %8.2f = %9.2f\n",
                $p->getReference(), $p->getNom(), $l['quantite'], $p->getPrix(), $p->getPrix() * $l['quantite']);
        }
        $texte .= sprintf("TOTAL : %.2f DH\n", $this->total());
        $texte .= "Statut : " . ($this->validee ? "validée" : "en attente") . "\n";
        return $texte;
    }
}