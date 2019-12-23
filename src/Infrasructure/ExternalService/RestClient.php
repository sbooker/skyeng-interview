<?php

declare(strict_types=1);

namespace Infrasructure\ExternalService;

use Psr\Log\LoggerInterface;

final class RestClient implements Client
{
    /** @var string */
    private $host;

    /** @var string */
    private $user;

    /** @var string */
    private $password;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(string $host, string $user, string $password, LoggerInterface $logger)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function get(Request $request): Response
    {
        try {
            // returns a response from external service
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());

            throw new ClientError($e->getMessage(), 0, $e);
        }
    }
}