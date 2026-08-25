<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->with('apiProducts')
            ->orderBy('sort_order')
            ->get();

        return PlanResource::collection($plans);
    }

    public function show(Plan $plan)
    {
        $plan->load('apiProducts');

        return new PlanResource($plan);
    }
}
