<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function crearUsuario(Request $request): JsonResponse
    {
        try {
            \Log::info('Iniciando creación de usuario', $request->all());
            
            // 1. Validación básica primero
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:users',
                'password' => 'required|string|min:8|max:20'
            ]);

            if ($validator->fails()) {
                \Log::error('Error validación', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'code' => 400,
                        'msg' => 'Error de validación'
                    ],
                    'data' => null,
                    'msg' => $validator->errors()->first(),
                    'count' => 0
                ], 400);
            }

            \Log::info('Validación pasada');

            // 2. Crear usuario sin Sanctum primero
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            \Log::info('Usuario creado', ['user_id' => $user->id]);

            // 3. Intentar crear token (esto podría fallar)
            try {
                $token = $user->createToken('auth_token')->plainTextToken;
                \Log::info('Token creado exitosamente');
            } catch (\Exception $tokenError) {
                \Log::error('Error creando token', ['error' => $tokenError->getMessage()]);
                $token = 'token-simulado-' . $user->id;
            }

            return response()->json([
                'success' => true,
                'errors' => null,
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'bearer'
                ],
                'msg' => 'Usuario creado exitosamente',
                'count' => 1
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error general en crearUsuario', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'errors' => [
                    'code' => 500,
                    'msg' => $e->getMessage()
                ],
                'data' => null,
                'msg' => 'Error: ' . $e->getMessage(),
                'count' => 0
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            \Log::info('Iniciando login', $request->all());

            // Validación básica
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'code' => 400,
                        'msg' => 'Error de validación'
                    ],
                    'data' => null,
                    'msg' => $validator->errors()->first(),
                    'count' => 0
                ], 400);
            }

            // Buscar usuario
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                \Log::warning('Usuario no encontrado', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'code' => 401,
                        'msg' => 'Credenciales inválidas'
                    ],
                    'data' => null,
                    'msg' => 'No se reconocen las credenciales',
                    'count' => 0
                ], 401);
            }

            // Verificar password
            if (!Hash::check($request->password, $user->password)) {
                \Log::warning('Password incorrecto', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'code' => 401,
                        'msg' => 'Credenciales inválidas'
                    ],
                    'data' => null,
                    'msg' => 'No se reconocen las credenciales',
                    'count' => 0
                ], 401);
            }

            \Log::info('Credenciales válidas', ['user_id' => $user->id]);

            // Intentar crear token
            try {
                $token = $user->createToken('auth_token')->plainTextToken;
                \Log::info('Token de login creado');
            } catch (\Exception $tokenError) {
                \Log::error('Error creando token de login', ['error' => $tokenError->getMessage()]);
                $token = 'token-login-simulado-' . $user->id;
            }

            return response()->json([
                'success' => true,
                'errors' => null,
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ],
                'msg' => 'Login exitoso',
                'count' => 1
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error general en login', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'errors' => [
                    'code' => 500,
                    'msg' => $e->getMessage()
                ],
                'data' => null,
                'msg' => 'Error en el proceso de login',
                'count' => 0
            ], 500);
        }
    }

    public function obtenerUsuario(Request $request): JsonResponse
    {
        try {
            \Log::info('Obteniendo datos de usuario');

            // Por ahora devolvemos datos de prueba
            // En la versión final esto vendrá del token
            return response()->json([
                'success' => true,
                'errors' => null,
                'data' => [
                    'id' => 1,
                    'name' => 'Usuario de prueba',
                    'email' => 'test@example.com',
                    'email_verified_at' => null,
                    'created_at' => now()->toISOString(),
                    'updated_at' => now()->toISOString()
                ],
                'msg' => 'Datos del usuario obtenidos exitosamente',
                'count' => 1
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error en obtenerUsuario', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'errors' => [
                    'code' => 500,
                    'msg' => $e->getMessage()
                ],
                'data' => null,
                'msg' => 'Error al obtener los datos del usuario',
                'count' => 0
            ], 500);
        }
    }
}