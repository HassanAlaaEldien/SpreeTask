<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewRequest;
use App\Http\Requests\UpdateNewRequest;
use App\Http\Responses\ResponsesInterface;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    protected $responder;

    /**
     * NewsController constructor.
     */
    public function __construct(ResponsesInterface $responder)
    {
        $this->middleware('auth')->except(['store', 'update']);
        $this->responder = $responder;
    }


    /**
     *  Handle the request for listing all news.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $news = News::all();

        return view('admin.news.index', compact('news'));
    }


    /**
     * Handle the request for creating news.
     *
     * @param CreateNewRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(CreateNewRequest $request)
    {
        Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') ? $approved = true : $approved = false;

        Auth::user()->news()->create(['new' => $request->new, 'approved' => $approved]);

        return $this->responder->respond(['success' => 'News created successfully!'], 'redirectBack');
    }

    /**
     * Handle the request for updating specific new.
     *
     * @param News $new
     * @param UpdateNewRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(News $new, UpdateNewRequest $request)
    {
        $new->update(['new' => $request->new]);

        return $this->responder->respond(['new' => $new], 'view', 'admin.news.update-view');
    }

    /**
     * Handle the request for toggling approval to specific new.
     *
     * @param News $new
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggleApproval(News $new)
    {
        $this->authorize('approveNews', News::class);

        $new->update(['approved' => $new->approved ? false : true]);

        return view('admin.news.update-view', compact('new'));
    }

    /**
     * Handle the request for deleting specific new.
     *
     * @param News $new
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(News $new)
    {
        $this->authorize('deleteNews', [News::class]);

        $new->delete();

        return response()->json(['success' => 'New deleted successfully!']);
    }
}
