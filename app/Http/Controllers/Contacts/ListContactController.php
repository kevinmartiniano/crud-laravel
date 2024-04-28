<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListContactController extends Controller
{
    public function __construct(
        private ContactService $service
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $args = $request->all() !== [] ? $request->all() : null;

        return new JsonResponse([
            'data' => $this->service->findByArgs($args)->toArray()
        ], Response::HTTP_OK);
    }
}