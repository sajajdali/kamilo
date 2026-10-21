<?php

namespace Modules\Support\Http\Controllers\Admin\Support;

use App\Base\AdminController;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Support\Entities\SupportQuestion;
use Modules\Support\Entities\TicketDetail;

class QuestionController extends AdminController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        global $paginate_count;
        
        $title = 'سوالات پرتکرار';
        
        /** @var SupportQuestion $questions */
        $questions = SupportQuestion::where(function ($query) use ($request) {
            if ($request->act == 'search') {
                if ($request->title != null) {
                    $query->where('title', 'like', '%' . $request->title . '%');
                }
                //serach by sender name
                if ($request->input('name') != null) {
                    $query->whereHas('user', function ($q) use ($request) {
                        $q->whereHas('userMetas', function ($qq) use ($request) {
                            $qq->where('meta_key', UserMeta::FIRST_NAME)
                                ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%')
                                ->orwhere('meta_key', UserMeta::LAST_NAME)
                                ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%');
                        });
                    });
                }
            }
        })->paginate($paginate_count);
    
        return view('support::admin.questions.index', compact('title', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $title = 'افزودن سوال جدید';
    
        $ticket = [];
        /** @var TicketDetail $ticketDetail */
        $ticketDetail = TicketDetail::find($request->ticketDetail);
        if ($ticketDetail != null) {
            $ticket['title'] = $ticketDetail->ticket->title;
            if ($ticketDetail->type == TicketDetail::TYPE_QUESTION) {
                $ticket['question'] = $ticketDetail->message;
                $ticket['answer'] = $ticketDetail->children()->exists() ? $ticketDetail->children()->first()->message : '' ;
            }
            if ($ticketDetail->type == TicketDetail::TYPE_ANSWER) {
                $ticket['question'] = $ticketDetail->parent()->exists() ? $ticketDetail->parent->message : '';
                $ticket['answer'] = $ticketDetail->message;
            }
        }
        
        return view('support::admin.questions.create', compact('title', 'ticket'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $messages = [
            'title.required' => 'عنوان سوال را وارد کنید.',
            'question.required' => 'متن سوال را وارد کنید.',
            'answer.required' => 'متن جواب را وارد کنید.',
        ];
        $this->validate($request, [
            'title' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ], $messages);
        
        /** @var User $user */
        $user = Auth::user();
        
        $question = new SupportQuestion();
        $question->title = $request->title;
        $question->question = $request->question;
        $question->answer = $request->answer;
        $user->supportQuestions()->save($question);
        
        $request->session()->flash('successful', 'سوال جدید با موفقیت ثبت شد.');
        return redirect(route('admin.support.questions.index'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(SupportQuestion $question)
    {
        $title = 'ویرایش سوال';
    
        return view('support::admin.questions.edit', compact('title', 'question'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, SupportQuestion $question)
    {
        $messages = [
            'title.required' => 'عنوان سوال را وارد کنید.',
            'question.required' => 'متن سوال را وارد کنید.',
            'answer.required' => 'متن جواب را وارد کنید.',
        ];
        $this->validate($request, [
            'title' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ], $messages);
    
        $question->title = $request->title;
        $question->question = $request->question;
        $question->answer = $request->answer;
        $question->save();
    
        $request->session()->flash('successful', 'سوال و جواب مربوطه با موفقیت ویرایش شد.');
        return redirect(route('admin.support.questions.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request         $request
     * @param SupportQuestion $question
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Request $request, SupportQuestion $question)
    {
        $question->delete();
    
        $request->session()->flash('successful', 'سوال و جواب مربوطه با موفقیت حذف شد.');
        return redirect(route('admin.support.questions.index'));
    }
}
