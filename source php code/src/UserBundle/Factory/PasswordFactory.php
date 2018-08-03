<?php
declare(strict_types = 1);

namespace App\UserBundle\Factory;

class PasswordFactory
{
    const DEFAULT_LENGTH = 8;

    /**
     * @return string
     */
    public function generate(): string
    {
        return $this->generateRandomString();
    }

    /**
     * @return string
     */
    private function generateRandomString()
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';

        for ($i = 0; $i < self::DEFAULT_LENGTH; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
