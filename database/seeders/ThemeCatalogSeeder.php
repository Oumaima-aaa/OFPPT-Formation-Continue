<?php

namespace Database\Seeders;

use App\Models\Domaine;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeCatalogSeeder extends Seeder
{
    /** @var array<string, list<string>> */
    protected array $catalog = [
        'Domaine Digital' => [
            'Introduction à l\'Intelligence Artificielle',
            'Développement Web avec Laravel',
            'Développement Front-End avec React',
            'Cybersécurité',
            'Cloud Computing',
            'Administration Systèmes Linux',
            'Analyse de Données avec Power BI',
            'Gestion de Bases de Données SQL',
            'Réseaux Informatiques',
            'DevOps et Docker',
        ],
        'Domaine Industriel' => [
            'Maintenance Industrielle',
            'Automatisation Industrielle',
            'Électromécanique',
            'Gestion de Production',
            'Lean Manufacturing',
            'Maintenance Préventive',
            'Qualité Industrielle ISO 9001',
            'Hydraulique Industrielle',
            'Pneumatique Industrielle',
            'Électricité Industrielle',
        ],
        'Domaine Génie Civil' => [
            'Lecture de Plans',
            'AutoCAD pour Génie Civil',
            'Métré et Estimation des Coûts',
            'Gestion de Chantier',
            'Topographie',
            'Béton Armé',
            'Sécurité sur Chantier',
            'BIM (Building Information Modeling)',
            'Planification des Travaux',
            'Contrôle Qualité BTP',
        ],
        'Domaine Management' => [
            'Leadership',
            'Gestion de Projet',
            'Communication Professionnelle',
            'Gestion des Conflits',
            'Management d\'Équipe',
            'Gestion du Temps',
            'Prise de Décision',
            'Coaching Professionnel',
            'Gestion du Changement',
            'Méthodes Agiles Scrum',
        ],
        'Domaine Ressources Humaines' => [
            'Gestion RH',
            'Recrutement et Sélection',
            'Évaluation des Performances',
            'Droit du Travail',
            'Paie et Administration du Personnel',
            'GPEC',
            'Formation et Développement',
            'Marque Employeur',
        ],
        'Domaine Qualité / Sécurité' => [
            'ISO 9001',
            'ISO 14001',
            'ISO 45001',
            'Audit Interne',
            'Management des Risques',
            'Santé et Sécurité au Travail',
            'HACCP',
            'Amélioration Continue',
        ],
    ];

    /** @return \Illuminate\Support\Collection<int, Theme> */
    public function run(): \Illuminate\Support\Collection
    {
        $themes = collect();

        foreach ($this->catalog as $nomDomaine => $intitules) {
            $domaine = Domaine::create([
                'nom_domaine' => $nomDomaine,
                'status' => Domaine::STATUS_ACTIF,
            ]);

            foreach ($intitules as $intitule) {
                $themes->push(Theme::create([
                    'domaines_id' => $domaine->id,
                    'intitule_theme' => $intitule,
                    'duree_formation' => $this->defaultDuration($nomDomaine),
                    'nbparticipantmaxi' => 15,
                    'status' => Theme::STATUS_ACTIF,
                ]));
            }
        }

        return $themes;
    }

    protected function defaultDuration(string $nomDomaine): int
    {
        return match ($nomDomaine) {
            'Domaine Digital' => 5,
            'Domaine Industriel' => 4,
            'Domaine Génie Civil' => 5,
            'Domaine Management' => 3,
            'Domaine Ressources Humaines' => 3,
            'Domaine Qualité / Sécurité' => 4,
            default => 5,
        };
    }
}
