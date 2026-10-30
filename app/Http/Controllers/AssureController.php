<?php

namespace App\Http\Controllers;

use App\Models\Assure;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class AssureController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $assure = Assure::findOrFail($id);
    $users = User::all();
    $assures = Assure::all();

    return view('assure.edit', compact('assure', 'assures', 'users'));
}

public function create()
{
    return view('assure.index');
}

public function store(Request $request)
{
    $nom = strtoupper($request->nom);

    $assure = Assure::create([
        'numero_assurer' => $request->numero_assurer,
        'prenom' => $request->prenom,
        'nom' => $nom,
        'tel' => $request->tel,
        'ogd' => $request->ogd,
    ]);

    event(new Registered($assure));

    return redirect('/assures')->with('success', 'Assuré créer avec succèss');
}

public function index()
{
    $users = User::all();
    $assures = Assure::orderBy('created_at', 'desc')->paginate(10);

    return view('assure.index', compact('assures', 'users'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'numero_assurer' => 'required|max:13',
        'prenom' => 'required',
        'nom' => 'required|max:255',
        'tel' => 'required|max:8',
        'ogd' => 'required|max:255'
    ]);

    Assure::whereId($id)->update($validatedData);

    return redirect('/assures')->with('success', 'Assuré mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $assure = Assure::findOrFail($id);
    $assure->delete();

    return redirect('/assures')->with('success', 'Assuré supprimer avec succèss');
}
}
