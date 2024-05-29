<?php

namespace App\Http\Controllers;

use App\Models\ProxyCheck;
use App\Models\ProxyCheckResult;

class HistoryController extends Controller
{
    public function index()
    {
        $checkResults = ProxyCheckResult::query()->latest()->get();

        return view('history.index', compact('checkResults'));
    }

    public function show(ProxyCheckResult $proxyCheckResult)
    {
        $proxyChecks = $proxyCheckResult->proxyChecks;

        return view('history.show', compact('proxyCheckResult', 'proxyChecks'));
    }
}
