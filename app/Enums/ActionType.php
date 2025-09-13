<?php

namespace App\Enums;

enum ActionType: string
{
        // Device Assignments and Ownership Changes
    case ASSIGN = 'assign';
    case REASSIGN = 'reassign';
    case UNASSIGN = 'unassign';
    case TEMPORARY_ASSIGN = 'temporary_assign';

        // Device Upgrades and Downgrades
    case UPGRADE_HARDWARE = 'upgrade_hardware';
    case DOWNGRADE_HARDWARE = 'downgrade_hardware';
    case UPGRADE_SOFTWARE = 'upgrade_software';
    case DOWNGRADE_SOFTWARE = 'downgrade_software';

        // Repairs and Maintenance
    case REPAIR_HARDWARE = 'repair_hardware';
    case REPAIR_SOFTWARE = 'repair_software';
    case CLEAN = 'clean';

        // Configuration Changes
    case RECONFIGURE = 'reconfigure';
    case CHANGE_NETWORK = 'change_network';
    case ADD_PERIPHERALS = 'add_peripherals';
    case REMOVE_PERIPHERALS = 'remove_peripherals';

        // Software and Licensing
    case INSTALL_SOFTWARE = 'install_software';
    case UNINSTALL_SOFTWARE = 'uninstall_software';
    case UPDATE_LICENSE = 'update_license';

        // Device Status
    case DECOMMISSION = 'decommission';
    case REACTIVATE = 'reactivate';
    case MARK_OFF_DUTY = 'mark_off_duty';
    case RETURN_TO_INVENTORY = 'return_to_inventory';

        // Troubleshooting and Support
    case TROUBLESHOOT = 'troubleshoot';
    case RESET_PASSWORD = 'reset_password';
    case REPLACE = 'replace';

        // Audits and Checks
    case AUDIT = 'audit';
    case BACKUP = 'backup';
    case RESTORE = 'restore';

        // Miscellaneous
    case LOAN = 'loan';
    case RETRIEVE = 'retrieve';
    case TAG = 'tag';

    /**
     * Get all the action type values as an array.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the display name for the action type.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            // Device Assignments and Ownership Changes
            self::ASSIGN => 'Assign',
            self::REASSIGN => 'Reassign',
            self::UNASSIGN => 'Unassign',
            self::TEMPORARY_ASSIGN => 'Temporary Assign',

            // Device Upgrades and Downgrades
            self::UPGRADE_HARDWARE => 'Upgrade Hardware',
            self::DOWNGRADE_HARDWARE => 'Downgrade Hardware',
            self::UPGRADE_SOFTWARE => 'Upgrade Software',
            self::DOWNGRADE_SOFTWARE => 'Downgrade Software',

            // Repairs and Maintenance
            self::REPAIR_HARDWARE => 'Repair Hardware',
            self::REPAIR_SOFTWARE => 'Repair Software',
            self::CLEAN => 'Clean',

            // Configuration Changes
            self::RECONFIGURE => 'Reconfigure',
            self::CHANGE_NETWORK => 'Change Network',
            self::ADD_PERIPHERALS => 'Add Peripherals',
            self::REMOVE_PERIPHERALS => 'Remove Peripherals',

            // Software and Licensing
            self::INSTALL_SOFTWARE => 'Install Software',
            self::UNINSTALL_SOFTWARE => 'Uninstall Software',
            self::UPDATE_LICENSE => 'Update License',

            // Device Status
            self::DECOMMISSION => 'Decommission',
            self::REACTIVATE => 'Reactivate',
            self::MARK_OFF_DUTY => 'Mark Off-Duty',
            self::RETURN_TO_INVENTORY => 'Return to Inventory',

            // Troubleshooting and Support
            self::TROUBLESHOOT => 'Troubleshoot',
            self::RESET_PASSWORD => 'Reset Password',
            self::REPLACE => 'Replace',

            // Audits and Checks
            self::AUDIT => 'Audit',
            self::BACKUP => 'Backup',
            self::RESTORE => 'Restore',

            // Miscellaneous
            self::LOAN => 'Loan',
            self::RETRIEVE => 'Retrieve',
            self::TAG => 'Tag',
        };
    }
}
