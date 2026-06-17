@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ $logoUrl }}" alt="Cerape Logo" style="height: 60px; width: auto;">
</div>

<h2 style="text-align: center; color: #333; margin: 20px 0;">🎂 Aniversariante do Dia</h2>

<p style="color: #555; font-size: 16px; line-height: 1.6; text-align: center;">
    Parabéns para <strong>{{ $acolhido->nome_completo_paciente ?? 'acolhido' }}</strong>! 🎉
</p>

<p style="color: #555; font-size: 15px; line-height: 1.6; text-align: center; margin: 20px 0;">
    Desejamos um dia especial, abencoado e cheio de alegria!
</p>

<div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; text-align: center;">
    <p style="margin: 5px 0; color: #333; font-weight: 500;">
        {{ $acolhido->data_nascimento->format('d \\d\\e F') }}
    </p>
</div>

@component('mail::button', ['url' => $profileUrl, 'color' => 'primary'])
    Acessar Perfil
@endcomponent

<p style="color: #777; font-size: 13px; margin-top: 30px; text-align: center;">
    Aproveite o dia para deixar uma mensagem de apoio e carinho.
</p>
@endcomponent
