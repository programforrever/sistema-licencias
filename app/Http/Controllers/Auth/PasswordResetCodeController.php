<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetCodeController extends Controller
{
    /**
     * Mostrar formulario para solicitar código de recuperación
     */
    public function requestCode(): View
    {
        return view('auth.forgot-password-code');
    }

    /**
     * Enviar código de recuperación al correo
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe ser válido.',
            'email.exists' => 'No encontramos una cuenta con ese correo electrónico.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No encontramos una cuenta con ese correo electrónico.',
            ]);
        }

        // Generar código de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Guardar código en base de datos (con timestamp manualmente)
        $user->passwordResetCodes()->create([
            'email' => $user->email,
            'code' => $code,
            'created_at' => now(),
        ]);

        // Enviar email
        Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user->name));

        // Redirigir a la página de verificación con el email guardado en sesión
        return redirect()->route('password.verify-code')
            ->with('status', 'Código enviado a tu correo electrónico. Ingrésalo en el campo de abajo.')
            ->with('email', $request->email);
    }

    /**
     * Mostrar formulario para ingresar código y nueva contraseña
     */
    public function verifyCode(): View
    {
        return view('auth.verify-reset-code');
    }

    /**
     * Validar código (sin cambiar contraseña)
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'numeric', 'digits:6'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe ser válido.',
            'email.exists' => 'No encontramos una cuenta con ese correo.',
            'code.required' => 'El código es obligatorio.',
            'code.numeric' => 'El código debe ser numérico.',
            'code.digits' => 'El código debe tener 6 dígitos.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No encontramos una cuenta con ese correo.'], 400);
        }

        // Buscar código válido (creado en los últimos 15 minutos)
        $resetCode = $user->passwordResetCodes()
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$resetCode) {
            return response()->json(['success' => false, 'message' => 'El código es inválido o ha expirado. Por favor, solicita uno nuevo.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Código válido. Ahora ingresa tu nueva contraseña.']);
    }

    /**
     * Actualizar contraseña (después de validar código)
     */
    public function updatePasswordAfterCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'numeric', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe ser válido.',
            'email.exists' => 'No encontramos una cuenta con ese correo.',
            'code.required' => 'El código es obligatorio.',
            'code.numeric' => 'El código debe ser numérico.',
            'code.digits' => 'El código debe tener 6 dígitos.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No encontramos una cuenta con ese correo.',
            ]);
        }

        // Buscar código válido (creado en los últimos 15 minutos)
        $resetCode = $user->passwordResetCodes()
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$resetCode) {
            throw ValidationException::withMessages([
                'code' => 'El código es inválido o ha expirado. Por favor, solicita uno nuevo.',
            ]);
        }

        // Actualizar contraseña
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        // Eliminar códigos usados
        $user->passwordResetCodes()->delete();

        return redirect()->route('login')->with('status', '¡Contraseña actualizada exitosamente! Por favor, inicia sesión con tu nueva contraseña.');
    }
}
