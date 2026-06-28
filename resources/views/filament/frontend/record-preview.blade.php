<div style="display: grid; gap: 12px; font-family: Inter, sans-serif;">
    @foreach ($record->toArray() as $key => $value)
        @continue(in_array($key, ['created_at', 'updated_at', 'deleted_at'], true) && blank($value))
        <div style="border-bottom: 1px solid #e2dbcb; padding-bottom: 8px;">
            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6b8e78; margin-bottom: 4px;">
                {{ str_replace('_', ' ', $key) }}
            </div>
            <div style="font-size: 13px; color: #2b2823;">
                @if (is_bool($value))
                    {{ $value ? 'Sim' : 'Não' }}
                @elseif (is_array($value))
                    {{ implode(', ', $value) }}
                @elseif (blank($value))
                    -
                @else
                    {{ $value }}
                @endif
            </div>
        </div>
    @endforeach
</div>
