<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class VoiceOnlyTestSeeder extends Seeder
{
    /**
     * Créer des utilisateurs avec authentification vocale uniquement (pas de password).
     */
    public function run(): void
    {
        // Utilisateur test avec empreinte vocale uniquement
        User::create([
            'name' => 'Voice User',
            'email' => 'voice@ndi.com',
            'password' => null, // PAS de mot de passe !
            'voice_hash' => hash('sha256', 'sample_voice_data_voice_user'),
            'secret_phrase' => encrypt('ma voix est mon identité'),
            'voice_registered_at' => now(),
        ]);

        // Utilisateur test 2
        User::create([
            'name' => 'Jean Voice',
            'email' => 'jean.voice@ndi.com',
            'password' => null,
            'voice_hash' => hash('sha256', 'sample_voice_data_jean'),
            'secret_phrase' => encrypt('ouvre-toi sésame'),
            'voice_registered_at' => now(),
        ]);

        $this->command->info('✅ Utilisateurs 100% vocaux créés avec succès !');
        $this->command->info('');
        $this->command->info('🎤 Comptes VOCAL UNIQUEMENT :');
        $this->command->info('1. voice@ndi.com - Phrase: "ma voix est mon identité"');
        $this->command->info('2. jean.voice@ndi.com - Phrase: "ouvre-toi sésame"');
        $this->command->info('');
        $this->command->info('⚠️  Ces comptes n\'ont PAS de mot de passe !');
        $this->command->info('📝 Connexion uniquement par reconnaissance vocale');
    }
}

