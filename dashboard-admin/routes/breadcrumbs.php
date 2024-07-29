<?php

// Home
Breadcrumbs::for('app.dashboard', function ($trail) {
    $trail->push('Home', route('app.dashboard'));
});

// Home > Meus dados
Breadcrumbs::for('app.meus-dados', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Meus dados');
});

// Home > Configurações
Breadcrumbs::for('app.user.configuracoes', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Configurações');
});

// Home > Alterar senha
Breadcrumbs::for('app.alterar-senha', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Alterar senha');
});

// Home > Canais de venda
Breadcrumbs::for('app.canal-venda', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Canais de venda', route('app.canal-venda'));
});

// Home > Canais de venda > Criar novo canal
Breadcrumbs::for('app.canal-venda.create', function ($trail) {
    $trail->parent('app.canal-venda');
    $trail->push('Criar novo canal');
});

// Home > Canais de venda > Detalhes
Breadcrumbs::for('app.canal-venda.view', function ($trail) {
    $trail->parent('app.canal-venda');
    $trail->push('Detalhes');
});

// Home > Fornecedores
Breadcrumbs::for('app.fornecedores.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Fornecedores', route('app.fornecedores.index'));
});

// Home > Fornecedores > Novo fornecedor
Breadcrumbs::for('app.fornecedores.create', function ($trail) {
    $trail->parent('app.fornecedores.index');
    $trail->push('Novo fornecedor');
});

// Home > Fornecedores > Detalhes
Breadcrumbs::for('app.fornecedores.view', function ($trail) {
    $trail->parent('app.fornecedores.index');
    $trail->push('Detalhes');
});

// Home > Afiliados
Breadcrumbs::for('app.afiliados.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Afiliados', route('app.afiliados.index'));
});

// Home > Afiliados > Novo afiliado
Breadcrumbs::for('app.afiliados.create', function ($trail) {
    $trail->parent('app.afiliados.index');
    $trail->push('Novo afiliado', route('app.afiliados.create'));
});

// Home > Afiliados > Editar afiliado
Breadcrumbs::for('app.afiliados.edit', function ($trail, $afiliado_id) {
    $trail->parent('app.afiliados.index');
    $trail->push('Editar afiliado', route('app.afiliados.edit', $afiliado_id));
});

// Home > Afiliados Relatórios
Breadcrumbs::for('app.afiliados.relatorios.index', function ($trail) {
    $trail->parent('app.afiliados.index');
    $trail->push('Relatório afiliados', route('app.relatorios.afiliados.index'));
});

// Home > Homelist Relatórios
Breadcrumbs::for('app.relatorios.homelist.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Relatório Homelist', route('app.relatorios.homelist.index'));
});

// Home > Vendas Internas
Breadcrumbs::for('app.vendas-internas.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Vendas internas', route('app.venda-interna.index'));
});

// Home > Cupons de desconto
Breadcrumbs::for('app.descontos.cupom.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Cupons de desconto', route('app.descontos.cupom.index'));
});

// Home > Cupons de desconto > Novo Cupom
Breadcrumbs::for('app.descontos.cupom.create', function ($trail) {
    $trail->parent('app.descontos.cupom.index');
    $trail->push('Novo cupom', route('app.descontos.cupom.create'));
});

// Home > Cupons de desconto > Editar Cupom
Breadcrumbs::for('app.descontos.cupom.edit', function ($trail) {
    $trail->parent('app.descontos.cupom.index');
    $trail->push('Editar cupom', route('app.descontos.cupom.edit'));
});

// Home > Cupons de desconto > Editar Cupom
Breadcrumbs::for('app.descontos.cupom.relatorio', function ($trail) {
    $trail->parent('app.descontos.cupom.index');
    $trail->push('Relatório cupom', route('app.descontos.cupom.relatorio'));
});

// Home > Descontos
Breadcrumbs::for('app.descontos.desconto.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Descontos', route('app.descontos.desconto.index'));
});

