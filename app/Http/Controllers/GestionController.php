<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterielUpdateRequest;
use App\Models\Materiel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MaterielController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $materiel = Materiel::findOrFail($id);

    return view('materiel.edit', compact('materiel'));
}

public function create()
{
    return view('materiel.index');
}

public function store(Request $request)
{

    $request->validate([
        'designation' => ['required', 'string', 'max:255', 'unique:'.Materiel::class],
    ]);

    $designation = Materiel::create([
        'designation' => $request->designation,
    ]);

    event(new Registered($designation));

    return redirect('/materiels')->with('success', 'Matériel créer avec succèss');
}

public function index()
{
    $materiels = Materiel::all();

    return view('materiel.index', compact('materiels'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'designation' => 'required|max:255'
    ]);

    Materiel::whereId($id)->update($validatedData);

    return redirect('/materiels')->with('success', 'Matériel mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $materiel = Materiel::findOrFail($id);
    $materiel->delete();

    return redirect('/materiels')->with('success', 'Matériel supprimer avec succèss');
}

    public function show($designation)
    {
        $event = Materiel::findOrFail($designation);
        return view('events.show', compact('event'));
    }


}
