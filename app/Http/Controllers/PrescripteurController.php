<?php

namespace App\Http\Controllers;

use App\Models\Prescripteur;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class PrescripteurController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $prescripteur = Prescripteur::findOrFail($id);
    $user = User::findOrFail($id);
    $users = User::all();
    $prescripteurs = Prescripteur::all();

    return view('prescripteur.edit', compact('prescripteur', 'prescripteurs', 'users', 'user'));
}

public function create()
{
    return view('prescripteur.index');
}

public function store(Request $request)
{
    $prescripteur = Prescripteur::create([
        'code_prescripteur' => $request->code_prescripteur,
        'prenom_prescripteur' => $request->prenom_prescripteur,
        'nom_prescripteur' => $request->nom_prescripteur,
    ]);

    event(new Registered($prescripteur));

    return redirect('/prescripteurs')->with('success', 'Prescripteur créer avec succèss');
}

public function index()
{
    $users = User::all();
    $prescripteurs = Prescripteur::orderBy('created_at', 'desc')->paginate(5);

    return view('prescripteur.index', compact('prescripteurs', 'users'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'code_prescripteur' => 'required',
        'prenom_prescripteur' => 'required',
        'nom_prescripteur' => 'required'
    ]);

    Prescripteur::whereId($id)->update($validatedData);

    return redirect('/prescripteurs')->with('success', 'Prescripteur mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $prescripteur = Prescripteur::findOrFail($id);
    $prescripteur->delete();

    return redirect('/prescripteurs')->with('success', 'prescripteur supprimer avec succèss');
}
}
