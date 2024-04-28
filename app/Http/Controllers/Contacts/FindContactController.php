<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\FindContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class FindContactController extends Controller
{
    public function __construct(
        private ContactService $service
    )
    {
    }

    public function __invoke(FindContactRequest $request): JsonResponse
    {
        try {
            $contact = $this->service->findContact($request->route('id'));
        } catch (Throwable $th) {
            Log::error(
                'error_find_contact_controller',
                [
                    'message' => $th->getMessage(),
                    'stack_trace' => $th->getTraceAsString(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                ]
            );

            return new JsonResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($contact instanceof Contact) {
            return new JsonResponse(
                [
                    'data' => $contact->toArray(),
                ],
                Response::HTTP_OK
            );
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}