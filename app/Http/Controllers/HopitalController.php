<?php

namespace App\Http\Controllers;

use App\Models\Hopital;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class HopitalController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $hopital = Hopital::findOrFail($id);
    $users = User::all();
    $hopitals = Hopital::all();

    return view('hopital.edit', compact('hopital', 'hopitals', 'users'));
}

public function create()
{
    return view('hopital.index');
}

public function store(Request $request)
{
    $hopital = Hopital::create([
        'code_hopital' => $request->code_hopital,
        'nom_hopital' => $request->nom_hopital,
    ]);

    event(new Registered($hopital));

    return redirect('/hopitals')->with('success', 'Hopital créer avec succèss');
}

public function index()
{
    $users = User::all();
    $hopitals = Hopital::orderBy('created_at', 'desc')->paginate(5);

    return view('hopital.index', compact('hopitals', 'users'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'code_hopital' => 'required',
        'nom_hopital' => 'required'
    ]);

    Hopital::whereId($id)->update($validatedData);

    return redirect('/hopitals')->with('success', 'Hopital mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $hopital = Hopital::findOrFail($id);
    $hopital->delete();

    return redirect('/hopitals')->with('success', 'Hopital supprimer avec succèss');
}
}
