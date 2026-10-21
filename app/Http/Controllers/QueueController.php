<?php

namespace App\Http\Controllers;

use App\Queue;
use Illuminate\Support\Arr;

class QueueController extends Controller
{
    public function execute($token)
    {
        abort_if($token != "sq1254287sdsdD57s87sD87Sasa56asSShkllu", 404);
        $getQueueItems = Queue::execute();
        if ($getQueueItems->exists()) {
            foreach ($getQueueItems->get() as $getQueueItem) {
                if ($getQueueItem->type == Queue::TYPE_SMS) {
                    $to = [];
                    if($getQueueItem->model == null){
                        $to = Arr::wrap($getQueueItem->to);
                    }else{
                        $model = $getQueueItem->model::find($getQueueItem->to);
                        if($model != null){
                            if($model->user != null){
                                $to = Arr::wrap($model->user->mobile);
                            }else{
                                $to = Arr::wrap($model->mobile);
                            }
                        }
                    }
                    $content = Arr::wrap($getQueueItem->content);
                    $result = sendAfeSMS($to, $content);
                    $getQueueItem->result = $result;
                    $getQueueItem->status = Queue::STATUS_SEND_COMPLETE;
                    $getQueueItem->save();
                }
            }
        }
    }
    
    public function sendGroupSMS()
    {
        $to = $messages = [];

        $todayQueues = Queue::cronExecute();
        $arrayQueues = $todayQueues->limit(10)->get();


        foreach ($arrayQueues as $queue) {
            if (!empty($queue->model)) {
                $model = $queue->model::find($queue->to);
                if (!empty($model)) {
                    if ($model->user != null) {
                        $to[] = $model->user->mobile;
                    } else {
                        $to[] = $model->mobile;
                    }
                }
            }
            $messages[] = $queue->content;
            //$messages[] = 'سلام';
        }
        dd($arrayQueues);


        $res = sendAfeSMS($to, $messages);
        if (!empty($res) && $res[0]['result'] != 'charge end') {
            foreach ($arrayQueues as $key => $q) {
                Queue::whereId($q->id)->update([
                    'status' => 1,
                    'result' => $res[0]['result'][$key]
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error'
            ], 422);
        }

        return response()->json([
            'status' => 'ok'
        ], 200);
    }
}
