<div class="panel panel-default animated-panel {{config('display.effect')}} list-attachments">
    <div class="panel-heading">
        {{ __( ucwords( str_replace('_',' ',$label??$name)) ) }}
        <div class="pull-right" style="margin-top: -6px;">
            <a href="#" class="btn btn-sm btn-info btn-add btn-img-brows" data-name="{{ $name ?? null }}" data-onlyupload="1" data-input="1" data-key="attachment" data-value="attachment" data-max-item="{{ $maxItem ?? null }}" data-accept="{{ $accept ?? null }}" title="{{__('Tambah')}}"><i class="fa fa-plus"></i></a>
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
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-file"></i></div>
                                <input type="text" name="{{ isset($name) ? 'attachment['. $name .']['. $k .'}][title]' : 'attachment['. $k .'][title]' }}" value="{!! $d['title'] !!}" class="form-control" />
                            </div>
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
                    @if( isset($name) )
                        @if( $a['tipe_attachment'] == $row->getTable() .'_'. $name )
                            <tr class="item">
                                <td class="text-center">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-file"></i></div>
                                        <input type="text" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][title]') : ('attachment['. $a->id .'][title]') }}" value="{!! $a['title'] !!}" class="form-control" />
                                    </div>
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
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-file"></i></div>
                                    <input type="text" name="{{ isset($name) ? ('attachment['. $name .']['. $a->id .'}][title]') : ('attachment['. $a->id .'][title]') }}" value="{!! $a['title'] !!}" class="form-control" />
                                </div>
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