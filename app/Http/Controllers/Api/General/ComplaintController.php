<?php

namespace App\Http\Controllers\Api\General;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Services\ComplaintService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\General\Complaints\StoreComplaintRequest;

class ComplaintController extends Controller
{
    use ResponseTrait;

    public function __construct(private ComplaintService $complaintService) {}

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $data = $this->complaintService->create(request: $request->all());
        return $this->successMsg($data['msg']);
    }
}
