@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ $logoUrl }}" alt="Cerape Logo" style="height: 60px; width: auto;">
</div>

<h2 style="text-align: center; color: #333; margin: 20px 0;">Acolhido Excluído</h2>

<p style="color: #555; font-size: 16px; line-height: 1.6;">
    O registro do acolhido <strong>{{ $acolhido->nome_completo_paciente ?? 'N/A' }}</strong> foi removido do sistema.
</p>

<p style="color: #777; font-size: 14px; margin-top: 30px;">
    Confira o histórico de exclusão e os próximos passos no painel administrativo.
</p>
@endcomponent
