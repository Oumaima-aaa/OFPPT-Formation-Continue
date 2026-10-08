<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\Certification;
use App\Models\Competence;
use App\Models\Diplome;
use App\Models\Entreprise;
use App\Models\Etablissement;
use App\Models\Intervenant;
use App\Models\Plan;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $this->createUser('Admin', 'Central', 'admin@ofppt.ma', User::ROLE_SUPER_ADMIN);
        $this->createUser('Formateur', 'OFPPT', 'trainer@ofppt.ma', User::ROLE_TRAINER, phone: '0644444444');

        $regions = $this->seedRegions();
        $this->seedRegionalManagers($regions);

        $themes = (new ThemeCatalogSeeder)->run();
        $etablissements = $this->seedEtablissements($regions);
        $this->seedLocalManagers($etablissements);

        $entreprises = $this->seedEntreprises();

        $this->seedIntervenants($etablissements);

        $this->seedPlans($themes, $entreprises, $etablissements);
        $this->seedActions($themes, $entreprises, $etablissements);
    }

    /** @param Collection<int, Etablissement> $etablissements */
    protected function seedIntervenants(Collection $etablissements): void
    {
        $trainerUserId = User::where('email', 'trainer@ofppt.ma')->value('id');

        $intervenantsData = [
            [
                'matricule' => 'INT-001', 'nom' => 'Alaoui', 'prenom' => 'Fatima', 'email' => 'fatima.alaoui@ofppt.ma',
                'telephone' => '0661122334', 'genre' => 'F', 'type_intervenant' => 'interne', 'user_id' => $trainerUserId,
                'competences' => [
                    ['nom' => 'Informatique', 'niveau' => 'expert', 'description' => 'Développement web et bases de données'],
                    ['nom' => 'Pédagogie', 'niveau' => 'expert', 'description' => 'Animation de formations professionnelles'],
                ],
                'diplomes' => [
                    ['intitule' => 'Master Informatique', 'universite' => 'Université Hassan II', 'annee_obtention' => 2018, 'niveau' => 'Bac+5', 'specialite' => 'Génie logiciel'],
                    ['intitule' => 'Licence Sciences Mathématiques et Informatique', 'universite' => 'Université Hassan II', 'annee_obtention' => 2016, 'niveau' => 'Bac+3', 'specialite' => 'Informatique'],
                    ['intitule' => 'BTS Développement Informatique', 'universite' => 'OFPPT Casablanca', 'annee_obtention' => 2014, 'niveau' => 'Bac+2', 'specialite' => 'Développement applications'],
                ],
                'certifications' => [
                    ['code' => 'CERT-001', 'intitule' => 'Formateur certifié OFPPT', 'type' => 'Professionnelle', 'domaine' => 'Informatique', 'organisme' => 'OFPPT', 'date_obtention' => '2019-06-15'],
                    ['code' => 'CERT-002', 'intitule' => 'AWS Cloud Practitioner', 'type' => 'Technique', 'domaine' => 'Cloud', 'organisme' => 'Amazon Web Services', 'date_obtention' => '2022-03-20'],
                ],
            ],
            [
                'matricule' => 'INT-002', 'nom' => 'Benkirane', 'prenom' => 'Youssef', 'email' => 'youssef.benkirane@ofppt.ma',
                'telephone' => '0662233445', 'genre' => 'M', 'type_intervenant' => 'interne', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Réseaux et systèmes', 'niveau' => 'expert', 'description' => 'Administration réseau et cybersécurité'],
                ],
                'diplomes' => [
                    ['intitule' => 'Ingénieur d\'État', 'universite' => 'EMI Rabat', 'annee_obtention' => 2015, 'niveau' => 'Bac+5', 'specialite' => 'Réseaux informatiques'],
                    ['intitule' => 'DEUG Mathématiques Appliquées', 'universite' => 'Université Mohammed V', 'annee_obtention' => 2012, 'niveau' => 'Bac+2', 'specialite' => 'Mathématiques'],
                ],
                'certifications' => [
                    ['code' => 'CERT-010', 'intitule' => 'CCNA', 'type' => 'Technique', 'domaine' => 'Réseaux', 'organisme' => 'Cisco', 'date_obtention' => '2020-11-10'],
                    ['code' => 'CERT-011', 'intitule' => 'CompTIA Security+', 'type' => 'Technique', 'domaine' => 'Sécurité', 'organisme' => 'CompTIA', 'date_obtention' => '2021-09-05'],
                ],
            ],
            [
                'matricule' => 'INT-003', 'nom' => 'Chraibi', 'prenom' => 'Salma', 'email' => 'salma.chraibi@ofppt.ma',
                'telephone' => '0663344556', 'genre' => 'F', 'type_intervenant' => 'interne', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Comptabilité', 'niveau' => 'expert', 'description' => 'Comptabilité générale et analytique'],
                    ['nom' => 'Fiscalité marocaine', 'niveau' => 'intermediaire', 'description' => 'TVA, IS et déclarations fiscales'],
                ],
                'diplomes' => [
                    ['intitule' => 'Master Finance et Audit', 'universite' => 'ENCG Casablanca', 'annee_obtention' => 2017, 'niveau' => 'Bac+5', 'specialite' => 'Finance'],
                    ['intitule' => 'Licence Économie et Gestion', 'universite' => 'Université Cadi Ayyad', 'annee_obtention' => 2015, 'niveau' => 'Bac+3', 'specialite' => 'Gestion financière'],
                ],
                'certifications' => [
                    ['code' => 'CERT-020', 'intitule' => 'Expert-comptable stagiaire', 'type' => 'Professionnelle', 'domaine' => 'Comptabilité', 'organisme' => 'Ordre des Experts-Comptables', 'date_obtention' => '2018-12-01'],
                ],
            ],
            [
                'matricule' => 'INT-004', 'nom' => 'El Amrani', 'prenom' => 'Karim', 'email' => 'karim.elamrani@ofppt.ma',
                'telephone' => '0664455667', 'genre' => 'M', 'type_intervenant' => 'externe', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Marketing digital', 'niveau' => 'expert', 'description' => 'SEO, SEA et réseaux sociaux'],
                ],
                'diplomes' => [
                    ['intitule' => 'Master Marketing et Communication', 'universite' => 'ISCAE Rabat', 'annee_obtention' => 2019, 'niveau' => 'Bac+5', 'specialite' => 'Marketing digital'],
                ],
                'certifications' => [
                    ['code' => 'CERT-030', 'intitule' => 'Google Ads Search', 'type' => 'Technique', 'domaine' => 'Marketing', 'organisme' => 'Google', 'date_obtention' => '2023-01-18'],
                    ['code' => 'CERT-031', 'intitule' => 'Meta Blueprint', 'type' => 'Technique', 'domaine' => 'Marketing', 'organisme' => 'Meta', 'date_obtention' => '2023-06-22'],
                ],
            ],
            [
                'matricule' => 'INT-005', 'nom' => 'Fassi', 'prenom' => 'Nadia', 'email' => 'nadia.fassi@ofppt.ma',
                'telephone' => '0665566778', 'genre' => 'F', 'type_intervenant' => 'interne', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Ressources humaines', 'niveau' => 'expert', 'description' => 'Gestion des talents et paie'],
                ],
                'diplomes' => [
                    ['intitule' => 'Master GRH', 'universite' => 'Université Mohammed V', 'annee_obtention' => 2016, 'niveau' => 'Bac+5', 'specialite' => 'Ressources humaines'],
                    ['intitule' => 'Licence Droit Social', 'universite' => 'Université Hassan I', 'annee_obtention' => 2014, 'niveau' => 'Bac+3', 'specialite' => 'Droit du travail'],
                    ['intitule' => 'Baccalauréat Sciences Économiques', 'universite' => 'Lycée Qualifiant', 'annee_obtention' => 2011, 'niveau' => 'Bac', 'specialite' => 'Sciences économiques'],
                ],
                'certifications' => [
                    ['code' => 'CERT-040', 'intitule' => 'Certification RHIP', 'type' => 'Professionnelle', 'domaine' => 'RH', 'organisme' => 'HR Certification Institute', 'date_obtention' => '2020-05-30'],
                ],
            ],
            [
                'matricule' => 'INT-006', 'nom' => 'Idrissi', 'prenom' => 'Mehdi', 'email' => 'mehdi.idrissi@ofppt.ma',
                'telephone' => '0666677889', 'genre' => 'M', 'type_intervenant' => 'interne', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Électricité industrielle', 'niveau' => 'expert', 'description' => 'Automatisme et maintenance électrique'],
                ],
                'diplomes' => [
                    ['intitule' => 'Technicien Spécialisé Électromécanique', 'universite' => 'OFPPT Marrakech', 'annee_obtention' => 2013, 'niveau' => 'Bac+2', 'specialite' => 'Électromécanique'],
                ],
                'certifications' => [
                    ['code' => 'CERT-050', 'intitule' => 'Habilitation électrique B2V', 'type' => 'Professionnelle', 'domaine' => 'Électricité', 'organisme' => 'APAVE Maroc', 'date_obtention' => '2021-02-14'],
                ],
            ],
            [
                'matricule' => 'INT-007', 'nom' => 'Lahlou', 'prenom' => 'Imane', 'email' => 'imane.lahlou@ofppt.ma',
                'telephone' => '0667788990', 'genre' => 'F', 'type_intervenant' => 'interne', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Langues', 'niveau' => 'expert', 'description' => 'Anglais et français des affaires'],
                ],
                'diplomes' => [
                    ['intitule' => 'Master Langues Étrangères Appliquées', 'universite' => 'Université Sidi Mohammed Ben Abdellah', 'annee_obtention' => 2018, 'niveau' => 'Bac+5', 'specialite' => 'Anglais'],
                    ['intitule' => 'Licence Études Anglaises', 'universite' => 'Université Sidi Mohammed Ben Abdellah', 'annee_obtention' => 2016, 'niveau' => 'Bac+3', 'specialite' => 'Linguistique'],
                ],
                'certifications' => [
                    ['code' => 'CERT-060', 'intitule' => 'Cambridge CPE', 'type' => 'Linguistique', 'domaine' => 'Anglais', 'organisme' => 'Cambridge Assessment', 'date_obtention' => '2019-07-08'],
                    ['code' => 'CERT-061', 'intitule' => 'DALF C1', 'type' => 'Linguistique', 'domaine' => 'Français', 'organisme' => 'France Éducation International', 'date_obtention' => '2017-11-20'],
                ],
            ],
            [
                'matricule' => 'INT-008', 'nom' => 'Moussaoui', 'prenom' => 'Hassan', 'email' => 'hassan.moussaoui@ofppt.ma',
                'telephone' => '0668899001', 'genre' => 'M', 'type_intervenant' => 'externe', 'user_id' => null,
                'competences' => [
                    ['nom' => 'BTP', 'niveau' => 'expert', 'description' => 'Conduite de travaux et sécurité chantier'],
                ],
                'diplomes' => [
                    ['intitule' => 'Licence Pro Génie Civil', 'universite' => 'EST Fès', 'annee_obtention' => 2015, 'niveau' => 'Bac+3', 'specialite' => 'Génie civil'],
                ],
                'certifications' => [
                    ['code' => 'CERT-070', 'intitule' => 'Coordinateur SPS', 'type' => 'Professionnelle', 'domaine' => 'BTP', 'organisme' => 'OPQIBI', 'date_obtention' => '2022-09-12'],
                ],
            ],
            [
                'matricule' => 'INT-009', 'nom' => 'Ouazzani', 'prenom' => 'Latifa', 'email' => 'latifa.ouazzani@ofppt.ma',
                'telephone' => '0669900112', 'genre' => 'F', 'type_intervenant' => 'interne', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Tourisme et hôtellerie', 'niveau' => 'intermediaire', 'description' => 'Accueil et gestion hôtelière'],
                ],
                'diplomes' => [
                    ['intitule' => 'Licence Tourisme', 'universite' => 'Université Ibn Zohr', 'annee_obtention' => 2017, 'niveau' => 'Bac+3', 'specialite' => 'Management hôtelier'],
                    ['intitule' => 'Technicien Spécialisé Hôtellerie', 'universite' => 'OFPPT Agadir', 'annee_obtention' => 2015, 'niveau' => 'Bac+2', 'specialite' => 'Hôtellerie'],
                ],
                'certifications' => [
                    ['code' => 'CERT-080', 'intitule' => 'Certification HACCP', 'type' => 'Professionnelle', 'domaine' => 'Restauration', 'organisme' => 'Bureau Veritas', 'date_obtention' => '2020-10-05'],
                ],
            ],
            [
                'matricule' => 'INT-010', 'nom' => 'Tazi', 'prenom' => 'Omar', 'email' => 'omar.tazi@ofppt.ma',
                'telephone' => '0660011223', 'genre' => 'M', 'type_intervenant' => 'interne', 'user_id' => null,
                'competences' => [
                    ['nom' => 'Data Science', 'niveau' => 'expert', 'description' => 'Python, ML et visualisation de données'],
                    ['nom' => 'Business Intelligence', 'niveau' => 'intermediaire', 'description' => 'Power BI et SQL avancé'],
                ],
                'diplomes' => [
                    ['intitule' => 'Master Data Science', 'universite' => 'Université Internationale de Rabat', 'annee_obtention' => 2020, 'niveau' => 'Bac+5', 'specialite' => 'Intelligence artificielle'],
                    ['intitule' => 'Licence Mathématiques Appliquées', 'universite' => 'Université Mohammed V', 'annee_obtention' => 2018, 'niveau' => 'Bac+3', 'specialite' => 'Statistiques'],
                ],
                'certifications' => [
                    ['code' => 'CERT-090', 'intitule' => 'Microsoft Power BI Data Analyst', 'type' => 'Technique', 'domaine' => 'BI', 'organisme' => 'Microsoft', 'date_obtention' => '2023-04-17'],
                    ['code' => 'CERT-091', 'intitule' => 'TensorFlow Developer', 'type' => 'Technique', 'domaine' => 'IA', 'organisme' => 'Google', 'date_obtention' => '2024-01-09'],
                ],
            ],
        ];

        foreach ($intervenantsData as $index => $data) {
            $etablissement = $etablissements[$index % $etablissements->count()];
            $userId = $data['user_id'];

            if ($userId === null) {
                $user = $this->createUser(
                    $data['prenom'],
                    $data['nom'],
                    $data['email'],
                    User::ROLE_TRAINER,
                    phone: $data['telephone'],
                    establishmentId: $etablissement->id,
                );
                $userId = $user->id;
            } else {
                User::whereKey($userId)->update(['establishment_id' => $etablissement->id]);
            }

            $intervenant = Intervenant::create([
                'matricule' => $data['matricule'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'genre' => $data['genre'],
                'type_intervenant' => $data['type_intervenant'],
                'etablissements_id' => $etablissement->id,
                'user_id' => $userId,
            ]);

            foreach ($data['competences'] as $competence) {
                Competence::create([...$competence, 'intervenant_id' => $intervenant->id]);
            }

            foreach ($data['diplomes'] as $diplome) {
                Diplome::create([...$diplome, 'intervenant_id' => $intervenant->id]);
            }

            foreach ($data['certifications'] as $certification) {
                Certification::create([...$certification, 'intervenant_id' => $intervenant->id]);
            }
        }
    }

    /** @return Collection<int, Entreprise> */
    protected function seedEntreprises(): Collection
    {
        $companies = [
            [
                'first' => 'Entreprise', 'last' => 'Demo', 'email' => 'entreprise@demo.ma', 'phone' => '0633333331',
                'raison' => 'Tech Solutions SARL', 'ice' => '002345678000012', 'contact' => 'contact@techsolutions.ma',
                'adresse' => '45 Avenue Hassan II, Casablanca', 'site' => 'https://techsolutions.ma',
                'representant' => 'Ahmed Benali', 'telephone1' => '0612345678',
            ],
            [
                'first' => 'Maroc', 'last' => 'Industrie', 'email' => 'contact@marocindustrie.ma', 'phone' => '0633333332',
                'raison' => 'Maroc Industrie SA', 'ice' => '002345678000023', 'contact' => 'info@marocindustrie.ma',
                'adresse' => 'Zone industrielle Aïn Sebaâ, Casablanca', 'site' => 'https://marocindustrie.ma',
                'representant' => 'Hassan El Amrani', 'telephone1' => '0623456789',
            ],
            [
                'first' => 'Atlas', 'last' => 'BTP', 'email' => 'contact@atlasbtp.ma', 'phone' => '0633333333',
                'raison' => 'Atlas BTP SARL', 'ice' => '002345678000034', 'contact' => 'direction@atlasbtp.ma',
                'adresse' => '78 Boulevard Zerktouni, Casablanca', 'site' => 'https://atlasbtp.ma',
                'representant' => 'Fatima Zahra Bennani', 'telephone1' => '0634567890',
            ],
            [
                'first' => 'Digital', 'last' => 'Maghreb', 'email' => 'hello@digitalmaghreb.ma', 'phone' => '0633333334',
                'raison' => 'Digital Maghreb SARL', 'ice' => '002345678000045', 'contact' => 'hello@digitalmaghreb.ma',
                'adresse' => '12 Rue Oukaimeden, Rabat', 'site' => 'https://digitalmaghreb.ma',
                'representant' => 'Youssef Kettani', 'telephone1' => '0645678901',
            ],
            [
                'first' => 'Agro', 'last' => 'Sud', 'email' => 'contact@agrosud.ma', 'phone' => '0633333335',
                'raison' => 'Agro Sud Coopérative', 'ice' => '002345678000056', 'contact' => 'contact@agrosud.ma',
                'adresse' => 'Route de Taroudant, Agadir', 'site' => 'https://agrosud.ma',
                'representant' => 'Aicha Mansouri', 'telephone1' => '0656789012',
            ],
            [
                'first' => 'Pharma', 'last' => 'Atlas', 'email' => 'info@pharmaatlas.ma', 'phone' => '0633333336',
                'raison' => 'Pharma Atlas SA', 'ice' => '002345678000067', 'contact' => 'info@pharmaatlas.ma',
                'adresse' => 'Parc industriel, Fès', 'site' => 'https://pharmaatlas.ma',
                'representant' => 'Dr. Karim Berrada', 'telephone1' => '0667890123',
            ],
            [
                'first' => 'Logistique', 'last' => 'Nord', 'email' => 'contact@logistiquenord.ma', 'phone' => '0633333337',
                'raison' => 'Logistique Nord SARL', 'ice' => '002345678000078', 'contact' => 'contact@logistiquenord.ma',
                'adresse' => 'Zone franche, Tanger', 'site' => 'https://logistiquenord.ma',
                'representant' => 'Mohamed Tazi', 'telephone1' => '0678901234',
            ],
            [
                'first' => 'Energie', 'last' => 'Verte', 'email' => 'contact@energieverte.ma', 'phone' => '0633333338',
                'raison' => 'Énergie Verte Maroc', 'ice' => '002345678000089', 'contact' => 'contact@energieverte.ma',
                'adresse' => 'Quartier administratif, Marrakech', 'site' => 'https://energieverte.ma',
                'representant' => 'Salma Idrissi', 'telephone1' => '0689012345',
            ],
            [
                'first' => 'Textile', 'last' => 'Oriental', 'email' => 'contact@textileoriental.ma', 'phone' => '0633333339',
                'raison' => 'Textile Oriental SA', 'ice' => '002345678000090', 'contact' => 'contact@textileoriental.ma',
                'adresse' => 'Zone industrielle, Oujda', 'site' => 'https://textileoriental.ma',
                'representant' => 'Rachid Ouazzani', 'telephone1' => '0690123456',
            ],
            [
                'first' => 'Conseil', 'last' => 'RH', 'email' => 'contact@conseilrh.ma', 'phone' => '0633333340',
                'raison' => 'Conseil RH & Formation', 'ice' => '002345678000101', 'contact' => 'contact@conseilrh.ma',
                'adresse' => '5 Avenue Allal Ben Abdellah, Rabat', 'site' => 'https://conseilrh.ma',
                'representant' => 'Nadia Cherkaoui', 'telephone1' => '0601234567',
            ],
        ];

        return collect($companies)->map(function (array $data) {
            $user = $this->createUser(
                $data['first'],
                $data['last'],
                $data['email'],
                User::ROLE_COMPANY,
                phone: $data['phone'],
            );

            return Entreprise::create([
                'raison' => $data['raison'],
                'ice' => $data['ice'],
                'email' => $data['contact'],
                'adresse' => $data['adresse'],
                'site' => $data['site'],
                'telephone1' => $data['telephone1'],
                'representant' => $data['representant'],
                'status' => Entreprise::STATUS_ACTIF,
                'users_id' => $user->id,
            ]);
        });
    }

    /** @param Collection<int, \App\Models\Theme> $themes */
    /** @param Collection<int, Entreprise> $entreprises */
    /** @param Collection<int, Etablissement> $etablissements */
    protected function seedPlans(Collection $themes, Collection $entreprises, Collection $etablissements): void
    {
        $exercice = (int) now()->format('Y');

        foreach ($entreprises as $index => $entreprise) {
            Plan::create([
                'exercice' => $exercice,
                'entreprises_id' => $entreprise->id,
                'etablissements_id' => $etablissements[$index % $etablissements->count()]->id,
                'themes_id' => $themes[$index % $themes->count()]->id,
                'nbjours' => 3 + ($index % 4),
                'nbparticipantmaxi' => 10 + ($index * 2),
                'nb_groupes' => 1 + ($index % 3),
                'date_debut_previsionnelle' => now()->addMonths(1 + $index)->toDateString(),
                'cout_previsionnel' => 12000 + ($index * 2500),
                'status' => $index % 2 === 0 ? Plan::STATUS_BROUILLON : Plan::STATUS_PLANIFIE,
            ]);
        }
    }

    /** @return Collection<int, Region> */
    protected function seedRegions(): Collection
    {
        $noms = [
            'Casablanca-Settat',
            'Rabat-Salé-Kénitra',
            'Marrakech-Safi',
            'Fès-Meknès',
            'Tanger-Tétouan-Al Hoceima',
            'Oriental',
            'Béni Mellal-Khénifra',
            'Souss-Massa',
            'Drâa-Tafilalet',
            'Guelmim-Oued Noun',
        ];

        return collect($noms)->map(fn (string $nom) => Region::create([
            'nom_region' => $nom,
        ]));
    }

    /** @param Collection<int, Region> $regions */
    protected function seedRegionalManagers(Collection $regions): void
    {
        $managers = [
            ['first' => 'Karim', 'last' => 'Benjelloun', 'email' => 'regional@ofppt.ma', 'phone' => '0611111111'],
            ['first' => 'Amine', 'last' => 'El Fassi', 'email' => 'regional.rabat@ofppt.ma', 'phone' => '0611111112'],
            ['first' => 'Sara', 'last' => 'Alaoui', 'email' => 'regional.marrakech@ofppt.ma', 'phone' => '0611111113'],
            ['first' => 'Youssef', 'last' => 'Idrissi', 'email' => 'regional.fes@ofppt.ma', 'phone' => '0611111114'],
            ['first' => 'Nadia', 'last' => 'Tazi', 'email' => 'regional.tanger@ofppt.ma', 'phone' => '0611111115'],
            ['first' => 'Omar', 'last' => 'Berrada', 'email' => 'regional.oriental@ofppt.ma', 'phone' => '0611111116'],
            ['first' => 'Houda', 'last' => 'Chakir', 'email' => 'regional.benimellal@ofppt.ma', 'phone' => '0611111117'],
            ['first' => 'Mehdi', 'last' => 'Amrani', 'email' => 'regional.agadir@ofppt.ma', 'phone' => '0611111118'],
            ['first' => 'Latifa', 'last' => 'Moussaoui', 'email' => 'regional.errachidia@ofppt.ma', 'phone' => '0611111119'],
            ['first' => 'Hamza', 'last' => 'Ouazzani', 'email' => 'regional.guelmim@ofppt.ma', 'phone' => '0611111120'],
        ];

        foreach ($managers as $index => $data) {
            $region = $regions[$index];
            $user = $this->createUser(
                $data['first'],
                $data['last'],
                $data['email'],
                User::ROLE_REGIONAL_MANAGER,
                phone: $data['phone'],
                regionId: $region->id,
            );

            $region->update(['users_id' => $user->id]);
        }
    }

    /** @return Collection<int, Etablissement> */
    protected function seedEtablissements(Collection $regions): Collection
    {
        $etablissementsByRegion = [
            'Casablanca-Settat' => [
                ['nom_efp' => 'OFPPT Casablanca Centre', 'ville' => 'Casablanca', 'adresse' => '123 Boulevard Zerktouni', 'tel' => '0522123456'],
                ['nom_efp' => 'OFPPT Casablanca Sidi Maarouf', 'ville' => 'Casablanca', 'adresse' => '18 Boulevard de la Corniche', 'tel' => '0522987654'],
                ['nom_efp' => 'OFPPT Settat', 'ville' => 'Settat', 'adresse' => '45 Avenue Hassan II', 'tel' => '0523712345'],
            ],
            'Rabat-Salé-Kénitra' => [
                ['nom_efp' => 'OFPPT Rabat Agdal', 'ville' => 'Rabat', 'adresse' => '45 Avenue Annakhil', 'tel' => '0537778899'],
                ['nom_efp' => 'OFPPT Salé Tabriquet', 'ville' => 'Salé', 'adresse' => '12 Rue Tabriquet', 'tel' => '0537812345'],
                ['nom_efp' => 'OFPPT Kénitra Médina', 'ville' => 'Kénitra', 'adresse' => '8 Avenue Mohamed Diouri', 'tel' => '0537367890'],
            ],
            'Marrakech-Safi' => [
                ['nom_efp' => 'OFPPT Marrakech Guéliz', 'ville' => 'Marrakech', 'adresse' => '12 Avenue Mohammed VI', 'tel' => '0524433221'],
                ['nom_efp' => 'OFPPT Marrakech Médina', 'ville' => 'Marrakech', 'adresse' => '5 Rue Riad Zitoun', 'tel' => '0524445566'],
                ['nom_efp' => 'OFPPT Safi', 'ville' => 'Safi', 'adresse' => '22 Boulevard Mohammed V', 'tel' => '0524467788'],
            ],
            'Fès-Meknès' => [
                ['nom_efp' => 'OFPPT Fès Ville Nouvelle', 'ville' => 'Fès', 'adresse' => '8 Rue Allal El Fassi', 'tel' => '0535623145'],
                ['nom_efp' => 'OFPPT Fès Saïss', 'ville' => 'Fès', 'adresse' => '14 Route de Sefrou', 'tel' => '0535654321'],
                ['nom_efp' => 'OFPPT Meknès Hamria', 'ville' => 'Meknès', 'adresse' => '30 Avenue des FAR', 'tel' => '0535521987'],
            ],
            'Tanger-Tétouan-Al Hoceima' => [
                ['nom_efp' => 'OFPPT Tanger Médina', 'ville' => 'Tanger', 'adresse' => '27 Boulevard Pasteur', 'tel' => '0539932145'],
                ['nom_efp' => 'OFPPT Tanger Malabata', 'ville' => 'Tanger', 'adresse' => '6 Avenue Moulay Youssef', 'tel' => '0539945678'],
                ['nom_efp' => 'OFPPT Tétouan Martil', 'ville' => 'Tétouan', 'adresse' => '11 Avenue Sania Ramel', 'tel' => '0539965432'],
            ],
            'Oriental' => [
                ['nom_efp' => 'OFPPT Oujda Hay Al Qods', 'ville' => 'Oujda', 'adresse' => '5 Avenue Hassan II', 'tel' => '0536684521'],
                ['nom_efp' => 'OFPPT Oujda Centre', 'ville' => 'Oujda', 'adresse' => '20 Rue Abu Bakr El Kadiri', 'tel' => '0536698765'],
                ['nom_efp' => 'OFPPT Nador', 'ville' => 'Nador', 'adresse' => '7 Boulevard Mohammed V', 'tel' => '0536321456'],
            ],
            'Béni Mellal-Khénifra' => [
                ['nom_efp' => 'OFPPT Béni Mellal Médina', 'ville' => 'Béni Mellal', 'adresse' => '18 Avenue Mohammed V', 'tel' => '0523487654'],
                ['nom_efp' => 'OFPPT Khénifra', 'ville' => 'Khénifra', 'adresse' => '3 Rue Ibn Khaldoun', 'tel' => '0523554321'],
                ['nom_efp' => 'OFPPT Kasba Tadla', 'ville' => 'Kasba Tadla', 'adresse' => '9 Avenue Al Massira', 'tel' => '0523578901'],
            ],
            'Souss-Massa' => [
                ['nom_efp' => 'OFPPT Agadir Talborjt', 'ville' => 'Agadir', 'adresse' => '33 Boulevard Mohammed V', 'tel' => '0528822331'],
                ['nom_efp' => 'OFPPT Agadir Hay Mohammadi', 'ville' => 'Agadir', 'adresse' => '15 Rue des Orangers', 'tel' => '0528845678'],
                ['nom_efp' => 'OFPPT Inezgane', 'ville' => 'Inezgane', 'adresse' => '4 Avenue des FAR', 'tel' => '0528867890'],
            ],
            'Drâa-Tafilalet' => [
                ['nom_efp' => 'OFPPT Errachidia Centre', 'ville' => 'Errachidia', 'adresse' => '7 Avenue Moulay Ismail', 'tel' => '0535574123'],
                ['nom_efp' => 'OFPPT Ouarzazate', 'ville' => 'Ouarzazate', 'adresse' => '21 Avenue Mohammed V', 'tel' => '0524887654'],
                ['nom_efp' => 'OFPPT Zagora', 'ville' => 'Zagora', 'adresse' => '2 Rue Al Wahda', 'tel' => '0528489012'],
            ],
            'Guelmim-Oued Noun' => [
                ['nom_efp' => 'OFPPT Guelmim Al Massira', 'ville' => 'Guelmim', 'adresse' => '2 Rue Al Massira', 'tel' => '0528776655'],
                ['nom_efp' => 'OFPPT Tan-Tan', 'ville' => 'Tan-Tan', 'adresse' => '6 Boulevard Hassan II', 'tel' => '0528812345'],
                ['nom_efp' => 'OFPPT Laâyoune', 'ville' => 'Laâyoune', 'adresse' => '10 Avenue Smara', 'tel' => '0528890123'],
            ],
        ];

        return $regions->flatMap(function (Region $region) use ($etablissementsByRegion) {
            $items = $etablissementsByRegion[$region->nom_region] ?? [];

            return collect($items)->map(fn (array $data) => Etablissement::create([
                ...$data,
                'status' => Etablissement::STATUS_ACTIF,
                'regions_id' => $region->id,
            ]));
        });
    }

    /** @param Collection<int, Etablissement> $etablissements */
    protected function seedLocalManagers(Collection $etablissements): void
    {
        $primaryManagers = [
            ['first' => 'Salma', 'last' => 'Bennani', 'email' => 'local@ofppt.ma', 'phone' => '0622222221'],
            ['first' => 'Rachid', 'last' => 'Kabbaj', 'email' => 'local.rabat@ofppt.ma', 'phone' => '0622222222'],
            ['first' => 'Imane', 'last' => 'Cherkaoui', 'email' => 'local.marrakech@ofppt.ma', 'phone' => '0622222223'],
            ['first' => 'Khalid', 'last' => 'Sefrioui', 'email' => 'local.fes@ofppt.ma', 'phone' => '0622222224'],
            ['first' => 'Meryem', 'last' => 'Lahlou', 'email' => 'local.tanger@ofppt.ma', 'phone' => '0622222225'],
            ['first' => 'Adil', 'last' => 'Ziani', 'email' => 'local.oujda@ofppt.ma', 'phone' => '0622222226'],
            ['first' => 'Samira', 'last' => 'Filali', 'email' => 'local.benimellal@ofppt.ma', 'phone' => '0622222227'],
            ['first' => 'Tarik', 'last' => 'Bouzid', 'email' => 'local.agadir@ofppt.ma', 'phone' => '0622222228'],
            ['first' => 'Noura', 'last' => 'Haddou', 'email' => 'local.errachidia@ofppt.ma', 'phone' => '0622222229'],
            ['first' => 'Ismail', 'last' => 'Rguibi', 'email' => 'local.guelmim@ofppt.ma', 'phone' => '0622222230'],
        ];

        foreach ($etablissements->values() as $index => $etablissement) {
            $regionIndex = intdiv($index, 3);
            $positionInRegion = $index % 3;

            if ($positionInRegion === 0 && isset($primaryManagers[$regionIndex])) {
                $data = $primaryManagers[$regionIndex];
            } else {
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $etablissement->ville));
                $data = [
                    'first' => 'Admin',
                    'last' => 'Local',
                    'email' => "local.{$slug}.{$etablissement->id}@ofppt.ma",
                    'phone' => '0623'.str_pad((string) ($index + 1), 7, '0', STR_PAD_LEFT),
                ];
            }

            $user = $this->createUser(
                $data['first'],
                $data['last'],
                $data['email'],
                User::ROLE_LOCAL_MANAGER,
                phone: $data['phone'],
                establishmentId: $etablissement->id,
            );

            $etablissement->update(['users_id' => $user->id]);
        }
    }

    /** @param Collection<int, \App\Models\Theme> $themes */
    /** @param Collection<int, Entreprise> $entreprises */
    /** @param Collection<int, Etablissement> $etablissements */
    protected function seedActions(Collection $themes, Collection $entreprises, Collection $etablissements): void
    {
        $exercice = (int) now()->format('Y');
        $statuses = [
            Action::STATUS_PENDING,
            Action::STATUS_PENDING,
            Action::STATUS_APPROVED,
            Action::STATUS_APPROVED,
            Action::STATUS_IN_PROGRESS,
            Action::STATUS_IN_PROGRESS,
            Action::STATUS_COMPLETED,
            Action::STATUS_COMPLETED,
            Action::STATUS_CANCELLED,
            Action::STATUS_CANCELLED,
        ];

        foreach (range(0, 9) as $index) {
            $startOffset = 5 + ($index * 7);
            $entreprise = $entreprises[$index % $entreprises->count()];

            Action::create([
                'exercice' => $exercice,
                'themes_id' => $themes[$index % $themes->count()]->id,
                'entreprises_id' => $entreprise->id,
                'etablissements_id' => $etablissements[$index % $etablissements->count()]->id,
                'date_debut' => now()->addDays($startOffset),
                'date_fin' => now()->addDays($startOffset + 3 + ($index % 3)),
                'prix_reel' => 8000 + ($index * 1500),
                'status' => $statuses[$index],
            ]);
        }
    }

    private function createUser(
        string $firstName,
        string $lastName,
        string $email,
        string $roleConstant,
        ?string $phone = null,
        ?string $address = null,
        ?int $regionId = null,
        ?int $establishmentId = null,
    ): User {
        $role = Role::findByName($roleConstant, 'web');

        $user = User::create([
            'role_id' => $role->id,
            'region_id' => $regionId,
            'establishment_id' => $establishmentId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'address' => $address ?? 'Casablanca, Maroc',
            'password' => 'password',
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        $user->assignSingleRole($role);

        return $user;
    }
}
