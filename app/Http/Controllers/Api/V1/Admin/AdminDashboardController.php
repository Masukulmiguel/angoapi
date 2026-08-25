<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiRequest;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalClients = User::count();
        $activeClients = User::where("status", User::STATUS_ACTIVE)->count();
        $pendingAccounts = User::where("status", User::STATUS_PENDING)->count();
        $pendingPayments = Payment::where("status", Payment::STATUS_PENDING)->count();
        $activeSubscriptions = Subscription::active()->count();
        $totalRequests = ApiRequest::count();
        $errors = ApiRequest::where("status_code", ">=", 400)->count();

        $annualRevenue = Payment::where("status", Payment::STATUS_APPROVED)
            ->whereYear("approved_at", now()->year)
            ->sum("amount");

        return response()->json([
            "total_clients" => $totalClients,
            "active_clients" => $activeClients,
            "pending_accounts" => $pendingAccounts,
            "pending_payments" => $pendingPayments,
            "active_subscriptions" => $activeSubscriptions,
            "annual_revenue" => $annualRevenue,
            "total_requests" => $totalRequests,
            "errors" => $errors,
        ]);
    }
}

