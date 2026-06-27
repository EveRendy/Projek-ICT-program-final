<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpuModel implements ValidationRule
{
    protected $brand;

    public function __construct($brand = '')
    {
        $this->brand = $brand;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Strict dictionary validation
        $jsonPath = public_path('data/cpu_models.json');
        if (file_exists($jsonPath)) {
            $cpuModels = json_decode(file_get_contents($jsonPath), true);
            if (is_array($cpuModels)) {
                $suffix = request('cpu_suffix');
                
                // Form 1: input + suffix
                $inputModelCombined = $value;
                if ($suffix && $suffix !== 'Polos') {
                    $inputModelCombined .= $suffix;
                }
                
                $normalizedInputCombined = strtolower(str_replace([' ', '-'], '', $inputModelCombined));
                $normalizedInputRaw = strtolower(str_replace([' ', '-'], '', $value));
                
                $isValid = false;
                foreach ($cpuModels as $model) {
                    $normalizedModel = strtolower(str_replace([' ', '-'], '', $model));
                    if ($normalizedInputCombined === $normalizedModel || $normalizedInputRaw === $normalizedModel) {
                        $isValid = true;
                        break;
                    }
                }
                
                if (!$isValid) {
                    $fail('Prosesor yang dipilih tidak terdaftar di database. Silakan pilih dari rekomendasi yang muncul saat Anda mengetik.');
                    return;
                }
            }
        }
    }
}
