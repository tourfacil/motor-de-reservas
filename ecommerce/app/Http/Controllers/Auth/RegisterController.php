<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;
use TourFacil\Core\Models\Cliente;
use TourFacil\Core\Services\Cache\DefaultCacheService;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     * @throws \Exception
     */
    protected function validator(array $data)
    {
        // Canal de venda atual
        $canal_venda_id = DefaultCacheService::getCanalVenda();

        return Validator::make($data, [
            'nome' => ['required', 'string', 'max:255', 'regex:/^[^<]*$/'],
            'password' => ['required', 'string', 'min:3', 'regex:/^[^<]*$/'],
            'email' => [
                'required', 'string', 'email', 'max:255', 'regex:/^[^<]*$/',
                Rule::unique('clientes')->where(function ($query) use ($canal_venda_id) {
                    return $query->where('canal_venda_id', $canal_venda_id);
                })
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    protected function create(array $data)
    {
        return Cliente::create([
            'nome' => formatarNome($data['nome']),
            'email' => mb_strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'canal_venda_id' => DefaultCacheService::getCanalVenda()
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $route = (carrinho()->check())
            ? route('ecommerce.carrinho.pagamento')
            : route("ecommerce.cliente.pedidos.index");

        return $this->autoResponseJson(true, "Cadastro realizado", "Seu cadastro foi realizado com sucesso!", $route);
    }
}
