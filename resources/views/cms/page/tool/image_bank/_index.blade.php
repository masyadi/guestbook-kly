@if( $position )
<div class="hpanel">
    <div class="panel-body">
        
        <a class="dropdown-toggle btn btn-default pull-right" href="#" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-gear"></i> {{__('Action')}}
        </a>
        <ul class="dropdown-menu pull-right">
            <li><a href="#modalNewFolder" class="bank-new-folder" data-toggle="modal"><i class="fa fa-folder-o"></i> {{__('New Folder')}}</a></li>
            <li><a href="#modalUpload" class="bank-new-upload" data-toggle="modal"><i class="fa fa-upload"></i> {{__('Upload')}}</a></li>
            @if( Helper::val($current, 'starred'))
            <li><a href="{{ Helper::CMS(Helper::page().'/'.request('parent')) }}" class="bank-action-unstarred"><i class="fa fa-star-o text-default"></i> {{__('Unfavorite')}}</a></li>
            @else
            <li><a href="{{ Helper::CMS(Helper::page().'/'.request('parent')) }}" class="bank-action-starred"><i class="fa fa-star text-warning"></i> {{__('Set as Favorite')}}</a></li>
            @endif
            <li><a href="{{ Helper::CMS(Helper::page().'/'.request('parent')) }}" class="bank-action-trash"><i class="fa fa-trash text-danger"></i> {{__('Delete Directory')}}</a></li>
        </ul>
        
        <ol class="hbreadcrumb breadcrumb">
            <li><a href="{{ Helper::CMS(Helper::page().'?view='.request('view')) }}"><i class="fa fa-home"></i></a></li>
            @foreach( $position as $k=>$b )
                <li><a href="{{ Helper::CMS(Helper::page().'?view='.request('view').'&parent='.$b['id']) }}">{{ $b['title'] }}</a></li>
            @endforeach
        </ol>
    </div>
</div>
@endif

