<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function json()
    {
        $filePath = storage_path('app/appointment.json') ;
        $users = json_decode(file_get_contents($filePath), true);

        $list = [];
        foreach ( $users as $user){
            $mobile = ($user['phone']);
            $appointment = AppointmentUser::whereHas('user', function ($query) use ($mobile) {
                $query->whereMobile($mobile);
            })
                ->where('created_at', '>', Carbon::now()->subDays(40))
                ->get();
            if ($appointment->count()){
                foreach ($appointment as $appointmentUser) {
                    $list[] = [
                        'name'              => $appointmentUser->user->first_name ,
                        'last_name'         => $appointmentUser->user->last_name ,
                        'mobile'            => $mobile ,
                        'appointment_code'  => $appointmentUser->code ,
                    ];
                }
            }
        }
        dd($list , count($list));


    }
}
