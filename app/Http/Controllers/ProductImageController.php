<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
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
			'errors' => $errors
		], $code);
	}

	/**
	 * GET /products/images/{productId}
	 */
	public function getProductImages(Request $request, $productId)
	{
		$product = Product::find($productId);
		if (!$product) {
			return $this->failed('Product not found', null, 404);
		}

		$query = ProductImage::where('product_id', $productId)->with('upload');

		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}

		if ($request->filled('is_primary')) {
			$query->where('is_primary', (bool) $request->is_primary);
		}

		$items = $query
			->orderByDesc('is_primary')
			->orderBy('sort_order')
			->orderBy('id')
			->get();

		return $this->success('Product images retrieved successfully', [
			'count' => $items->count(),
			'items' => $items,
		]);
	}
}
