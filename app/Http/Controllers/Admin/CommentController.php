<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-approved-comments')->only(['index']);
        $this->middleware('can:show-unapproved-comments')->only(['unapproved']);
        $this->middleware('can:edit-comment')->only(['edit', 'updateComment']);
        $this->middleware('can:approved-comment')->only(['update']);
        $this->middleware('can:delete-comment')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::query();

        if ($keyword = request('search')) {
            $comments->where('comment', 'LIKE', "%{$keyword}%")->orWhereHas('user', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })->orWhereHasMorph('commentable', 'App\Models\Product', function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%");
            });
        }

        $comments = $comments->whereApproved(1)->with('parent')->latest()->paginate(20);

        return view('admin.comments.all', compact('comments'));
    }

    public function unapproved()
    {
        $comments = Comment::whereApproved(0)->with('parent')->latest()->paginate(20);

        return view('admin.comments.unapproved', compact('comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function updateComment(Request $request, Comment $comment)
    {
        $validData = $request->validate([
            'comment' => 'required',
        ]);
        if (! $request->has('approve')) {
            $comment->approved = 0;
        }

        $comment->update($validData);

        Alert::success('نظر شما با موفقیت ویرایش شد');
        return redirect(route('admin.comments.index'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update(['approved' => 1]);

        Alert::success('نظر موردنظر تایید شد');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        Alert::success('نظر شما با موفقیت حذف شد');
        return back();
    }
}
