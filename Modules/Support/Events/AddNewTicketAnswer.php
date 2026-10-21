<?php

namespace Modules\Support\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Support\Entities\TicketDetail;

class AddNewTicketAnswer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TicketDetail $ticketDetail)
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
