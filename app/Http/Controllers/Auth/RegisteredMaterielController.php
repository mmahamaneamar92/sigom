<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredMaterielController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.registerMateriel');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): View
    {
        $request->validate([
            'designation' => ['required', 'string', 'max:255', 'unique:'.Materiel::class],
        ]);

        $designation = Materiel::create([
            'designation' => $request->designation,
        ]);

        event(new Registered($designation));

        //Auth::login($designation);

        //return redirect(RouteServiceProvider::HOME);
        return view('auth.registerMateriel');
    }

    public function show()
    {
        $materiels = Materiel::paginate(5);
        return view('materiel.show', compact('materiels'));
    }

    public function destroy(Materiel $materiels): View
    {

        $materiels->delete();

        return view('materiel.show', compact('materiels'));
    }
}
