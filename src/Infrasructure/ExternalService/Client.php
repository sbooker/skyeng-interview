<?php

declare(strict_types=1);

namespace Infrasructure\ExternalService;

interface Client
{
    /**
     * @throws ClientError
     */
    public function get(Request $request): Response;
}