<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Responses\ResponsesInterface;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    protected $responder;

    /**
     * NewsController constructor.
     */
    public function __construct(ResponsesInterface $responder)
    {
        $this->middleware('auth')->except(['getApprovedComments', 'store', 'update']);
        $this->responder = $responder;
    }


    /**
     *  Handle the request for listing all comments.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(News $new)
    {
        $this->authorize('listComments', Comment::class);

        $comments = $new->comments;

        return view('admin.comments.index', compact('comments', 'new'));
    }

    /**
     * Handle the request for creating comment.
     *
     * @param CreateCommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(News $new, CreateCommentRequest $request)
    {
        Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') ? $approved = true : $approved = false;

        Auth::user()->comments()->create(['comment' => $request->comment, 'news_id' => $new->id, 'approved' => $approved]);

        return $this->responder->respond(['success' => 'Comment created successfully!'], 'redirectBack');
    }

    /**
     * Handle the request for updating specific comment.
     *
     * @param News $new
     * @param Comment $comment
     * @param UpdateCommentRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(News $new, Comment $comment, UpdateCommentRequest $request)
    {
        $comment->update(['comment' => $request->comment]);

        return $this->responder->respond(['comment' => $comment], 'view', 'admin.comments.update-view');
    }

    /**
     * Handle the request for toggling approval to specific comment.
     *
     * @param News $new
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggleApproval(News $new, Comment $comment)
    {
        $this->authorize('approveComment', Comment::class);

        $comment->update(['approved' => $comment->approved ? false : true]);

        return view('admin.comments.update-view', compact('comment'));
    }

    /**
     * Handle the request for deleting specific comment.
     *
     * @param News $new
     * @param Comment $comment
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(News $new, Comment $comment)
    {
        $this->authorize('deleteComment', Comment::class);

        $comment->delete();

        return response()->json(['success' => 'Comment deleted successfully!']);
    }

    /**
     *  Handle the request for listing all approved comments.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getApprovedComments(News $new)
    {
        $this->authorize('getApprovedComments', Comment::class);

        $comments = $new->comments()->where('approved', true)->get();

        return $this->responder->respond(['comments' => $comments], 'view', 'admin.list-comments');
    }
}
