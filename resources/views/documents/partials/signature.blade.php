@php($signatures = $signatures ?? [])
@if ($signatures !== [])
    <div class="document-signatures">
        @foreach ($signatures as $signature)
            <div class="document-signature">
                <div class="document-signature-line"></div>
                <strong>{{ $signature['name'] ?? '' }}</strong><br>
                <span class="document-note">{{ $signature['role'] ?? '' }}</span>
            </div>
        @endforeach
    </div>
@endif
