<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $asset_id
 * @property string $maintenance_type
 * @property string $description
 * @property string|null $cost
 * @property Carbon $start_date
 * @property Carbon|null $end_date
 * @property string $status
 * @property string|null $performed_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'asset_id',
    'maintenance_type',
    'description',
    'cost',
    'start_date',
    'end_date',
    'status',
    'performed_by',
])]
class MaintenanceLog extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    /**
     * Get the asset under maintenance.
     *
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
