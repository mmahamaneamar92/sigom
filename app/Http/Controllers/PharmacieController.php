<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class PharmacieController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $pharmacie = Pharmacie::findOrFail($id);
    $user = User::findOrFail($id);
    $users = User::all();
    $pharmacies = Pharmacie::all();

    return view('pharmacie.edit', compact('pharmacie', 'pharmacies', 'users', 'user'));
}

public function create()
{
    return view('pharmacie.index');
}

public function store(Request $request)
{
    $pharmacie = Pharmacie::create([
        'code_pharmacie' => $request->code_pharmacie,
        'nom_pharmacie' => $request->nom_pharmacie,
    ]);

    event(new Registered($pharmacie));

    return redirect('/pharmacies')->with('success', 'Pharmacie créer avec succèss');
}

public function index()
{
    $users = User::all();
    $pharmacies = Pharmacie::orderBy('created_at', 'desc')->paginate(5);

    return view('pharmacie.index', compact('pharmacies', 'users'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'code_pharmacie' => 'required',
        'nom_pharmacie' => 'required'
    ]);

    Pharmacie::whereId($id)->update($validatedData);

    return redirect('/pharmacies')->with('success', 'Pharmacie mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $pharmacie = Pharmacie::findOrFail($id);
    $pharmacie->delete();

    return redirect('/pharmacies')->with('success', 'Pharmacie supprimer avec succèss');
}
}
