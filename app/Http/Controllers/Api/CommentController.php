<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'comment' => 'required|string',
            'parent_id' => 'nullable|integer',
        ]);

        // product id foe clear cach
        $product = Product::where('id', $validated['commentable_id'])->first();

        $modelClass = $validated['commentable_type'];

        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Invalid commentable type'], 422);
        }

        $commentable = $modelClass::find($validated['commentable_id']);
        if (!$commentable) {
            return response()->json(['error' => 'Commentable not found'], 404);
        }

        $comment = $commentable->comments()->create([
            'user_id' =>  $validated['user_id'],
            'comment' => $validated['comment'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        Cache::forget("product_single_page_{$product->slug}");

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user', 'child')
        ]);
    }
}
