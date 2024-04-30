<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateContactController extends Controller
{
    public function __construct(
        private ContactService $service
    ) {
    }

    public function __invoke(UpdateContactRequest $request): JsonResponse
    {
        try {
            $data = $this->service->updateContact(
                $request->route('id'),
                $request->all()
            );
        } catch (Throwable $th) {
            Log::error(
                'error_update_contact_controller',
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
