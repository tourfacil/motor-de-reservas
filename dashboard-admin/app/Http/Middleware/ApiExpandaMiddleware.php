<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiExpandaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Busca os header e coloca em um array para validação
        $headers = [
            'user' => $request->header('user'),
            'password' => $request->header('password'),
            'token' => $request->header('token')
        ];

        // Faz a validação dos headers
        $validator = Validator::make($headers, [
            'user' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'token' => 'required|string|max:255',
        ]);

        // Caso a req tenha problemas, retorna um 400
        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        // Busca os dados da req para buscar o usuario
        $email = $request->header('user');
        $password = $request->header('password');
        $token = $request->header('token');

        // Busca o usuario
        $user = User::where('email', $email)
            ->where('token_api', $token)
            ->get()
            ->first();

        // Flag para guardar se a req esta negada
        $acesso_negado = false;

        // Caso não retornar usuário, seta o acesso negado
        if ($user == null)
            $acesso_negado = true;

        // Caso não tenha acesso negado, verifica se a senha informada é a correta
        if(!$acesso_negado) {
            if (!Hash::check($password, $user->password))
                $acesso_negado = true;
        }

        // Caso o acesso negado esteja true, retorna "ACESSO NEGADO - 401"
        if($acesso_negado) {
            return response([
                'info' => 'Acesso negado',
                'status' => false,
            ], 401);
        }

        // Aplica o usuário na req
        $request->merge(['user' =>$user]);

        // Retorna para a aplicação
        return $next($request);
    }
}
