<?php
declare(strict_types = 1);

namespace MigrationBundle;

use MigrationBundle\DependencyInjection\MigrationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MigrationBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new MigrationExtension();
    }
}
