ID,Item title,Item description,Price,Final URL,Image URL,Item category
@foreach ($servicos as $servico)
{{ $servico->uuid }},"{!! str_replace(",", "", str_replace("\r\n", "", $servico->nome)) !!}","{!! str_replace(",", "", str_replace("\r\n", "", str_replace('"', "'", $servico->descricao_curta))) !!}",{{ $servico->valor_venda }} BRL,{{ route('ecommerce.servico.view', [$servico->destino->slug, $servico->categoria->slug, $servico->slug]) }},{{ $servico->fotoPrincipal->foto_large }},{{ $servico->categoria->nome }}
@endforeach
