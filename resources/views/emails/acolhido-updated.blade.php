@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ $logoUrl }}" alt="Cerape Logo" style="height: 60px; width: auto;">
</div>

<h2 style="text-align: center; color: #333; margin: 20px 0;">Acolhido Atualizado</h2>

<p style="color: #555; font-size: 16px; line-height: 1.6;">
    O cadastro do acolhido <strong>{{ $acolhido->nome_completo_paciente ?? 'N/A' }}</strong> foi atualizado.
</p>

@if(count($changes) > 0)
<div style="background-color: #f5f5f5; border-left: 4px solid #009688; padding: 15px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0 0 10px; font-weight: 600; color: #333;">Campos alterados:</p>
    <ul style="margin: 0; padding-left: 20px; color: #333;">
        @foreach($changes as $field => $value)
            <li>{{ ucfirst(str_replace('_', ' ', $field)) }}: {{ is_bool($value) ? ($value ? 'Ativo' : 'Inativo') : $value }}</li>
        @endforeach
    </ul>
</div>
@endif

@component('mail::button', ['url' => $profileUrl, 'color' => 'primary'])
    Ver Perfil do Acolhido
@endcomponent

<p style="color: #777; font-size: 14px; margin-top: 30px;">
    Acesse o sistema para revisar a atualização.
</p>
@endcomponent
