<?php

namespace app\Http\Controllers\Api\V1\General;

use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\General\Complaints\StoreComplaintRequest;
use App\Services\ComplaintService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

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
