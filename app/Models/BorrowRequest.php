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
 * @property int $user_id
 * @property string $status
 * @property Carbon|null $borrow_date
 * @property Carbon|null $expected_return_date
 * @property Carbon|null $return_date
 * @property string|null $borrow_condition
 * @property string|null $return_condition
 * @property string|null $remarks
 * @property int|null $authorized_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'asset_id',
    'user_id',
    'status',
    'borrow_date',
    'expected_return_date',
    'return_date',
    'borrow_condition',
    'return_condition',
    'remarks',
    'authorized_by',
])]
class BorrowRequest extends Model
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
            'borrow_date' => 'datetime',
            'expected_return_date' => 'datetime',
            'return_date' => 'datetime',
        ];
    }

    /**
     * Get the asset requested for borrowing.
     *
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the member who requested the borrowing.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved/rejected the request.
     *
     * @return BelongsTo<User, $this>
     */
    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }
}
