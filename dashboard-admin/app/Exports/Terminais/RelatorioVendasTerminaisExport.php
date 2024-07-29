<?php namespace App\Exports\Terminais;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * Class RelatorioVendasTerminaisExport
 * @package App\Exports\Terminais
 */
class RelatorioVendasTerminaisExport implements FromView, ShouldAutoSize
{
    use Exportable;

    /**
     * @var view name
     */
    private $view_name;

    /**
     * @var array
     */
    private $variables;

    /**
     * RelatorioVendasTerminaisExport constructor.
     * @param $view_name
     * @param $variables
     */
    public function __construct($view_name, $variables)
    {
        $this->view_name = $view_name;
        $this->variables = $variables;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view($this->view_name, $this->variables);
    }
}
