@extends('template.master')

@section('title', "Política de privacidade - Tour Fácil")

@section('body')

    @include('template.navbar-clear')

    <div class="bg-light py-4">
        <main class="container">
            <h1 class="font-weight-medium text-blue mb-4">Política de Privacidade</h1>
            <p>
                Ao se cadastrar no TourFácil para realizar uma compra ou para receber e-mails diários com ofertas,
                armazenamos o seu endereço de e-mail, endereço IP e os demais dados que foram informados ao site em
                nosso banco de dados protegido e sigilosos, no qual apenas alguns funcionários habilitados, que são
                obrigados, por contrato, a manter confidencialidade de suas informações.
            </p>
            <p>
                Quando você se cadastra, solicitamos informações tais como seu nome, endereço de e-mail, data de nascimento,
                sexo, CEP, assim como assuntos de interesse pessoal, dentre outras. Ao se cadastrar no TourFácil e utilizar
                nossos serviços, você deixa de ser anônimo para nós.
            </p>
            <p>
                Utilizamos os seus dados para o envio de nossas ofertas diárias e e-mails promocionais. Você poderá a qualquer
                momento solicitar a retirada de seu endereço de e-mail de nossas listas de envio, solicitar a retirada a qualquer
                momento através do link disponível em nossas mensagens de e-mail.
            </p>
            <p>
                O TourFácil não aluga, não vende e não compartilha as informações pessoais com outras pessoas ou com empresas não afiliadas,
                exceto com objetivo de fornecer a você os produtos e serviços solicitados, tendo obtido para tanto a sua permissão, ou em
                circunstâncias específicas como ordem judicial ou advindas de lei.
            </p>
            <p>
                Ao se logar em nosso site, armazenaremos um cookie em seu computador com a única finalidade de identificar a sua conta TourFácil.
                Ao deslogar, este cookie será apagado. Armazenamos também a informação de sua cidade de escolha, para que nas próximas vezes que
                você acessar o site sempre apareça a cidade escolhida.
            </p>
            <p>
                O TourFácil poderá eventualmente atualizar esta política sem aviso prévio. As mudanças mais significativas, quando oportuno,
                poderão ser comunicadas em área de destaque no website ou através de e-mail
            </p>
            <p>
                Para obter mais informações entre em contato através do nosso e-mail:
                <a href="mailto:atendimento@tourfacil.com.br" target="_blank" title="Email contato">atendimento@tourfacil.com.br</a>
            </p>
        </main>
    </div>

    @include('partials.newsletter')

    @include('template.footer')

@endsection

@section('seo')
    <meta name="description" content="Veja a política de privacidade e uso do nosso site." />
    <meta property="og:description" content="Veja a política de privacidade e uso do nosso site." />
    <meta name="twitter:description" content="Veja a política de privacidade e uso do nosso site." />
@endsection
