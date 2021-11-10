@extends('CMS::layouts.less')

@section('content')
<form method="POST" style="margin-bottom: 25px" class="validate-form">

    <x-form.input name="password_baru" type="password" attributes='placeholder="Password Baru" required="required"' />
    
    <x-form.input name="ulangi_password" type="password" :label="__('Ulangi Password')" attributes='placeholder="Ulangi Password Baru" required="required"' />

    <button type="submit" class="btn btn-primary" data-label="{{__('Resetting...')}}">{{__('Reset')}}</button>

    <div class="pull-right"><a href="{{Helper::CMS('login?_c='.request('_c'))}}" class="btn btn-link">{{__('Login')}}</a></div>

    @csrf()
</form>
@endsection