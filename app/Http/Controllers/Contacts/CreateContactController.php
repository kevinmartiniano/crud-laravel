<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateContactController extends Controller
{
    public function __construct(
        private ContactService $service
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = $this->service->createContact($request->all());
        } catch (Throwable $th) {
            Log::error(
                'error_create_contact_controller',
                [
                    'message' => $th->getMessage(),
                    'stack_trace' => $th->getTraceAsString(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                ]
            );

            return new JsonResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(
            [
                'data' => $data,
            ],
            Response::HTTP_CREATED
        );
    }
}