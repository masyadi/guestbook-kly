@php
$uID = \Auth::user()->id;

//GET TOTAL
$total = [
    'inbox' => \App\Models\MessageHeader::where(function($q) use($uID) {
        $q->orWhere('to_id', $uID);
        $q->orWhere(function($q) use($uID) {
            $q->where('from_id', $uID);
            $q->whereHas('relmessages', function($q){
                $q->where('is_from_sender', false);
            });
        });
    })->count(),

    'sent' => \App\Models\MessageHeader::where(function($q) use($uID) {
        $q->orWhere('from_id', $uID);
        $q->orWhere(function($q) use($uID) {
            $q->where('to_id', $uID);
            $q->whereHas('relmessages', function($q){
                $q->where('is_from_sender', false);
            });
        });
    })->count()
];
@endphp
<a href="{{ url('message/create') }}" class="btn btn-success btn-block m-b-md">{{__('Compose')}}</a>

<ul class="mailbox-list">
    <li class="{{!request('view') ? 'active' : ''}}" >
        <a href="{{ url('message') }}">
            <span class="pull-right">{{Helper::formatNumber($total['inbox'])}}</span>
            <i class="fa fa-envelope"></i> Inbox
        </a>
    </li>
    <li class="{{request('view') == 'sent' ? 'active' : ''}}">
        <a href="{{ url('message?view=sent') }}">
        <span class="pull-right">{{Helper::formatNumber($total['sent'])}}</span>
        <i class="fa fa-paper-plane"></i> Sent</a>
    </li>
</ul>