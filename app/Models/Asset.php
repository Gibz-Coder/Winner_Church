<?php

namespace App\Models;

use App\Enums\AssetStatus;
use Database\Factories\AssetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $serial_number
 * @property string|null $model_number
 * @property string|null $brand
 * @property Carbon|null $purchase_date
 * @property string|null $cost
 * @property AssetStatus $status
 * @property string|null $current_location
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 */
#[Fillable([
    'category_id',
    'name',
    'serial_number',
    'model_number',
    'brand',
    'description',
    'purchase_date',
    'cost',
    'status',
    'current_location',
    'assigned_ministry',
    'image',
    'qr_code',
    'notes',
])]
class Asset extends Model
{
    /** @use HasFactory<AssetFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'cost' => 'decimal:2',
            'status' => AssetStatus::class,
        ];
    }

    /**
     * Get the category that owns the asset.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the activity logs for the asset.
     *
     * @return HasMany<AssetLog, $this>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AssetLog::class)->latest();
    }

    /**
     * Get the borrow requests for the asset.
     *
     * @return HasMany<BorrowRequest, $this>
     */
    public function borrowRequests(): HasMany
    {
        return $this->hasMany(BorrowRequest::class)->latest();
    }

    /**
     * Get the maintenance logs for the asset.
     *
     * @return HasMany<MaintenanceLog, $this>
     */
    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class)->latest();
    }
}
