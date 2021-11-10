@extends('CMS::layouts.form')

@section('form')
	
    <x-form.checkbox name="status" label='Aktif' :row="$row??null" />

    <x-form.input name="username" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />
    
    <x-form.input name="name" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />
    
    <x-form.input name="email" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />
    
    <x-form.select name="role_id" label="Role" attributes='required="required"'
    :selected="request('parent_id')" 
    :values="\App\Models\Role::where('status', '1')->where('slug','!=', 'reseller')->pluck('name','id')"
    :row="$row??null" />

    <hr/>
    <x-form.input name="password" type="password" :row="$row??null" attributes='placeholder="password"' />
    @if( isset($row->id) )
        <small class="help">{{__('*kosongkan password jika tidak ingin mengubah')}}</small>
    @endif
    
@endsection