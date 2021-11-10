@extends('CMS::layouts.main')

@php $users = Helper::messageUser($row) @endphp

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="hpanel">
            <div class="panel-body">

            	@include('page.tool.message._nav', ['section'=>'inbox'])
                
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <form method="post" class="validate-form" action="{{url('message')}}">

            <div class="hpanel email-compose">
                <div class="panel-heading hbuilt">
                    <div class="p-xs h4">

                        {{$row->subject}}

                    </div>
                </div>
                <div class="panel-heading hbuilt no-padding">
                        
                        @if( $rows )
                        <div class="panel-group" id="messageContent" role="tablist" aria-multiselectable="true">
                            
                            @foreach( $rows as $k=>$r )

                            @php $from = $r['is_from_sender'] ? $users['from'] : $users['to']; @endphp
                            @php $to = $r['is_from_sender'] ? $users['to'] : $users['from']; @endphp
                            @php $active = $k==count($rows)-1?true:false; @endphp

                            <div class="panel panel-default" style="border-radius: 0;margin-top: 0;border: none;border-top: 1px solid #ddd;">
                                <div class="panel-heading" role="tab" id="hd-{{$k}}" style="border: none;border-top-left-radius: 0;border-top-right-radius: 0;">
                                    <span class="pull-right" style="padding-top: 10px;font-style:italic;font-weight: normal;font-size: 12px;color: #666;">
                                        {{ Helper::formatDate($r['created_at'], "d M Y H:i") }}
                                    </span>
                                    <h4 class="panel-title" style="text-transform: none;">
                                        <a data-toggle="collapse" data-parent="#messageContent" href="#cl-{{$k}}" aria-expanded="true" aria-controls="cl-{{$k}}">
                                            <div style=" background: url({{$from['image']}}); background-size: cover; width: 45px;height: 45px;border-radius:45px;float: left;margin: -5px 10px 0 -5px;"></div>
                                            <h5>{{ $from['name'] }}</h5>
                                            <small style="font-size: 12px;">to {{ $to['name'] }}</small>
                                        </a>
                                    </h4>
                                </div>
                                <div id="cl-{{$k}}" class="panel-collapse collapse {{ $active?'in':'' }}" role="tabpanel" aria-labelledby="hd-{{$k}}">
                                    <div class="panel-body">
                                        {!! $r['content'] !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        @endif
                </div>
                <div class="panel-body">
                    <x-form.editor name="content"  attributes='required="required"' :label="__('Balas')" />
                </div>

                <div class="panel-footer">
                    <div class="pull-right">
                        <a href="{{url('message')}}" class="btn btn-default">{{__('Kembali')}}</a>
                    </div>
                    <button type="submit" name="send" value="1" class="btn btn-primary">{{__('Send Message')}}</button>
                    <input type="hidden" name="slug" value="{{$slug}}">
                    @csrf()
                </div>
            </div>

        </form>
    </div>
</div>
@endsection