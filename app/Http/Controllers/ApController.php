<?php

namespace App\Http\Controllers;

use App\Models\Ap;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class ApController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)

{
    $ap = Ap::findOrFail($id);
    $aps = Ap::all();
    $users = User::all();

    return view('ap.edit', compact('ap', 'aps', 'users'));
}

public function create()
{
    return view('ap.index');
}

public function store(Request $request)
{
    $ap = Ap::create([
        'code_ap' => $request->code_ap,
        'designation_ap' => $request->designation_ap,
        'prix_rembousement_ap' => $request->prix_rembousement_ap,
        'prix_public_ap' => $request->prix_public_ap,
    ]);

    event(new Registered($ap));

    return redirect('/aps')->with('success', 'Accord Préalable créer avec succèss');
}

public function index()
{
    $users = User::all();
    $aps = Ap::orderBy('created_at', 'desc')->paginate(10);

    return view('ap.index', compact('aps', 'users'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $id = $request->id;

    $validatedData = $request->validate([
        'code_ap' => 'required',
        'designation_ap' => 'required',
        'prix_rembousement_ap' => 'required|max:255',
        'prix_public_ap' => 'required|max:255'
    ]);

    Ap::whereId($id)->update($validatedData);

    return redirect('/aps')->with('success', 'Accord Préalable mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $ap = Ap::findOrFail($id);
    $ap->delete();

    return redirect('/aps')->with('success', 'Accord Préalable supprimer avec succèss');
}
}
