@extends('CMS::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-3">
            
            <div class="hpanel">
                <div class="panel-body">
                    <form method="GET" action="{{url(Helper::page())}}">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="hidden" name="view" value="recent" />
                                <input type="text" class="form-control" name="q" placeholder="Search image" value="{{ request('q') }}"/> 
                                <span class="input-group-btn"> 
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button> 
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        
            <div class="hpanel">
                <div class="panel-body">
                    <div class="dropdown">
                        <a class="dropdown-toggle btn btn-primary btn-block" href="#" data-toggle="dropdown" aria-expanded="false">
                            {{__('NEW')}}
                        </a>
                        <ul class="dropdown-menu filedropdown m-l">
                            <li><a href="#modalNewFolder" class="bank-new-folder" data-toggle="modal"><i class="fa fa-folder-o"></i> {{__('Folder')}}</a></li>
                            <li><a href="#modalUpload" class="bank-new-upload" data-toggle="modal"><i class="fa fa-upload"></i> {{__('Upload')}}</a></li>
                        </ul>
                    </div>

                    <ul class="h-list m-t">
                        <li class="{{ request('view')=='shared' || !request('view') ? 'active' : '' }}"><a href="{{ url(Helper::page().'?view=shared') }}"><i class="fa fa-user text-info"></i> {{__('Shared with me')}}</a></li>
                        <li class="{{ request('view')=='my-file' ? 'active' : '' }}"><a href="{{ url(Helper::page().'?view=my-file') }}"><i class="fa fa-folder"></i> {{__('My Files')}}</a></li>
                        <li class="{{ request('view')=='recent' ? 'active' : '' }}"><a href="{{ url(Helper::page().'?view=recent') }}"><i class="fa fa-clock-o text-success"></i> {{__('Recent')}}</a></li>
                        <li class="{{ request('view')=='starred' ? 'active' : '' }}"><a href="{{ url(Helper::page().'?view=starred') }}"><i class="fa fa-star text-warning"></i> {{__('Starred')}}</a></li>
                        <li class="{{ request('view')=='trash' ? 'active' : '' }}"><a href="{{ url(Helper::page().'?view=trash') }}"><i class="fa fa-trash text-danger"></i> {{__('Trash')}}</a></li>
                    </ul>
                </div>

            </div>
            
        </div>
        <div class="col-md-9">
            
            @include('CMS::page.tool.image_bank._index')
                  
        </div>
    </div>
    
    <div class="modal fade" id="modalNewFolder" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{url(Helper::page())}}" class="validate-form">
                <div class="modal-content" style="background: #f4f4f4;"> 
                    <div class="color-line"></div>
                    <div class="modal-header text-center">
                        <h4 class="modal-title">{{ __('Create New Folder') }}</h4>
                        <small class="font-bold">{{ __('by using folders, it\'s easier to group') }}.</small>
                        @if( $position )
                        <div>
                            <ol class="hbreadcrumb breadcrumb text-danger" style="background: none;">
                                @foreach( $position as $k=>$b )
                                    <li>{{ $b['title'] }}</li>
                                @endforeach
                            </ol>
                        </div>
                        @endif
                    </div>
                    <div class="modal-body">
                        
                        <x-form.input name="title" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />

                        <input type="hidden" name="parent" value="{{ request('parent', 0) }}" />
                        <input type="hidden" name="action" value="create_folder" />
                        <input type="hidden" name="mime_type" value="__dir" />
                        @csrf()
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> {{__('Create')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        
                <div class="modal-content">
                    <div class="color-line"></div>
                    <div class="modal-header text-center">
                        <h4 class="modal-title">{{ __('Upload Image') }}</h4>
                        <small class="font-bold">{{ __('by using folders, it\'s easier to group') }}.</small>
                    </div>
                    <div class="modal-body" style="background: #f4f4f4;">
                        @include('CMS::page.tool.image_bank._form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" class="btn btn-primary" disabled="disabled" id="imgbank-button-upload"><i class="fa fa-upload"></i> {{__('Upload')}}</button>
                    </div>
                </div>
                
        </div>
    </div>
@endsection