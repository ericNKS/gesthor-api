<?php

namespace App\Utils;

class CnpjUtils
{
    private string $cnpj;

    public function __construct(string $cnpj)
    {
        $this->cnpj = $this->validate($cnpj);
    }

    public function toString(): string
    {
        return $this->cnpj;
    }

    public function toStringFormated(): string
    {
        return sprintf(
            "%s.%s.%s/%s-%s",
            substr($this->cnpj, 0, 2),
            substr($this->cnpj, 2, 3),
            substr($this->cnpj, 5, 3),
            substr($this->cnpj, 8, 4),
            substr($this->cnpj, 12, 2)
        );
    }

    private function validate(string $cnpj): string
    {
        // Remove any non-digit character
        $cleaned = preg_replace('/[^0-9]/', '', $cnpj);

        // Check if it has 14 digits after cleaning
        if (strlen($cleaned) !== 14) {
            throw new \InvalidArgumentException('CNPJ must have 14 digits');
        }

        return $cleaned;
    }

    /**
     * Optional: Add a method to validate the check digits
     */
    public function isValid(): bool
    {
        // Check for known invalid sequences
        if (preg_match('/^(\d)\1{13}$/', $this->cnpj)) {
            return false;
        }

        // Validate first check digit
        $sum = 0;
        $weight = 5;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$this->cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }
        $mod = $sum % 11;
        $digit1 = ($mod < 2) ? 0 : 11 - $mod;

        // Validate second check digit
        $sum = 0;
        $weight = 6;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int)$this->cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }
        $mod = $sum % 11;
        $digit2 = ($mod < 2) ? 0 : 11 - $mod;

        // Check if calculated check digits match the provided ones
        return ((int)$this->cnpj[12] == $digit1 && (int)$this->cnpj[13] == $digit2);
    }
}