<?php

namespace Database\Seeders;

use App\Models\TypeEtablissement;
use Illuminate\Database\Seeder;

class TypeEtablissementSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'nom' => 'Hôtel',
                'slug' => 'hotel',
                'description' => 'Établissement proposant des chambres et des services hôteliers.',
            ],
            [
                'nom' => 'Motel',
                'slug' => 'motel',
                'description' => 'Établissement d’hébergement destiné notamment aux voyageurs.',
            ],
            [
                'nom' => 'Auberge',
                'slug' => 'auberge',
                'description' => 'Établissement proposant un hébergement convivial.',
            ],
            [
                'nom' => 'Résidence hôtelière',
                'slug' => 'residence-hoteliere',
                'description' => 'Résidence proposant des logements meublés avec services.',
            ],
            [
                'nom' => 'Maison d’hôtes',
                'slug' => 'maison-hotes',
                'description' => 'Hébergement touristique chez un hôte.',
            ],
            [
                'nom' => 'Appart’hôtel',
                'slug' => 'apparthotel',
                'description' => 'Appartements meublés avec des services hôteliers.',
            ],
            [
                'nom' => 'Gîte touristique',
                'slug' => 'gite-touristique',
                'description' => 'Hébergement indépendant destiné aux séjours touristiques.',
            ],
            [
                'nom' => 'Camping / Village de vacances',
                'slug' => 'camping-village-vacances',
                'description' => 'Établissement proposant des hébergements de loisirs.',
            ],
            [
                'nom' => 'Autre établissement',
                'slug' => 'autre-etablissement',
                'description' => 'Autre type de structure d’hébergement.',
            ],
        ];

        foreach ($types as $type) {
            TypeEtablissement::updateOrCreate(
                ['slug' => $type['slug']],
                [
                    ...$type,
                    'est_actif' => true,
                ]
            );
        }
    }
}