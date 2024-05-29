<?php

namespace App\Services;

use App\Models\ProxyCheck;
use App\Models\ProxyCheckResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\TransferStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ResponseInterface;

class ProxyCheckService
{
    private array $verifiedProxies = [];
    private float $startTime;
    const TIMEOUT = 30;

    public function __construct(private readonly Client $client)
    {
        $this->startTime = microtime(true);
    }

    public function check(array $proxies): array
    {
        $promises = [];
        foreach ($proxies as $proxy) {
            $proxy = trim($proxy);
            if (empty($proxy)) {
                continue;
            }

            $promises[] = $this->checkByProtocol($proxy, 'socks5');
            $promises[] = $this->checkByProtocol($proxy, 'https');
            $promises[] = $this->checkByProtocol($proxy, 'http');
        }
        Promise\Utils::settle($promises)->wait();

        return [
            'result'  => $this->saveResult(),
            'proxies' => $this->verifiedProxies,
        ];
    }

    private function checkByProtocol(string $proxy, string $protocol): PromiseInterface
    {
        return $this->client->getAsync('http://ip-api.com/json', [
            'proxy'    => "$protocol://$proxy",
            'timeout'  => self::TIMEOUT,
            'on_stats' => function (TransferStats $stats) use (&$results, $proxy, $protocol) {
                $result = [
                    'ip'          => explode(':', $proxy)[0],
                    'port'        => explode(':', $proxy)[1],
                    'type'        => null,
                    'country'     => null,
                    'city'        => null,
                    'is_working'  => false,
                    'speed'       => self::TIMEOUT * 1000,
                    'external_ip' => null,
                ];

                if ($stats->hasResponse()) {
                    $result['type']       = $protocol;
                    $result['is_working'] = true;
                    $result['speed']      = $stats->getTransferTime() * 1000; // скорость в миллисекундах
                }

                if (empty($this->verifiedProxies[$proxy]['is_working'])) {
                    $this->verifiedProxies[$proxy] = $result;
                }
            }
        ])->then(
            function (ResponseInterface $response) use ($proxy) {
                $data = json_decode($response->getBody());
                if ($data->status === 'success') {
                    $this->verifiedProxies[$proxy]['country']     = $data->country;
                    $this->verifiedProxies[$proxy]['city']        = $data->city;
                    $this->verifiedProxies[$proxy]['external_ip'] = $data->query;
                }
            },
            function (RequestException $e) use ($proxy) {
                if (empty($this->verifiedProxies[$proxy]['is_working'])) {
                    $this->verifiedProxies[$proxy]['is_working'] = false;
                }
            }
        );
    }

    private function saveResult(): Builder|Model
    {
        $duration = microtime(true) - $this->startTime;
        $checkResult = ProxyCheckResult::query()->create([
            'duration'        => round($duration, 3),
            'total_proxies'   => count($this->verifiedProxies),
            'working_proxies' => count(array_filter($this->verifiedProxies, fn($r) => $r['is_working'])),
        ]);

        foreach ($this->verifiedProxies as $proxy) {
            $proxy['proxy_check_result_id'] = $checkResult->id;
            ProxyCheck::create($proxy);
        }

        return $checkResult;
    }
}