<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materiel;
use App\Models\User;
use App\Models\UserMateriel;
use Illuminate\Auth\Events\Registered;
use Barryvdh\DomPDF\Facade\Pdf;

class BordereauController extends Controller
{
    public function edit($id)

{
    $usermateriel = UserMateriel::findOrFail($id);
    $user = User::findOrFail($id);
    $materiels = Materiel::all();
    $users = User::all();
    $usersmateriels = UserMateriel::all();

    return view('gestion.edit', compact('usermateriel', 'materiels', 'users', 'usersmateriels', 'user'));
}

public function create()
{
    return view('gestion.index');
}

public function store(Request $request)
{

    if($request->type == "Sortie"){
        $q = 0 - $request->quantite;
    }else {
    $q = $request->quantite;
}
    $userMateriel = UserMateriel::create([
        'type' => $request->type,
        'users_email' => $request->users_email,
        'materiels_designation' => $request->materiels_designation,
        'quantite' => $q,
        'commentaire' => $request->commentaire,
    ]);

    event(new Registered($userMateriel));

    return redirect('/gestions')->with('success', 'Matériel créer avec succèss');
}

public function index()
{
    $materiels = Materiel::all();
    $users = User::all();
    //$usersMateriels = UserMateriel::all();
    $usersMateriels = UserMateriel::paginate(5);

    return view('gestion.be', compact('materiels', 'users', 'usersMateriels'));
}

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'type' => 'required',
        'users_email' => 'required',
        'materiels_designation' => 'required|max:255',
        'quantite' => 'required|max:255',
        'commentaire' => 'required'
    ]);

    UserMateriel::whereId($id)->update($validatedData);

    return redirect('/gestions')->with('success', 'Matériel mis à jour avec succès');
}

    /**
     * Delete the user's account.
     */
    public function destroy($id)
{
    $userMateriel = UserMateriel::findOrFail($id);
    $userMateriel->delete();

    return redirect('/gestions')->with('success', 'Matériel supprimer avec succèss');
}

public function recherche(Request $request)
{
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    $materiels = Materiel::all();
    $users = User::all();

    $resultats = UserMateriel::whereBetween('created_at', [$start_date, $end_date])->get();

    return view('/gestion.recherche', compact('resultats', 'users', 'materiels'));
}

public function generatePDF(Request $request)
{
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    $usersmateriels = UserMateriel::get();
    $users = User::get();
    $resultats = UserMateriel::whereBetween('created_at', [$start_date, $end_date])->get();

    $data = [
        'title' => 'Rapport De Gestion De Materiels Informatique ',
        'date' => date('m/d/Y'),
        'usersmateriels' => $usersmateriels,
        'users' => $users,
        'resultats' => $resultats,
        'start_date' => $start_date,
        'end_date' => $end_date
    ];

    $pdf = Pdf::loadView('gestion.createPdf', $data);

    return $pdf->download('itsolutionstuff.pdf');
}

public function bordereau($id)

{
    $usermateriel = UserMateriel::findOrFail($id);
    $user = User::findOrFail($id);
    $materiels = Materiel::get();
    $users = User::get();
    $usersmateriels = UserMateriel::get();

    $data = [
        'title' => 'Bordereau',
        'date' => date('m/d/Y'),
        'usersmateriels' => $usersmateriels,
        'users' => $users,
        'usermateriel' => $usermateriel,
        'user' => $user
    ];

    $pdf = Pdf::loadView('gestion.createBEPdf', $data);

    return $pdf->download('Bordereau.pdf');
}
}
