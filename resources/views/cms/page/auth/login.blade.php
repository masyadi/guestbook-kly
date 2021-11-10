@extends('CMS::layouts.less')

@section('content')
<form method="POST" style="margin-bottom: 25px" class="validate-form">
    
    <x-form.input name="user" attributes='placeholder="username / email" required="required"' />
    
    <x-form.input name="password" type="password" attributes='placeholder="password" required="required"' />
    
    <x-form.button type="submit" label="{{__('Login')}}" attributes='class="btn btn-primary" data-label="Authorising"' />

    <div class="pull-right"><a href="{{Helper::CMS('forgot?_c='.request('_c'))}}" class="btn btn-link">{{__('Forgot password')}}</a></div>

    @csrf()
</form>
@endsection