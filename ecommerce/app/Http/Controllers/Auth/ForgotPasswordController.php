<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use TourFacil\Core\Services\Cache\DefaultCacheService;

/**
 * Class ForgotPasswordController
 * @package App\Http\Controllers\Auth
 */
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Get the needed authentication credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    protected function credentials(Request $request)
    {
        // Recupera as credenciais default do Laravel
        $credentials = $request->only('email');

        // Adiciona o canal de venda como filtro
        $credentials['canal_venda_id'] = DefaultCacheService::getCanalVenda();

        return $credentials;
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param Request $request
     * @param $response
     * @return array
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $this->autoResponseJson(true, "Verifique seu e-mail", trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param $response
     * @return array
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->autoResponseJson(false, "Não foi possível solicitar sua senha", trans($response));
    }
}
