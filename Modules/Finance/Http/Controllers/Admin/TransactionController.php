<?php

namespace Modules\Finance\Http\Controllers\Admin;

use App\Base\AdminController;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\Diet\Entities\DietPackage\DietPackage;
use Modules\Finance\Entities\Transaction;
use Modules\Finance\Entities\TransactionMeta;

class TransactionController extends AdminController
{
    protected $item_list = 10;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        global $item_list;
        $title = 'تراکنش ها';
        /*----------- Admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        $data['user_type_access'] = (!$user->can('admin_all_transactions_access') ? false : true);
        $appoints = null;
        if ($request->input('act') != null) {
            $data['transaction'] = Transaction::where(function ($q) use ($user, $request) {
                if ($request->input('code') != null) {
                    $q->where('code', $request->input('code'));
                }
                if ($request->input('start_at') != null) {
                    $from = explode('/', $request->input('start_at'));
                    $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                    $from = $from . ' 00:00:00';
                    $q->where('created_at', '>=', $from);
                }
                if ($request->input('end_at') != null) {
                    $to = explode('/', $request->input('end_at'));
                    $to = jalali_to_gregorian($to[0], $to[1], $to[2], '-');
                    $to = $to . ' 00:00:00';
                    $q->where('created_at', '<=', $to);
                }
                if ($request->input('status') != null) {
                    $q->where('status', $request->input('status'));
                }
            })->whereHas('user', function ($query) use ($user, $request) {
                //serach by sender name
                if ($request->input('name') != null) {
                    $query->whereHas('userMetas', function ($q) use ($request) {
                        $q->where('meta_key', UserMeta::FIRST_NAME)
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%')
                            ->orwhere('meta_key', UserMeta::LAST_NAME)
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%');
                    });
                }
            })->orderBy('created_at', 'DESC')->paginate($item_list);
        } else {

            $data['transaction'] = Transaction::orderBy('created_at', 'DESC')->paginate($item_list);
            //chart data
            $chart = array();
            $day_of_month = jdate('j', '', '', '', 'en');
            for ($i = 1; $i <= $day_of_month; $i++) {
                $start_date = date('Y-m-d H:i:s', jmktime(0, 0, 0, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $end_date = date('Y-m-d H:i:s', jmktime(24, 59, 59, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $chart[] = Transaction::where(function ($query) use ($start_date, $end_date, $user) {
                    $query->where('status', Transaction::STATUS_SUCCESS);
                    $query->where('created_at', '>=', $start_date);
                    $query->where('created_at', '<=', $end_date);
                })->count();;
            }
            $data['statistics']['chart'] = $chart;
        }
        $data['transaction']->appends(Input::except('page'));
        return view('finance::admin.transactions.index', compact('title', 'data', 'appoints'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $title = 'افزودن تراکنش';
        return view('finance::admin.transactions.create', compact('title', 'data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'price' => 'required',
        ]);

        $price = str_replace(',', '', $request->price);
        $user_id = str_replace(',', '', $request->user_id);
        $status = $request->status;
        $type = $request->type;
        $metas = $request->metas;
        //check errors
        if(!User::whereId($user_id)->exists()){
            $request->session()->flash('sys_error', 'کاربر با شماره کاربری '.$user_id.' یافت نشد');
            return redirect()->back();
        }
        /** @var User $user */
        $user = User::findOrfail($user_id);
        if($status == Transaction::STATUS_SUCCESS && ($type == Transaction::TYPE_REFUND || $type == Transaction::TYPE_BUY) && $user->charge < $price){
            $request->session()->flash('sys_error', 'موجودی کاربر کمتر از مبلغ وارد شده است');
            return redirect()->back();
        }
        /** @var Transaction $transaction */
        $transaction = Transaction::create([
            'user_id' => $user_id,
            'code' => '0',
            'status' => $status,
            'price' => $price,
            'ip' => $request->ip(),
            'type' => $type
        ]);
        $transaction->update([
            'code' => Transaction::codeGenerator($transaction->id)
        ]);
        $metasArray = [];
        foreach($metas as $key => $value){
            $metasArray[] = new TransactionMeta([
                'meta_key' => $key,
                'meta_value' => $value
            ]);
        }
        $transaction->transactionMetas()->saveMany($metasArray);
        if($status == Transaction::STATUS_SUCCESS){
            if($type == Transaction::TYPE_REFUND || $type == Transaction::TYPE_BUY){
                $user->decrement('charge', $price);
            }else{
                $user->increment('charge', $price);
            }
        }

        $request->session()->flash('successful', 'تراکنش با موفقیت انجام شد . کد پیگیری تراکنش : ' . $transaction->code);
        return redirect(route('admin.finance.transactions.index'));
    }

    public function show(Request $request,$transaction)
    {
        $transaction = Transaction::findOrfail($transaction);
        return view('finance::admin.transactions.view', compact( 'transaction'));
    }

    public function update(Request $request, $transaction)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        /** @var Transaction $transaction */
        $transaction = Transaction::findOrfail($transaction);
        if($transaction->status != Transaction::STATUS_PENDING){
            $request->session()->flash('sys_error', 'فقط سفارش‌های با وضعیت «در انتظار» قابل ویرایش می‌باشند');
            return redirect()->back();
        }
        $transaction->update([
            'status' => $request->status
        ]);
        $metas = $request->metas;
        $status = $request->status;
        $metasArray = [];
        foreach($metas as $key => $value){
            $metasArray[] = new TransactionMeta([
                'meta_key' => $key,
                'meta_value' => $value
            ]);
        }
        $transaction->transactionMetas()->saveMany($metasArray);
        /** @var User $user */
        $user = $transaction->user;
        if($status == Transaction::STATUS_SUCCESS && ($transaction->type == Transaction::TYPE_REFUND || $transaction->type == Transaction::TYPE_BUY) && $user->charge < $transaction->price){
            $request->session()->flash('sys_error', 'موجودی کاربر کمتر از مبلغ وارد شده است');
            return redirect()->back();
        }

        if($status == Transaction::STATUS_SUCCESS){
            if($transaction->type == Transaction::TYPE_REFUND || $transaction->type == Transaction::TYPE_BUY){
                $user->decrement('charge', $transaction->price);
            }else{
                $user->increment('charge', $transaction->price);
            }
            if($transaction->transactionMetas()->whereMetaKey(TransactionMeta::META_DIET_PACKAGE_ID)->exists()){
                $package = DietPackage::whereId($transaction->transactionMetas()->whereMetaKey(TransactionMeta::META_DIET_PACKAGE_ID)->first()->meta_value);
                if($package->exists()){
                    $package->first()->increment('counter',1);
                }
            }
        }

        $request->session()->flash('successful', 'تراکنش با موفقیت بروزرسانی شد');
        return redirect(route('admin.finance.transactions.index'));
    }

}
