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
        <form method="post" class="validate-form" action="{{url('message')}}">

            <div class="hpanel email-compose">
                <div class="panel-heading hbuilt">
                    <div class="p-xs h4">

                        {{__('New Message')}}

                    </div>
                </div>
                <div class="panel-heading hbuilt">
                    <div class="p-xs">
                            
                            <x-form.token name="to_id" :label="__('Kepada')" attribute='required="required"'
                            :default="request('to')" 
                            :model="\App\User::query()"
                            :row="$row??null"
                            :max="1" field="name" :url="url('remote?act=lookup-user')" />

                            <x-form.input name="subject" :default="request('subject')" attributes='required="required" minlength="2" maxlength="255"' />

                    </div>
                </div>
                <div class="panel-body">
                    <x-form.editor name="content"  attributes='required="required"' />
                </div>

                <div class="panel-footer">
                    <button type="submit" name="send" value="1" class="btn btn-primary">{{__('Send Message')}}</button>
                    @csrf()
                </div>
            </div>

        </form>
    </div>
</div>
@endsection