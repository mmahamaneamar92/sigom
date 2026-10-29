<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\MissionAgentController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\MissionInitiationController;
use App\Http\Controllers\MissionPdfController;
use App\Http\Controllers\PassController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\VoitureController;


/*
|--------------------------------------------------------------------------
| PAGE D'ACCUEIL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.log');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', [
    DashboardController::class,
    'index',
])->name('dashboard');


/*
|--------------------------------------------------------------------------
| INITIATIONS DE MISSION
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post(
        '/mission-initiations',
        [MissionInitiationController::class, 'store']
    )->name('mission-initiations.store');

    Route::get(
        '/mission-initiations/{missionInitiation}/edit',
        [MissionInitiationController::class, 'edit']
    )->name('mission-initiations.edit');

    Route::put(
        '/mission-initiations/{missionInitiation}',
        [MissionInitiationController::class, 'update']
    )->name('mission-initiations.update');

    Route::delete(
        '/mission-initiations/{missionInitiation}',
        [MissionInitiationController::class, 'destroy']
    )->name('mission-initiations.destroy');

    Route::post(
        '/mission-initiations/{missionInitiation}/transmettre-cg',
        [MissionInitiationController::class, 'transmettreCg']
    )->name('mission-initiations.transmettre-cg');

    Route::get(
        '/mission-initiations/{missionInitiation}/tdr',
        [MissionInitiationController::class, 'telechargerTdr']
    )->name('mission-initiations.tdr');
});


/*
|--------------------------------------------------------------------------
| PROFIL UTILISATEUR
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [
        ProfileController::class,
        'edit',
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update',
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy',
    ])->name('profile.destroy');

    Route::resource('profiles', ProfileController::class);
});


/*
|--------------------------------------------------------------------------
| AUTRES RESSOURCES SIGOM
|--------------------------------------------------------------------------
|
| Ces routes étaient présentes dans le fichier d'origine.
| Vérifie que les contrôleurs appliquent aussi les autorisations par profil.
|
*/

