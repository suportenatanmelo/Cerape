<?php

namespace App\Services;

use App\Models\Acolhido;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;

class AcolhidoLifecycleService
{
    public const STATUS_PRE_ACOLHIMENTO = 'pre_acolhimento';
    public const STATUS_ACOLHIDO = 'acolhido';
    public const STATUS_ALTA = 'alta';
    public const STATUS_TRANSFERIDO = 'transferido';
    public const STATUS_DESISTENTE = 'desistente';
    public const STATUS_FALECIDO = 'falecido';
    public const STATUS_EVASAO = 'evasao';

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PRE_ACOLHIMENTO => 'Pré-Acolhido',
            self::STATUS_ACOLHIDO => 'Acolhido',
            self::STATUS_ALTA => 'Alta',
            self::STATUS_TRANSFERIDO => 'Transferido',
            self::STATUS_DESISTENTE => 'Desistente',
            self::STATUS_FALECIDO => 'Falecido',
            self::STATUS_EVASAO => 'Evasão',
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function allowedTransitions(): array
    {
        return [
            self::STATUS_PRE_ACOLHIMENTO => [self::STATUS_ACOLHIDO],
            self::STATUS_ACOLHIDO => [self::STATUS_ALTA, self::STATUS_TRANSFERIDO, self::STATUS_DESISTENTE, self::STATUS_FALECIDO, self::STATUS_EVASAO],
            self::STATUS_ALTA => [self::STATUS_TRANSFERIDO, self::STATUS_DESISTENTE, self::STATUS_FALECIDO, self::STATUS_EVASAO],
            self::STATUS_TRANSFERIDO => [self::STATUS_ALTA, self::STATUS_DESISTENTE, self::STATUS_FALECIDO, self::STATUS_EVASAO],
            self::STATUS_DESISTENTE => [self::STATUS_ALTA, self::STATUS_TRANSFERIDO, self::STATUS_FALECIDO, self::STATUS_EVASAO],
            self::STATUS_FALECIDO => [],
            self::STATUS_EVASAO => [self::STATUS_ALTA, self::STATUS_TRANSFERIDO, self::STATUS_DESISTENTE, self::STATUS_FALECIDO],
        ];
    }

    public static function canTransition(string $currentStatus, string $nextStatus): bool
    {
        return in_array($nextStatus, self::allowedTransitions()[$currentStatus] ?? [], true);
    }

    /**
     * @return array<string, string>
     */
    public static function housingStatusOptions(): array
    {
        return [
            'casa_propria' => 'Casa Própria',
            'casa_alugada' => 'Casa Alugada',
            'casa_cedida' => 'Casa Cedida',
            'casa_financiada' => 'Casa Financiada',
            'morador_de_rua' => 'Morador de Rua',
            'outra' => 'Outras situações',
        ];
    }

    public static function isHousingStatusAllowedForYes(bool $possuiMoradia, string $status): bool
    {
        if ($possuiMoradia) {
            return ! in_array($status, ['morador_de_rua'], true);
        }

        return $status === 'morador_de_rua';
    }

