<?php

namespace App\Services\PublicSections;

use App\Services\Core\BaseService;
use App\Models\PublicSections\Complaint;

class ComplaintService extends BaseService
{

    public function __construct()
    {
        $this->model = Complaint::class;
    }

    public function myComplaints(): array
    {
        return ['key' => 'success', 'data' => auth()->user()->complaints, 'msg' => __('apis.success')];
    }

    public function replay($id,$request): array
    {
        $complaint = $this->find($id);
        $complaint->replays()->create($request);
        return ['key' => 'success', 'msg' => __('apis.success')];
    }
}
