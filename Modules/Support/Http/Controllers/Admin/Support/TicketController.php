<?php

namespace Modules\Support\Http\Controllers\Admin\Support;

use App\User;
use App\Base\AdminController;
use App\UserMeta;
use Modules\Support\Entities\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\Support\Entities\TicketDetail;
use Modules\Support\Entities\TicketDetailMeta;

class TicketController extends AdminController
{
    public function index(Request $request)
    {
        global $paginate_count;
        // Statistics
        $statistics = array();
        $title = 'تیکت ها';
        $user = Auth::user();

        if ($request->input('act') != null) {

            //search in tickets
            $tickets = Ticket::where(function ($query) use ($request, $user) {
                
                if (!$user->can('admin_all_support_ticket_access')) {
                    $query->whereAdviserId($user->id);
                }
                
                //search by code
                if ($request->input('code') != null) {
                    $query->where('code', $request->input('code'));
                }
                
                //search by type
                if ($request->input('type') != 0) {
                    $query->where('type', $request->input('type'));
                }
                
                //serach by date
                if ($request->input('start_at') != null) {
                    $from = explode('/', $request->input('start_at'));
                    $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                    $from = $from . ' 00:00:00';
                    $query->where('created_at', '>=', $from);
                }
                
                if ($request->input('end_at') != null) {
                    $to = explode('/', $request->input('end_at'));
                    $to = jalali_to_gregorian($to[0], $to[1], $to[2], '-');
                    $to = $to . ' 00:00:00';
                    $query->where('created_at', '<=', $to);
                }
                
                //serach by ticket status
                if ($request->input('status') == Ticket::STATUS_WAITING) {
                    $query->where('status', Ticket::STATUS_WAITING);
                } elseif ($request->input('status') == Ticket::STATUS_ANSWERED) {
                    $query->where('status', Ticket::STATUS_ANSWERED);
                } elseif ($request->input('status') == Ticket::STATUS_CLOSED) {
                    $query->where('status', Ticket::STATUS_CLOSED);
                }
            })->whereHas('user', function ($query) use ($request) {
                //serach by sender name
                if ($request->input('name') != null) {
                    $query->whereHas('userMetas', function ($q) use ($request) {
                        $q->where('meta_key', UserMeta::FIRST_NAME)
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%')
                            ->orwhere('meta_key', UserMeta::LAST_NAME)
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%');
                    });
                }
            })->with(array('user' => function ($query) use ($user, $request) {
                //search by sender name
                if ($request->input('name') != null) {
                    $query->whereHas('userMetas', function ($q) use ($request) {
                        $q->where('meta_key', UserMeta::FIRST_NAME)
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%')
                            ->orwhere('meta_key', UserMeta::LAST_NAME)
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%');
                    });
                }
            }))->orderBy('created_at', 'desc')->paginate($paginate_count);

        } else {

            //all tickets
            $tickets = Ticket::whereHas('user', function ($query) use ($user) {
                if (!$user->can('admin_all_support_ticket_access')) {
                    $query->whereAdviserId($user->id);
                }
            })->orderBy('created_at', 'desc')->paginate($paginate_count);

            //statistics

            //all ticket
            $statistics['all_tickets'] = Ticket::where(function ($query) use ($user) {
                if (!$user->can('admin_all_support_ticket_access')) {
                    $query->whereAdviserId($user->id);
                }
            })->count();
            
            //Pending
            $statistics['pending_tickets'] = Ticket::where(function ($query) use ($user) {
                if (!$user->can('admin_all_support_ticket_access')) {
                    $query->whereAdviserId($user->id);
                }
                $query->where('status', Ticket::STATUS_WAITING);
            })->count();

            //chart data
            $chart = array();
            $day_of_month = jdate('j', '', '', '', 'en');
            for ($i = 1; $i <= $day_of_month; $i++) {
                $start_date = date('Y-m-d H:i:s', jmktime(0, 0, 0, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $end_at = date('Y-m-d H:i:s', jmktime(24, 59, 59, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $chart[] = $this->statisticHelper($start_date, $end_at);
            }
            $statistics['chart'] = $chart;

            //today tickets
            $start_date = jdate('G', '', '', '', 'en');
            $start_date = time() - ($start_date * 24 * 3600);
            $start_date = date('Y-m-d H:i:s', $start_date);
            $statistics['today_tickets'] = $this->statisticHelper($start_date, date('Y-m-d H:i:s'));

        }
        $tickets->appends(Input::except('page'));
        
        return view('support::admin.tickets.index', compact('title', 'tickets', 'statistics'));
    }

    public function edit(Request $request, Ticket $ticket)
    {
        global $paginate_count;
        
        $title = 'پاسخ به تیکت';
        $data = array();
        $data['ticket_massage_cont'] = Ticket::where('code', $ticket->code)->whereHas('details')->count();
        $data['ticket_type'] = $ticket->type;
        
        /** @var Ticket $items */
        $ticketDetails = $ticket->details()
            ->where('parent_id', 0)
            ->orderBy('created_at', 'ASC')
            ->paginate($paginate_count);
        $data['counter'] = 0;
        
        /** @var TicketDetail $lastQuestionTicket */
        $lastQuestionTicket = TicketDetail::whereType(TicketDetail::TYPE_QUESTION)->get()->last();
    
        return view('support::admin.tickets.edit', compact('title', 'ticket', 'ticketDetails', 'lastQuestionTicket', 'data'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->validate($request, [
            'message' => 'required',
            'attach' => 'max:8000|mimes:doc,docx,pdf,png,zip,jpg,jpeg,gif,mpga,mp4,ogg,avi,wav'
        ]);
        
        $user = Auth::user();
        
        $ticket_detail = new TicketDetail();
        $ticket_detail->parent_id = $request->parent_id;
        $ticket_detail->user_id = $user->id;
        $ticket_detail->message = $request->message;
        $ticket_detail->type = TicketDetail::TYPE_ANSWER;
        $ticket->details()->save($ticket_detail);
        $ticket->status = Ticket::STATUS_ANSWERED;
        $ticket->save();
        
        if ($request->hasFile('attach')) {
            $fileName = $ticket->code . uniqid('__' . $request->file('attach')->getClientOriginalExtension() . '_') . '.' . $request->file('attach')->getClientOriginalExtension();
            $request->file('attach')->move(public_path('uploads/ticket'), $fileName);
            $ticket_detail_meta = new TicketDetailMeta();
            $ticket_detail_meta->meta_key = TicketDetailMeta::META_KEY_FILE;
            $ticket_detail_meta->meta_value = $fileName;
            $ticket_detail->metas()->save($ticket_detail_meta);
        }

        //send Message to telegram
        if (UserMeta::where(['meta_key' => 'chat_id', 'user_id' => $ticket->user_id])->exists()) {
            $chatdId = UserMeta::where(['meta_key' => 'chat_id', 'user_id' => $ticket->user_id])->first()->meta_value;
            $message = "پاسخی برای تیکت " . $ticket->code . "ارسال شد.
	    	---------------------------------
	    	" . strip_tags($request->input('message')) . "
	    	";
            $message = urlencode($message);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'http://rejim24.net/Bot/hook.php?ticketChangeStatus&cid=' . $chatdId . '&message=' . $message . '');
            $result = curl_exec($ch);
            curl_close($ch);
        }

//        $t_user = User::where('id', $ticket->user_id)->first();

        //start sms
//        if ($send_sms) {
//            $sms_text = SmsConfig::find(3);
//            $message_text = $sms_text->message;
//            if ($send_sms) {
//                $filter = array(
//                    'fName' => $t_user->getMeta('FIRSTNAME'),
//                    'lName' => $t_user->getMeta('LASTNAME'),
//                    'Gender' => $t_user->getMeta('GENDER'),
//                    'Code' => $ticket->code,
//                );
//                $sms_message = smsTextFilter($message_text, $filter);
//                sendMessageHelper(array($t_user->mobile), array($sms_message));
//            }
//        }
        //end sms

        $request->session()->flash('successful', 'پاسخ تیکت با موفقیت ارسال شد.');
        return redirect(route('admin.support.tickets.edit', $ticket->id));
    }

    /**
     * @param Request $request
     * @param $code
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, $code)
    {
        Ticket::where('code', $code)->delete();
        $request->session()->flash('successful', 'تیکت با موفقیت حذف شد.');
        return redirect(route('admin.support.tickets.index'));
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Request $request, Ticket $ticket)
    {
        $this->authorize('change_status_admin_support_ticket');

        if ($ticket->status == 2) {
            if ($ticket->details()->exists()) {
                $ticket->status = 1;
            } else {
                $ticket->status = 0;
            }
            $status_message = 'تیکت با موفقیت باز شد.';
        } else {
            $ticket->status = 2;
            $status_message = 'تیکت با موفقیت بسته شد.';
        }
        $ticket->save();
        $request->session()->flash('successful', $status_message);
        return redirect(route('admin.support.tickets.index'));
    }

    /**
     * @param string $start_date
     * @param string $end_date
     * @return mixed
     */
    public function statisticHelper($start_date = '', $end_date = '')
    {
        $user = Auth::user();

        $statistics = Ticket::where(function ($query) use ($start_date, $end_date, $user) {
            if (!$user->can('admin_all_support_ticket_access')) {
                $query->whereAdviserId($user->id);
            }
            $query->where('created_at', '>=', $start_date);
            $query->where('created_at', '<=', $end_date);
        })->count();
        return $statistics;
    }
}