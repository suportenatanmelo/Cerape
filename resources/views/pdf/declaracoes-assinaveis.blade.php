@extends('pdf.layout')

@section('styles')
<style>
    .document-body {
        padding-top: 90px;
        padding-bottom: 84px;
    }
    .doc-page {
        font-family: DejaVu Serif, serif;
        font-size: 11pt;
        line-height: 1.35;
        margin: 0 2cm;
        text-align: justify;
    }
    .doc-section {
        page-break-inside: avoid;
        margin-bottom: 10px;
    }
    .doc-title {
        color: #111111;
        font-size: 13pt;
        font-weight: 700;
        letter-spacing: 0.04em;
        margin: 0 0 14px;
        text-align: center;
        text-transform: uppercase;
    }
    .doc-paragraph {
        color: #111111;
        font-size: 11pt;
        line-height: 1.35;
        margin: 0 0 8px;
        text-align: justify;
        text-indent: 1.25cm;
    }
    .doc-date {
        font-size: 11pt;
        margin: 14px 0 12px;
        text-indent: 0;
    }
    .doc-signature {
        margin-top: 30px;
        text-align: center;
    }
    .signature-line {
        border-top: 1px solid #111111;
        display: inline-block;
        font-size: 11pt;
        min-width: 260px;
        padding-top: 5px;
    }
    .two-lines {
        margin-top: 22px;
    }
    .two-lines .signature-line {
        display: block;
        margin-bottom: 14px;
        min-width: 280px;
    }
    .doc-list {
        margin: 0.3rem 0 0.8rem 1.25cm;
        padding-left: 0;
    }
    .doc-list div {
        font-size: 11pt;
        margin-bottom: 0.3rem;
    }
    .blank-block {
        border-bottom: 1px solid #111111;
        display: inline-block;
        min-height: 1.1em;
        min-width: 190px;
        vertical-align: baseline;
    }
    .blank-wide { min-width: 320px; }
    .blank-full { display: block; margin-top: 0.4rem; min-width: 100%; }
    .doc-multi-line { border-bottom: 1px solid #111111; display: block; height: 22px; margin-top: 0.45rem; width: 100%; }
    .doc-multi-line + .doc-multi-line { margin-top: 0.55rem; }
    .contract-section { margin-bottom: 10px; }
    .contract-label { font-size: 11pt; font-weight: 700; text-transform: uppercase; }
    .contract-block {
        margin-bottom: 8px;
    }
    .ptc-header {
        margin-bottom: 12px;
        text-align: center;
    }
    .ptc-body {
        margin-top: 6px;
    }
    .ptc-body .doc-paragraph {
        text-indent: 1.25cm;
    }
    .ptc-signature {
        margin-top: 22px;
        text-align: center;
    }
    .ptc-signature .signature-line {
        min-width: 300px;
    }
</style>
@endsection

@section('content')
    @php($acolhido = $payload['acolhido'])
    @php($interventor = $payload['interventor'] ?? [])
    @php($acolhidoProfile = $payload['acolhidoProfile'] ?? [])

    <div class="doc-page">
    @switch($payload['type'])
        @case('leitura_ptc')
            <div class="doc-section">
            <div class="ptc-header">
                <h1 class="doc-title">{{ $payload['title'] }}</h1>
            </div>
            <div class="ptc-body">
                <p class="doc-paragraph">
                    Declaro para os devidos fins que foi lido o Programa Terapêutico do CR - CERAPE, sendo discriminadas suas 4 (quatro) fases: 1ª fase com 105 (cento e cinco) dias, 2ª fase com 60 (sessenta) dias, 3ª fase com 60 (sessenta) dias e 4ª fase com 45 (quarenta e cinco) dias, podendo estender-se a 140 (cento e quarenta) dias, caso o acolhido necessite de reinserção ao mercado de trabalho. Fica igualmente registrado que também foi apresentado o programa semanal de horários de atividades, contemplando todas as terapias e rotinas do CRC.
                </p>
                <p class="doc-paragraph">Por ser verdade, firmo a presente declaração.</p>
                <p class="doc-date">{{ $payload['dateText'] }}</p>
                <div class="ptc-signature"><span class="signature-line">Assinatura do acolhido</span></div>
            </div>
            </div>
            @break

        @case('termo_desligamento')
            <div class="doc-section">
            <h1 class="doc-title">{{ $payload['title'] }}</h1>
            <p class="doc-paragraph">
                Eu, {{ $acolhido?->nome_completo_paciente ?? '____________________________________________________________' }}
                filiacao(mae): {{ $acolhido?->nome_da_mae ?? '__________________________________________' }}
                (pai): {{ $acolhido?->nome_do_pai ?? '____________________________________________________' }},
                acolhido no Centro de Reabilitacao do CERAPE - CRC, desde {{ $payload['admissionDate'] }}, solicito o meu desligamento do acolhimento voluntario, pelo motivo abaixo:
            </p>
            <div class="doc-list">
                <div>(   ) Desistencia a PEDIDO</div>
                <div>(   ) Desligamento Administrativo</div>
                <div>(   ) Desligamento em caso de mandato judicial</div>
                <div>(   ) Desistencia com Evasao do CRC</div>
                <div>(   ) Conclusao do PTC</div>
            </div>
            <p class="doc-paragraph"><strong>PLANO DE SAIDA (opcional):</strong></p>
            <p class="doc-paragraph">Endereco destino: <span class="blank-block blank-wide">&nbsp;</span></p>
            <p class="doc-paragraph">Parentes? SIM (   )   NAO (   )</p>
            <p class="doc-paragraph">Amigos? SIM (   )   NAO (   )</p>
            <p class="doc-paragraph">Retorno a rua SIM (   )   NAO (   )</p>
            <p class="doc-paragraph"><strong>REGISTRO INSTITUCIONAL:</strong></p>
            <p class="doc-paragraph">Reclamacao:</p>
            <span class="doc-multi-line"></span>
            <span class="doc-multi-line"></span>
            <span class="doc-multi-line"></span>
            <p class="doc-paragraph" style="margin-top: 1rem;">Elogio: <span class="blank-block blank-wide">&nbsp;</span></p>
            <p class="doc-date">{{ $payload['dateText'] }}</p>
            <div class="doc-signature"><span class="signature-line">Assinatura do acolhido</span></div>
            <div class="two-lines">
                <p class="doc-paragraph"><strong>TESTEMUNHAS:</strong></p>
                <span class="signature-line">&nbsp;</span>
                <span class="signature-line">&nbsp;</span>
            </div>
            </div>
            @break

        @case('uso_imagem')
            <div class="doc-section">
            <h1 class="doc-title">{{ $payload['title'] }}</h1>
            <p class="doc-paragraph">
                Neste ato eu {{ $acolhido?->nome_completo_paciente ?? '____________________________________________________________' }},
                portador da Cedula de Identidade RG n&ordm; {{ $payload['rg'] }} e do CPF/MF n&ordm; {{ $payload['cpf'] }},
                residente {{ $payload['addressLine'] }}.
            </p>
            <p class="doc-paragraph">
                Autorizo o uso de minha imagem em todo e qualquer material seja em fotos avulsas como em documentos, para ser utilizada em campanhas promocionais e institucionais do CERAPE/CRC, CNPJ n&ordm; 00.857.994/0001-67, localizado na Fazenda Quintas no Park Alvorada II Luziânia - GO.
            </p>
            <p class="doc-paragraph">
                A presente autorizacao e concedida para uso de imagem, sem nenhum onus para a instituicao acima mencionada em todo territorio nacional e no exterior, da forma que a instituicao achar conveniente, para uso em qualquer midia, seja ela impressa ou eletronica de qualquer forma, ou seja outdoors, folhetos em geral, encartes, malas direta, catalogo, folder, anuncios em revista e jornais, home page, paineis, video, televisao, cinema, programa de radio, entre outros.
            </p>
            <p class="doc-paragraph">
                Por ser esta, a expressao da minha vontade, declaro que autorizo o uso acima descrito sem que haja a ser reclamado a titulo de direitos conexos a minha imagem, assino a presente autorizacao em 2 (duas) vias de igual teor e forma.
            </p>
            <p class="doc-date">{{ $payload['dateText'] }}</p>
            <div class="doc-signature"><span class="signature-line">Acolhido CPF n&ordm; {{ $payload['cpf'] }}</span></div>
            </div>
            @break

        @case('desistencia_ptc')
            <div class="doc-section">
            <h1 class="doc-title">{{ $payload['title'] }}</h1>
            <p class="doc-paragraph">
                Declaro para os devidos fins que na ocorrencia da desistencia do Programa Terapeutico do CR - CERAPE, nao serao devolvidos doacoes de itens do acolhimento tais como material de higiene pessoal, camisetas do PTC para uso apenas durante o Programa e demais itens que sao de uso coletivo entregues no dia do acolhimento, sendo os mesmos para uso da Comunidade Terapeutica.
            </p>
            <p class="doc-paragraph">Por ser verdade, firmo a presente declaracao,</p>
            <p class="doc-date">{{ $payload['dateText'] }}</p>
            <div class="doc-signature"><span class="signature-line">Acolhido CPF n&ordm; {{ $payload['cpf'] }}</span></div>
            </div>
            @break

        @case('acolhimento_voluntario')
            <div class="doc-section">
            <h1 class="doc-title">{{ $payload['title'] }}</h1>
            <p class="doc-paragraph">
                Declaro para os devidos fins que sou voluntario ao acolhimento no CENTRO DE REABILITACAO DO CERAPE - CRC, submetendo-me as normas e recomendacoes internas do CRC, bem como, sua Programacao Diaria, ciente tambem de que nao terei nenhum vinculo empregaticio, nem obrigacao de natureza trabalhista, previdenciaria e afins, em conformidade com a Lei 9.608, de fevereiro de 1998 e Lei n&ordm; 13.840 item II de 05/06/2020, enquanto durar minha permanencia no CRC.
            </p>
            <p class="doc-paragraph">
                Por ser expressao da verdade, assumindo inteira responsabilidade pelas declaracoes acima sob as penas da lei, assino a presente declaracao para que produza seus efeitos legais.
            </p>
            <p class="doc-date">{{ $payload['dateText'] }}</p>
            <div class="doc-signature"><span class="signature-line">Acolhido CPF n&ordm; {{ $payload['cpf'] }}</span></div>
            </div>
            @break

        @case('contrato_prevencao_recaida')
            <div class="doc-section">
            <h1 class="doc-title">{{ $payload['title'] }}</h1>
            <p class="doc-paragraph contract-block" style="text-indent: 0; text-align: center;">CENTRO DE REABILITAÇÃO DO CERAPE - CRC</p>
            <p class="doc-paragraph contract-block" style="text-indent: 0; text-align: center;">CONTRATO TERAPEUTICO - SERVIÇO: PROGRAMA DE PREVENÇÃO A RECAIDA E REABILITAÇÃO.</p>
            <p class="doc-paragraph contract-block" style="text-indent: 0;"><span class="contract-label">CONTRATANTE</span></p>
            <p class="doc-paragraph">
                <strong>INTERVENTOR DO ACOLHIMENTO:</strong><br>
                Nome: {{ $interventor['nome'] ?? '________________________________________________________________' }}, CPF {{ $interventor['cpf'] ?? '___________________' }}, RG: {{ $interventor['rg'] ?? '___________' }} Exp. {{ $interventor['exp'] ?? '___________' }} UF {{ $interventor['rgUf'] ?? '______' }}<br>
                Profissão: {{ $interventor['profissao'] ?? '______________' }} Data de Nascimento: {{ $interventor['dataNascimento'] ?? '__/__/____' }}<br>
                Residente: {{ $interventor['residente'] ?? '____________________________________________________________' }}, Complemento: {{ $interventor['complemento'] ?? '_____________________' }}, Bairro: {{ $interventor['bairro'] ?? '_________________________' }}, Cidade: {{ $interventor['cidade'] ?? '_______________________________' }}, UF: {{ $interventor['uf'] ?? '______' }}<br>
                Tel: {{ $interventor['telefone'] ?? '___________________________' }}
            </p>
            <p class="doc-paragraph">
                <strong>ACOLHIDO:</strong> {{ $acolhidoProfile['nome'] ?? '________________________________________________________' }} Data de Nascimento: {{ $acolhidoProfile['dataNascimento'] ?? '__/__/____' }}, CPF: {{ $acolhidoProfile['cpf'] ?? '___________________' }}<br>
                RG: {{ $acolhidoProfile['rg'] ?? '_______________________' }}, Profissão: {{ $acolhidoProfile['profissao'] ?? '_____________________________' }}, Naturalidade: {{ $acolhidoProfile['naturalidade'] ?? '___________________________________' }} UF {{ $acolhidoProfile['uf'] ?? '______' }}<br>
                Nacionalidade: {{ $acolhidoProfile['nacionalidade'] ?? '______________' }}.
            </p>
            <p class="doc-paragraph"><span class="contract-label">CONTRATADO:</span><br> CENTRO DE REABILITACAO DO CERAPE - CRC, CNPJ nº 857.994.0002-48, estabelecido na Área Rural, Park Alvorada III, Fazenda Quintas, Chácara da Igreja Batista Rhema. As partes ajustam entre si, por este instrumento particular e na melhor forma de direito, a prestação de serviços terapêuticos para tratamento da dependência química, com as seguintes cláusulas e condições:</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 1ª</strong> - Propoe-se o serviço de tratamento e acompanhamento de prevenção e reabilitação de recaída, desenvolvido por meio do Programa Terapêutico do CRC, voltado à abstinência e a conscientização do uso e abuso de substâncias psicoativas, bem como a promoção individual e social do dependente químico, sendo ele o principal responsável pelo seu processo de recuperação e construção de um novo estilo de vida, levando em consideração suas potencialidades e a força presente na coletividade, com foco na abstinência total.</p>
            <p class="doc-paragraph"><strong>§ 1º O ACOLHIDO</strong> - De agora em diante, declara, neste ato, do acolhimento que é de livre e espontânea vontade, que se compromete a seguir todas as normas estabelecidas pelo CRC, contidas no Regimento Interno e Programa Terapêutico, o qual declara conhecer.</p>
            <p class="doc-paragraph"><strong>§ 2º DURAÇÃO</strong> - O Programa Terapêutico de Prevenção e Reabilitação de Recaída - PTPRR tem duração de até 105 (cento e cinco) dias, equivalente a três meses e meio, sendo os primeiros 45 dias sem direito a visita, ou convívio familiar, uso do telefone, fone de ouvido e para quaisquer contatos com sua rede familiar ou amigos, cerceado também neste período o uso de fone de ouvido. Aos 60 (sessenta) dias o acolhido poderá utilizar seu aparelho celular, para o uso restrito e pessoal, de acordo com o Regimento do CRC, sem no entanto poder emprestar, em nenhuma hipótese. Finaliza-se o PTPRR, completos os 105 dias, faremos uma avaliação considerando a opinião da Equipe Técnica, da Família ou Interventores e do Acolhido. Podendo, de comum acordo, decidir sobre um aditivo para ampliar o programa para até 240 (duzentos e quarenta) dias considerando as quatro fases do Programa Terapêutico do Cerape - PTC.</p>
            <p class="doc-paragraph"><strong>§ 3º SERVIÇOS</strong> - Além do Programa Terapêutico em regime de acolhimento, estão inclusos os serviços de hospedagem completa incluindo quatro refeições diárias.</p>
            <p class="doc-paragraph"><strong>§ 4º ATIVIDADES</strong> - Nas rotinas diárias são realizados atividades físicas, atividades práticas de manutenção e conservação interna e externa da casa, cultivo de horticultura orgânica, plantio de árvores nativas para revitalização do solo e nascentes, oficina de reciclagem, artesanato, preparo de refeições, lavanderia, aconselhamento individual e em grupo, espiritualidade por meio de leituras, estudos bíblicos, rodas terapêuticas e cultos, cursos de formação continuada, quer sejam presenciais ou EAD, programas EJA e ENCEJA, dinâmicas e reuniões, aulas teóricas e práticas de ética e cidadania, aplicação dos 12 (doze) passos na metodologia do "Celebrando a Recuperação", dentre outras atividades propostas pelo CRC durante o acolhimento.</p>
            <p class="doc-paragraph"><strong>§ 5º ATENDIMENTO</strong> - O atendimento é realizado nas dependências do CENTRO DE REABILITAÇÃO DO CERAPE - CRC, localizado na Área Rural, Park Alvorada III, Fazenda Quintas, Chácara CERAPE, e poderá se estender em ambientes externos quando se fizer necessário.</p>
            <p class="doc-paragraph"><strong>§ 6º VISITAS</strong> - Mediante as visitas estabelecidas previamente pela Instituição, a família deverá acompanhar a evolução do tratamento oferecido pela CONTRATADA, por intermédio do Grupo de Familiares e reuniões convocadas pela Gestão da Instituição.</p>
            <p class="doc-paragraph"><strong>§ 7º CONVÍVIO FAMILIAR</strong> - O acolhido terá direito a convívio familiar, com duração de 72 horas, a partir dos 60 (sessenta) dias, podendo variar de acordo com a evolução do mesmo mediante adaptação ao tratamento e cumprimento do estabelecido.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 2ª</strong> - Para a execução dos serviços de acolhimento, com todos os recursos previstos no Programa Terapêutico, visando a reabilitação do acolhido, a CONTRATADA efetivará o pagamento de R$ 2.625,00 (dois mil, seiscentos e vinte e cinco reais), por 105 (cento e cinco) dias, o que equivale à diária de R$ 25,00 (vinte e cinco reais), sendo cobrada apenas taxa para o uso das camisetas referente às fases.</p>
            <p class="doc-paragraph"><strong>Parágrafo único</strong> - Até o décimo quinto dia a CONTRATADA se compromete a depositar R$ 70,00 (setenta reais) no pix da empresa fornecedora dos uniformes do Cerape, referente a uma camiseta e uma mochila da instituição que será utilizada no interior da chácara e em ocasiões que se fizer necessária, ficando bem claro que este uniforme não poderá ser levado pelo acolhido quando no encerramento do programa, para preservar a exposição da marca da instituição.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 3ª</strong> - As despesas com medicamentos, objetos de uso pessoal, locomoção do acolhido para perícias, consultas médicas e dentárias, audiências judiciais ou quaisquer outras necessidades são de responsabilidade da CONTRATANTE, mediante comprovação.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 4ª</strong> - Visitas, ligações, acesso a lan house, fones de ouvido só serão permitidos após 45 dias de acolhimento. As visitas acontecem aos sábados e domingos, das 14hs às 17hs, desde que previamente agendadas.</p>
            <p class="doc-paragraph"><strong>§ 1º</strong> - Os contatos telefônicos são feitos por meio do celular da casa, que é de uso coletivo, tendo cada acolhido direito a 20 minutos semanais de ligação.</p>
            <p class="doc-paragraph"><strong>§ 2º</strong> - Fica proibido o uso de telefone de terceiros ou de acolhidos que já possuem aparelho para uso próprio, sem prévia autorização.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 5ª</strong> - Em caso de eventuais danos contra o patrimônio da Instituição que o acolhido der causa, serão de responsabilidade da CONTRATANTE.</p>
            <p class="doc-paragraph"><strong>§ 1º</strong> - Em caso de desistência do programa com evasão do acolhido, os pertences serão doados num prazo máximo de 15 (quinze) dias, a partir da data do desligamento.</p>
            <p class="doc-paragraph"><strong>§ 2º</strong> - Em caso de óbito do acolhido, a família será comunicada e providenciará todos os trâmites legais de cartório, funeral e sepultamento, inclusive locomoção do corpo.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 6ª</strong> - A Contratante fica ciente de que, caso o acolhido possua pendências com a Justiça ou que venha a ser expedido mandado de prisão durante o período de tratamento, a Contratada comunicará de imediato o Poder Judiciário.</p>
            <p class="doc-paragraph"><strong>§ 1º</strong> - Caso o acolhido seja foragido da Justiça, não será admitido na instituição, a menos que se torne público mediante comunicado ao Juizado competente.</p>
            <p class="doc-paragraph"><strong>§ 2º</strong> - Não serão admitidos acolhidos portadores de qualquer tipo de doença mental comprovada e/ou caso seja constatada a enfermidade no decorrer do tratamento, serão desligados e entregues à Contratante ou, em casos específicos, encaminhados para um local adequado, caso não tenha encaminhamento médico que garanta a capacidade de boa convivência entre os pares e em grupo.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 7ª</strong> - A Contratada não se responsabiliza por atos de agressão física ou verbal cometidos entre os acolhidos. Se ocorrerem, a responsabilidade será apurada pela equipe multidisciplinar que poderá promover o desligamento dos envolvidos e efetuar denúncia formal, nos órgãos competentes.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 8ª</strong> - A família é considerada parte integrante do Programa Terapêutico, portanto, fica estabelecida a sua participação frequente nas reuniões do CERAPE, que acontecem durante todo o ano, em datas previamente estabelecidas, via link encaminhado no grupo de WhatsApp da família.</p>
            <p class="doc-paragraph">A não participação do representante familiar e/ou interventor nas reuniões mensais poderá motivar impedimento na visitação do acolhido ou em convívio familiar. O cumprimento de regras também pode interferir nas visitas familiares.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 9ª</strong> - O acolhido deverá assinar os seguintes documentos: Declaração referente ao uso de imagem; Declaração da leitura do PTC; Declaração de desistência; Declaração do acolhimento voluntário e Termo de desligamento.</p>
            <p class="doc-paragraph">Por estarem de pleno acordo, as partes firmam o presente.</p>
            <p class="doc-date">{{ $payload['signatureDateLine'] }}</p>
            <div class="two-lines">
                <span class="signature-line">ASSINATURA RESPONSÁVEL PELA INTERVENÇÃO DO ACOLHIDO</span>
                <span class="signature-line">ASSINATURA DO ACOLHIDO</span>
            </div>
            </div>
            @break

        @case('contrato_terapeutico_9_meses')
            <div class="doc-section">
            <h1 class="doc-title">{{ $payload['title'] }}</h1>
            <p class="doc-paragraph contract-block" style="text-indent: 0; text-align: center;">CENTRO DE REABILITAÇÃO DO CERAPE - CRC</p>
            <p class="doc-paragraph contract-block" style="text-indent: 0; text-align: center;">CONTRATO TERAPEUTICO</p>
            <p class="doc-paragraph contract-block" style="text-indent: 0;"><span class="contract-label">CONTRATANTE</span></p>
            <p class="doc-paragraph">
                <strong>INTERVENTOR PELO ACOLHIMENTO:</strong><br>
                Nome: {{ $interventor['nome'] ?? '________________________________________________________________' }}, CPF {{ $interventor['cpf'] ?? '___________________' }}, RG: {{ $interventor['rg'] ?? '___________' }} Exp. {{ $interventor['exp'] ?? '___________' }} UF {{ $interventor['rgUf'] ?? '______' }}<br>
                Profissão: {{ $interventor['profissao'] ?? '______________' }} Data de Nascimento: {{ $interventor['dataNascimento'] ?? '__/__/____' }}<br>
                Nacionalidade: {{ $interventor['nacionalidade'] ?? '_______________' }}<br>
                Residente à {{ $interventor['residente'] ?? '____________________________________________________________' }}<br>
                Complemento: {{ $interventor['complemento'] ?? '_____________________' }}, Bairro: {{ $interventor['bairro'] ?? '_________________________' }}, Cidade: {{ $interventor['cidade'] ?? '_______________________________' }}, UF: {{ $interventor['uf'] ?? '______' }}<br>
                Tel: {{ $interventor['telefone'] ?? '___________________________' }}
            </p>
            <p class="doc-paragraph">
                <strong>ACOLHIDO:</strong> {{ $acolhidoProfile['nome'] ?? '________________________________________________________' }} Data de Nascimento: {{ $acolhidoProfile['dataNascimento'] ?? '__/__/____' }}, CPF: {{ $acolhidoProfile['cpf'] ?? '___________________' }}<br>
                RG: {{ $acolhidoProfile['rg'] ?? '_______________________' }}, Profissão: {{ $acolhidoProfile['profissao'] ?? '_____________________________' }}, Naturalidade: {{ $acolhidoProfile['naturalidade'] ?? '___________________________________' }} UF {{ $acolhidoProfile['uf'] ?? '______' }}<br>
                Nacionalidade: {{ $acolhidoProfile['nacionalidade'] ?? '______________' }}.
            </p>
            <p class="doc-paragraph">
                <span class="contract-label">CONTRATO:</span><br>
                CENTRO DE REABILITAÇÃO DO CERAPE – CRC, CNPJ nº 857.994.0002-48, Área Rural, Park Alvorada III, Fazenda Quintas, Chácara CERAPE. As partes ajustam entre si, por este instrumento particular e na melhor forma de direito, a prestação de serviços terapêuticos para tratamento da dependência química, com as seguintes cláusulas e condições:
            </p>
            <p class="doc-paragraph"><strong>CLÁUSULA 1ª</strong> - O tratamento da dependência química é desenvolvido por meio do Programa Terapêutico do CRC, voltado à abstinência e a conscientização do uso e abuso de substâncias psicoativas, bem como a promoção individual e social do dependente químico, sendo ele, o principal responsável pelo seu processo de recuperação e construção de um novo estilo de vida, levando em consideração suas potencialidades e a força presente na coletividade, com foco na abstinência total.</p>
            <p class="doc-paragraph"><strong>§ 1° O ACOLHIDO</strong> - De agora em diante, declara, neste ato, do acolhimento que é de livre e espontânea vontade, que se compromete a seguir todas as normas estabelecidas pelo CRC, contidas no Regimento Interno e Programa Terapêutico, o qual declara conhecer.</p>
            <p class="doc-paragraph"><strong>§ 2° DURAÇÃO</strong> - O Programa Terapêutico tem duração de até 12 (doze) meses, sendo: 15 dias de adaptação e 90 dias para primeira fase; 60 dias para segunda fase; 60 dias para terceira fase e mais 45 dias para finalizar o tratamento, totalizando 270 dias (nove meses) das 4 fases evolutivas que se seguem.</p>
            <p class="doc-paragraph"><strong>§ 3° SERVIÇOS</strong> - No Programa Terapêutico em regime de acolhimento estão inclusos os serviços de hospedagem completa incluindo quatro refeições diárias.</p>
            <p class="doc-paragraph"><strong>§ 4º ATIVIDADES</strong> - Nas rotinas diárias são realizados atividades físicas, atividades práticas de manutenção e conservação interna e externa da casa, cultivo de horticultura orgânica, plantio de árvores nativas para revitalização do solo e nascentes, oficina de reciclagem, artesanato, preparo de refeições, lavanderia, aconselhamento individual e em grupo, espiritualidade por meio de leituras, estudos bíblicos, rodas terapêuticas e cultos, cursos de formação continuadas, quer sejam presenciais ou EAD, programas EJA e Enceja, dinâmicas e reuniões, aulas teóricas e práticas de ética e cidadania, aplicação dos 12 (doze) passos na metodologia do “Celebrando a Recuperação”, dentre outras atividades propostas pelo CRC durante o acolhimento.</p>
            <p class="doc-paragraph"><strong>§ 5º ATENDIMENTO</strong> - O atendimento é realizado nas dependências do CENTRO DE REABILITAÇÃO DO CERAPE - CRC, localizado na Área Rural, Park Alvorada III, Fazenda Quintas, Chácara CERAPE, e poderá se estender em ambientes externos quando se fizer necessário.</p>
            <p class="doc-paragraph"><strong>§ 6º CONVÍVIO FAMILIAR</strong> - O acolhido terá direito a convívio familiar, com duração de 72 horas, a partir de 105 dias, podendo variar de acordo com a evolução do mesmo mediante adaptação ao tratamento e cumprimento do estabelecido.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 2ª</strong> - Para a execução dos serviços de acolhimento, com todos os recursos previstos no Programa Terapêutico, visando a reabilitação do acolhido, o CRC não cobra nenhuma mensalidade, sendo cobrada apenas taxa para o uso das camisetas referente às fases.</p>
            <p class="doc-paragraph"><strong>§ Único</strong> - O CRC possui vagas de acolhimento financiadas pelo Governo Federal através do Departamento de Entidades de Apoio e Acolhimento Atuantes em Álcool e Drogas do Ministério do Desenvolvimento e Assistência Social, Família e Combate à Fome (MDS), o CONTRATANTE fica ciente de que o acolhido poderá ser incluído na respectiva vaga ou de outro programa para financiamento de vagas.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 3ª</strong> - As despesas com medicamentos, objetos de uso pessoal, locomoção do acolhido para perícias, consultas médicas e dentárias, audiências judiciais ou quaisquer outras necessidades é de responsabilidade da CONTRATANTE, mediante comprovação.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 4ª</strong> - Visitas, ligações, acesso a lan house, fones de ouvido só serão permitidos após 45 dias de acolhimento. As visitas acontecem aos sábados e domingos, das 14hs às 17hs, desde que previamente agendadas.</p>
            <p class="doc-paragraph"><strong>§ 1°</strong> - Os contatos telefônicos são feitos por meio do celular da casa, que é de uso coletivo, tendo cada acolhido direito a 20 minutos semanais de ligação.</p>
            <p class="doc-paragraph"><strong>§ 2°</strong> - Fica proibido o uso de telefone de terceiros ou de acolhidos que já possuem aparelho para uso próprio, sem prévia autorização.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 5ª</strong> - Após o sexto mês de acolhimento é permitido que o acolhido, dentro de suas condições, tenha aparelho telefônico, tablete ou notebook, para completar sua trilha profissional e educacional.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 6ª</strong> - Em caso de eventuais danos contra o patrimônio da Instituição que o acolhido der causa, serão de responsabilidade da CONTRATANTE.</p>
            <p class="doc-paragraph"><strong>§ 1º</strong> - Em caso de desistência do programa com evasão do acolhido os pertences serão doados num prazo máximo de 15 (dias) dias, a partir da data do desligamento.</p>
            <p class="doc-paragraph"><strong>§ 2º</strong> - Em caso de óbito do acolhido, a família será comunicada e providenciará todos os trâmites legais de cartório, funeral e sepultamento inclusive locomoção do corpo.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 7ª</strong> - A Contratante fica ciente de que, caso o acolhido possua pendências com a Justiça ou que venha a ser expedido mandado de prisão durante o período de tratamento, a Contratada comunicará, de imediato, o Poder Judiciário.</p>
            <p class="doc-paragraph"><strong>§ 1º</strong> - Caso o Acolhido seja foragido da Justiça, não será admitido na Instituição Cerape, a menos, que se torne público mediante comunicado ao Juizado competente.</p>
            <p class="doc-paragraph"><strong>§ 2º</strong> - Não serão admitidos acolhidos portadores de qualquer tipo de doença mental comprovada e/ou caso seja constatada a enfermidade no decorrer do tratamento, serão desligados e entregues à Contratante ou em casos específicos, encaminhados para um local adequado.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 8ª</strong> - A Contratada não se responsabiliza por atos de agressão física ou verbal cometidos entre os acolhidos. Se ocorrerem, a responsabilidade será apurada pela equipe multidisciplinar que poderá promover o desligamento dos envolvidos e efetuar denúncia, formal, nos órgãos competentes.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 9ª</strong> - A família é considerada parte integrante do Programa Terapêutico, portanto, fica estabelecida a sua participação frequente nas reuniões do CERAPE, que acontecem durante todo o ano, em datas previamente estabelecidas, via link encaminhado no grupo de whatsapp da família.</p>
            <p class="doc-paragraph"><strong>§ Único</strong> - A não participação do representante familiar e/ou interventor nas reuniões mensais poderá motivar impedimento na visitação do acolhido ou em convívio familiar. O não cumprimento de regras também pode interferir nas visitas familiares.</p>
            <p class="doc-paragraph"><strong>CLÁUSULA 10ª</strong> - O acolhido deverá assinar os seguintes documentos: Declaração referente ao uso de imagem; Declaração da leitura de do PTC; Declaração de desistência; Declaração do acolhimento voluntário e Termo de desligamento.</p>
            <p class="doc-paragraph">Por estarem de pleno acordo, as partes firmam o presente.</p>
            <p class="doc-date">{{ $payload['signatureDateLine'] }}</p>
            <div class="two-lines">
                <span class="signature-line">ASSINATURA RESPONSÁVEL PELA INTERVENÇÃO DO ACOLHIDO</span>
                <span class="signature-line">ASSINATURA DO ACOLHIDO</span>
            </div>
            <div class="two-lines">
                <span class="signature-line">ASSINATURA DO CERAPE</span>
            </div>
            </div>
            @break
    @endswitch
    </div>
@endsection
