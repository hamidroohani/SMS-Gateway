<?php

namespace App\Http\Controllers\Api;

use App\Http\Repositories\ProviderRepository;
use App\Http\Requests\Provider\ProviderStoreRequest;
use App\Http\Requests\Provider\ProviderUpdateRequest;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;

class ProvidersController extends ResponseController
{
    private ProviderRepository $providerRepository;

    public function __construct()
    {
        $this->providerRepository = new ProviderRepository(new Provider());
    }

    public function index(): JsonResponse
    {
        return $this->success($this->providerRepository->paginate_cache());
    }

    public function store(ProviderStoreRequest $request): JsonResponse
    {
        $data = $this->providerRepository->make_array_for_insert($request);
        $provider = $this->providerRepository->store($data);
        return $this->success($provider);
    }

    public function show($provider): JsonResponse
    {
        $provider = $this->providerRepository->show($provider);
        return $this->success($provider);
    }

    public function update(ProviderUpdateRequest $request, $provider): JsonResponse
    {
        $data = $this->providerRepository->make_array_for_insert($request);
        $provider = $this->providerRepository->update($provider, $data);
        return $this->success($provider);
    }

    public function delete($provider): JsonResponse
    {
        $delete = $this->providerRepository->delete($provider);
        return $this->success($delete);
    }
}
