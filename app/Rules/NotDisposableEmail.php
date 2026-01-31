<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotDisposableEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! str_contains($value, '@')) {
            return;
        }

        $domain = strtolower((string) substr($value, strrpos($value, '@') + 1));
        $domains = array_map('strtolower', config('disposable_domains.domains', []));

        if (in_array($domain, $domains, true)) {
            $fail('Please use a permanent email address. Disposable or temporary email addresses are not allowed.');
        }
    }
}
