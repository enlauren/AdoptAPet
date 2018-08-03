<?php
declare(strict_types = 1);

namespace App\AppBundle\Services\Captcha;

// todo move this class elsewhere
use function var_dump;

class CaptchaFakeValidator implements CaptchaValidatorInterface
{
    public function validate(string $captcha, string $clientIp) : bool
    {
        return true;
    }
}
