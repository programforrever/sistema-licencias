<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSignatureController extends Controller
{
    /**
     * Muestra el formulario para gestionar firmas de usuarios
     */
    public function index()
    {
        $this->authorize('manageSignatures', auth()->user());
        
        $users = User::with('signature')->get();
        
        return view('admin.signatures.index', compact('users'));
    }

    /**
     * Muestra el formulario para subir firma de un usuario
     */
    public function edit(User $user)
    {
        $this->authorize('manageSignatures', auth()->user());
        
        $signature = $user->signature;
        $firmaUrl = $signature ? Storage::url($signature->firma_path) : null;
        
        return view('admin.signatures.edit', compact('user', 'signature', 'firmaUrl'));
    }

    /**
     * Guarda la firma de un usuario
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('manageSignatures', auth()->user());

        $validated = $request->validate([
            'firma' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB
        ], [
            'firma.required' => 'Debes seleccionar una imagen de firma',
            'firma.image' => 'El archivo debe ser una imagen válida',
            'firma.mimes' => 'La firma debe ser JPG o PNG',
            'firma.max' => 'La firma no debe exceder 5MB',
        ]);

        try {
            // Crear directorio si no existe
            $dir = "signatures/{$user->id}";
            Storage::disk('public')->makeDirectory($dir, 0755, true);

            // Eliminar firma anterior si existe
            $existingSignature = UserSignature::where('user_id', $user->id)->first();
            if ($existingSignature && Storage::disk('public')->exists($existingSignature->firma_path)) {
                Storage::disk('public')->delete($existingSignature->firma_path);
            }

            // Guardar nueva firma
            $filename = 'firma.png'; // Nombre consistente
            $path = $request->file('firma')->storeAs($dir, $filename, 'public');

            // Actualizar o crear registro en user_signatures
            UserSignature::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'firma_path' => $path,
                    'uploaded_at' => now(),
                ]
            );

            return redirect()->back()->with('success', "Firma de {$user->name} guardada exitosamente");

        } catch (\Exception $e) {
            \Log::error('Error al guardar firma', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Error al guardar la firma: ' . $e->getMessage());
        }
    }

    /**
     * Elimina la firma de un usuario
     */
    public function destroy(User $user)
    {
        $this->authorize('manageSignatures', auth()->user());

        try {
            $signature = UserSignature::where('user_id', $user->id)->first();
            
            if ($signature) {
                if (Storage::exists($signature->firma_path)) {
                    Storage::delete($signature->firma_path);
                }
                $signature->delete();
            }

            return redirect()->back()->with('success', "Firma de {$user->name} eliminada");

        } catch (\Exception $e) {
            \Log::error('Error al eliminar firma', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Error al eliminar la firma');
        }
    }
}
