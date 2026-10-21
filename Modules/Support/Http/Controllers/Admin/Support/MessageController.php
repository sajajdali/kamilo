<?php

namespace Modules\Support\Http\Controllers\Admin\Support;

use App\Base\AdminController;
use App\Base\Authorizable;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Support\Entities\Massage;
use Modules\Support\Entities\Message;

class MessageController extends AdminController
{
    use  Authorizable;

    /**
     * Display list message
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = array();
        $message = Message::paginate(10);
        return view('support::admin.message.index', compact('message'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        /** @var User $user */
        $user = User::findOrfail($request->uid);

        return view('support::admin.message.create', compact('user'));
    }

    /**
     * Store new message
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $agent = Auth::user();
        $messages = [
            'title.required' => 'کاربر مورد نظر یافت نشد',
            'content.required' => 'متن پیام خود را وارد کنید',
        ];
        $this->validate($request, [
            'user_id' => 'required',
            'content' => 'required',
        ], $messages);
        $message = new Message();
        $message->user_id = $request->user_id;
        $message->agent_id = $agent->id;
        $message->content = $request->content;
        $message->save();

        $request->session()->flash('successful', 'پیام با موفقیت ارسال شد.');
        return redirect(route('admin.support.message.index'));
    }

    /**
     * Remove message
     *
     * @param Request $request
     * @param Message $message
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Request $request, Message $message)
    {
        $message->delete();
        $request->session()->flash('successful', 'پیام با موفقیت حذف شد');
        return redirect(route('admin.support.message.index'));
    }
}
