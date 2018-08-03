<?php
declare(strict_types = 1);

namespace App\AppBundle\Services\Captcha;

interface CaptchaValidatorInterface
{
    public function validate(string $captcha, string $clientIp) : bool;
}
