<?php

namespace App\Consumer;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public const MODE_ID = 'i';
    public const MODE_TITLE = 't';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $omdbClient)
    {
        $this->client = $omdbClient;
    }

    public function consume(string $type, string $value): array
    {
        if (!\in_array($type, [self::MODE_ID, self::MODE_TITLE])) {
            throw new \InvalidArgumentException();
        }

        return $this->client->request(
            'GET',
            '',
            [
                'query' => [
                    $type => $value,
                ],
            ]
        )->toArray();
    }
}