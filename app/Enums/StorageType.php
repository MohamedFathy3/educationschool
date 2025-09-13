<?php

namespace App\Enums;

enum StorageType: string
{
    case SSD = 'ssd';
    case HDD = 'hdd';
    case HYBRID = 'hybrid';
    case EMMC = 'emmc';
    case NVME = 'nvme';
    case M2 = 'm2';
    case SATA = 'sata';
    case USB = 'usb';

    /**
     * Get all the storage type values as an array.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the display name for the storage type.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::SSD => 'Solid State Drive (SSD)',
            self::HDD => 'Hard Disk Drive (HDD)',
            self::HYBRID => 'Hybrid Drive',
            self::EMMC => 'Embedded MultiMediaCard (eMMC)',
            self::NVME => 'Non-Volatile Memory Express (NVMe)',
            self::M2 => 'M.2 Storage',
            self::SATA => 'Serial ATA (SATA)',
            self::USB => 'USB Storage',
        };
    }
}