    /**
     * @return array<string, string>
     */
    public static function statusDateFields(): array
    {
        return [
            self::STATUS_PRE_ACOLHIMENTO => 'data_pre_acolhimento',
            self::STATUS_ACOLHIDO => 'data_acolhimento',
            self::STATUS_ALTA => 'data_alta',
            self::STATUS_TRANSFERIDO => 'data_transferencia',
            self::STATUS_DESISTENTE => 'data_desistencia',
            self::STATUS_FALECIDO => 'data_falecimento',
            self::STATUS_EVASAO => 'data_evasao',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function validateAndNormalize(array $data, ?Acolhido $acolhido = null): array
    {
        $data = self::normalizeHousingData($data, $acolhido);
        $data = self::normalizeStatusData($data, $acolhido);

        $validator = ValidatorFacade::make($data, [
            'possui_moradia' => ['nullable', 'boolean'],
            'mora_aluguel' => ['nullable', 'boolean'],
            'situacao_habitacional' => ['nullable', 'string'],
            'status_acolhido' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalizeHousingData(array $data, ?Acolhido $acolhido = null): array
    {
        $hasHousingInput = array_key_exists('possui_moradia', $data)
            || array_key_exists('mora_aluguel', $data)
            || array_key_exists('situacao_habitacional', $data)
            || $acolhido?->exists;

        if (! $hasHousingInput) {
            return $data;
        }

        $possuiMoradia = (bool) ($data['possui_moradia'] ?? $acolhido?->possui_moradia ?? false);
        $moraAluguel = (bool) ($data['mora_aluguel'] ?? $acolhido?->mora_aluguel ?? false);
        $situacaoHabitacional = (string) ($data['situacao_habitacional'] ?? $acolhido?->situacao_habitacional ?? '');

        if (! $possuiMoradia) {
            if ($situacaoHabitacional !== 'morador_de_rua') {
                throw ValidationException::withMessages([
                    'situacao_habitacional' => ['Quando possui moradia = Não, a única situação permitida é Morador de Rua.'],
                ]);
            }

            $data['possui_moradia'] = false;
            $data['mora_aluguel'] = false;
            $data['situacao_habitacional'] = 'morador_de_rua';

            return $data;
        }

        $data['possui_moradia'] = true;

        if ($situacaoHabitacional === 'morador_de_rua') {
            throw ValidationException::withMessages([
                'situacao_habitacional' => ['Morador de Rua não é permitido quando possui moradia = Sim.'],
            ]);
        }

        if (! self::isHousingStatusAllowedForYes(true, $situacaoHabitacional)) {
            throw ValidationException::withMessages([
                'situacao_habitacional' => ['Situação habitacional inválida para a escolha de moradia.'],
            ]);
        }

        $data['mora_aluguel'] = $moraAluguel;
        $data['situacao_habitacional'] = $situacaoHabitacional;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalizeStatusData(array $data, ?Acolhido $acolhido = null): array
    {
        $status = self::normalizeStatus($data['status_acolhido'] ?? $data['status'] ?? $acolhido?->status_acolhido ?? null);
        $data['status_acolhido'] = $status;

        $currentStatus = blank($acolhido?->status_acolhido) ? null : self::normalizeStatus($acolhido?->status_acolhido);

        if ($currentStatus !== null && $currentStatus !== $status && ! self::canTransition($currentStatus, $status)) {
            throw ValidationException::withMessages([
                'status_acolhido' => ['Transição de status inválida para o fluxo atual do acolhido.'],
            ]);
        }
        $dateField = self::statusDateFields()[$status] ?? null;
        $allDateFields = array_values(self::statusDateFields());

        foreach ($allDateFields as $field) {
            if (! array_key_exists($field, $data) && blank($data[$field] ?? null)) {
                $existingDate = $acolhido?->getAttribute($field);

                if (! blank($existingDate)) {
                    $data[$field] = $existingDate;
                }
            }
        }

        if ($dateField && blank($data[$dateField] ?? null)) {
            $existingDate = $acolhido?->getAttribute($dateField);

            if (blank($existingDate)) {
                $data[$dateField] = now()->toDateString();
            } else {
                $data[$dateField] = $existingDate;
            }
        }

        if ($status !== $currentStatus && $currentStatus !== null && $dateField !== null) {
            $data['status_anterior'] = $currentStatus;
            $data['status_novo'] = $status;
            $data['status_data'] = now()->toDateTimeString();
            $data['status_usuario'] = auth()->id();
        }

        return $data;
    }

    public static function normalizeStatus(?string $value): string
    {
        $normalized = Str::of((string) ($value ?? ''))
            ->lower()
            ->ascii()
            ->trim()
            ->value();

        return match ($normalized) {
            'pre-acolhido', 'pre_acolhido', 'preacolhido' => self::STATUS_PRE_ACOLHIMENTO,
            'acolhido' => self::STATUS_ACOLHIDO,
            'alta' => self::STATUS_ALTA,
            'transferido' => self::STATUS_TRANSFERIDO,
            'desistente' => self::STATUS_DESISTENTE,
            'falecido' => self::STATUS_FALECIDO,
            'evasao', 'evasão' => self::STATUS_EVASAO,
            default => self::STATUS_PRE_ACOLHIMENTO,
        };
    }
}
