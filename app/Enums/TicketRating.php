<?php

namespace App\Enums;

enum TicketRating: int
{
    case ONE = 1.0;
    case TWO = 2.0;
    case THREE = 3.0;
    case FOUR = 4.0;
    case FIVE = 5.0;

    /**
     * Get all the rating values as an array.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the display name for the rating.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ONE => '1 - Poor',
            self::TWO => '2 - Fair',
            self::THREE => '3 - Good',
            self::FOUR => '4 - Very Good',
            self::FIVE => '5 - Excellent',
        };
    }
}