// Home > Descontos > Novo Desconto
Breadcrumbs::for('app.descontos.desconto.create', function ($trail) {
    $trail->parent('app.descontos.desconto.index');
    $trail->push('Novo desconto', route('app.descontos.desconto.create'));
});

// Home > Descontos > Novo Desconto
Breadcrumbs::for('app.descontos.desconto.edit', function ($trail) {
    $trail->parent('app.descontos.desconto.index');
    $trail->push('Editar desconto', route('app.descontos.desconto.edit'));
});

// Home > Faturas
Breadcrumbs::for('app.faturas.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Faturas', route('app.faturas.index'));
});

// Home > Faturas > Fatura
Breadcrumbs::for('app.faturas.show', function ($trail) {
    $trail->parent('app.faturas.index');
    $trail->push('Fatura', route('app.faturas.show'));
});

// Home > Faturas > Faturas para pagamento
Breadcrumbs::for('app.faturas.pendente-pagamento', function ($trail) {
    $trail->parent('app.faturas.index');
    $trail->push('Fatura para pagamento', route('app.faturas.pendente-pagamento'));
});

// Home > Faturas > Faturas previsao
Breadcrumbs::for('app.faturas.fatura-previstao', function ($trail) {
    $trail->parent('app.faturas.index');
    $trail->push('Previsão de faturas', route('app.faturas.fatura-prevista'));
});

// Home > Faturas > Faturas previsao > Fatura
Breadcrumbs::for('app.faturas.fatura-prevista-individual', function ($trail) {
    $trail->parent('app.faturas.fatura-previstao');
    $trail->push('Previsão de fatura', route('app.faturas.fatura-prevista-individual'));
});

// Home > Servicos > Avaliações
Breadcrumbs::for('app.servicos.avaliacoes.index', function ($trail) {
    $trail->parent('app.servicos.index');
    $trail->push('Avaliações', route('app.servicos.avaliacoes.index'));
});

// Home > Servicos >  Avaliações > Nova
Breadcrumbs::for('app.servicos.avaliacoes.create', function ($trail) {
    $trail->parent('app.servicos.avaliacoes.index');
    $trail->push('Nova avaliação', route('app.servicos.avaliacoes.create'));
});

// Home > Servicos >  Avaliações > Nova
Breadcrumbs::for('app.servicos.avaliacoes.edit', function ($trail, $id) {
    $trail->parent('app.servicos.avaliacoes.index');
    $trail->push('Editar avaliação', route('app.servicos.avaliacoes.edit', $id));
});


// Home > Destinos
Breadcrumbs::for('app.destinos.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Destinos', route('app.destinos.index'));
});

// Home > Destinos > Novo Destino
Breadcrumbs::for('app.destinos.create', function ($trail) {
    $trail->parent('app.destinos.index');
    $trail->push('Novo Destino');
});

// Home > Destinos > Detalhes
Breadcrumbs::for('app.destinos.view', function ($trail) {
    $trail->parent('app.destinos.index');
    $trail->push('Detalhes');
});

// Home > Categorias
Breadcrumbs::for('app.categorias.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Categorias', route('app.categorias.index'));
});

// Home > Categorias > Nova categoria
Breadcrumbs::for('app.categorias.create', function ($trail) {
    $trail->parent('app.categorias.index');
    $trail->push('Nova categoria');
});

// Home > Categorias > Detalhes
Breadcrumbs::for('app.categorias.view', function ($trail) {
    $trail->parent('app.categorias.index');
    $trail->push('Detalhes');
});

// Home > Serviços
Breadcrumbs::for('app.servicos.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Serviços', route('app.servicos.index'));
});

// Home > Serviços > Novo Serviço
Breadcrumbs::for('app.servicos.create', function ($trail) {
    $trail->parent('app.servicos.index');
    $trail->push('Novo Serviço');
});

// Home > Serviços > Detalhes
Breadcrumbs::for('app.servicos.view', function ($trail) {
    $trail->parent('app.servicos.index');
    $trail->push('Detalhes');
});

// Home > Serviços pendentes
Breadcrumbs::for('app.servicos.pendentes.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Serviços pendentes');
});

