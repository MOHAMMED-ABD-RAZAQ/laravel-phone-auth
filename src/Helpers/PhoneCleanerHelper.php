<?php

namespace Package\Auth\Helpers;

class PhoneCleanerHelper
{
    protected $phone;

    /**
     * Create a new phone cleaner instance.
     *
     * @param string $phone
     */
    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * Clean and format phone number.
     *
     * @return string|null
     */
    public function clean(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        // Remove leading zeros
        $phone = ltrim($phone, '0');

        // Check if phone number is valid
        if (strlen($phone) < 8 || strlen($phone) > 15) {
            return null;
        }

        return $phone;
    }

    /**
     * Format phone number for display.
     *
     * @return string
     */
    public function format(): string
    {
        $phone = $this->clean();

        if (!$phone) {
            return $this->phone;
        }

        // Add country code if not present
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Check if phone number is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->clean() !== null;
    }

    /**
     * Get phone number without country code.
     *
     * @return string|null
     */
    public function withoutCountryCode(): ?string
    {
        $phone = $this->clean();

        if (!$phone) {
            return null;
        }

        // Remove country code if present (assuming +1, +44, +91, etc.)
        if (strlen($phone) > 10) {
            $phone = substr($phone, -10);
        }

        return $phone;
    }
} 