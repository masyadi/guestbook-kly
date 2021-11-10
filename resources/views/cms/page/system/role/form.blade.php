@extends('CMS::layouts.form')

@section('form')

    <x-form.checkbox name="status" label='Aktif' :row="$row??null" />

    <x-form.input name="name" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />

    <hr/>
    <label>{{__('Role Access')}}</label>
    @if( $rowMenu = Helper::getMenu() )
        
        @php

        function buildListMenu($menu, $rowMenu, $dtMenu)
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
                        $child = '<ol class="dd-list">'.buildListMenu($p, $rowMenu, $dtMenu).'</ol>';
                    }

                    $list.= '<li class="dd-item" data-id="'.Helper::val($r, 'id').'">
                                <span class="pull-left" style="margin-top: 5px; margin-left: 10px;margin-right: 10px;">
                                    
                                	<div>
								    	<label> 
								    		<input type="checkbox" 
								    			name="menu[]" 
								    			class="i-checks role-check" 
								    			value="'.Helper::val($r, 'id').'"

								    			'.(in_array(Helper::val($r, 'id'), old('menu', (isset($dtMenu) ? $dtMenu : [])  )) ? 'checked="checked"' : '').'

								    			'.(Helper::val($r, 'show_all')=='1' ? 'checked="checked" disabled="disabled"' : '').'
								    		>
								    	</label>
								    </div>

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
        
        <div class="dd">
            <ol class="dd-list">
                {!! buildListMenu(Helper::val($rowMenu, 'parent_id.0'), $rowMenu, (isset($dtMenu)?$dtMenu:[]) ) !!}
            </ol>
        </div>
        
    
    @endif
    
@endsection