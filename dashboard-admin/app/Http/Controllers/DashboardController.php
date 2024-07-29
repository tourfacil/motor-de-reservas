<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $dados = DashboardService::getDadosDashBoard();

        return view('paginas.dashboard', $dados);
    }
}
