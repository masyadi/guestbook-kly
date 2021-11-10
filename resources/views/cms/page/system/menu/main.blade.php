@extends('CMS::layouts.main')

@section('content')

<div class="hpanel animated-panel {{config('display.effect')}}">
    <div class="panel-body">
    
    @if( $rowMenu = Helper::getMenu() )
        
        @php
        
        function buildListMenu($menu, $rowMenu)
        {
            $list = '';

            if( $menu )
            {
                foreach( $menu as $m )
                {
                    $r = Helper::val($rowMenu, 'data.'.$m);
                    

                    $child = '';

                    if( $p = Helper::val($rowMenu, 'parent_id.'.$m) )
                    {
                        $child = '<ol class="dd-list">'.buildListMenu($p, $rowMenu).'</ol>';
                    }
                    
                    $list.= '<li class="dd-item" data-id="'.Helper::val($r, 'id').'">
                                <span class="pull-right" style="margin-top: 5px; margin-right: 10px;">
                                    <a href="'.Helper::CMS(Helper::page().'/create?parent_id='.Helper::val($r, 'id') ).'" class="btn btn-xs btn-info" title="'.__('Add Child').'"><i class="fa fa-plus"></i> </a>
                                    <a href="'.Helper::CMS(Helper::page().'/'.Helper::val($r, 'id').'/edit' ).'" class="btn btn-xs btn-default" title="'.__('Edit Menu').'"><i class="fa fa-pencil"></i> </a>
                                    <a href="'.Helper::CMS(Helper::page().'/'.Helper::val($r, 'id') ).'" class="btn btn-xs btn-danger action-del" title="'.__('Delete Menu').'"><i class="fa fa-trash"></i> </a>
                                </span>
                                <div class="dd-handle '.(Helper::val($r, 'status')=='1' ? '' : 'text-danger').' ">
                                    <span class="label h-bg-navy-blue"><i class="'.Helper::val($r, 'icon').'"></i></span> 

                                    '.Helper::val($r, 'name').'

                                    '.(Helper::val($r, 'show_all')=='1' ? '<sup class="text-warning"><i class="fa fa-asterisk" title="Show in all role"></i></sup>' : '').'

                                </div>'                                    
                                .$child.'
                            </li>';
                }
            }

            return $list;
        }
        @endphp
        
        <div class="dd nestable">
            <ol class="dd-list">
                {!! buildListMenu(Helper::val($rowMenu, 'parent_id.0'), $rowMenu) !!}
            </ol>
        </div>
        
    
    @endif
        
        
    </div>
</div>

    
@endsection

@section('button_top_right')
    <a href="{{ Helper::CMS(Helper::page().'/create') }}" class="btn btn-primary" data-toggle="tooltip" title="{{__('Tambah Baru')}}" data-placement="bottom"><i class="fa fa-plus"></i> <span class="hidden-xs">{{ __('Tambah Baru') }}</span></a>
@endsection