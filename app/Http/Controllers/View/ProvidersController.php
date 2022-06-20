<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Api\ResponseController;
use App\Http\Repositories\ProviderRepository;
use App\Models\Provider;
use Illuminate\Contracts\View\View;

class ProvidersController extends ResponseController
{
    private ProviderRepository $providerRepository;

    public function __construct()
    {
        $this->providerRepository = new ProviderRepository(new Provider());
    }

    public function index(): view
    {
        $providers = $this->providerRepository->paginate_cache();
        return view('pages.providers', compact('providers'));
    }
}
