<?php

class Client
{
    private $nom;
    private $numeroCompte;
    private $solde;
    private $decouvertAutorise;
    private $historique = [];

    public function __construct($nom, $numeroCompte, $solde, $decouvertAutorise)
    {
        $this->nom = $nom;
        $this->numeroCompte = $numeroCompte;
        $this->solde = $solde;
        $this->decouvertAutorise = $decouvertAutorise;
    }

    private function saveOperation($type, $montant)
    {
        $operation = [
            'date' => date('Y-m-d H:i:s'),
            'montant' => $montant,
            'type' => $type,
            'auteur' => $this->nom,
            'solde' => $this->solde
        ];

        $this->historique[] = $operation;
    }

    public function deposerDevise($montant)
    {
        $this->solde += $montant;
        $this->saveOperation('Dépôt', $montant);
    }

    public function retirerDevise($montant)
    {
        if (($this->solde - $montant) < (-$this->decouvertAutorise)) {
            echo "Opération refusée : dépassement du découvert autorisé.";
            return;
        }

        $this->solde -= $montant;
        $this->saveOperation('Retrait', $montant);
    }

    public function consulterHistorique()
    {
        foreach ($this->historique as $operation) {
            echo "Date : " . $operation['date'] . "<br>";
            echo "Montant : " . $operation['montant'] . "<br>";
            echo "Type : " . $operation['type'] . "<br>";
            echo "Auteur : " . $operation['auteur'] . "<br>";
            echo "Solde : " . $operation['solde'] . "<br>";
            echo "-----------------------------<br>";
        }
    }


    public function getSolde()
    {
        return $this->solde;
    }

}


// Exemple d'exécution

$client = new Client('Abdoulaye', 'Numéro de compte: AK21224321', 1000, 500);

$client->deposerDevise(200);
echo "Dépôt effectué avec succès. Solde actuel : " . $client->getSolde() . "<br>";

$client->retirerDevise(100);
echo "Retrait effectué avec succès. Solde actuel : " . $client->getSolde() . "<br>";

echo "Historique des opérations :<br>";
$client->consulterHistorique();



           
