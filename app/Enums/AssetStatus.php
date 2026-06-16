<?php

namespace App\Enums;

enum AssetStatus: string
{
    case Available = 'available';
    case InUse = 'in_use';
    case UnderRepair = 'under_repair';
    case Disposed = 'disposed';

    /**
     * Get the human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available',
            self::InUse => 'In Use',
            self::UnderRepair => 'Under Repair',
            self::Disposed => 'Disposed',
        };
    }

    /**
     * Get all statuses as value/label pairs for frontend selects.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $status): array => ['value' => $status->value, 'label' => $status->label()],
            self::cases(),
        );
    }
}
