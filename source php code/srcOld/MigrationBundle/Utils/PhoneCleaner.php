<?php
declare(strict_types = 1);

namespace MigrationBundle\Utils;

class PhoneCleaner
{
    /**
     * @param string $phone
     * @return string
     */
    public function clean($phone)
    {
        $phone = str_replace('.', '', $phone);
        $phone = str_replace(' ', '', $phone);

        if (strlen($phone) < 10) {
            return '';
        }

        return $phone;
    }
}
