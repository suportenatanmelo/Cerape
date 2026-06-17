@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ $logoUrl }}" alt="Cerape Logo" style="height: 60px; width: auto;">
</div>

<h2 style="text-align: center; color: #333; margin: 20px 0;">Status do Acolhido Alterado</h2>

<p style="color: #555; font-size: 16px; line-height: 1.6;">
    O status do acolhido <strong>{{ $acolhido->nome_completo_paciente ?? 'N/A' }}</strong> foi alterado.
</p>

<div style="background-color: #f0f9ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px; color: #111;">
    <p style="margin: 0; font-weight: 600;">Status anterior:</p>
    <p style="margin: 0 0 10px;">{{ $oldStatus ? 'Ativo' : 'Inativo' }}</p>
    <p style="margin: 0; font-weight: 600;">Novo status:</p>
    <p style="margin: 0;">{{ $newStatus ? 'Ativo' : 'Inativo' }}</p>
</div>

@component('mail::button', ['url' => $profileUrl, 'color' => 'primary'])
    Ver Perfil do Acolhido
@endcomponent

<p style="color: #777; font-size: 14px; margin-top: 30px;">
    Acesse o sistema para revisar as informações e o histórico do acolhido.
</p>
@endcomponent
