<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Factory;

use App\AppBundle\Entity\Feedback;

class FeedbackFactory
{
    public function create()
    {
        return new Feedback();
    }
}
