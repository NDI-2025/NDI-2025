<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VoiceAuth extends Controller
{
    /**
     * Affiche la page d'authentification vocale
     */
    public function showLoginForm()
    {
        return view('auth.voice-login');
    }

    /**
     * Enregistre l'empreinte vocale lors de l'inscription
     */
    public function registerVoiceprint(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'audio' => 'required',
            'secret_phrase' => 'required|string|min:5'
        ]);

        $user = User::findOrFail($request->user_id);

        // Décoder l'audio base64
        $audioData = base64_decode(preg_replace('#^data:audio/\w+;base64,#i', '', $request->audio));

        // Créer un hash unique pour l'empreinte vocale
        $voiceHash = hash('sha256', $audioData . $request->secret_phrase);

        // Sauvegarder l'empreinte vocale
        $user->update([
            'voice_hash' => $voiceHash,
            'secret_phrase' => encrypt($request->secret_phrase),
            'voice_registered_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Empreinte vocale enregistrée avec succès !'
        ]);
    }

    /**
     * Authentifie l'utilisateur via reconnaissance vocale
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'audio' => 'required',
            'transcription' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !$user->voice_hash) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé ou empreinte vocale non enregistrée'
            ], 401);
        }

        // Vérifier la phrase secrète
        $secretPhrase = decrypt($user->secret_phrase);
        $similarity = similar_text(
            strtolower($secretPhrase),
            strtolower($request->transcription)
        );

        $matchPercentage = ($similarity / strlen($secretPhrase)) * 100;

        // Vérifier avec une tolérance de 80% pour la phrase
        if ($matchPercentage >= 80) {
            // Connexion réussie
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => '🎉 Accès autorisé ! Bienvenue ' . $user->name,
                'redirect' => route('welcome')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '❌ Phrase secrète incorrecte. Réessayez !'
        ], 401);
    }

    /**
     * Affiche le formulaire d'inscription vocale uniquement
     */
    public function showVoiceRegisterForm()
    {
        return view('auth.voice-only-register');
    }

    /**
     * Inscription avec authentification vocale uniquement (pas de password)
     */
    public function registerVoiceAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'username' => 'required|string|min:3|max:50|unique:users|alpha_dash',
            'audio' => 'required',
            'secret_phrase' => 'required|string|min:5',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'name.min' => 'Le nom doit contenir au moins 2 caractères',
            'username.required' => 'Le pseudonyme est obligatoire',
            'username.min' => 'Le pseudonyme doit contenir au moins 3 caractères',
            'username.unique' => 'Ce pseudonyme est déjà utilisé',
            'username.alpha_dash' => 'Le pseudonyme ne peut contenir que des lettres, chiffres, tirets et underscores',
            'audio.required' => 'L\'enregistrement vocal est obligatoire',
            'secret_phrase.required' => 'La phrase secrète est obligatoire',
            'secret_phrase.min' => 'La phrase secrète doit contenir au moins 5 caractères',
        ]);

        // Décoder l'audio base64
        $audioData = base64_decode(preg_replace('#^data:audio/\w+;base64,#i', '', $request->audio));

        // Créer un hash unique pour l'empreinte vocale
        $voiceHash = hash('sha256', $audioData . $request->secret_phrase);

        // Créer l'utilisateur avec empreinte vocale (PAS de password)
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => null, // Pas d'email !
            'password' => null, // Pas de mot de passe !
            'voice_hash' => $voiceHash,
            'secret_phrase' => encrypt($request->secret_phrase),
            'voice_registered_at' => now(),
        ]);

        // Connexion automatique après inscription
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => '🎉 Compte créé avec succès ! Bienvenue ' . $user->name,
            'redirect' => route('welcome')
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', '👋 Déconnexion réussie !');
    }
}
