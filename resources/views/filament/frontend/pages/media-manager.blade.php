<x-filament-panels::page>
    <div style="display:grid; gap:24px; font-family: Inter, sans-serif;">
        @foreach ($this->getMediaGroups() as $group => $files)
            <section style="border:1px solid #e2dbcb; border-radius:22px; padding:18px; background:rgba(255,255,255,.92); box-shadow:0 18px 36px rgba(30,61,54,.06);">
                <h2 style="margin:0 0 14px; font-size:18px; font-family:Fraunces, serif; color:#1e3d36;">{{ $group }}</h2>

                @if (blank($files))
                    <p style="margin:0; color:#6b6459;">Nenhum arquivo encontrado em {{ $group }}.</p>
                @else
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                        @foreach ($files as $file)
                            <article style="border:1px solid #e2dbcb; border-radius:16px; overflow:hidden; background:#faf7f2;">
                                <div style="aspect-ratio: 16 / 10; background:#f1ece2; display:flex; align-items:center; justify-content:center;">
                                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" style="width:100%; height:100%; object-fit:cover;">
                                </div>
                                <div style="padding:12px;">
                                    <div style="font-weight:700; font-size:13px; margin-bottom:6px; color:#1e3d36;">{{ $file['name'] }}</div>
                                    <div style="font-size:12px; color:#6b6459; margin-bottom:10px;">{{ $file['path'] }}</div>
                                    <div style="font-size:12px; color:#6b6459; margin-bottom:12px;">{{ number_format($file['size'] / 1024, 1) }} KB</div>
                                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                        <a href="{{ $file['url'] }}" target="_blank" style="padding:8px 10px; border-radius:999px; background:#1e3d36; color:white; font-size:12px;">Abrir</a>
                                        <button type="button" onclick="navigator.clipboard.writeText(@js($file['url']))" style="padding:8px 10px; border-radius:999px; background:#e2dbcb; color:#1e3d36; font-size:12px; border:0;">Copiar URL</button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach
    </div>
</x-filament-panels::page>
