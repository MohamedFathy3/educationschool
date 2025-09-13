<?php

namespace App\Enums;

enum UserRole: string
{
    case EMPLOYEE = 'employee';
    case HELP_DESK = 'help_desk';
    case ADMIN = 'admin';

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
            self::EMPLOYEE => 'Employee',
            self::HELP_DESK => 'Help Desk',
            self::ADMIN => 'Administrator',
        };
    }
}
