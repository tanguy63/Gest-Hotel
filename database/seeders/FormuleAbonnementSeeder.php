<?php

namespace Database\Seeders;

use App\Models\FormuleAbonnement;
use Illuminate\Database\Seeder;

class FormuleAbonnementSeeder extends Seeder
{
    public function run(): void
    {
        FormuleAbonnement::updateOrCreate(
            ['slug' => 'essai-gratuit'],
            [
                'nom' => 'Essai gratuit',
                'description' => 'Découvrez GEST-HOTEL gratuitement pendant 7 jours.',
                'prix_mensuel' => 0,
                'prix_annuel' => 0,
                'devise' => 'XOF',
                'duree_essai_jours' => 7,
                'limite_chambres' => null,
                'limite_utilisateurs' => null,
                'fonctionnalites' => [
                    'gestion_chambres',
                    'gestion_reservations',
                    'gestion_clients',
                    'gestion_paiements',
                    'tableau_de_bord',
                ],
                'est_formule_essai' => true,
                'est_actif' => true,
            ]
        );
    }
}