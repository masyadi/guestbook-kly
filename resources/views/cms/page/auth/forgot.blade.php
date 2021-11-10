@extends('CMS::layouts.less')

@section('content')
<form method="POST" style="margin-bottom: 25px" class="validate-form">

    <x-form.input name="user" attributes='placeholder="username / email" required="required"' />

    <button type="submit" class="btn btn-primary" data-label="{{__('Resetting...')}}">{{__('Reset')}}</button>

    <div class="pull-right"><a href="{{Helper::CMS('login?_c='.request('_c'))}}" class="btn btn-link">{{__('Login')}}</a></div>

    @csrf()
</form>
@endsection