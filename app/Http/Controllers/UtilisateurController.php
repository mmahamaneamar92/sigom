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
    $profil = Profil::findOrFail($id);
    $user = User::findOrFail($id);
    $users = User::all();
    $profils = Profil::all();

    return view('profile.edition', compact('profil', 'profils', 'users', 'user'));
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
    $validatedData = $request->validate([
        'name' => 'required',
        'email' => 'required',
        'profil' => 'required|max:255',
        'password' => 'required|max:255'
    ]);

    User::whereId($id)->update($validatedData);

    return redirect('/profiles')->with('success', 'Technicien mis à jour avec succès');
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
