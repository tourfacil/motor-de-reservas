<?php

// Home
Breadcrumbs::for('app.dashboard', function ($trail) {
    $trail->push('Home', route('app.dashboard'));
});

// Home > Meus dados
Breadcrumbs::for('app.meus-dados', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Meus dados', route('app.meus-dados.index'));
});

// Home > Meus dados > Alterar senha
Breadcrumbs::for('app.meus-dados.senha.index', function ($trail) {
    $trail->parent('app.meus-dados');
    $trail->push('Alterar senha');
});

// Home > Serviços
Breadcrumbs::for('app.servicos.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Serviços');
});

// Home > Reservas
Breadcrumbs::for('app.reservas.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Reservas', route('app.reservas.index'));
});

// Home > Reservas > Detalhes
Breadcrumbs::for('app.reservas.view', function ($trail) {
    $trail->parent('app.reservas.index');
    $trail->push('Detalhes');
});

// Home > Validador
Breadcrumbs::for('app.validor.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Validador');
});

// Home > Relatório de autenticados
Breadcrumbs::for('app.relatorios.autenticados', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Relatório de autenticados');
});

// Home > Relatório de vendidos
Breadcrumbs::for('app.relatorios.vendidos', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Relatório de vendidos');
});

// Home > Agenda
Breadcrumbs::for('app.agenda.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Agenda');
});

// Home > Agenda
Breadcrumbs::for('app.relatorios.homelist.index', function ($trail) {
    $trail->parent('app.dashboard');
    $trail->push('Homelist');
});

// Home > Agenda > Detalhes Agenda
Breadcrumbs::for('app.agenda.view', function ($trail) {
    $trail->parent('app.agenda.index');
    $trail->push('Detalhes Agenda');
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

