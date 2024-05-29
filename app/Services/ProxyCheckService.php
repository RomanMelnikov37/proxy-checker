<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;

class ProxyCheckService
{
    public function __construct(private readonly Client $client)
    {
    }

    public function check(array $proxies): array
    {
        $results  = [];
        $promises = [];

        foreach ($proxies as $proxy) {
            $proxy = trim($proxy);
            if (empty($proxy)) {
                continue;
            }

            $promises[] = $this->checkByProtocol($results, $proxy, 'socks5');
            $promises[] = $this->checkByProtocol($results, $proxy, 'https');
            $promises[] = $this->checkByProtocol($results, $proxy, 'http');
        }
        Promise\Utils::settle($promises)->wait();

        return $results;
    }

    private function checkByProtocol(array &$results, string $proxy, string $protocol): PromiseInterface
    {
        return $this->client->getAsync('http://ip-api.com/json', [
            'proxy'    => "$protocol://$proxy",
            'timeout'  => 30,
            'on_stats' => function (TransferStats $stats) use (&$results, $proxy, $protocol) {
                $result = [
                    'ip'          => explode(':', $proxy)[0],
                    'port'        => explode(':', $proxy)[1],
                    'type'        => null,
                    'country'     => null,
                    'city'        => null,
                    'is_working'  => false,
                    'speed'       => null,
                    'external_ip' => null,
                ];

                if ($stats->hasResponse()) {
                    $result['type']       = $protocol;
                    $result['is_working'] = true;
                    $result['speed']      = $stats->getTransferTime() * 1000; // скорость в миллисекундах
                }

                if (empty($results[$proxy]['is_working'])) {
                    $results[$proxy] = $result;
                }
            }
        ])->then(
            function (ResponseInterface $response) use (&$results, $proxy) {
                $data = json_decode($response->getBody());
                if ($data->status === 'success') {
                    $results[$proxy]['country']     = $data->country;
                    $results[$proxy]['city']        = $data->city;
                    $results[$proxy]['external_ip'] = $data->query;
                }
            },
            function (RequestException $e) use (&$results, $proxy) {
                if (empty($results[$proxy]['is_working'])) {
                    $results[$proxy]['is_working'] = false;
                }
            }
        );
    }
}