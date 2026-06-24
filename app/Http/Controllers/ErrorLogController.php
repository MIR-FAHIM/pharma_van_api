<?php

namespace App\Http\Controllers;

use App\Models\ProductCreateErrorLog;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    private function success($message, $data = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    private function failed($message, $errors = null, int $code = 400)
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * GET /error-logs/product-create
     * Optional query params: level, user_id, search, from, to, per_page
     */
    public function listProductCreateErrorLogs(Request $request)
    {
        try {
            $query = ProductCreateErrorLog::query()->with('user');

            if ($request->filled('level')) {
                $query->where('level', $request->query('level'));
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', (int) $request->query('user_id'));
            }

            if ($request->filled('search')) {
                $search = trim((string) $request->query('search'));
                $query->where(function ($q) use ($search) {
                    $like = '%' . $search . '%';

                    $q->where('message', 'like', $like)
                        ->orWhere('file', 'like', $like)
                        ->orWhere('url', 'like', $like)
                        ->orWhere('method', 'like', $like)
                        ->orWhere('ip_address', 'like', $like);
                });
            }

            if ($request->filled('from')) {
                $query->whereDate('created_at', '>=', $request->query('from'));
            }

            if ($request->filled('to')) {
                $query->whereDate('created_at', '<=', $request->query('to'));
            }

            $perPage = (int) $request->query('per_page', 20);
            if ($perPage <= 0) {
                $perPage = 20;
            }

            $logs = $query->latest('created_at')->paginate($perPage);

            return $this->success('Product create error logs fetched successfully', $logs);
        } catch (\Throwable $e) {
            return $this->failed('Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }
}
