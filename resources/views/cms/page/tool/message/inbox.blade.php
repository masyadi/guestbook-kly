@extends('CMS::layouts.main')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="hpanel">
            <div class="panel-body">

            	@include('page.tool.message._nav')
                
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="hpanel">
            <div class="panel-heading hbuilt">
                <div class="text-center p-xs font-normal">
                    <form method="get">
                        <input type="hidden" name="view" value="{{request('view')}}">
                        <div class="input-group"><input type="text" value="{{request('q')}}" name="q" class="form-control input-sm" placeholder="{{__('Search message...')}}"> <span class="input-group-btn"> <button type="submit" class="btn btn-sm btn-default">{{__('Search')}}
                        </button> </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    @php $c = 0; @endphp
                    <table class="table table-hover table-mailbox">
                        <tbody>
                        @foreach( $rows as $r )
                        <tr class="{{ $r->unread ? 'unread' : '' }}">
                            <!--<td class="fit">
                                <input type="checkbox" 
					    			name="status"
					    			class="i-checks" value="1"> 
                            </td>-->
                            <td class="fit"><a href="{{ url('message/'.$r->slug) }}">{{ $r->sender }}</a></td>
                            <td><a href="{{ url('message/'.$r->slug) }}">{{ $r->subject }}</a></td>
                            <td class="fit">{!! $r->is_attachment ? '<i class="fa fa-paperclip"></i>' : '' !!}</td>
                            <td class="fit text-right mail-date">{{ Helper::formatDate($r->date, 'd M Y H:i') }}</td>
                        </tr>
                        @php $c+= $r->unread; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="row">
                    <div class="text-center">{!! $rows->appends(request()->except('page'))->render() !!}</div>
                </div>
            </div>
            <div class="panel-footer">
                <i class="fa fa-eye"> </i> {{Helper::formatNumber($c)}} unread
            </div>
        </div>
    </div>
</div>
@endsection