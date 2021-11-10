<div class="panel panel-default animated-panel {{config('display.effect')}} list-attachments">
    <div class="panel-heading">
        {{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}
        <div class="pull-right" style="margin-top: -6px;">
            <a href="#" class="btn btn-sm btn-info btn-add btn-img-brows" data-type="params" data-name="{{ $name ?? null }}" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" data-max-item="{{ $maxItem ?? null }}" data-accept="{{ $accept ?? null }}" title="{{__('Tambah')}}"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="panel-body no-padding">
        <table class="table table-striped">
            @if( $default??null )

                @php
                
                    if( $row->relattachments??null )
                    {
                        foreach( $row->relattachments as $a )
                        {
                            foreach( $default as $k=>$d )
                            {
                                if( $d == $a['title'] )
                                {
                                    unset($default[$k]);
                                }
                            }
                        }
                    }
                @endphp

                @foreach( $default as $k => $d )
                    <tr class="item">
                        <td class="text-center">
                            <img src="{{asset('static/png/no-image.png')}}" is-image="true" data-src="{{asset('static/png/no-image.png')}}" class="widget-preview" style="max-width: 100%;min-width: 48px;cursor: pointer;"/>
                            <input type="text" name="{{ isset($name) ? 'attachment['. $name .']['. $k .'}][title]' : 'attachment['. $k .'][title]' }}" value="{!! $d['title'] !!}" class="form-control" />
                            <input type="hidden" name="{{ isset($name) ? 'attachment['. $name .']['. $k .'}][path]' : 'attachment['. $k .'][path]' }}" value="{{$d['path']}}" />
                        </td>
                        <td class="fit text-center">
                            <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs" style="margin-bottom: 5px"><i class="fa fa-close text-danger"></i></a>
                            <br/>
                            <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" style="margin-bottom: 5px"><i class="fa fa-upload text-info"></i></a>
                            <br>
                            <a href="{{ $d['path'] ? \Storage::url($d['path']) : null }}" target="__blank" title="Download item" class="btn btn-default btn-xs" style="margin-bottom: 5px"><i class="fa fa-download text-success"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif

            @if( $row->relattachments??null )
                @foreach( $row->relattachments as $a )
                    
                    @php
                        $isImage = true;
                        $ext = strtolower(pathinfo($a['path'])['extension']);
                        $thumbUrl = Helper::imgSrc($a['path'], '300x300');
                    @endphp    
                    @if( isset($name) )
                        @if( $a['tipe_attachment'] == $row->getTable() .'_'. $name )
                            <tr class="item">
                                <td class="text-center">
                                    <img src="{{$thumbUrl}}" is-image="{{$isImage?'true':'false'}}" data-src="{{Helper::imgSrc($a['path'])}}" class="widget-preview" style="max-width: 100%;min-width: 48px;cursor: pointer;"/>
                                    <input type="text" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][title]') : ('attachment['. $a->id .'][title]') }}" value="{!! $a['title'] !!}" class="form-control" />
                                    <input type="text" placeholder="URL" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][params][url]') : ('attachment['. $a->id .'][params][url]') }}" value="{!! ($a['params']??null) ? (json_decode($a['params'], TRUE)['url']??'') : '' !!}" class="form-control" />
                                    <input type="hidden" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][path]') : ('attachment['. $a->id .'][path]') }}" value="{{$a['path']}}" />
                                </td>
                                <td class="fit text-center">
                                    <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs" style="margin-bottom: 5px"><i class="fa fa-close text-danger"></i></a>
                                    <br/>
                                    <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" style="margin-bottom: 5px"><i class="fa fa-upload text-info"></i></a>
                                    <br>
                                    <a href="{{ $a->path ? \Storage::url($a->path) : null }}" target="__blank" title="Download item" class="btn btn-default btn-xs" style="margin-bottom: 5px"><i class="fa fa-download text-success"></i></a>
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr class="item">
                            <td class="text-center">
                                
                                <img src="{{$thumbUrl}}" is-image="{{$isImage?'true':'false'}}" data-src="{{Helper::imgSrc($a['path'])}}" class="widget-preview" style="max-width: 100%;min-width: 48px;cursor: pointer;"/>
                                <input type="text" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][title]') : ('attachment['. $a->id .'][title]') }}" value="{!! $a['title'] !!}" class="form-control" />
                                <input type="text" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][params][url]') : ('attachment['. $a->id .'][params][url]') }}" value="{!! $a['params']['url'] !!}" class="form-control" />
                                    
                                <input type="hidden" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][path]') : ('attachment['. $a->id .'][path]') }}" value="{{$a['path']}}" />
                            </td>
                            <td class="fit text-center">
                                <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs" style="margin-bottom: 5px"><i class="fa fa-close text-danger"></i></a>
                                <br/>
                                <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" style="margin-bottom: 5px"><i class="fa fa-upload text-info"></i></a>
                                <br>
                                <a href="{{ $a->path ? \Storage::url($a->path) : null }}" target="__blank" title="Download item" class="btn btn-default btn-xs" style="margin-bottom: 5px"><i class="fa fa-download text-success"></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
            <tr class="empty">
                <td colspan="3" class="text-center">
                    <i>{{__('Belum ada file')}}</i>,
                    <a href="#" class="btn-add btn-img-brows" data-name="{{ $name ?? null }}" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" data-max-item="{{ $maxItem ?? null }}" data-accept="{{ $accept ?? null }}" title="{{__('Tambah')}}">{{__('tambahkan')}}</a>
                </td>
            </tr>
        </table>
    </div>
</div>