<?php

namespace App\Http\Controllers\Contacts;

use App\DTO\FilterListContactDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class ListContactController extends Controller
{
    public function __construct(
        private ContactService $service
    )
    {
    }

    public function __invoke(ListContactRequest $request): JsonResponse
    {
        $filter = new FilterListContactDTO(
            $request->get('id'),
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('company'),
            $request->get('phone_number'),
            $request->get('mobile_number'),
            $request->get('email')
        );

        $hidratedFilters = array_filter($filter->toArray(), function ($value) {
            return !is_null($value) ? true : false;
        }, ARRAY_FILTER_USE_BOTH);

        try {
            $data = $this->service->findByArgs($hidratedFilters);
        } catch (Throwable $th) {
            Log::error(
                'error_list_contact_controller',
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
            Response::HTTP_OK
        );
    }
}