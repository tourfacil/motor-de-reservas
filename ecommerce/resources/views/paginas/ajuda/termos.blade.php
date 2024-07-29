@extends('template.master')

@section('title', 'Termos e Condições - Tour Fácil')

@section('body')

    @include('template.navbar-clear')

    <div class="bg-light py-4">
        <main class="container">
            <h1 class="font-weight-medium text-blue mb-4">Termos e Condições</h1>
            <p>
                Este Termo de Uso apresenta as “Condições Gerais” aplicáveis ao uso dos serviços oferecidos por TOURFACIL,
                site pertencente à TOURFACIL VENDA DE INGRESSOS ON LINE LTDA, inscrita no CNPJ sob n.º 37.510.782/0001-19,
                com sede na Rua João Alfredo Schneider, 635 - Planalto, Gramado - RS, 95670-000, doravante denominada
                “TOURFACIL”,
                incluindo os serviços para a aquisição com desconto de bens, produtos e serviços dentro do site
                <a href="{{ route('ecommerce.index') }}" title="Página inicial">https://www.tourfacil.com.br</a>
            </p>
            <p>
                Qualquer pessoa, doravante nominada “Usuário”, que pretenda utilizar os serviços do TourFácil deverá aceitar
                este Termo de Uso,
                e todas as demais políticas e princípios que o regem. A UTILIZAÇÃO DO WEBSITE E SEUS SERVIÇOS PRESUME A
                ACEITAÇÃO DO TERMO DE USO.
            </p>
            <p>
                O Usuário deverá ler, certificar-se de haver entendido e aceitar todas as condições estabelecidas no Termo
                de Uso, no ato do acesso ao Website,
                assim como nos demais documentos incorporados aos mesmos por referência, antes de seu cadastro como Usuário
                do TourFácil.
            </p>
            <p>
                O usuário pode entrar em contato com o TourFácil através do e-mail <a href="mailto:contato@tourfacil.com.br"
                    title="Email para contato">contato@tourfacil.com.br</a>
            </p>
            <p>
                O usuário concorda que, para ficar ciente de informações importantes relativas à sua conta e manter a
                comunicação com a empresa, o endereço de e-mail cadastrado será o canal de comunicação utilizado pelo
                TourFácil.
            </p>
            <strong class="text-uppercase">Objeto</strong>
            <p>Os serviços objeto do presente Termo de Uso consistem em colocar a disposição dos Usuários, direta ou
                indiretamente cadastrados em nosso website, ofertas periódicas para aquisição com desconto de produtos e/ou
                serviços de empresas parceiras do TourFácil.</p>
            <p>O TourFácil não vende produtos e nem presta os serviços divulgados em nosso site, os serviços e produtos
                ofertados são vendidos através de empresas terceiras.</p>
            <p>A função do TourFácil é divulgar e intermediar no seu website as ofertas dos Anunciantes/Parceiros, que só
                serão publicadas após uma criteriosa análise do setor de aprovação de ofertas.</p>
            <p>Os termos e condições das ofertas, bem como a prestação dos serviços ou venda dos produtos, são todos
                estabelecidos ou realizados diretamente pelos Parceiros. Contamos com a ajuda dos usuários para avaliar os
                nossos Parceiro/Anunciantes, para que o nível de satisfação seja mantido.</p>
            <p>Ao adquirir um produto e/ou serviço através do Website, o Usuário declara ter aderido ao Regulamento da
                Oferta e conhecer as condições de uso, pagamento, recebimento e prazo de validade destes.</p>
            <p>Ressaltando que, o simples ato de envio de dados de pagamento não implica necessariamente na aquisição da
                oferta, tendo em vista a possibilidade de restrição, devido à checagem de dados relacionados por
                intermediador financeiro.</p>
            <p>O TourFácil, por ser apenas um shopping on line, não é o proprietário dos produtos e/ou serviços ofertados
                pelos Parceiros, não guarda a posse deles e não realiza Ofertas em seu nome, entretanto, fica à disposição,
                através de seus canais de atendimento ao Usuário, para intermediar qualquer comunicação necessária com o
                Parceiro e tomar as providências necessárias para a solução de eventual transtorno.</p>
            <p>O TourFácil utiliza os cookies para facilitar o uso e melhor adaptar o site aos interesses do usuário. Os
                cookies também podem ser utilizados para ajudar a acelerar suas atividades e experiências futuras em nossa
                Página. Também utilizamos cookies para compilar estatísticas anônimas e agregadas que nos permitem entender
                como as pessoas utilizam nossa Página e para nos ajudar a melhorar suas estruturas e conteúdos.</p>
            <strong class="text-uppercase">Cancelamento do cupom</strong>
            <p>
                O Usuário têm 7 (sete) dias para desistir da compra efetuada, a contar do dia da compra do serviço, no
                website do TourFácil, em acordo com o art. 49 do Código de Defesa do Consumidor. A desistência deverá ser
                comunicada através do e-mail cancelamento@tourfacil.com.br, no prazo previsto de 7 (sete) dias. O
                cancelamento grátis não é válido caso a data da compra e a data de utilização do serviço seja inferior a 7
                (sete) dias. Leia mais sobre nossa Política de Cancelamento
                <a href="{{ route('ecommerce.ajuda.cancelamento') }}" title="Politica de cancelamento"
                    target="_blank">aqui</a>
            </p>
            <p>
                O TourFácil solicitará, após o contato do usuário, o estorno do valor do(s) cupom(s) adquirido(s). O prazo
                de efetiva devolução do valor dependerá da forma de pagamento adotada e dos prazos existentes perante as
                intermediárias financeiras e operadoras de cartão de crédito.
            </p>
            <p>Nas hipóteses de falha na entrega do produto e/ou serviço ou cancelamento da oferta, o TourFácil poderá
                reembolsar os Usuários compradores de Oferta, após análise de cada caso. Tais casos serão comunicados pelos
                canais de atendimento ao usuário, onde serão apresentadas duas opções de reembolso, a exclusivo critério do
                Usuário: em estorno do valor pago para aquisição da Oferta ou crédito no valor pago para uso no website,
                pelo prazo comunicado no momento da opção do usuário.</p>
            <p>A solicitação do estorno do valor pago será feita de imediato pelo TourFácil, porém o prazo da efetiva
                devolução do valor dependerá da forma de pagamento adotada e dos prazos existentes perante as intermediárias
                financeiras e operadoras de cartão de crédito.</p>
            <p>Caso não haja manifestação do Usuário fornecendo dados necessários para a devolução dos valores, em até 60
                (sessenta) dias, o TourFácil, se for acionado, fará a conversão desses valores em créditos para uso no
                website.</p>
            <p>O crédito concedido para uso no website terá prazo de validade não superior a 60 (sessenta) dias, a contar da
                data da concessão</p>
            <p>Caso o usuário, adquira um serviço e não o tenha utilizado em razão de não comparecimento no momento agendado
                (quando se tratar de serviço com agendamento obrigatório), o TourFácil não será obrigado a realizar o
                estorno do cupom.</p>
            <strong class="text-uppercase">Cadastro no Website</strong>
            <p>Os serviços do TourFácil estão disponíveis apenas para as pessoas que tenham capacidade legal para
                contratá-los. Não podem utilizá-los, assim, pessoas que não gozem dessa capacidade, inclusive menores de
                idade, ou pessoas que tenham sido inabilitadas do TourFácil temporária ou definitivamente.</p>
            <p>Em nenhuma hipótese serão permitidas a cessão, venda, aluguel ou outra forma de transferência da conta.
                Também não se permitirá a manutenção de mais de um cadastro por uma mesma pessoa, ou ainda a criação de
                novos cadastros por pessoas cujos cadastros originais tenham sido cancelados por infrações às políticas do
                TourFácil. Em todos estes casos, o TourFácil se reserva no direito de excluir todas as contas existentes do
                Usuário infrator.</p>
            <p>Somente será confirmado o cadastramento do interessado que preencher todos os campos do cadastro. O futuro
                Usuário deverá completá-lo com informações exatas, precisas e verdadeiras, e assume o compromisso de
                atualizar os dados pessoais sempre que neles ocorrer alguma alteração.</p>
            <p>O TourFácil não se responsabiliza pela correção dos dados pessoais inseridos por seus Usuários. Os Usuários
                garantem e respondem, em qualquer caso, pela veracidade, exatidão e autenticidade dos dados pessoais
                cadastrados.</p>
            <p>O TourFácil se reserva o direito de utilizar todos os meios válidos e possíveis para identificar seus
                Usuários, bem como de solicitar dados adicionais e documento de identificação válido que estime ser
                pertinente, a fim de conferir os dados pessoais informados.</p>
            <p>Caso exista a necessidade, por qualquer motivo, de conferência dos dados cadastrais de um Usuário e se
                constate haver dados incorretos ou inverídicos, ou ainda caso o Usuário se furte ou negue a enviar os
                documentos requeridos para conferência, o TourFácil poderá cancelar, definitivamente ou não, o cadastro e/ou
                pedidos e reservas do Usuário, sem prejuízo de outras medidas que entender necessárias e oportunas.</p>
            <p>O Usuário acessará sua conta através de e-mail e senha e compromete-se a não informar a terceiros esses
                dados, responsabilizando-se integralmente pelo uso que deles seja feito.</p>
            <p>O Usuário compromete-se a notificar o TourFácil imediatamente, e através de meio seguro, a respeito de
                qualquer uso não autorizado de sua conta, bem como o acesso não autorizado por terceiros à mesma. O Usuário
                será o único responsável pelas operações efetuadas em sua conta, uma vez que o acesso à mesma só será
                possível mediante a aposição da senha, cujo conhecimento é exclusivo do Usuário</p>

            <strong class="text-uppercase">Pagamento</strong>
            <p>O Usuário está ciente e concorda que a aquisição da oferta se dará através da prestação de serviço de empresa
                responsável por gestão de pagamentos, de administração independente do TourFácil, motivo pelo qual este não
                intervém nos resultados da relação do Usuário com a empresa responsável por gestão de pagamentos.</p>
            <p>O TourFácil não pode garantir que os serviços prestados pela empresa responsável pela gestão de pagamentos
                funcionarão livres de erros, interrupções, mau funcionamento, atrasos ou outras imperfeições.</p>

            <strong class="text-uppercase">Campanhas Promocionais</strong>
            <p>O TourFácil poderá realizar campanhas promocionais que garantem ao Usuário desconto no valor da compra, por
                meio de códigos promocionais ou outros meios. O regulamento de tais campanhas é definido única e
                exclusivamente pelo TourFácil, podendo ser alterado ou encerrado a qualquer momento.</p>
            <p>Sobre essas campanhas, fica a critério do TourFácil definir, entre outras coisas:</p>
            <p>
                a. usuários que estão elegíveis a participar,<br>
                b. ofertas que estão elegíveis a receber descontos adicionais,<br>
                c. regras específicas para ativação de descontos, como valor mínimo da compra e quantidade de cupons,<br>
                d. valor do desconto,<br>
                e. quantidade disponível de códigos promocionais.
            </p>
            <strong class="text-uppercase">Modificações do Termo de Uso</strong>
            <p>O TourFácil poderá alterar, a qualquer tempo, este Termo de Uso, visando seu aprimoramento e melhoria dos
                serviços prestados. O novo Termo de Uso entrará em vigor a partir de sua publicação no website. No prazo de
                24 (vinte e quatro) horas contadas a partir da publicação das modificações, o Usuário deverá comunicar por
                e-mail caso não concorde com o Termo de Uso alterado. Nesse caso, o vínculo contratual deixará de existir,
                desde que não haja contas ou dívidas em aberto em nome do Usuário. Não havendo manifestação no prazo
                estipulado, entender-se-á que o Usuário aceitou tacitamente o novo Termo de Uso e o contrato continuará
                vinculando as partes.</p>
            <p>As alterações não vigorarão em relação a Ofertas, Compromissos e aquisições já iniciados ao tempo em que as
                mesmas alterações sejam publicadas. Para estes, o Termo de Uso valerá com a redação anterior.</p>

            <strong class="text-uppercase">Privacidade da informação</strong>
            <p>O TourFácil tomará todas as medidas possíveis para manter a confidencialidade e a segurança descritas nesta
                cláusula, porém não responderá por prejuízo que possa ser derivado da violação dessas medidas por parte de
                terceiros que utilizem as redes públicas ou a internet, subvertendo os sistemas de segurança para acessar as
                informações de Usuários.</p>
            <p>
                Para obter maiores informações sobre a Política de Privacidade do TourFácil, o Usuário pode acessar o link
                <a href="{{ route('ecommerce.ajuda.privacidade') }}"
                    title="Politica de Privacidade">{{ route('ecommerce.ajuda.privacidade') }}</a> ou obter informações
                através do email
                <a href="mailto:contato@tourfacil.com.br" title="Email de atendimento">contato@tourfacil.com.br</a>
            </p>

            <strong class="text-uppercase">Obrigações dos usuários</strong>
            <p>Os Usuários interessados nas Ofertas anunciadas pelo TourFácil devem realizar as aquisições e pagamento
                durante o Período de Publicação. A Publicação da Oferta encerra-se quando expirado o prazo definido pelo
                TourFácil ou quando esgotar a quantidade do produto e/ou serviço oferecido pelo Parceiro.</p>
            <p>Ao adquirir a Oferta através do website o Usuário comprador declara-se ciente sobre as condições de
                recebimento e/ou uso do produto e/ou serviço publicadas no website e reproduzidas no cupom eletrônico.</p>
            <p>O TourFácil não se responsabiliza pelas obrigações tributárias que recaiam sobre as atividades dos Usuários
                do site, bem como sobre a de seus Parceiros. Assim como estabelece a legislação pertinente em vigor, o
                consumidor deverá exigir nota fiscal do Parceiro em suas transações.</p>
            <p>O Usuário tem responsabilidade única e exclusiva de acompanhar o prazo de expiração do seu cupom, bem como as
                condições de uso do mesmo.</p>

            <strong class="text-uppercase">Violação no sistema ou base de dados</strong>
            <p>Não é permitida a utilização de nenhum dispositivo, software, ou outro recurso que venha a interferir nas
                atividades e operações do TourFácil, bem como nas publicações de Oferta, descrições, contas ou seus bancos
                de dados. Qualquer intromissão, tentativa de, ou atividade que viole ou contrarie as leis de direito de
                propriedade intelectual e/ou as proibições estipuladas neste Termo de Uso, tornarão o responsável passível
                das ações legais pertinentes, bem como das sanções aqui previstas, sendo ainda responsável pelas
                indenizações por eventuais danos causados.</p>

            <strong class="text-uppercase">Sanções</strong>
            <p>Sem prejuízo de outras medidas, o TourFácil poderá advertir, suspender ou cancelar, temporária ou
                definitivamente, a conta de um Usuário a qualquer tempo, e iniciar as ações legais cabíveis se:</p>
            <p>
                a) O Usuário que não cumprir qualquer dispositivo deste Termos de Uso;<br>
                b) Se descumprir com seus deveres de Usuário;<br>
                c) Se praticar atos fraudulentos ou dolosos;<br>
                d) Se não puder ser verificada a identidade do Usuário ou qualquer informação fornecida por ele esteja
                incorreta;<br>
                e) Se o TourFácil entender que os anúncios ou qualquer atitude do Usuário tenham causado algum dano a
                terceiros ou ao próprio TourFácil ou tenham a potencialidade de assim o fazer. Nos casos de inabilitação do
                cadastro do Usuário, todas as Aquisições de Ofertas ativas serão automaticamente canceladas. O TourFácil
                reserva-se o direito de, a qualquer momento e a seu exclusivo critério, solicitar o envio de documentação
                pessoal.
            </p>

            <strong class="text-uppercase">Responsabilidade de funcionamento</strong>
            <p>Tendo em vista, ainda, a impossibilidade de funcionamento integral e ininterrupto de qualquer sistema de
                telecomunicação ou de informática, inclusive em razão da dependência de serviços de telecomunicações
                prestados por terceiros, o TourFácil não garante a prestação do serviço de forma ininterrupta e/ou isenta de
                erros. Eventualmente, o sistema poderá não estar disponível por motivos técnicos ou falhas da internet, ou
                por qualquer outro evento fortuito ou de força maior alheio ao controle do TourFácil.</p>
            <p>O TOURFACIL não é o proprietário dos produtos ou responsável pela qualidade dos produtos/ serviços ofertados.
                Logo, não intervém na entrega dos produtos ou na prestação dos serviços, atuando na qualidade de shopping on
                line.</p>

            <strong class="text-uppercase">Propriedade Intelectual</strong>
            <p>O uso comercial da expressão “TourFácil” como marca, nome empresarial ou nome de domínio, bem como os
                conteúdos das telas relativas aos serviços do TourFácil assim como os programas, bancos de dados, redes e
                arquivos, que permitem que o Usuário acesse e use sua conta, são de propriedade do TourFácil e estão
                protegidos pelas leis e tratados internacionais de direito autoral, marcas, patentes, modelos e desenhos
                industriais. O uso indevido e a reprodução total ou parcial dos referidos conteúdos são proibidos, salvo a
                autorização expressa do TourFácil.</p>
            <p>O TourFácil não será responsável pelos conteúdos, práticas e serviços ofertados em outros sites que não sejam
                de propriedade ou operados por si, ainda que exista independentemente do motivo, link para os mesmos no
                website TourFácil. A presença de links para outros sites não implica relação de sociedade, de supervisão, de
                cumplicidade ou solidariedade do TourFácil para com esses sites e seus conteúdos.</p>

            <strong class="text-uppercase">Indenização</strong>
            <p>O Usuário indenizará o TourFácil, suas filiais, empresas controladas ou controladoras, diretores,
                administradores, colaboradores, representantes e empregados por qualquer demanda promovida por outros
                usuários ou terceiros decorrentes de suas atividades no site ou por seu descumprimento dos Termos de Uso ou
                pela violação de qualquer lei ou direitos de terceiros, incluindo honorários de advogados.</p>
            <p>Não obstante a existência do presente Termo de Uso, a relação entre o TourFácil e o usuário é regida pelas
                leis brasileiras, em especial pelo Código de Defesa do Consumidor.</p>

            <strong class="text-uppercase">Intermediação dos serviços</strong>
            <p>Natureza da Intermediação: A TOURFACIL atua exclusivamente como intermediadora entre o Usuário (turista) e os
                diversos fornecedores de serviços turísticos (doravante "Fornecedores"). Desta forma, a TOURFACIL facilita o
                acesso dos Usuários às ofertas de serviços turísticos, mas não é responsável pela sua execução.</p>
            <p>Relação com Fornecedores: Os serviços adquiridos pelo Usuário são operados e geridos por Fornecedores
                independentes e não por TOURFACIL. Cada Fornecedor é responsável exclusivo pela disponibilidade, qualidade,
                e cumprimento dos serviços turísticos oferecidos.</p>
            <p>Informações dos Serviços: As informações relativas aos serviços oferecidos, incluindo disponibilidade,
                características e preços, são fornecidas diretamente pelos respectivos Fornecedores.</p>
            <p>Alterações no Serviço: Os Fornecedores reservam-se o direito de alterar itinerários ou cancelar serviços em
                resposta a condições climáticas adversas, indisponibilidade de atrações ou outras circunstâncias fora do
                controle habitual que não configuram uma alteração substancial do serviço original contratado. Essas
                alterações podem ser feitas sem aviso prévio ao Usuário.</p>
            <p>Responsabilidades do Usuário: Ao adquirir um serviço turístico através da TOURFACIL, o Usuário confirma que
                aceita os termos e condições estabelecidos pelo respectivo Fornecedor e reconhece que a TOURFACIL não é
                parte nas obrigações contratuais específicas para a execução dos serviços turísticos</p>
            <p>Isenção de Responsabilidade da TOURFACIL: A TOURFACIL não será responsável por qualquer falha na prestação
                dos serviços contratados através de seu portal, salvo em casos de negligência ou omissão na verificação das
                informações fornecidas pelos Fornecedores. A responsabilidade da TOURFACIL limita-se à intermediação entre o
                Usuário e o Fornecedor.</p>
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
