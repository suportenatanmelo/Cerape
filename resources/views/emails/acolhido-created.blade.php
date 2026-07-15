@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ $logoUrl }}" alt="Cerape Logo" style="height: 60px; width: auto;">
</div>

<h2 style="text-align: center; color: #333; margin: 20px 0;">Novo Acolhido Registrado</h2>

<p style="color: #555; font-size: 16px; line-height: 1.6;">
    Um novo acolhido foi registrado no sistema Cerape:
</p>

<div style="background-color: #f5f5f5; border-left: 4px solid #009688; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 5px 0; color: #333;">
        <strong>Nome:</strong> {{ $acolhido->nome_completo_paciente ?? 'N/A' }}
    </p>
    <p style="margin: 5px 0; color: #333;">
        <strong>Data de Cadastro:</strong> {{ $acolhido->created_at->format('d/m/Y H:i') }}
    </p>
    @if($acolhido->data_nascimento)
        <p style="margin: 5px 0; color: #333;">
            <strong>Data de Nascimento:</strong> {{ $acolhido->data_nascimento->format('d/m/Y') }}
        </p>
    @endif
</div>

@component('mail::button', ['url' => $profileUrl, 'color' => 'primary'])
    Ver Perfil do Acolhido
@endcomponent

<p style="color: #777; font-size: 14px; margin-top: 30px;">
    Acesse o sistema para visualizar mais informações e atualizações sobre o acolhido.
</p>
@endcomponent
