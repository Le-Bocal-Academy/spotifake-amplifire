<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        // TODO obliger des mots de passe plus sécurisés
        try {
            $validatedData = $request->validate([
                'nickname' => 'required|string|max:55',
                'firstname' => 'required|string|max:55',
                'lastname' => 'required|string|max:55',
                'email' => 'email|required|unique:accounts',
                // le body doit inclure les champs "password" et "password_confirmation"
                'password' => 'required|confirmed'
            ]);


            $validatedData['password'] = Hash::make($request->password);

            Account::create($validatedData);

            return response(['message' => 'Utilisateur créé'], 201);
        } catch (\Exception $exception) {
            Log::error($exception); // l'erreur s'affiche dans /storage/logs/laravel.log
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $account = Account::where('email', $validatedData['email'])->first();

            if (!$account || !Hash::check($validatedData['password'], $account->password)) {
                return response(['erreur' => 'Email ou mot de passe incorrect'], 400);
            }

            // retourne l'access token au front qui doit le stocker en "localstorage" 
            // puis utiliser ce token dans le header "Authorization" de toutes les autre requêtes
            return $account->createToken('token')->plainTextToken;
        } catch (\Exception $exception) {
            Log::error($exception); // l'erreur s'affiche dans /storage/logs/laravel.log            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            // Recherche du user actuellement authentifié
            $user = $request->user();

            // Récupération du token actuellement utilisé par l'utilisateur
            $currentToken = $user->currentAccessToken();

            // Suppression du token
            $user->tokens()->where('id', $currentToken->id)->delete();

            return response(['message' => 'Utilisateur déconnecté'], 200);
        } catch (\Exception $exception) {
            Log::error($exception); // l'erreur s'affiche dans /storage/logs/laravel.log
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }

    // se renseigner sur "mailpit", créer un email et ajouter les infos de l'adresse au .env "MAIL_***"
    // vérifier les deux fonctions suivantes
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)], 200)
            : response()->json(['email' => [__($status)]], 400);
    }

    public function test(Request $request)
    {
        return response()->json(['user' => $request->user()], 200);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)], 200)
            : response()->json(['email' => [__($status)]], 400);
    }
}
