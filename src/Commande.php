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
}