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
                // Messages d'erreur personnalisés pour chaque règle de validation
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
                'password.confirmed' => 'Les mots de passe ne correspondent pas',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
                'password.regex' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial',
            ]);


            $validatedData['password'] = Hash::make($request->password);

            // Création du compte utilisateur en utilisant les données validées
            Account::create($validatedData);

            $account = Account::where('email', $validatedData['email'])->first();

            // Envoyer un mail de confirmation
            $account->sendMailAdressConfirmationNotification($account->id);

            return response(['message' => 'Compte créé, vous allez recevoir un mail pour activer votre compte.'], 201);
        } catch (ValidationException $exception) {

            return response(['erreur' => $exception->errors()], 422);
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
                // Vérification si le compte utilisateur n'existe pas ou si le mot de passe fourni ne correspond pas au mot de passe hashé stocké dans la base de données
                return response(['erreur' => 'Email ou mot de passe incorrect'], 400);
            }

            unset($account->password);

            if (!$account->email_verified_at) {
                // Vérification si l'e-mail de l'utilisateur n'a pas encore été vérifié et si le compte n'est pas activé
                return response(['erreur' => 'Votre compte n\'est pas encore activé. Veuillez vérifier vos mails.'], 400);
            }

            $account->token = $account->createToken('token')->plainTextToken;

            // retourne l'access token au front qui doit le stocker en "localstorage" 
            // puis utiliser ce token dans le header "Authorization" de toutes les autre requêtes
            return response(['data' => $account, 'message' => 'Utilisateur connecté'], 200);
        } catch (ValidationException $exception) {

            return response(['erreur' => $exception->errors()], 422);
        } catch (Exception $exception) {

            Log::error($exception);
            return response(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            // Récupération du jeton d'accès actuel de l'utilisateur
            $currentToken = $user->currentAccessToken();

            // Suppression du jeton d'accès de l'utilisateur
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

            // Envoi du lien de réinitialisation du mot de passe à l'adresse email fournie
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Vérification du statut de l'envoi du lien de réinitialisation
            if ($status === Password::RESET_LINK_SENT) {
                // Si le lien de réinitialisation du mot de passe a été envoyé avec succès
                return response()->json(['status' => 'Un email a été envoyé'], 200);
            } else {
                // Si une erreur s'est produite lors de l'envoi du lien de réinitialisation
                return response()->json(['email' => [__($status)]], 400);
            }
        } catch (ValidationException $exception) {

            return response(['erreur' => $exception->errors()], 422);
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

            // Réinitialisation du mot de passe en utilisant la méthode `reset` de la classe `Password`
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {

                    // Mise à jour du mot de passe de l'utilisateur avec le nouveau mot de passe hashé
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ]);

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                // Si le mot de passe a été réinitialisé avec succès
                return response()->json(['message' => 'Le mot de passe a été modifié pour l\'email ' . $request->email], 200);
            } else {
                // Si une erreur s'est produite lors de la réinitialisation du mot de passe
                return response()->json(['erreur' => [__($status)]], 400);
            }
        } catch (ValidationException $exception) {

            return response(['erreur' => $exception->errors()], 422);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }

    public function confirmEmail($id)
    {
        try {
            $account = Account::find($id);

            if (!$account) {
                return response(['erreur' => 'l\'tilisateur n\'existe pas'], 404);
            }

            if ($account->email_verified_at) {
                return response(['erreur' => 'Votre compte est déjà activé'], 400);
            }

            // Marquage de la date de vérification de l'email à l'heure actuelle
            $account->email_verified_at = now();
            $account->save();

            // Redirection vers la page de connexion
            return redirect()->to(config('app.url') . ":8080/login");
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }


    public function resendEmailConfirmation($id)
    {
        try {
            $account = Account::find($id);

            if (!$account) {
                return response(['erreur' => 'l\'tilisateur n\'existe pas'], 404);
            }

            if ($account->email_verified_at) {
                return response(['erreur' => 'Votre compte est déjà activé'], 400);
            }

            // Renvoi d'un mail de confirmation
            $account->sendMailAdressConfirmationNotification($account->id);

            return response()->json(['message' => 'Un email a été envoyé à l\'adresse ' . $account->email], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['erreur' => 'Une erreur s\'est produite'], 500);
        }
    }
}
