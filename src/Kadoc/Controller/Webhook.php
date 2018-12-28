<?php

declare(strict_types=1);

namespace Kadoc\Controller;

use Symfony\Component\HttpFoundation\Response;

final class Webhook
{
    public function github()
    {
        return new Response('hello world');
    }
}
