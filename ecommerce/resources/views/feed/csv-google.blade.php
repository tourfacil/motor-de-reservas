id,title,description,availability,condition,price,link,image_link,brand
@foreach ($servicos as $servico)
{{ $servico->uuid }},"{!! str_replace(",", "", str_replace("\r\n", "", $servico->nome)) !!}","{!! str_replace(",", "", str_replace("\r\n", "", str_replace('"', "'", $servico->descricao_curta))) !!}",in stock,new,{{ $servico->valor_venda }} BRL,{{ route('ecommerce.servico.view', [$servico->destino->slug, $servico->categoria->slug, $servico->slug]) }},{{ $servico->fotoPrincipal->foto_large }},{{ $servico->categoria->nome }}
@endforeach
