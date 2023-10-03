<?php

namespace App\Http\Controllers;

use App\Models\Materiel;
use App\Models\User;
use App\Models\UserMateriel;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Barryvdh\DomPDF\Facade\Pdf;

class UserMaterielController extends Controller
{
    /**
     * Display the user's profile form.
     */
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

    return view('gestion.index', compact('materiels', 'users', 'usersMateriels'));
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

    $type = $request->input('type');

    $materiels = Materiel::all();
    $users = User::all();

    $resultats = UserMateriel::whereBetween('created_at', [$start_date, $end_date])->where('type', $type)->get();

    return view('/gestion.recherche', compact('resultats', 'users', 'materiels'));
}

public function generatePDF(Request $request)
{
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    $type = $request->input('type');

    $usersmateriels = UserMateriel::get();
    $users = User::get();
    $resultats = UserMateriel::whereBetween('created_at', [$start_date, $end_date])->where('type', $type)->get();

    $data = [
        'title' => 'Rapport De Gestion De Materiels Informatique ',
        'date' => date('m/d/Y'),
        'usersmateriels' => $usersmateriels,
        'users' => $users,
        'resultats' => $resultats,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'type' => $type
    ];

    $pdf = Pdf::loadView('gestion.createPdf', $data);

    return $pdf->download('RapportGestionMateriel.pdf');
}

public function bordereau(Request $request)

{
    $numero_bordereau = $request->input('numero_bordereau');
    $type_bordereau = $request->input('type_bordereau');
    $date = $request->input('date');
    $materiels_designation = $request->input('materiels_designation');
    $quantite = $request->input('quantite');
    $materiels_designation2 = $request->input('materiels_designation2');
    $quantite2 = $request->input('quantite2');
    $materiels_designation3 = $request->input('materiels_designation3');
    $quantite3 = $request->input('quantite3');
    $materiels_designation4 = $request->input('materiels_designation4');
    $quantite4 = $request->input('quantite4');
    $materiels_designation5 = $request->input('materiels_designation5');
    $quantite5 = $request->input('quantite5');
    $commentaire = $request->input('commentaire');
    $destination = $request->input('destination');
    $total = $quantite + $quantite2 + $quantite3 + $quantite4 + $quantite5;

    $data = [
        'title' => "BORDEREAU ",
        'type_bordereau' => $type_bordereau,
        'date' => $date,
        'expediteur' => "Monsieur le Directeur du Système d'Information",
        'numero_bordereau' => $numero_bordereau,
        'materiels_designation' => $materiels_designation,
        'quantite' => $quantite,
        'materiels_designation2' => $materiels_designation2,
        'quantite2' => $quantite2,
        'materiels_designation3' => $materiels_designation3,
        'quantite3' => $quantite3,
        'materiels_designation4' => $materiels_designation4,
        'quantite4' => $quantite4,
        'materiels_designation5' => $materiels_designation5,
        'quantite5' => $quantite5,
        'commentaire' => $commentaire,
        'destination' => $destination,
        'total' => $total
    ];

    $pdf = Pdf::loadView('gestion.createBEPdf', $data);

    return $pdf->download('Bordereau.pdf');
}



}
