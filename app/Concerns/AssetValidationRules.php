<?php

namespace App\Concerns;

use App\Enums\AssetStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

trait AssetValidationRules
{
    /**
     * Get the validation rules used to validate an asset.
     *
     * @return array<string, array<int, ValidationRule|Enum|array<mixed>|string>>
     */
    protected function assetRules(?int $assetId = null): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                $assetId === null
                    ? Rule::unique('assets', 'serial_number')
                    : Rule::unique('assets', 'serial_number')->ignore($assetId),
            ],
            'model_number' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date', 'before_or_equal:today'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::enum(AssetStatus::class)],
            'current_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
