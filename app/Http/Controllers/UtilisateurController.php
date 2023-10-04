<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UtilisateurController extends Controller
{

    public function edit($id)

{
    $user = User::findOrFail($id);
    $users = User::all();
    $profils = Profil::all();

    return view('profile.edition', compact('profils', 'users', 'user'));
}
    public function index()
{
    $users = User::orderBy('created_at', 'desc')->paginate(5);
    //$usersMateriels = UserMateriel::all();
    $profils = Profil::all();

    return view('profile.index', compact('profils', 'users'));
}

public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'profil' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'profil' => $request->profil,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        //Auth::login($user);

        return redirect('/profiles')->with('success', 'Utilisateur créer avec succèss');
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required',
        'profil' => 'required'
    ]);

    User::whereId($id)->update($validated);

    return redirect('/profiles')->with('success', 'Utilisateur mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect('/profiles')->with('success', 'Matériel supprimer avec succèss');
}
}