<div class="row">
    
    @if( $rows->count()>0 )
    @foreach( $rows as $r )
    <div class="col-md-3">
        <div class="hpanel contact-panel">
            @if( $r->starred )
                <i class="fa fa-star text-warning blink starred-icon"></i>
            @endif

            @if( $r->mime_type=='__dir' )
            <div class="panel-body file-body preview-body">
                <a href="{{ Helper::CMS(Helper::page().'?view='.( request('view')!='starred' ? request('view') : 'shared' ).'&parent='.$r->id) }}"><i class="fa fa-folder text-info"></i></a>
            </div>
            <div class="panel-footer">
                <a href="{{ Helper::CMS(Helper::page().'?view='.( request('view')!='starred' ? request('view') : 'shared' ).'&parent='.$r->id) }}" class="pull-left wrap-footer-bank">{{ $r->title }}</a>
                
                <a class="dropdown-toggle btn btn-sm btn-default pull-right" href="#" data-toggle="dropdown" aria-expanded="false" style="margin-top: -7px;margin-right: -10px;">
                    <i class="fa fa-gear"></i>
                </a>
                <ul class="dropdown-menu pull-right" style="margin-top: -25px;">
                    @if( $r->starred )
                    <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-unstarred"><i class="fa fa-star-o text-default"></i> {{__('Unfavorite')}}</a></li>
                    @else
                    <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-starred"><i class="fa fa-star text-warning"></i> {{__('Set as Favorite')}}</a></li>
                    @endif
                    
                    @if( $r->status=='-1' )
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-restore"><i class="fa fa-share text-info"></i> {{__('Restore')}}</a></li>
                    @else
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-trash"><i class="fa fa-trash text-danger"></i> {{__('Delete')}}</a></li>
                    @endif
                        
                </ul>
                
                <div class="clearfix"></div>
            </div>
            {{-- @elseif( explode('/', $r->mime_type)[0]=='image' ) --}}
            @elseif( preg_match('/\w+\/[-+.\w]+/', $r->mime_type) )
                <div class="panel-body preview-body" style="background-image: url('{{Helper::imgSrc($r->path, config('app.thumb_size'))}}');">
                    @if( $isAjax )
                    <a href="#" class="OpenModalPreview" data-row="{{ json_encode($r) }}" data-url="{{Helper::imgSrc($r->path)}}"></a>
                    @else
                    <a href="#modalPreview-{{$r->id}}" class="OpenModalPreview" data-row="{{ json_encode($r) }}" data-toggle="modal"></a>
                    @endif
                </div>
                <div class="panel-footer">
                    <a href="#modalPreview-{{$r->id}}" class="OpenModalPreview" data-row="{{ json_encode($r) }}" data-toggle="modal" class="pull-left wrap-footer-bank">{{ $r->title }}</a>
                    
                    <a class="dropdown-toggle btn btn-sm btn-default pull-right" href="#" data-toggle="dropdown" aria-expanded="false" style="margin-top: -7px;margin-right: -10px;">
                        <i class="fa fa-gear"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" style="margin-top: -25px;">
                        @if( $r->starred )
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-unstarred"><i class="fa fa-star-o text-default"></i> {{__('Unfavorite')}}</a></li>
                        @else
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-starred"><i class="fa fa-star text-warning"></i> {{__('Set as Favorite')}}</a></li>
                        @endif
                        
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-edit" data-action="rotate_left"><i class="fa fa-undo text-default"></i> {{__('Rotate Left')}}</a></li>
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-edit" data-action="rotate_right"><i class="fa fa-repeat text-default"></i> {{__('Rotate Right')}}</a></li>
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-edit" data-action="flip_h"><i class="fa fa-shield fa-flip-horizontal text-default"></i> {{__('Flip Horizontal')}}</a></li>
                        <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-edit" data-action="flip_v"><i class="fa fa-shield fa-flip-vertical text-default"></i> {{__('flip Vertical')}}</a></li>
                        
                        @if( $r->status=='-1' )
                            <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-restore"><i class="fa fa-share text-info"></i> {{__('Restore')}}</a></li>
                        @else
                            <li><a href="{{ Helper::CMS(Helper::page().'/'.$r->id) }}" class="bank-action-trash"><i class="fa fa-trash text-danger"></i> {{__('Delete')}}</a></li>
                        @endif

                    </ul>
                    
                    <div class="clearfix"></div>
                </div>
                
                <!-- Modal preview -->
                <div class="modal fade" id="modalPreview-{{$r->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    
                    <a href="#" class="close-corner" data-dismiss="modal">&times;</a>
                    
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body" style="padding: 0">
                                <div class="row" style="background: #000;">
                                    <div class="col-md-8" style="padding: 0;">
                                    
                                        <div class="tab-content">
                                        
                                            <div id="note-history-current-{{$r->id}}" class="tab-pane active">
                                                <div class="image-big-preview">
                                                    <img src="{{Storage::url($r->path)}}" />
                                                </div>
                                            </div>
                                            
                                            @if( $r->rev )
                                                @foreach( json_decode($r->rev) as $kv=>$rv )
                                                    <div id="note-history-{{$kv}}-{{$r->id}}" class="tab-pane animated fadeIn">
                                                        <div class="image-big-preview">
                                                            <img src="{{Storage::url($rv->file)}}" />
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-4 img-info-pane">
                                    
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#tab-info-{{$r->id}}"> {{__('Info')}} </a></li>
                                            <li class=""><a data-toggle="tab" href="#tab-history-{{$r->id}}">{{ __('History') }}</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="tab-info-{{$r->id}}" class="tab-pane active">
                                                <div class="panel-body" style="padding: 0;">
                                                    <div class="table-responsive" style="margin: -1px;">
                                                        <table class="table" style="margin-bottom: 0;">
                                                            @foreach( [
                                                                'title'=> 'Title', 'event'=> 'Event', 'date'=> 'Date',
                                                                'location'=> 'Location', 'copyright'=> 'Copyright', 'photographer'=> 'Photographer',
                                                                'caption'=> 'Caption', 'keywords'=> 'Keywords', 'mime_type'=> 'Type',
                                                                'exif_camera'=> 'Camera', 'exif_software'=> 'Software', 'exif_date_taken'=> 'Taken at',
                                                            ] as $k_i=>$v_i )
                                                            <tr>
                                                                <td class="fit">{{__($v_i)}}</td>
                                                                <td>: {{ $r->{$k_i} }}</td>
                                                            </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td class="fit">{{__('Size')}}</td>
                                                                <td>: {{ $r->width }}x{{ $r->height }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fit">{{__('Ratio')}}</td>
                                                                <td>: {{ $r->ratio }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fit">{{__('File size')}}</td>
                                                                <td>: {{ Helper::formatBytes($r->file_size) }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="tab-history-{{$r->id}}" class="tab-pane">
                                                <div class="hpanel panel-group">
                                                    <div class="collapse notes-history" style="margin-top: -1px;">
                                                    
                                                        @if( $r->rev )
                                                            @foreach( json_decode($r->rev) as $kv=>$rv )
                                                            <div class="panel-body note-link {{($kv+1)==count(json_decode($r->rev))?'active':''}} ">
                                                                <a href="#note-history-{{$kv}}-{{$r->id}}" data-toggle="tab">
                                                                <small class="pull-right text-muted">{{ Helper::formatDate(Helper::val($rv, 'output.updated_at'), "d M Y H:i") }}</small>
                                                                    <h5>{{ Helper::val($rv, 'action') }}</h5>
                                                                    <div class="small">
                                                                        <table>
                                                                            <tr>
                                                                                <td>{{__('Size')}}</td><td>: {{ Helper::val($rv, 'output.width') }}x{{ Helper::val($rv, 'output.height') }} px</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>{{__('Ratio')}}</td><td>: {{ Helper::val($rv, 'output.ratio') }}px</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>{{__('File size')}}</td><td>: {{ Helper::formatBytes(Helper::val($rv, 'output.file_size')) }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>{{__('Author')}}</td><td>: {{ Helper::val(\App\User::find(Helper::val($rv, 'output.updated_by')), 'name') }}</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            @endforeach
                                                        @else
                                                        <h4>{{__('There is no history')}}</h4>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal preview -->
            @endif
        </div>
    </div>

    @endforeach
    @else
    <div class="col-md-12">
        <div class="hpanel">
            <div class="panel-body file-body">
                <p>{{ __('There is no image yet') }}</p>
                <p>{!! __('You can <a href="#modalNewFolder" data-toggle="modal">create folders</a> or <a href="#modalUpload" data-toggle="modal">upload images</a>') !!}</p>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="text-center">{!! $rows->appends(Request::except('page'))->onEachSide(3)->links() !!}</div>