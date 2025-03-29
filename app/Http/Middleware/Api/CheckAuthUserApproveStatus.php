<?php

namespace App\Http\Middleware\Api;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckAuthUserApproveStatus
{
  use ResponseTrait;

  public function handle(Request $request, Closure $next)
  {
    
    if (!auth()->user()->is_approved) {
      return $this->failMsg(__('auth.account_waiting_approve'));
    }

    return $next($request);
  }
}
