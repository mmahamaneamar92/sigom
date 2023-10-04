<?php

namespace App\Http\Controllers;

use App\Models\Technicien;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class TechnicienController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $technicien = Technicien::findOrFail($id);
    $users = User::all();
    $techniciens = Technicien::all();

    return view('technicien.edit', compact('technicien', 'techniciens', 'users'));
}

public function create()
{
    return view('technicien.index');
}

public function store(Request $request)
{

    $nom = strtoupper($request->nom_technicien);

    $technicien = Technicien::create([
        'code_technicien' => $request->code_technicien,
        'prenom_technicien' => $request->prenom_technicien,
        'nom_technicien' => $nom,
    ]);

    event(new Registered($technicien));

    return redirect('/techniciens')->with('success', 'Technicien créer avec succèss');
}

public function index()
{
    $users = User::all();
    //$usersMateriels = UserMateriel::all();
    $techniciens = Technicien::orderBy('created_at', 'desc')->paginate(5);

    return view('technicien.index', compact('techniciens', 'users'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'code_technicien' => 'required',
        'nom_technicien' => 'required',
        'prenom_technicien' => 'required|max:255'
    ]);

    Technicien::whereId($id)->update($validatedData);

    return redirect('/techniciens')->with('success', 'Technicien mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $technicien = Technicien::findOrFail($id);
    $technicien->delete();

    return redirect('/gestions')->with('success', 'Matériel supprimer avec succèss');
}

}
