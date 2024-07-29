<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use TourFacil\Core\Services\Cache\DefaultCacheService;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('clientes');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        return view('auth.login', compact(
            'url_logo'
        ));
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    protected function credentials(Request $request)
    {
        // Recupera as credenciais default do Laravel
        $credentials = $request->only($this->username(), 'password');

        // Adiciona o canal de venda como filtro
        $credentials['canal_venda_id'] = DefaultCacheService::getCanalVenda();

        return $credentials;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Verifica se o cliente possui itens no carrinho
        $route = (carrinho()->check())
            ? route('ecommerce.carrinho.pagamento')
            : route("ecommerce.cliente.pedidos.index");

        return $this->autoResponseJson(true, "Login realizado", "Login realizado com sucesso!", $route);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $carrinho = carrinho()->all();

        $this->guard()->logout();

        $request->session()->invalidate();

        carrinho()->put($carrinho->toArray());

        return $this->loggedOut($request);
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return ['logout' => true];
    }
}
