<?php

namespace Package\Auth\Rules;

use Illuminate\Contracts\Validation\Rule;
use Package\Auth\Helpers\PhoneCleanerHelper;

class UniquePhoneNumber implements Rule
{
    protected $model;
    protected $ignoreId;

    /**
     * Create a new rule instance.
     *
     * @param string $model
     * @param int|null $ignoreId
     */
    public function __construct(string $model, ?int $ignoreId = null)
    {
        $this->model = $model;
        $this->ignoreId = $ignoreId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $phoneNumber = (new PhoneCleanerHelper($value))->clean();

        if (!$phoneNumber) {
            return false;
        }

        $query = $this->model::where(config('auth-package.phone_column', 'phone'), $phoneNumber);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        return !$query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('auth-package::auth.phone_already_registered');
    }
} 