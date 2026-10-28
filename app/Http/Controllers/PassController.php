<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PassController extends Controller
{
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => 'required'
        ]);

        User::whereId($id)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect('/profiles')->with('success', 'Mot de passe mis à jour avec succès');
    }
}
