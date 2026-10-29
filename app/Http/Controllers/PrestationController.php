<?php

namespace App\Http\Controllers;

use App\Models\Ap;
use App\Models\Assure;
use App\Models\Hopital;
use App\Models\Pharmacie;
use App\Models\Prescripteur;
use App\Models\Prestation;
use App\Models\Technicien;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestationController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, $id)
    {
        $prestation = Prestation::findOrFail($id);
        $prestations = Prestation::all();

        $users = User::all();
        $prescripteurs = DB::table('prescripteurs')->orderBy('prenom_prescripteur', 'asc')->get();
        $techniciens = DB::table('techniciens')->orderBy('nom_technicien', 'asc')->get();
        $hopitals = DB::table('hopitals')->orderBy('code_hopital', 'asc')->get();
        $pharmacies = DB::table('pharmacies')->orderBy('nom_pharmacie', 'asc')->get();
        $assures = Assure::all();
        $aps = DB::table('aps')->orderBy('designation_ap', 'asc')->get();

        return view('prestation.edit', compact('prestation', 'aps', 'prestations', 'users', 'prescripteurs', 'techniciens', 'hopitals', 'pharmacies'));
    }

    public function create()
    {
        return view('prestation.index');
    }

    public function store(Request $request)
    {
        $typeFeuille = $request->input('typeFeuille');
        $feuille = DB::raw("CONCAT('$typeFeuille', ' ', '$request->feuille')");

        $prestation = Prestation::create([
            'date' => $request->date,
            'assure' => $request->assure,
            'nature_examen' => $request->nature_examen,
            'renseignement' => $request->renseignement,
            'prescripteur' => $request->prescripteur,
            'technicien' => $request->technicien,
            'provenance' => $request->provenance,
            'pharmacie' => $request->pharmacie,
            'feuille' => $feuille,
            'controleur' => $request->controleur,
            'ap1' => $request->ap1,
            'ap2' => $request->ap2,
            'ap3' => $request->ap3,
            'ap4' => $request->ap4,
            'ap5' => $request->ap5,
            'ap6' => $request->ap6,
            'ap7' => $request->ap7,
            'ap8' => $request->ap8,
            'ap9' => $request->ap9,
            'ap10' => $request->ap10,
            'type1' => $request->type1,
            'type2' => $request->type2,
            'type3' => $request->type3,
            'type4' => $request->type4,
            'type5' => $request->type5,
            'type6' => $request->type6,
            'type7' => $request->type7,
            'type8' => $request->type8,
            'type9' => $request->type9,
            'type10' => $request->type10,
            'quantite1' => $request->quantite1,
            'quantite2' => $request->quantite2,
            'quantite3' => $request->quantite3,
            'quantite4' => $request->quantite4,
            'quantite5' => $request->quantite5,
            'quantite6' => $request->quantite6,
            'quantite7' => $request->quantite7,
            'quantite8' => $request->quantite8,
            'quantite9' => $request->quantite9,
            'quantite10' => $request->quantite10,
        ]);

        event(new Registered($prestation));

        return redirect('/prestations')->with('success', 'Prestation créer avec succèss');
    }

    public function index()
    {
        $prestations = Prestation::orderBy('created_at', 'desc')->paginate(5);
        $users = User::all();
        $prescripteurs = DB::table('prescripteurs')->orderBy('prenom_prescripteur', 'asc')->get();
        $techniciens = DB::table('techniciens')->orderBy('nom_technicien', 'asc')->get();
        $hopitals = DB::table('hopitals')->orderBy('code_hopital', 'asc')->get();
        $pharmacies = DB::table('pharmacies')->orderBy('nom_pharmacie', 'asc')->get();
        $assures = Assure::all();
        $aps = DB::table('aps')->orderBy('designation_ap', 'asc')->get();
        $prests = Prestation::all();

        return view('prestation.index', compact('prestations', 'prests', 'users', 'assures', 'prescripteurs', 'techniciens', 'hopitals', 'pharmacies', 'aps'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required',
            'assure' => 'required|max:13',
            'nature_examen' => '',
            'renseignement' => '',
            'prescripteur' => 'required|max:255',
            'technicien' => '',
            'provenance' => 'required',
            'pharmacie' => '',
            'feuille' => 'required|max:255',
            'controleur' => 'required|max:255',
            'ap1' => 'required',
            'ap2' => '',
            'ap3' => '',
            'ap4' => '',
            'ap5' => '',
            'ap6' => '',
            'ap7' => '',
            'ap8' => '',
            'ap9' => '',
            'ap10' => '',
            'type1' => 'required',
            'type2' => '',
            'type3' => '',
            'type4' => '',
            'type5' => '',
            'type6' => '',
            'type7' => '',
            'type8' => '',
            'type9' => '',
            'type10' => '',
            'quantite1' => 'required',
            'quantite2' => '',
            'quantite3' => '',
            'quantite4' => '',
            'quantite5' => '',
            'quantite6' => '',
            'quantite7' => '',
            'quantite8' => '',
            'quantite9' => '',
            'quantite10' => '',
        ]);

        Prestation::whereId($id)->update($validatedData);

        return redirect('/prestations')->with('success', 'Prestation mis à jour avec succès');
    }

    /**
     * Delete the user's account.
     */
    public function destroy($id)
    {
        $prestation = Prestation::findOrFail($id);
        $prestation->delete();

        return redirect('/prestations')->with('success', 'Prestation supprimer avec succèss');
    }

    public function recherche(Request $request)
    {
        $assure = $request->input('assure');
        $assures = Assure::all();

        $prestations = Prestation::orderBy('created_at', 'desc')->paginate(1);
        $users = User::all();
        $prescripteurs = Prescripteur::all();
        $techniciens = Technicien::all();
        $hopitals = Hopital::all();
        $pharmacies = Pharmacie::all();
        $aps = Ap::all();

        $resultats = Prestation::where('assure', $assure)->orderBy('created_at', 'desc')->get();

        return view('/prestation.recherche', compact('assure', 'assures', 'prestations', 'resultats', 'users', 'prescripteurs', 'techniciens', 'hopitals', 'pharmacies', 'aps'));
    }

    public function valider(Request $request)
    {
        $assure = $request->input('assure');
        $date = $request->input('date');

        $code1 = $request->input('ap1');
        $code2 = $request->input('ap2');
        $code3 = $request->input('ap3');
        $code4 = $request->input('ap4');
        $code5 = $request->input('ap5');
        $code6 = $request->input('ap6');
        $code7 = $request->input('ap7');
        $code8 = $request->input('ap8');
        $code9 = $request->input('ap9');
        $code10 = $request->input('ap10');

        $type1 = $request->input('type1');
        $type11 = $request->input('type11');
        $type2 = $request->input('type2');
        $type3 = $request->input('type3');
        $type4 = $request->input('type4');
        $type5 = $request->input('type5');
        $type6 = $request->input('type6');
        $type7 = $request->input('type7');
        $type8 = $request->input('type8');
        $type9 = $request->input('type9');
        $type10 = $request->input('type10');

        $quantite1 = $request->input('quantite1');
        $quantite2 = $request->input('quantite2');
        $quantite3 = $request->input('quantite3');
        $quantite4 = $request->input('quantite4');
        $quantite5 = $request->input('quantite5');
        $quantite6 = $request->input('quantite6');
        $quantite7 = $request->input('quantite7');
        $quantite8 = $request->input('quantite8');
        $quantite9 = $request->input('quantite9');
        $quantite10 = $request->input('quantite10');

        $aps1 = Ap::where('code_ap', $code1)->get();

        $aps2 = Ap::where('code_ap', $code2)->get();

        $aps3 = Ap::where('code_ap', $code3)->get();

        $aps4 = Ap::where('code_ap', $code4)->get();

        $aps5 = Ap::where('code_ap', $code5)->get();

        $aps6 = Ap::where('code_ap', $code6)->get();

        $aps7 = Ap::where('code_ap', $code7)->get();

        $aps8 = Ap::where('code_ap', $code8)->get();

        $aps9 = Ap::where('code_ap', $code9)->get();

        $aps10 = Ap::where('code_ap', $code10)->get();

        $aps = Ap::all();
        $prestations = Prestation::orderBy('created_at', 'desc')->paginate(5);
        $users = User::all();
        $prescripteurs = Prescripteur::all();
        $techniciens = Technicien::all();
        $hopitals = Hopital::all();
        $pharmacies = Pharmacie::all();
        $assures = Assure::all();

        $data = [
            'assure' => $assure,
            'date' => $date,
            'code1' => $code1,
            'code2' => $code2,
            'code3' => $code3,
            'code4' => $code4,
            'code5' => $code5,
            'code6' => $code6,
            'code7' => $code7,
            'code8' => $code8,
            'code9' => $code9,
            'code10' => $code10,
            'type1' => $type1,
            'type11' => $type11,
            'type2' => $type2,
            'type3' => $type3,
            'type4' => $type4,
            'type5' => $type5,
            'type6' => $type6,
            'type7' => $type7,
            'type8' => $type8,
            'type9' => $type9,
            'type10' => $type10,
            'quantite1' => $quantite1,
            'quantite2' => $quantite2,
            'quantite3' => $quantite3,
            'quantite4' => $quantite4,
            'quantite5' => $quantite5,
            'quantite6' => $quantite6,
            'quantite7' => $quantite7,
            'quantite8' => $quantite8,
            'quantite9' => $quantite9,
            'quantite10' => $quantite10,
            'aps' => $aps,
        ];

        return view('/prestation.validation', compact('assure', 'date', 'assures', 'aps', 'aps1', 'aps2', 'aps3', 'aps4', 'aps5', 'aps6', 'aps7', 'aps8', 'aps9', 'aps10',
            'code1', 'code2', 'code3', 'code4', 'code5', 'code6', 'code7', 'code8', 'code9', 'code10', 'type2',
            'type1', 'type11', 'type3', 'type4', 'type5', 'type6', 'type7', 'type8', 'type9', 'type10',
            'quantite1', 'quantite2', 'quantite3', 'quantite4', 'quantite5', 'quantite6', 'quantite7', 'quantite8', 'quantite9', 'quantite10',
            'users', 'assures', 'prescripteurs', 'techniciens', 'hopitals', 'pharmacies'
        ));
    }

    public function getPrix(Request $request)
    {
        $code_ap1 = $request->input('ap1');

        // Validation : Vérifiez si le code AP est non vide
        if (empty($code_ap1)) {
            return response()->json(['error' => 'Code AP manquant'], 400); // 400 Bad Request
        }

        // Recherche le code AP dans la base de données
        $ap1 = Ap::where('code_ap', $code_ap1)->first();

        // Vérifiez si le code AP a été trouvé
        if (!$ap1) {
            return response()->json(['error' => 'Code AP non trouvé'], 404); // 404 Not Found
        }

        // Récupérez le prix public ou définissez-le sur "Non Disponible" si non trouvé
        $prix1 = $ap1->code_ap1;

        return response()->json(['prix1' => $prix1]);
    }

    public function generatePDF(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        //$prestations = Prestation::get();
        $users = User::get();
        $assures = Assure::all();
        $aps = Ap::all();
        $users = User::all();
        $prescripteurs = Prescripteur::all();
        $techniciens = Technicien::all();
        $hopitals = Hopital::all();
        $pharmacies = Pharmacie::all();
        $assures = Assure::all();
        $prestations = Prestation::whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();

        $data = [
            'title' => 'Rapport De Gestion Des Accords Préalables ',
            'date' => date('m/d/Y'),
            'prestations' => $prestations,
            'users' => $users,
            'assures' => $assures,
            'prescripteurs' => $prescripteurs,
            'techniciens' => $techniciens,
            'hopitals' => $hopitals,
            'pharmacies' => $pharmacies,
            'aps' => $aps,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        $pdf = Pdf::loadView('prestation.createPdf', $data)->setPaper('landscape');

        return $pdf->download('RapportGestionAccordPrealable.pdf');
    }

}