Route::middleware('auth')->group(function () {
    Route::resource('types', TypeController::class);
    Route::resource('utilisateurs', UtilisateurController::class);
    Route::resource('pass', PassController::class);

    /*
    |--------------------------------------------------------------------------
    | DIRECTIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/directions', [
        DirectionController::class,
        'index',
    ])->name('indexDirection');

    Route::post('/directions', [
        DirectionController::class,
        'store',
    ])->name('storeDirection');

    Route::get('/fetch-directions', [
        DirectionController::class,
        'fetchdirection',
    ])->name('fetchdirection');

    Route::get('/edit-direction/{id}', [
        DirectionController::class,
        'edit',
    ])->name('editDirection');

    Route::put('/update-direction/{id}', [
        DirectionController::class,
        'update',
    ])->name('updateDirection');

    Route::delete('/delete-direction/{id}', [
        DirectionController::class,
        'destroy',
    ])->name('deleteDirection');

    Route::get('/getServices/{id}', [
        DirectionController::class,
        'getServices',
    ])->name('getServices');

    Route::post('/fetch', [
        DirectionController::class,
        'fetch',
    ])->name('fetch');


    /*
    |--------------------------------------------------------------------------
    | SERVICES
    |--------------------------------------------------------------------------
    */

    Route::get('/services', [
        ServiceController::class,
        'index',
    ])->name('indexService');

    Route::post('/services', [
        ServiceController::class,
        'store',
    ])->name('storeService');

    Route::get('/fetch-services', [
        ServiceController::class,
        'fetchservice',
    ])->name('fetchservice');

    Route::get('/edit-service/{id}', [
        ServiceController::class,
        'edit',
    ])->name('editService');

    Route::put('/update-service/{id}', [
        ServiceController::class,
        'update',
    ])->name('updateService');

    Route::delete('/delete-service/{id}', [
        ServiceController::class,
        'destroy',
    ])->name('deleteService');


    /*
    |--------------------------------------------------------------------------
    | AGENTS
    |--------------------------------------------------------------------------
    */

    Route::get('/agents', [
        AgentController::class,
        'index',
    ])->name('agents.index');

    Route::post('/agents', [
        AgentController::class,
        'store',
    ])->name('agents.store');

    Route::get('/fetch-agents', [
        AgentController::class,
        'fetchagent',
    ])->name('fetchagent');

    Route::get('/edit-agent/{id}', [
        AgentController::class,
        'edit',
    ])->name('editAgent');

    Route::put('/update-agent/{id}', [
        AgentController::class,
        'update',
    ])->name('updateAgent');

    Route::delete('/delete-agent/{id}', [
        AgentController::class,
        'destroy',
    ])->name('deleteAgent');


    /*
    |--------------------------------------------------------------------------
    | FONCTIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/fonctions', [
        FonctionController::class,
        'index',
    ])->name('fonctions.index');

    Route::get('/fetch-fonctions', [
        FonctionController::class,
        'fetchfonction',
    ])->name('fonctions.fetch');

    Route::post('/fonctions', [
        FonctionController::class,
        'store',
    ])->name('fonctions.store');

    Route::get('/edit-fonction/{id}', [
        FonctionController::class,
        'edit',
    ])->name('fonctions.edit');

    Route::put('/update-fonction/{id}', [
        FonctionController::class,
        'update',
    ])->name('fonctions.update');

    Route::delete('/delete-fonction/{id}', [
        FonctionController::class,
        'destroy',
    ])->name('fonctions.destroy');


    /*
    |--------------------------------------------------------------------------
    | CATÉGORIES
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [
        CategorieController::class,
        'index',
    ])->name('categories.index');

    Route::get('/fetch-categories', [
        CategorieController::class,
        'fetchcategorie',
    ])->name('categories.fetch');

    Route::post('/categories', [
        CategorieController::class,
        'store',
    ])->name('categories.store');

    Route::get('/edit-categorie/{id}', [
        CategorieController::class,
        'edit',
    ])->name('categories.edit');

    Route::put('/update-categorie/{id}', [
        CategorieController::class,
        'update',
    ])->name('categories.update');

    Route::delete('/delete-categorie/{id}', [
        CategorieController::class,
        'destroy',
    ])->name('categories.destroy');


    /*
    |--------------------------------------------------------------------------
    | VOITURES
    |--------------------------------------------------------------------------
    */

    Route::get('/voitures', [
        VoitureController::class,
        'index',
    ])->name('voitures.index');

    Route::get('/fetch-voitures', [
        VoitureController::class,
        'fetchvoiture',
    ])->name('voitures.fetch');

    Route::post('/voitures', [
        VoitureController::class,
        'store',
    ])->name('voitures.store');

    Route::get('/edit-voiture/{id}', [
        VoitureController::class,
        'edit',
    ])->name('voitures.edit');

    Route::put('/update-voiture/{id}', [
        VoitureController::class,
        'update',
    ])->name('voitures.update');

    Route::delete('/delete-voiture/{id}', [
        VoitureController::class,
        'destroy',
    ])->name('voitures.destroy');


    /*
    |--------------------------------------------------------------------------
    | PAYS
    |--------------------------------------------------------------------------
    */

    Route::get('/pays', [
        PaysController::class,
        'index',
    ])->name('pays.index');

    Route::get('/fetch-pays', [
        PaysController::class,
        'fetchpays',
    ])->name('pays.fetch');

    Route::post('/pays', [
        PaysController::class,
        'store',
    ])->name('pays.store');

    Route::get('/edit-pays/{id}', [
        PaysController::class,
        'edit',
    ])->name('pays.edit');

    Route::put('/update-pays/{id}', [
        PaysController::class,
        'update',
    ])->name('pays.update');

    Route::delete('/delete-pays/{id}', [
        PaysController::class,
        'destroy',
    ])->name('pays.destroy');


    /*
    |--------------------------------------------------------------------------
    | RÉGIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/regions', [
        RegionController::class,
        'index',
    ])->name('regions.index');

    Route::get('/fetch-regions', [
        RegionController::class,
        'fetchregion',
    ])->name('regions.fetch');

    Route::post('/regions', [
        RegionController::class,
        'store',
    ])->name('regions.store');

    Route::get('/edit-region/{id}', [
        RegionController::class,
        'edit',
    ])->name('regions.edit');

    Route::put('/update-region/{id}', [
        RegionController::class,
        'update',
    ])->name('regions.update');

    Route::delete('/delete-region/{id}', [
        RegionController::class,
        'destroy',
    ])->name('regions.destroy');

    Route::get('/pays/{paysId}/regions', [
        RegionController::class,
        'byPays',
    ])->name('regions.byPays');
});


/*
|--------------------------------------------------------------------------
| INITIATIONS ET ORDRES DE MISSION
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | MISSIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/missions', [
        MissionController::class,
        'index',
    ])->name('missions.index');

    Route::get('/missions/create', [
        MissionController::class,
        'create',
    ])->middleware('profil:controle_gestion')
      ->name('missions.create');

    Route::post('/missions', [
        MissionController::class,
        'store',
    ])->middleware('profil:controle_gestion')
      ->name('missions.store');


    /*
    |--------------------------------------------------------------------------
    | AGENTS D'UNE MISSION
    |--------------------------------------------------------------------------
    */

    Route::get('/missions/{missionId}/agents', [
        MissionAgentController::class,
        'index',
    ])->name('missions.agents.index');

    Route::get('/missions/{missionId}/agents/create', [
        MissionAgentController::class,
        'create',
    ])->middleware('profil:direction')
      ->name('missions.agents.create');

    Route::post('/missions/{missionId}/agents', [
        MissionAgentController::class,
        'store',
    ])->middleware('profil:direction')
      ->name('missions.agents.store');

    Route::post('/missions/{missionId}/agents-plusieurs', [
        MissionAgentController::class,
        'ajouterPlusieurs',
    ])->middleware('profil:direction')
      ->name('missions.agents.plusieurs');


    /*
    |--------------------------------------------------------------------------
    | MODIFICATION ET SUPPRESSION
    |--------------------------------------------------------------------------
    */

    Route::get('/missions/{id}/edit', [
        MissionController::class,
        'edit',
    ])->middleware('profil:controle_gestion')
      ->name('missions.edit');

    Route::put('/missions/{id}', [
        MissionController::class,
        'update',
    ])->middleware('profil:controle_gestion')
      ->name('missions.update');

    Route::delete('/missions/{id}', [
        MissionController::class,
        'destroy',
    ])->middleware('profil:controle_gestion')
      ->name('missions.destroy');


    /*
    |--------------------------------------------------------------------------
    | WORKFLOW SIGOM-CANAM
    |--------------------------------------------------------------------------
    */

    Route::post('/missions/{id}/transmettre-cg', [
        MissionController::class,
        'transmettreCG',
    ])->middleware('profil:service_demandeur')
      ->name('missions.transmettre-cg');

    Route::post('/missions/{id}/preparer-cg', [
        MissionController::class,
        'preparerCG',
    ])->middleware('profil:cg')
      ->name('missions.preparer-cg');

    Route::post('/missions/{id}/transmettre-service-demandeur', [
        MissionController::class,
        'transmettreServiceDemandeur',
    ])->middleware('profil:cg')
      ->name('missions.transmettre-service-demandeur');

    Route::post('/missions/{id}/transmettre-dga', [
        MissionController::class,
        'transmettreDGA',
    ])->middleware('profil:service_demandeur')
      ->name('missions.transmettre-dga');

    Route::post('/missions/{id}/viser-dga', [
        MissionController::class,
        'viserDGA',
    ])->middleware('profil:dga')
      ->name('missions.viser-dga');

    Route::post('/missions/{id}/viser-dagrh', [
        MissionController::class,
        'viserDAGRH',
    ])->middleware('profil:dagrh')
      ->name('missions.viser-dagrh');

    Route::get('/missions/{id}/vehicules-disponibles', [
        MissionController::class,
        'vehiculesDisponibles',
    ])->middleware('profil:moyens_generaux')
      ->name('missions.vehicules-disponibles');

    Route::post('/missions/{id}/affecter-voiture', [
        MissionController::class,
        'affecterVoiture',
    ])->middleware('profil:moyens_generaux')
      ->name('missions.affecter-voiture');

    Route::post('/missions/{id}/valider-rh', [
        MissionController::class,
        'validerRH',
    ])->middleware('profil:rh')
      ->name('missions.valider-rh');

    Route::post('/missions/{id}/transmettre-rh', [
        MissionController::class,
        'transmettreRH',
    ])->middleware('profil:rh')
      ->name('missions.transmettre-rh');

    Route::post('/missions/{id}/envoyer-budget', [
        MissionController::class,
        'envoyerBudget',
    ])->middleware('profil:rh')
      ->name('missions.envoyer-budget');

    Route::post('/missions/{id}/valider-budget', [
        MissionController::class,
        'validerBudget',
    ])->middleware('profil:budget')
      ->name('missions.valider-budget');

    Route::post('/missions/{id}/retour-rh-budget', [
        MissionController::class,
        'retourRHBudget',
    ])->middleware('profil:budget')
      ->name('missions.retour-rh-budget');

    Route::post('/missions/{id}/payer', [
        MissionController::class,
        'payer',
    ])->middleware('profil:comptabilite')
      ->name('missions.payer');

    Route::post('/missions/{id}/terminer', [
        MissionController::class,
        'terminer',
    ])->middleware('profil:service_demandeur')
      ->name('missions.terminer');

    Route::post('/missions/{id}/annuler', [
        MissionController::class,
        'annuler',
    ])->name('missions.annuler');


    /*
    |--------------------------------------------------------------------------
    | TRANSMISSIONS ET RETOURS
    |--------------------------------------------------------------------------
    */

    Route::post('/missions/{id}/transmettre-direction-consultation', [
        MissionController::class,
        'transmettreDirectionConsultation',
    ])->name('missions.transmettreDirectionConsultation');

    Route::post('/missions/{id}/retour-direction-consultation', [
        MissionController::class,
        'retourDirectionConsultation',
    ])->name('missions.retour-direction-consultation');

    Route::post('/missions/{id}/retour-dga', [
        MissionController::class,
        'retourDGA',
    ])->name('missions.retour-dga');

    Route::post('/missions/{id}/retour-cg', [
        MissionController::class,
        'retourCG',
    ])->name('missions.retour-cg');

    Route::post('/missions/{id}/transmettre-dagrh', [
        MissionController::class,
        'transmettreDAGRH',
    ])->name('missions.transmettre-dagrh');

    Route::post('/missions/{id}/transmettre-moyens-generaux', [
        MissionController::class,
        'transmettreMoyensGeneraux',
    ])->name('missions.transmettre-moyens-generaux');

    Route::post('/missions/{id}/transmettre-rh-apres-mg', [
        MissionController::class,
        'transmettreRHApresMG',
    ])->name('missions.transmettre-rh-apres-mg');

    Route::post('/missions/{id}/retourner-dagrh', [
        MissionController::class,
        'retournerDAGRH',
    ])->name('missions.retourner-dagrh');

    Route::post('/missions/{id}/retour-direction', [
        MissionController::class,
        'retourDirection',
    ])->name('missions.retour-direction');

    Route::post('/missions/{id}/retourner-mg-rh', [
        MissionController::class,
        'retournerMGDepuisRH',
    ])->name('missions.retourner-mg-rh');


    /*
    |--------------------------------------------------------------------------
    | AGENTS DE MISSION
    |--------------------------------------------------------------------------
    */

    Route::get('/mission-agents/{id}/edit', [
        MissionAgentController::class,
        'edit',
    ])->middleware('profil:direction')
      ->name('mission-agents.edit');

    Route::put('/mission-agents/{id}', [
        MissionAgentController::class,
        'update',
    ])->middleware('profil:direction')
      ->name('mission-agents.update');

    Route::delete('/mission-agents/{id}', [
        MissionAgentController::class,
        'destroy',
    ])->middleware('profil:direction')
      ->name('mission-agents.destroy');


    /*
    |--------------------------------------------------------------------------
    | CARBURANT ET BUDGET INTERNATIONAL
    |--------------------------------------------------------------------------
    */

    Route::post('/missions/{id}/recalculer', [
        MissionController::class,
        'recalculer',
    ])->middleware('profil:direction')
      ->name('missions.recalculer');

    Route::post('/missions/{mission}/carburant', [
        MissionController::class,
        'gererCarburant',
    ])->name('missions.carburant.update');

    Route::post('/missions/{mission}/budget-international', [
        MissionController::class,
        'gererBudgetInternational',
    ])->name('missions.budget-international.update');


    /*
    |--------------------------------------------------------------------------
    | PDF ET RAPPORTS
    |--------------------------------------------------------------------------
    */

    Route::get('/missions/{mission}/pdf/budget-international', [
        MissionPdfController::class,
        'budgetInternational',
    ])->name('missions.pdf.budget-international');

    Route::get('/missions/{mission}/pdf/budget-national', [
        MissionPdfController::class,
        'budgetNational',
    ])->name('missions.pdf.budget-national');

    Route::get('/missions/{mission}/agents/{agent}/decharge', [
        MissionPdfController::class,
        'dechargeAgent',
    ])->name('missions.pdf.decharge');

    Route::get('/missions/{mission}/pdf/ordre', [
        MissionPdfController::class,
        'ordreMission',
    ])->name('missions.pdf.ordre');

    Route::get('/dashboard/rapport-agents-pdf', [
        DashboardController::class,
        'rapportAgentsPdf',
    ])->name('dashboard.rapport.agents.pdf');


    /*
    |--------------------------------------------------------------------------
    | RÉGIONS ET CERCLES
    |--------------------------------------------------------------------------
    */

    Route::get('/regions/{regionId}/cercles', function ($regionId) {
        return DB::table('cercles')
            ->where('region_id', $regionId)
            ->orderBy('nom_cercle')
            ->get(['id', 'nom_cercle']);
    })->whereNumber('regionId')
      ->name('regions.cercles');


    /*
    |--------------------------------------------------------------------------
    | AFFICHAGE D'UNE MISSION
    |--------------------------------------------------------------------------
    |
    | Cette route doit rester après les routes spécifiques /missions/{id}/...
    |
    */

    Route::get('/missions/{id}', [
        MissionController::class,
        'show',
    ])->name('missions.show');
});


require __DIR__ . '/auth.php';
