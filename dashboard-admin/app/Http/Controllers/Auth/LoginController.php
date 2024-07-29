<?php namespace App\Http\Controllers\Auth;

use App\Enum\ConfiguracoesEnum;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use TourFacil\Core\Services\CanalVendaService;

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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/painel/dashboard';

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
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Guarda o canal de venda default do usuario
        UserService::configureCanalUserDefault();

        // Recupera a rota de redirecionamento da configuracao do usuario
        $route = ConfiguracoesEnum::getPageDefault($user);

        return redirect()->intended($route);
    }

    /**
     * The user has logged out of the application.
     * @return array
     */
    protected function loggedOut()
    {
        return ['logout' => true, 'url' => route('login')];
    }
}
