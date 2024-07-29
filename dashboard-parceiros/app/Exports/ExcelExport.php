<?php namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * Class ExcelExport
 * @package App\Exports
 */
class ExcelExport implements FromView, ShouldAutoSize
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
     * ExcelExport constructor.
     *
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
