@foreach($ticketDetails as $ticketDetail)
    <div class="timeline-item" style="{{ (!isset($margin) ?  : ('margin-right: ' . $margin . 'px;')) }}" >
        <div class="timeline-icon">
            <img style="position: relative; top: -2px; width: 36px; border-radius: 15px;"
                 src="{{ $ticketDetail->user->avatar }}" />
        </div>
        <div class="panel panel-border top @if($ticketDetail->type == \Modules\Support\Entities\TicketDetail::TYPE_ANSWER) panel-success @else panel-info @endif ">
            <div class="panel-heading">
                <span class="panel-title">
                    <span class="glyphicon glyphicon-comment"></span>
                    {{ $ticketDetail->user->full_name }}
                </span>
                <div class="panel-header-menu pull-left mr10 text-muted fs12"> {{ jdate('l d F - H:i',strtotime($ticketDetail->created_at)) }} </div>
            </div>
            <div class="panel-body">
                <?=nl2br($ticketDetail->message)?>
            </div>
            <div class="panel-footer p15 rtl yekan">
                @if($ticketDetail->file)
                    <p>
                        دریافت فایل ضمیمه :
                        <a href="{{ url('uploads/ticket/'.$ticketDetail->file) }}">
                            دانلود
                        </a>
                    </p>
                @endif
                <span class="index ml10 p5"></span>
                
                <button type="button" class="btn btn-success br2 btn-sm fs12 mr10 yekan left reply"
                        data-parent="{{ $ticketDetail->id }}">
                    پاسخ
                    <span class="glyphicons glyphicons-unshare"></span>
                </button>
                
                <a type="button" class="btn btn-info br2 btn-sm fs12 mr10 yekan left"
                        href="{{ route('admin.support.questions.create') . '?ticketDetail=' . $ticketDetail->id }}">
                    افزودن به سوالات پرتکرار
                </a>
                <div class="clear-div"></div>
            </div>
        </div>
    </div>
    @if($ticketDetail->children()->exists())
        @php($margin += 50)
        @include('support::admin.tickets.ticket_details', ['ticketDetails' => $ticketDetail->children, 'margin' => $margin])
        @php($margin -= 50)
    @endif
@endforeach
