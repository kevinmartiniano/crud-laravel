<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FindContactController extends Controller
{
    public function __construct(
        private ContactService $service
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->service->findContact($request->route('id'))->toArray()
        ], Response::HTTP_OK);
    }
}