<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requests\TransactionRequest;
use App\Services\TransferService;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function transfer($id, TransactionRequest $request, TransferService $TransferService)
    {
        try {

            $TransferService->transfer(
                $id,
                $request->to,
                $request->amount,
                $request->details
            );

            return response()->json([
                'success' => 'true'
            ], Response::HTTP_CREATED);

        } catch (\Throwable $error) {

            return response()->json([
                'success' => 'false',
                'errors' => [
                    $error->getMessage()
                ]
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
