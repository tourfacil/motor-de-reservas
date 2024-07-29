<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\Vendedor;
use TourFacil\Core\Services\VendedorService;

class VendedorController extends Controller
{
    /**
     * Método responsavel por atribuir um afiliado a uma reserva de forma manual
     * Não possui muitas validações pois é um método simples e que só é usado no ADMIN pelo operacional
     * @param  mixed $request
     * @return void
     */
    public function atribuirVendedorReserva(Request $request) {

        if(userIsAdmin() == false) {
            return response(['status' => false, 'motivo' => 'Permissão insuficiente'], 200);
        }

        // Busca a reserva no banco de dados
        $reserva = ReservaPedido::find($request->get('reserva_id'));

        // Pega o afiliado ID da req
        $vendedor_id = $request->get('vendedor_id');

        if($vendedor_id != "0") {
            // Caso o vendedor_id seja válido diferente de zero. Ele seta o devido afiliado a reserva
            $reserva->update(['vendedor_id' => $vendedor_id]);

        } else {
            // Caso o vendedor_id seja 0. Ele seta o valor como NULLO para deixar a reserva sem afiliado
            $reserva->update(['vendedor_id' => null]);

        }

        // Retorna um STATUS DE OK
        return response(['status' => true], 200);
    }

    public function relatorioVendedor(Request $request)
    {   
         // Recupera todos os vendedores
         $vendedores_lista = Vendedor::all();

         // Recupera informações da URL
         $vendedor_id = $request->get('vendedor_id');
         $data_inicio = $request->get('inicio');
         $data_final = $request->get('final');
 
         // Caso não sejam informados parametros, será enviada uma página vazia
         if($vendedor_id == null || $data_inicio == null || $data_final == null) {
 
             $dados = [
                 'vendedor' => null,
                 'data_inicio' => null,
                 'data_final' => null,
                 'reservas' => [],
                 'vendedores_lista' => $vendedores_lista,
                 'tipo_operacao' => null,
                 'total_comissionado' => 0,
                 'total_vendido' => 0,
                 'quantidade_reservas' => 0,
             ];
 
             return view('paginas.vendedores.relatorio', $dados);
         }
 
         // Caso o usuário for vendedor ou vendedor e tentar ver o relatório de outra pessoa. Ele será redirecionado
         if(userIsVendedor() || userIsVendedor()) {
 
             if(auth()->user()->vendedor_id != $request->get('vendedor_id')) {
                 return redirect()->route('app.relatorios.vendedores.index');
             }
         }
 
         // Service faz os calculos do relatório
         $dados = VendedorService::relatorioVendedor($request);
         $dados['vendedores_lista'] = $vendedores_lista;
 
         return view('paginas.vendedores.relatorio', $dados);
    }

    public function relatorioVendedores(Request $request) {

        // Service faz todos os calculos necessários
        $dados = VendedorService::relatorioVendedores($request);

        return view('paginas.vendedores.relatorio-vendedores', $dados);
    }
}
