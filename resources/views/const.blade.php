<script type="text/javascript">
	window.ANH = {
		msg: [],
		url: {
			web: '{{url(config('app.url'))}}',
			base: '{{Helper::CMS('')}}',
			page: '{{Helper::page()}}',
			full: '{{url()->full()}}',
			asset: '{{asset('static')}}',
			current: '{{url()->current()}}',
		},
		token: '{{csrf_token()}}',
		loading: {
			'icon': '<img src="data:image/gif;base64,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" style="max-width: 100%;"/>',
		}
	}
</script>

@if( $ses = Session::get('success') )
<script type="text/javascript">
	window.ANH.msg.push({
		type: 'success',
		title: 'SUCCESS',
		text: '{!! $ses !!}'
	});
</script>
@endif

@if( $ses = Session::get('warning') )
<script type="text/javascript">
	window.ANH.msg.push({
		type: 'warning',
		title: 'WARNING!!!',
		text: '{!! $ses !!}'
	});
</script>
@endif

@if( $ses = Session::get('error') )
<script type="text/javascript">
	window.ANH.msg.push({
		type: 'error',
		title: 'ERROR!!!',
		text: '{!! $ses !!}'
	});
</script>
@endif

@if( $ses = Session::get('error_big') )
<div class="modal fade" id="modal-autoload" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header text-center">
                <h4 class="modal-title">{{__('ERROR')}}</h4>
            </div>
            <div class="modal-body" style="background: #fde1e1;color: #000;">
                {!! $ses !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

@if( $errors )
<script type="text/javascript">
	@foreach( $errors->all() as $e )
	window.ANH.msg.push({
		type: 'error',
		title: 'ERROR!!!',
		text: '{!! $e !!}'
	});
	@endforeach
</script>
@endif

@if( $ses = session('user') )
<script type="text/javascript">
	window.ANH.auth = {
		id: '{{md5($ses->id)}}',
		name: '{{$ses->name}}',
		photo: '{{$ses->photo ? Helper::ImgSrc($ses->photo, config('app.thumb_size')) : asset('static/png/no-image.png')}}',
	};
</script>
@endif