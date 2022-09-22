<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationData;
use Password;
use TourFacil\Core\Services\Cache\DefaultCacheService;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

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
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param Request $request
     * @param null $token
     * @param $email
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null, $email = null)
    {
        $email = $request->get('email');

        // Valida o conteudo na URL
        if((is_mail($email) == false) || is_null($token)) {
            return redirect()->route('ecommerce.index');
        }

        return view('auth.reset-password')->with([
            'token' => $token, 'email' => $email
        ]);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    protected function credentials(Request $request)
    {
        // Recupera as credenciais default do Laravel
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        // Adiciona o canal de venda como filtro
        $credentials['canal_venda_id'] = DefaultCacheService::getCanalVenda();

        return $credentials;
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param Request $request
     * @param $response
     * @return array
     */
    protected function sendResetResponse(Request $request, $response)
    {
        // Verifica se o cliente possui itens no carrinho
        $route = (carrinho()->check())
            ? route('ecommerce.carrinho.pagamento')
            : route("ecommerce.cliente.pedidos.index");

        return $this->autoResponseJson(true, "Senha alterada", trans($response), $route);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param Request $request
     * @param $response
     * @return array
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->autoResponseJson(false, "Falha ao alterar a senha", trans($response));
    }
}
