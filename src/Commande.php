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
}