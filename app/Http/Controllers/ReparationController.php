<?php

namespace App\Http\Controllers;

use App\Models\Materiel;
use App\Models\Reparation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class ReparationController extends Controller
{
    public function edit($id)

{
    $reparation = Reparation::findOrFail($id);
    $materiels = Materiel::all();

    return view('reparation.edit', compact('reparation', 'materiels'));
}

public function create()
{
    return view('reparation.index');
}

public function store(Request $request)
{

    $reparation = Reparation::create([
        'materiels_designation' => $request->materiels_designation,
        'quantite' => $request->quantite,
        'commentaire' => $request->commentaire
    ]);

    event(new Registered($reparation));

    return redirect('/reparations')->with('success', 'Réparation créer avec succèss');
}

public function index()
{
    $materiels = Materiel::all();
    $reparations = Reparation::paginate(5);

    return view('reparation.index', compact('materiels', 'reparations'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'materiels_designation' => 'required|max:255',
        'quantite' => 'required|max:255',
        'commentaire' => 'required'
    ]);

    Reparation::whereId($id)->update($validatedData);

    return redirect('/reparations')->with('success', 'Réparation mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $reparation = Reparation::findOrFail($id);
    $reparation->delete();

    return redirect('/reparations')->with('success', 'Réparation supprimer avec succèss');
}
}
