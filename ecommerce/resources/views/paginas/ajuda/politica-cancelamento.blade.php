@extends('template.master')

@section('title', "Política de cancelamento - Tour Fácil")

@section('body')

    @include('template.navbar-clear')

    <div class="bg-light py-4">
        <main class="container">
            <h1 class="font-weight-medium text-blue mb-4">Política de Cancelamento</h1>
            <p>
                1.0 - Informa que, o adquirente terá o prazo de 07 (sete) dias, a contar da aprovação da compra,
                para efetuar o cancelamento do serviço contratado sem qualquer retenção de valores por parte da contratada,
                conforme preceitua o artigo 49 do Código de Defesa do Consumidor.
            </p>
            <p>
                <strong>1.1 - O cancelamento não será cabível nos casos:</strong>
            </p>
            <p>
                a) Em que o serviço já houver sido usufruído pelo contratante;<br>
                b) Quando a data entre a compra até a data da realização dos serviços for menor que sete dias, fica ciente o cliente da disponibilização do seu direito;<br>
                c) Quando findar o prazo para uso dos serviços, sem que tenha exercido o direito em utilizá-los;<br>
                d) Quando não forem atendidos qualquer dos requisitos aqui expostos para efetuar o cancelamento da contratação;<br>
                e) Quando o contratante houver usufruído parcialmente dos serviços contratados.
            </p>
            <p>
                2.0 - Em se tratando de prazo superior a sete dias, haverá retenção de valores, conforme discriminado abaixo:
            </p>
            <p>
                3.0 - Para fins das cláusulas que seguem, considerar-se-á os horários de Brasília, tendo como marco final do dia o horário
                das 23:59:59 (onze horas cinquenta e nove minutos e cinquenta e nove segundos) e inicial a partir das 00:00:00 (meia noite em ponto):
            </p>
            <p>
                3.1 - A contar do 8° (oitavo) dia até o 14° (décimo quarto) dia, haverá retenção de 25% do valor total dos serviços contratados;
            </p>
            <p>3.2 - A contar do 15° (décimo quinto) dia ao 21° (vigésimo primeiro) dia, haverá retenção de 50% do valor pago;</p>
            <p>
                3.3 - E, a partir do 22° (vigésimo segundo) dia será retido 100% do valor dos serviços adquiridos, sem direito a qualquer
                restituição de valores ao contratante, servindo o pagamento, ainda que inicial, do contratado, o aceite destas cláusulas
                específicas de represamento de valores.
            </p>
            <p>
                4.0 - Para a validação do cancelamento dos serviços contratados, a solicitação deve ser feita única e exclusivamente
                por escrito e endereçada ao e-mail <a href="mailto:atendimento@expandaviagens.com.br" target="_blank" title="Email contato">atendimento@expandaviagens.com.br</a>,
                informando claramente seu requerimento. Não sendo válido qualquer aviso ou cancelamento verbal feita pelos canais de contato fonéticos
                (central de atendimento telefônico), através do WhatsApp ou qualquer rede social da empresa.
            </p>
            <p>
                4.1 - Na solicitação, deverá conter todos os dados do contratante, bem como informações específicas acerca dos serviços contratados, e solicitação cristalina
                sobre a pretensão de cancelamento daquele serviço, bem como seja enviado todas as solicitações necessárias para a aprovação do cancelamento que os atendentes
                da contratada requeiram.
            </p>
            <p>
                4.2 - Após o envio da solicitação de cancelamento, o atendente da empresa irá avaliá-lo e retornar ao requerente através de contato eletrônico (e-mail),
                no prazo de 7 (sete) dias úteis.
            </p>
            <p>4.3 - A devolução/estorno, será efetuada em até 15 (quinze) dias úteis a contar da aprovação do requerimento de cancelamento.</p>
            <p>
                5.0 - Ainda que, após contratada a prestação e serviços tida como principal, caso sobrevenha nova contratação, cada uma respeitará,
                como marco inicial, a data do fechamento do contrato, não podendo se valer para fins destas cláusulas de novação.
            </p>
        </main>
    </div>

    @include('partials.newsletter')

    @include('template.footer')

@endsection

@section('seo')
    <meta name="description" content="Veja a política de cancelamento dos seus ingressos comprados." />
    <meta property="og:description" content="Veja a política de cancelamento dos seus ingressos comprados." />
    <meta name="twitter:description" content="Veja a política de cancelamento dos seus ingressos comprados." />
@endsection
