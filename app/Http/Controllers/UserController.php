<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs admin
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier les autorisations (seuls les super admin peuvent voir tous les utilisateurs)
        if ($user->role !== 'superadmin') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        }
        
        $query = User::with('ecole');
        
        // Filtres
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('ecole_id') && $request->ecole_id !== 'all') {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('active', $request->status === 'active');
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $ecoles = Ecole::orderBy('nom')->get();
        
        return view('users.index', compact('users', 'ecoles'));
    }
    
    /**
     * Affiche le formulaire de création d'un utilisateur admin
     */
    public function create()
    {
        $user = Auth::user();
        
        // Vérifier les autorisations (seuls les super admin peuvent créer des utilisateurs)
        if ($user->role !== 'superadmin') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        }
        
        $ecoles = Ecole::orderBy('nom')->get();
        
        return view('users.create', compact('ecoles'));
    }
    
    /**
     * Enregistre un nouvel utilisateur admin
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier les autorisations (seuls les super admin peuvent créer des utilisateurs)
        if ($user->role !== 'superadmin') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }
        
        $request->validate([
            'nom' => 'required|string|min:2|max:255',
            'prenom' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role' => 'required|in:admin,instructor',
            'ecole_id' => 'required|exists:ecoles,id',
            'active' => 'boolean',
            'send_credentials' => 'boolean',
        ]);
        
        $newUser = new User();
        $newUser->nom = $request->nom;
        $newUser->prenom = $request->prenom;
        $newUser->email = $request->email;
        $newUser->telephone = $request->telephone;
        $newUser->password = Hash::make($request->password);
        $newUser->role = $request->role;
        $newUser->ecole_id = $request->ecole_id;
        $newUser->active = $request->boolean('active', true);
        $newUser->save();
        
        Log::info("Utilisateur créé : {$newUser->prenom} {$newUser->nom} (ID: {$newUser->id}) par user #{$user->id}");
        
        // Envoi des credentials par email
        if ($request->boolean('send_credentials', false)) {
            // Implémentation future : envoyer les informations de connexion par email
            // Mail::to($newUser->email)->send(new UserCredentialsMail($newUser, $request->password));
        }
        
        return redirect()->route('users.index')->with('success', 'Administrateur créé avec succès.');
    }
    
    /**
     * Affiche les informations d'un utilisateur
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les autorisations
        if ($currentUser->role !== 'superadmin' && $currentUser->id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        }
        
        $user->load('ecole');
        
        return view('users.show', compact('user'));
    }
    
    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les autorisations
        if ($currentUser->role !== 'superadmin' && $currentUser->id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        }
        
        // Superadmin ne peut pas être édité sauf par lui-même
        if ($user->role === 'superadmin' && $currentUser->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'Les super administrateurs ne peuvent être modifiés que par eux-mêmes.');
        }
        
        $ecoles = Ecole::orderBy('nom')->get();
        
        return view('users.edit', compact('user', 'ecoles'));
    }
    
    /**
     * Met à jour les informations d'un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les autorisations
        if ($currentUser->role !== 'superadmin' && $currentUser->id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }
        
        // Superadmin ne peut pas être édité sauf par lui-même
        if ($user->role === 'superadmin' && $currentUser->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'Les super administrateurs ne peuvent être modifiés que par eux-mêmes.');
        }
        
        $rules = [
            'nom' => 'required|string|min:2|max:255',
            'prenom' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
        ];
        
        // Pour les super admins qui modifient d'autres utilisateurs
        if ($currentUser->role === 'superadmin' && $currentUser->id !== $user->id) {
            $rules['role'] = 'required|in:admin,instructor';
            $rules['ecole_id'] = 'required|exists:ecoles,id';
            $rules['active'] = 'boolean';
        }
        
        // Si le mot de passe est fourni, le valider
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()];
        }
        
        $request->validate($rules);
        
        // Mettre à jour les informations de base
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        
        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // Pour les super admins qui modifient d'autres utilisateurs
        if ($currentUser->role === 'superadmin' && $currentUser->id !== $user->id) {
            $user->role = $request->role;
            $user->ecole_id = $request->ecole_id;
            $user->active = $request->boolean('active', true);
        }
        
        $user->save();
        
        Log::info("Utilisateur modifié : {$user->prenom} {$user->nom} (ID: {$user->id}) par user #{$currentUser->id}");
        
        if ($currentUser->id === $user->id) {
            return redirect()->route('users.show', $user)->with('success', 'Votre profil a été mis à jour avec succès.');
        } else {
            return redirect()->route('users.index')->with('success', 'Administrateur mis à jour avec succès.');
        }
    }
    
    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les autorisations (seuls les super admin peuvent supprimer des utilisateurs)
        if ($currentUser->role !== 'superladmin') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }
        
        // Empêcher la suppression de son propre compte
        if ($currentUser->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        // Empêcher la suppression d'un superadmin
        if ($user->role === 'superadmin') {
            return redirect()->route('users.index')->with('error', 'Les super administrateurs ne peuvent pas être supprimés.');
        }
        
        Log::info("Utilisateur supprimé : {$user->prenom} {$user->nom} (ID: {$user->id}) par user #{$currentUser->id}");
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'Administrateur supprimé avec succès.');
    }
    
    /**
     * Modifie le statut actif/inactif d'un utilisateur
     */
    public function toggleStatus(User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les autorisations
        if ($currentUser->role !== 'superadmin') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }
        
        // Empêcher la désactivation de son propre compte
        if ($currentUser->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
        
        // Empêcher la désactivation d'un superadmin
        if ($user->role === 'superadmin') {
            return redirect()->route('users.index')->with('error', 'Les super administrateurs ne peuvent pas être désactivés.');
        }
        
        $user->active = !$user->active;
        $user->save();
        
        $statusText = $user->active ? 'activé' : 'désactivé';
        Log::info("Utilisateur {$statusText} : {$user->prenom} {$user->nom} (ID: {$user->id}) par user #{$currentUser->id}");
        
        return redirect()->route('users.index')->with('success', "Administrateur {$statusText} avec succès.");
    }
    
    /**
     * Réinitialise le mot de passe d'un utilisateur
     */
    public function resetPassword(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Vérifier les autorisations
        if ($currentUser->role !== 'superadmin' && $currentUser->id !== $user->id) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }
        
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        Log::info("Mot de passe réinitialisé pour : {$user->prenom} {$user->nom} (ID: {$user->id}) par user #{$currentUser->id}");
        
        return redirect()->route('users.show', $user)->with('success', 'Mot de passe réinitialisé avec succès.');
    }
}
