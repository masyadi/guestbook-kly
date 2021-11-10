<div class="panel panel-default animated-panel {{config('display.effect')}} list-attachments">
    <div class="panel-heading">
        {{ $title ?? __('Foto')}}
        <div class="pull-right" style="margin-top: -6px;">
            <a href="#" class="btn btn-sm btn-info btn-add btn-img-brows" data-name="{{ $name ?? null }}" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" data-max-item="{{ $maxItem ?? null }}" title="{{__('Tambah')}}"><i class="fa fa-plus"></i></a>
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

                @foreach( $default as $k=>$d )
                    <tr class="item">
                        <td class="text-center">
                            <img src="{{asset('static/png/no-image.png')}}" is-image="true" data-src="{{asset('static/png/no-image.png')}}" class="widget-preview" style="max-width: 100%;min-width: 48px;cursor: pointer;"/>
                            <textarea class="form-control" maxlength="255" name="{{ isset($name) ? ('attachment['. $name .']['. $k .'}][title]') : ('attachment['. $k .'][title]') }}" style="min-height: 48px;" title="Keterangan" placeholder="Keterangan">{!! $d !!}</textarea>
                            <input type="hidden" name="{{ isset($name) ? 'attachment['. $name .']['. $k .'}][path]' : 'attachment['. $k .'][path]' }}" value=""/>
                        </td>
                        <td class="fit text-center">
                            <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs"><i class="fa fa-close text-danger"></i></a>
                            <br/><br/>
                            <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment"><i class="fa fa-upload text-info"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif

            @if( $row->relattachments??null )
                @foreach( $row->relattachments as $a )
                    
                    @php
                        $isImage = false;
                        $ext = strtolower(pathinfo($a['path'])['extension']);
                        $thumbUrl = asset('static/ext/'.$ext.'.png');
                    @endphp    

                    @if( isset($name) )
                        @if( $a['tipe_attachment'] == $row->getTable() .'_'. $name )
                            @php
                                if( in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp']) )
                                {
                                    $thumbUrl = Helper::imgSrc($a['path'], '300x300');

                                    $isImage = true;
                                }
                            @endphp

                            <tr class="item">
                                <td class="text-center">
                                    <img src="{{$thumbUrl}}" is-image="{{$isImage?'true':'false'}}" data-src="{{Helper::imgSrc($a['path'])}}" class="widget-preview" style="max-width: 100%;min-width: 48px;cursor: pointer;"/>
                                    <textarea class="form-control" maxlength="255" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][title]') : ('attachment['. $a->id .'][title]') }}" style="min-height: 48px;" title="Keterangan" placeholder="Keterangan">{!! $a['title'] !!}</textarea>
                                    <input type="hidden" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][path]') : ('attachment['. $a->id .'][path]') }}" value="{{$a['path']}}"/>
                                </td>
                                <td class="fit text-center">
                                    <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs"><i class="fa fa-close text-danger"></i></a>
                                    <br/><br/>
                                    <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment"><i class="fa fa-upload text-info"></i></a>
                                </td>
                            </tr>
                        @endif
                    @else
                        @php
                            if( in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp']) )
                            {
                                $thumbUrl = Helper::imgSrc($a['path'], '300x300');

                                $isImage = true;
                            }
                        @endphp

                        <tr class="item">
                            <td class="text-center">
                                <img src="{{$thumbUrl}}" is-image="{{$isImage?'true':'false'}}" data-src="{{Helper::imgSrc($a['path'])}}" class="widget-preview" style="max-width: 100%;min-width: 48px;cursor: pointer;"/>
                                <textarea class="form-control" maxlength="255" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][title]') : ('attachment['. $a->id .'][title]') }}" style="min-height: 48px;" title="Keterangan" placeholder="Keterangan">{!! $a['title'] !!}</textarea>
                                <input type="hidden" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][path]') : ('attachment['. $a->id .'][path]') }}" value="{{$a['path']}}"/>
                            </td>
                            <td class="fit text-center">
                                <a href="#" title="Hapus item" class="btn btn-default btn-del btn-xs"><i class="fa fa-close text-danger"></i></a>
                                <br/><br/>
                                <a href="#" title="Upload item" class="btn btn-default btn-upload btn-xs btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment"><i class="fa fa-upload text-info"></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
            <tr class="empty">
                <td colspan="3" class="text-center">
                    <i>{{__('Belum ada foto')}}</i>,
                    <a href="#" class="btn-add btn-img-brows" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" title="{{__('Tambah')}}">{{__('tambahkan')}}</a>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="modal fade" id="attachmentPreview" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <img src="{{ asset('static/png/no-image.png')}}" data-dismiss="modal" style="width: 100%;cursor: zoom-out;" />
        </div>
    </div>
</div>