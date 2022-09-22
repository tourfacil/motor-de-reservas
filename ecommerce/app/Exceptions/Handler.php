<?php namespace App\Exceptions;

use App\Services\RedirectService;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use TourFacil\Core\Enum\CategoriasEnum;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Models\Servico;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Redirecionamentos
        $redirect = $this->getRedirecionamentos($request);

        // Caso a URL tenha redirecionamento
        if($redirect['redirect']) {
            return redirect()->to($redirect['path'], $redirect['code']);
        }

        return parent::render($request, $exception);
    }

    /**
     * Redirecionamentos dos servicos antigos
     *
     * @param $request
     * @return array
     */
    private function getRedirecionamentos($request)
    {
        return RedirectService::getRedirecionamentos($request);
    }
}
