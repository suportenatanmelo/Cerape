@php($acolhido = $payload['acolhido'])
@php($interventor = $payload['interventor'] ?? [])
@php($acolhidoProfile = $payload['acolhidoProfile'] ?? [])

<style>
    .doc-title { color: #0f172a; font-size: 1.2rem; font-weight: 800; letter-spacing: 0.08em; margin: 0 0 1.6rem; text-align: center; text-transform: uppercase; }
    .doc-paragraph { color: #111827; line-height: 1.75; margin: 0 0 0.95rem; text-align: justify; }
    .doc-date { margin: 1.8rem 0 1.5rem; }
    .doc-signature { margin-top: 2.4rem; text-align: center; }
    .signature-line { border-top: 1px solid #111827; display: inline-block; min-width: 260px; padding-top: 0.4rem; }
    .two-lines { margin-top: 1.6rem; }
    .two-lines .signature-line { display: block; margin-bottom: 1.2rem; min-width: 280px; }
    .doc-list { margin: 0.45rem 0 0.95rem; padding-left: 0; }
    .doc-list div { margin-bottom: 0.35rem; }
    .blank-block { border-bottom: 1px solid #111827; display: inline-block; min-height: 1.1em; min-width: 190px; vertical-align: baseline; }
    .blank-wide { min-width: 320px; }
    .blank-full { display: block; margin-top: 0.4rem; min-width: 100%; }
    .doc-multi-line { border-bottom: 1px solid #111827; display: block; height: 22px; margin-top: 0.45rem; width: 100%; }
    .doc-multi-line + .doc-multi-line { margin-top: 0.55rem; }
    .doc-compact { line-height: 1.65; }
</style>

@switch($payload['type'])
    @case('leitura_ptc')
        <h1 class="doc-title">{{ $payload['title'] }}</h1>
        <p class="doc-paragraph">
            Declaro para os devidos fins que foi lido o Programa Terapeutico do CR - CERAPE, sendo discriminado suas 4 (quatro) FASES, 1a. FASE 105 (cento e cinco dias) dias, 2a. FASE 60 (sessenta dias) dias, 3a. FASE 60 (sessenta dias) e 4a. FASE 45 (quarenta e cinco) dias, podendo estender-se a 140 (cento e quarenta) dias, caso o acolhido necessite de reinsercao ao mercado de trabalho. Lido tambem o programa semanal de horarios de atividades de todas as terapias com todas as rotinas do CRC descritos.
        </p>
        <p class="doc-paragraph">
            Por ser verdade, firmo a presente declaracao,
        </p>
        <p class="doc-date">{{ $payload['dateText'] }}</p>
        <div class="doc-signature">
            <span class="signature-line">&nbsp;</span>
        </div>
        @break

    @case('termo_desligamento')
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
        <div class="doc-signature">
            <span class="signature-line">Assinatura do acolhido</span>
        </div>
        <div class="two-lines">
            <p class="doc-paragraph"><strong>TESTEMUNHAS:</strong></p>
            <span class="signature-line">&nbsp;</span>
            <span class="signature-line">&nbsp;</span>
        </div>
        @break

    @case('uso_imagem')
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
            Por ser esta a expressão da minha vontade, declaro que autorizo o uso acima descrito sem que haja a ser reclamado a título de direitos conexos à minha imagem. Assino a presente autorização em 2 (duas) vias de igual teor e forma.
        </p>
        <p class="doc-date">{{ $payload['dateText'] }}</p>
        <div class="doc-signature">
            <span class="signature-line">Acolhido CPF n&ordm; {{ $payload['cpf'] }}</span>
        </div>
        @break

    @case('desistencia_ptc')
        <h1 class="doc-title">{{ $payload['title'] }}</h1>
        <p class="doc-paragraph">
            Declaro para os devidos fins que na ocorrencia da desistencia do Programa Terapeutico do CR - CERAPE, nao serao devolvidos doacoes de itens do acolhimento tais como material de higiene pessoal, camisetas do PTC para uso apenas durante o Programa e demais itens que sao de uso coletivo entregues no dia do acolhimento, sendo os mesmos para uso da Comunidade Terapeutica.
        </p>
        <p class="doc-paragraph">
            Por ser verdade, firmo a presente declaracao,
        </p>
        <p class="doc-date">{{ $payload['dateText'] }}</p>
        <div class="doc-signature">
            <span class="signature-line">Acolhido CPF n&ordm; {{ $payload['cpf'] }}</span>
        </div>
        @break

    @case('acolhimento_voluntario')
        <h1 class="doc-title">{{ $payload['title'] }}</h1>
        <p class="doc-paragraph">
            Declaro para os devidos fins que sou voluntario ao acolhimento no CENTRO DE REABILITACAO DO CERAPE - CRC, submetendo-me as normas e recomendacoes internas do CRC, bem como, sua Programacao Diaria, ciente tambem de que nao terei nenhum vinculo empregaticio, nem obrigacao de natureza trabalhista, previdenciaria e afins, em conformidade com a Lei 9.608, de fevereiro de 1998 e Lei n&ordm; 13.840 item II de 05/06/2020, enquanto durar minha permanencia no CRC.
        </p>
        <p class="doc-paragraph">
            Por ser expressao da verdade, assumindo inteira responsabilidade pelas declaracoes acima sob as penas da lei, assino a presente declaracao para que produza seus efeitos legais.
        </p>
        <p class="doc-date">{{ $payload['dateText'] }}</p>
        <div class="doc-signature">
            <span class="signature-line">Acolhido CPF n&ordm; {{ $payload['cpf'] }}</span>
        </div>
        @break

    @case('contrato_prevencao_recaida')
        <h1 class="doc-title">{{ $payload['title'] }}</h1>
        <p class="doc-paragraph">
            CONTRATADO: CENTRO DE REABILITACAO DO CERAPE - CRC, CNPJ n&ordm; 00.857.994/0002-48, estabelecido na Area Rural, Park Alvorada III, Fazenda Quintas, Chacara da Igreja Batista Rhema, Luzi&acirc;nia - GO.
        </p>
        <p class="doc-paragraph doc-compact">
            INTERVENTOR DO ACOLHIMENTO:<br>
            Nome: {{ $interventor['nome'] ?? '________________________________________________________________' }}, CPF {{ $interventor['cpf'] ?? '___________________' }}, RG: {{ $interventor['rg'] ?? '___________' }}, Exp. {{ $interventor['exp'] ?? '___________' }}, UF {{ $interventor['rgUf'] ?? '______' }}<br>
            Profissao: {{ $interventor['profissao'] ?? '______________' }} Data de Nascimento: {{ $interventor['dataNascimento'] ?? '__/__/____' }},<br>
            Residente: {{ $interventor['residente'] ?? '____________________________________________________________' }}, Complemento: {{ $interventor['complemento'] ?? '_____________________' }}, Bairro: {{ $interventor['bairro'] ?? '_________________________' }} Cidade: {{ $interventor['cidade'] ?? '_______________________________' }}, UF: {{ $interventor['uf'] ?? '______' }},<br>
            Tel: {{ $interventor['telefone'] ?? '___________________________' }},
        </p>
        <p class="doc-paragraph doc-compact">
            ACOLHIDO: {{ $acolhidoProfile['nome'] ?? '________________________________________________________' }} Data de Nascimento: {{ $acolhidoProfile['dataNascimento'] ?? '__/__/____' }}, CPF: {{ $acolhidoProfile['cpf'] ?? '___________________' }},<br>
            RG: {{ $acolhidoProfile['rg'] ?? '_______________________' }}, Profissao: {{ $acolhidoProfile['profissao'] ?? '_____________________________' }}, Naturalidade: {{ $acolhidoProfile['naturalidade'] ?? '___________________________________' }} UF {{ $acolhidoProfile['uf'] ?? '______' }}<br>
            Nacionalidade: {{ $acolhidoProfile['nacionalidade'] ?? '______________' }}.
        </p>
        <p class="doc-paragraph">
            CLAUSULA 1&ordf; - Propoe-se o servico de tratamento e acompanhamento de prevencao e reabilitacao de recaida, conforme as normas internas, rotina terapeutica e orientacoes institucionais do CERAPE / CRC.
        </p>
        <p class="doc-paragraph">
            CLÁUSULA 2&ordf; - O interventor declara estar ciente do funcionamento do programa, das condições de acompanhamento e da necessidade de cooperação com a equipe técnica durante o período de atendimento.
        </p>
        <p class="doc-paragraph">
            CLAUSULA 3&ordf; - O acolhido compromete-se a observar as regras internas, participar das atividades propostas e manter conduta compativel com o processo terapeutico.
        </p>
        <p class="doc-paragraph">
            E, por estarem de acordo, firmam o presente contrato para que produza seus efeitos.
        </p>
        <p class="doc-date">{{ $payload['signatureDateLine'] }}</p>
        <div class="two-lines">
            <span class="signature-line">Interventor do acolhimento</span>
            <span class="signature-line">Acolhido</span>
        </div>
        @break
@endswitch
