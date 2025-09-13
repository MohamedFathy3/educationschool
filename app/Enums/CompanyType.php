<?php

namespace App\Enums;

enum CompanyType: string
{
    case HQ = 'hq';
    case BRANCH = 'branch';

    /**
     * Get all the role values as an array.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the display name for the role.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::HQ => 'HQ',
            self::BRANCH => 'Branch',
        };
    }
}
