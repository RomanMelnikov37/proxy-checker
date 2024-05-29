<?php

namespace App\Http\Controllers;

use App\Services\ProxyCheckService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ProxyCheckController extends Controller
{
    public function __construct(private readonly ProxyCheckService $service)
    {
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('proxy_check.index');
    }

    public function check(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $proxies = explode("\n", trim($request->input('proxies')));
        $data = $this->service->check($proxies);

        return view('proxy_check.result', [
            'results' => $data['proxies'],
            'total'   => $data['result']->total_proxies,
            'working' => $data['result']->working_proxies,
            'duration' => $data['result']->duration,
        ]);
    }
}
