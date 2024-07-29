<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedirectController extends Controller
{
    public function redirect1() 
    {
        return redirect('/jericoacoara/atracoes/tour-litoral-leste-de-jericoacoara', 301);
    }

    public function redirect2()
    {
        return redirect('/jericoacoara/atracoes/tour-litoral-oeste-de-jericoacoara', 301);
    }

    public function redirect3()
    {
        return redirect('/jericoacoara/atracoes/transfer-aeroporto-de-cruz-x-vila-de-jericoacoara', 301);
    }

    public function redirect4()
    {
        return redirect('/jericoacoara/atracoes/transfer-ida-fortaleza-x-jericoacoara', 301);
    }

    public function redirect5()
    {
        return redirect('/jericoacoara/atracoes/transfer-ida-volta-fortaleza-x-jericoacoara', 301);
    }

    public function redirect6()
    {
        return redirect('/jericoacoara/atracoes/transfer-vila-de-jericoacoara-x-aeroporto-de-cruz', 301);
    }

    public function redirect7()
    {
        return redirect('/jericoacoara/atracoes/transfer-volta-jericoacoara-x-fortaleza', 301);
    }

    public function redirect8()
    {
        return redirect('/gramado-e-canela/ingressos/passaporte-8-atracoes-grupo-dreams', 301);
    }

    public function acheigramadoRedirect()
    {
        return redirect()->away('https://www.tourfacil.com.br/gramado-e-canela/?aid=167', 301);
    }

    public function promoRedirect()
    {
        return redirect()->away('https://www.tourfacil.com.br/gramado-e-canela/?aid=108', 301);
    }
}
