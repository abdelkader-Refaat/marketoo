<?php

namespace App\Http\Interfaces;

use App\Http\Requests\Admin\Country\StoreRequest;
interface BasicInterface
{
    public function store(StoreRequest $request);
}
