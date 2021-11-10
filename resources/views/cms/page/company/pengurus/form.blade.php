@extends('CMS::layouts.form')

@section('form')
	
	@if( isset($row->id) )
        @if( $row->status=='0' && !$row->email_verified_at )
    	   <x-form.checkbox name="invite" label='Resend Invitation' :row="$row??null" attributes='checked="checked"' />
        @else
            <x-form.checkbox name="status" label='Aktif' :row="$row??null" />
        @endif
    @else
        <x-form.checkbox name="invite" label='Send Invitation' :row="$row??null" attributes='checked="checked"' />
    @endif
    
    <x-form.input name="name" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />
    
    <x-form.input name="email" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />
    
    <x-form.select name="role_id" label="Role" attributes='required="required"'
    :selected="request('parent_id')" 
    :values="\App\Models\Role::where('status', '1')->whereNotIn('slug', ['super-admin'])->pluck('name','id')"
    :row="$row??null" />
    
@endsection