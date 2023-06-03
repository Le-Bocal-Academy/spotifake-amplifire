<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nickname' => 'required|string|unique:accounts|max:20',
                'firstname' => 'required|string|max:20',
                'lastname' => 'required|string|max:20',
                'email' => 'email|required|unique:accounts',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/', // Le mot de passe doit comporter au moins un caractère spécial
                ],
            ], [
                'nickname.required' => 'Le pseudo est requis',
                'nickname.unique' => 'Le pseudo n\'est pas valide',
                'nickname.max' => 'Le pseudo ne peut pas dépasser 20 caractères',
                'firstname.required' => 'Le prénom est requis',
                'firstname.max' => 'Le prénom ne peut pas dépasser 20 caractères',
                'lastname.required' => 'Le nom est requis',
                'lastname.max' => 'Le nom ne peut pas dépasser 20 caractères',
                'email.required' => 'L\'email est requis',
                'email.email' => 'L\'email n\'est pas valide',
                'email.unique' => 'L\'email n\'est pas valide',
                'password.required' => 'Le mot de passe est requis',
                'password.confirmed' => 'Les mot de passe ne correspondent pas',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
                'password.regex' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial',
            ]);


            $validatedData['password'] = Hash::make($request->password);

            Account::create($validatedData);

            return response(['message' => 'Utilisateur créé'], 201);
        } catch (ValidationException $exception) {

            return response(['errors' => $exception->errors()], 422);
        } catch (\Exception $exception) {

            Log::error($exception);
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

            unset($account->password);

            $account->token = $account->createToken('token')->plainTextToken;

            // retourne l'access token au front qui doit le stocker en "localstorage" 
            // puis utiliser ce token dans le header "Authorization" de toutes les autre requêtes
            return response(['data' => $account, 'message' => 'Utilisateur connecté'], 200);
        } catch (ValidationException $exception) {

            return response(['errors' => $exception->errors()], 422);
        } catch (Exception $exception) {

            Log::error($exception);
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            $currentToken = $user->currentAccessToken();

            $user->tokens()->where('id', $currentToken->id)->delete();

            return response(['message' => 'Utilisateur déconnecté'], 200);
        } catch (Exception $exception) {
            Log::error($exception);
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => __($status)], 200)
                : response()->json(['email' => [__($status)]], 400);
        } catch (ValidationException $exception) {

            return response(['errors' => $exception->errors()], 422);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }


    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/', // Le mot de passe doit comporter au moins un caractère spécial
                ],
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
                ? response()->json(['message' => 'Un email à été envoyé à l\'adresse ' . $request->email], 200)
                : response()->json(['erreur' => [__($status)]], 400);
        } catch (ValidationException $exception) {

            return response(['errors' => $exception->errors()], 422);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }
}
