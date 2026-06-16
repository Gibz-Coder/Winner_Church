<?php

namespace App\Enums;

enum AssetLogAction: string
{
    case Created = 'created';
    case Updated = 'updated';
    case StatusChange = 'status_change';
    case CheckedOut = 'checked_out';
    case CheckedIn = 'checked_in';

    /**
     * Get the human-readable label for the action.
     */
    public function label(): string
    {
        return match ($this) {
            self::Created => 'Created',
            self::Updated => 'Updated',
            self::StatusChange => 'Status Changed',
            self::CheckedOut => 'Checked Out',
            self::CheckedIn => 'Checked In',
        };
    }
}
