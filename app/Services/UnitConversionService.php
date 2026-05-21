<?php

namespace App\Services;

class UnitConversionService
{
    /**
     * Base units and their conversion factors to the smallest base unit.
     * All weights convert to GRAM, all volumes convert to MILILITER.
     */
    const UNIT_GROUPS = [
        'weight' => [
            'gram' => 1,
            'g' => 1,
            'kg' => 1000,
            'kilogram' => 1000,
            'ons' => 100,
            'hg' => 100,
        ],
        'volume' => [
            'ml' => 1,
            'mililiter' => 1,
            'liter' => 1000,
            'l' => 1000,
        ],
        'quantity' => [
            'buah' => 1,
            'pcs' => 1,
            'butir' => 1,
            'sachet' => 1,
            'bungkus' => 1,
            'ikat' => 1,
            'siung' => 1,
        ],
    ];

    /**
     * Convert a value from one unit to another.
     *
     * @param float $value The value to convert
     * @param string $fromUnit The source unit (e.g., 'kg', 'gram', 'liter')
     * @param string $toUnit The target unit (e.g., 'gram', 'ml')
     * @return float The converted value
     * @throws \InvalidArgumentException
     */
    public function convert(float $value, string $fromUnit, string $toUnit): float
    {
        $fromUnit = strtolower(trim($fromUnit));
        $toUnit = strtolower(trim($toUnit));

        if ($fromUnit === $toUnit) {
            return $value;
        }

        $fromGroup = $this->getUnitGroup($fromUnit);
        $toGroup = $this->getUnitGroup($toUnit);

        if ($fromGroup !== $toGroup) {
            throw new \InvalidArgumentException(
                "Cannot convert from '{$fromUnit}' ({$fromGroup}) to '{$toUnit}' ({$toGroup}). Incompatible unit groups."
            );
        }

        $fromFactor = $this->getFactor($fromUnit, $fromGroup);
        $toFactor = $this->getFactor($toUnit, $toGroup);

        // Convert to base unit, then to target unit
        $baseValue = $value * $fromFactor;
        $convertedValue = $baseValue / $toFactor;

        return $convertedValue;
    }

    /**
     * Calculate how many portions can be made from available stock.
     *
     * @param float $stockQty Available stock quantity
     * @param string $stockUnit Stock unit (e.g., 'kg', 'gram')
     * @param float $neededPerPortion Quantity needed per portion
     * @param string $neededUnit Unit of the needed quantity
     * @return int Maximum number of portions (floored)
     */
    public function calculatePortions(
        float $stockQty,
        string $stockUnit,
        float $neededPerPortion,
        string $neededUnit
    ): int {
        // Convert stock to the same unit as the need
        $stockInNeededUnit = $this->convert($stockQty, $stockUnit, $neededUnit);

        if ($neededPerPortion <= 0 || $stockInNeededUnit <= 0) {
            return 0;
        }

        return (int) floor($stockInNeededUnit / $neededPerPortion);
    }

    /**
     * Normalize a unit string to a standard form.
     *
     * @param string $unit
     * @return string
     */
    public function normalizeUnit(string $unit): string
    {
        $unit = strtolower(trim($unit));
        $map = [
            'g' => 'gram',
            'kg' => 'kg',
            'kilogram' => 'kg',
            'ons' => 'ons',
            'hg' => 'hg',
            'ml' => 'ml',
            'mililiter' => 'ml',
            'liter' => 'liter',
            'l' => 'liter',
            'buah' => 'buah',
            'pcs' => 'pcs',
            'butir' => 'butir',
            'sachet' => 'sachet',
            'bungkus' => 'bungkus',
            'ikat' => 'ikat',
            'siung' => 'siung',
        ];

        return $map[$unit] ?? $unit;
    }

    /**
     * Format a quantity with appropriate unit.
     *
     * @param float $quantity
     * @param string $unit
     * @return string
     */
    public function formatQuantity(float $quantity, string $unit): string
    {
        $unit = $this->normalizeUnit($unit);

        // Auto-convert large numbers to larger units for display
        if ($unit === 'gram' && $quantity >= 1000) {
            return number_format($quantity / 1000, 2, ',', '.') . ' kg';
        }

        if ($unit === 'ml' && $quantity >= 1000) {
            return number_format($quantity / 1000, 2, ',', '.') . ' liter';
        }

        if (floor($quantity) === $quantity) {
            return number_format($quantity, 0, ',', '.') . ' ' . $unit;
        }

        return number_format($quantity, 2, ',', '.') . ' ' . $unit;
    }

    /**
     * Check if two units are compatible (can be converted).
     *
     * @param string $unit1
     * @param string $unit2
     * @return bool
     */
    public function areUnitsCompatible(string $unit1, string $unit2): bool
    {
        try {
            $group1 = $this->getUnitGroup($unit1);
            $group2 = $this->getUnitGroup($unit2);
            return $group1 === $group2;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Get the unit group for a given unit.
     */
    private function getUnitGroup(string $unit): string
    {
        foreach (self::UNIT_GROUPS as $group => $units) {
            if (array_key_exists($unit, $units)) {
                return $group;
            }
        }

        throw new \InvalidArgumentException("Unknown unit: '{$unit}'");
    }

    /**
     * Get the conversion factor for a unit within its group.
     */
    private function getFactor(string $unit, string $group): float
    {
        return self::UNIT_GROUPS[$group][$unit];
    }
}
