<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case PENDING = 'pending';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case TRANSFERED = 'transfered';
    case POSTPONED = 'postponed';

    /**
     * Get all the status values as an array.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the display name for the status.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::PENDING => 'Pending',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
            self::TRANSFERED => 'transfered',
            self::POSTPONED => 'postponed',
        };
    }
}
