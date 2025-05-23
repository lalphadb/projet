<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cours;
use App\Models\CoursHoraire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateCoursHoraires extends Command
{
    protected $signature = 'migrate:cours-horaires';
    protected $description = 'Migrer les anciens horaires de cours vers la nouvelle table cours_horaires';

    public function handle()
    {
        $this->info('Début de la migration des horaires de cours...');
        
        // Vérifier quelles colonnes existent dans la table cours
        $coursColumns = Schema::getColumnListing('cours');
        $this->info('Colonnes disponibles dans cours: ' . implode(', ', $coursColumns));
        
        $hasHeureDebut = in_array('heure_debut', $coursColumns);
        $hasHeureFin = in_array('heure_fin', $coursColumns);
        $hasJours = in_array('jours', $coursColumns);
        
        if (!$hasJours) {
            $this->error('La colonne "jours" n\'existe pas dans la table cours');
            return 1;
        }
        
        // Construire la requête selon les colonnes disponibles
        $query = Cours::whereNotNull('jours');
        
        if ($hasHeureDebut) {
            $query->whereNotNull('heure_debut');
        }
        if ($hasHeureFin) {
            $query->whereNotNull('heure_fin');
        }
        
        $coursWithOldHoraires = $query->get();
        
        $this->info("Nombre de cours à migrer : {$coursWithOldHoraires->count()}");
        
        if ($coursWithOldHoraires->count() === 0) {
            $this->info('Aucun cours à migrer trouvé.');
            return 0;
        }
        
        $migrated = 0;
        $errors = 0;
        
        foreach ($coursWithOldHoraires as $cours) {
            try {
                DB::beginTransaction();
                
                // Décoder les jours
                $jours = is_string($cours->jours) ? json_decode($cours->jours, true) : $cours->jours;
                
                if (is_array($jours)) {
                    // Utiliser des valeurs par défaut si les colonnes n'existent pas
                    $heureDebut = $hasHeureDebut ? $cours->heure_debut : '09:00';
                    $heureFin = $hasHeureFin ? $cours->heure_fin : '10:00';
                    
                    // Si on n'a pas d'heures, demander à l'utilisateur
                    if (!$hasHeureDebut && !$hasHeureFin) {
                        $this->warn("Cours #{$cours->id} ({$cours->nom}) : Pas d'horaires définis, utilisation de 09:00-10:00 par défaut");
                    }
                    
                    foreach ($jours as $jour) {
                        // Vérifier si l'horaire n'existe pas déjà
                        $existing = CoursHoraire::where('cours_id', $cours->id)
                                               ->where('jour', strtolower($jour))
                                               ->where('heure_debut', $heureDebut)
                                               ->where('heure_fin', $heureFin)
                                               ->first();
                        
                        if (!$existing) {
                            CoursHoraire::create([
                                'cours_id' => $cours->id,
                                'jour' => strtolower($jour),
                                'heure_debut' => $heureDebut,
                                'heure_fin' => $heureFin,
                                'active' => true
                            ]);
                        }
                    }
                    $migrated++;
                    $this->line("✓ Cours #{$cours->id} : {$cours->nom}");
                }
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                $errors++;
                $this->error("✗ Erreur pour le cours #{$cours->id} : {$e->getMessage()}");
            }
        }
        
        $this->info("\n--- Résumé de la migration ---");
        $this->info("Cours migrés avec succès : {$migrated}");
        if ($errors > 0) {
            $this->error("Cours avec erreurs : {$errors}");
        }
        
        if (!$hasHeureDebut || !$hasHeureFin) {
            $this->warn("\n⚠️  ATTENTION: Les horaires par défaut (09:00-10:00) ont été utilisés.");
            $this->warn("Vous devrez les modifier manuellement pour chaque cours.");
        }
        
        $this->info("Migration terminée !");
        return 0;
    }
}
