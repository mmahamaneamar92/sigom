<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $profil = Profil::findOrFail($id);
    $user = User::findOrFail($id);
    $users = User::all();
    $profils = Profil::all();

    return view('type.edit', compact('profil', 'profils', 'users', 'user'));
}

public function create()
{
    return view('type.index');
}

public function store(Request $request)
{
    $profil = Profil::create([
        'nom_profil' => $request->nom_profil,
    ]);

    event(new Registered($profil));

    return redirect('/types')->with('success', 'Technicien créer avec succèss');
}

public function index()
{
    $users = User::all();
    //$usersMateriels = UserMateriel::all();
    $profils = Profil::orderBy('created_at', 'desc')->paginate(5);

    return view('type.index', compact('profils', 'users'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'nom_profil' => 'required|max:255'
    ]);

    Profil::whereId($id)->update($validatedData);

    return redirect('/types')->with('success', 'Technicien mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $profil = Profil::findOrFail($id);
    $profil->delete();

    return redirect('/types')->with('success', 'Matériel supprimer avec succèss');
}
}