// Home > Agenda serviços
Breadcrumbs::for('app.agenda.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Agenda serviços', route('app.agenda.index'));
});

// Home > Agenda serviços > Novo
Breadcrumbs::for('app.agenda.create', function ($trail) {
    $trail->parent('app.agenda.index');
    $trail->push('Novo');
});

// Home > Agenda serviços > Detalhes
Breadcrumbs::for('app.agenda.view', function ($trail) {
    $trail->parent('app.agenda.index');
    $trail->push('Detalhes');
});

// Home > Terminais
Breadcrumbs::for('app.terminais.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Terminais', route('app.terminais.index'));
});

// Home > Terminais > Novo
Breadcrumbs::for('app.terminais.create', function ($trail) {
    $trail->parent('app.terminais.index');
    $trail->push('Novo');
});

// Home > Terminais > Detalhes
Breadcrumbs::for('app.terminais.view', function ($trail) {
    $trail->parent('app.terminais.index');
    $trail->push('Detalhes');
});

// Home > Pedidos
Breadcrumbs::for('app.pedidos.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Pedidos', route('app.pedidos.index'));
});

// Home > Pedidos > Detalhes
Breadcrumbs::for('app.pedidos.view', function ($trail) {
    $trail->parent('app.pedidos.index');
    $trail->push('Detalhes');
});

// Home > Pedido > Detalhes reserva
Breadcrumbs::for('app.reservas.view', function ($trail, $pedido) {
    $trail->parent('app.dashboard');
    $trail->push('Pedido', route('app.pedidos.view', $pedido->codigo));
    $trail->push('Detalhes reserva');
});

// Home > Colaboradores
Breadcrumbs::for('app.colaboradores.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Colaboradores', route('app.colaboradores.index'));
});

// Home > Colaboradores > Detalhes
Breadcrumbs::for('app.colaboradores.view', function ($trail) {
    $trail->parent('app.colaboradores.index');
    $trail->push('Detalhes');
});

// Home > Clientes
Breadcrumbs::for('app.clientes.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Clientes', route('app.clientes.index'));
});

// Home > Clientes > Detalhes
Breadcrumbs::for('app.clientes.view', function ($trail) {
    $trail->parent('app.clientes.index');
    $trail->push('Detalhes');
});

// Home > Relatório terminais
Breadcrumbs::for('app.terminais.relatorios.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Relatório terminais', route('app.terminais.relatorios.index'));
});

// Home > Relatório terminais > Detalhes
Breadcrumbs::for('app.terminais.relatorios.view', function ($trail) {
    $trail->parent('app.terminais.relatorios.index');
    $trail->push('Detalhes');
});

// Home > Pagamento de comissões
Breadcrumbs::for('app.terminais.comissoes.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Pagamento de comissões', route('app.terminais.comissoes.index'));
});

// Home > Reservas autenticadas
Breadcrumbs::for('app.relatorios.autenticados.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Reservas autenticadas');
});

// Home > Ingressos vendidos
Breadcrumbs::for('app.relatorios.vendidos.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Ingressos vendidos');
});

// Home > Relatório fornecedores
Breadcrumbs::for('app.relatorios.fornecedores.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Relatório fornecedores');
});

// Home > Banners
Breadcrumbs::for('app.banners.index', function ($trail, $destino_id = null) {
    $trail->parent('app.dashboard');
    if(is_null($destino_id)) {
        $trail->push('Banners', route('app.banners.index'));
    } else {
        $trail->push('Banners', route('app.banners.index', $destino_id));
    }
});

// Home > Banners > Novo
Breadcrumbs::for('app.banners.create', function ($trail, $destino_id = null) {
    $trail->parent('app.banners.index', $destino_id);
    $trail->push('Novo');
});

// Home > Banners > Detalhes
Breadcrumbs::for('app.banners.view', function ($trail, $destino_id = null) {
    $trail->parent('app.banners.index', $destino_id);
    $trail->push('Detalhes');
});

// Home > Newsletters
Breadcrumbs::for('app.newsletter.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Newsletters', route('app.newsletter.index'));
});
