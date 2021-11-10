@extends('CMS::layouts.form')

@section('form')

    <x-form.checkbox name="status" label='Aktif' :row="$row??null" />

    <x-form.select name="parent" 
        :selected="request('parent_id')" 
        :values="[0=>'---'] + \App\Models\Menu::where('status', '1')->pluck('name','id')->toArray()"
        :row="$row??null" />

    <x-form.input name="name" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />
    
    <x-form.input name="slug" :row="$row??null" label='Menu Url' attributes='required="required" minlength="1" maxlength="150"' />
    
    <x-form.textarea name="description" :row="$row??null" attributes='minlength="2" maxlength="255"' />

    <hr/>

    <x-form.radio name="show_all" label="Show Menu in" 
        :row="$row??null" 
        :checked="isset($row->show_all) ? $row->show_all : null" 
        :values="[
	    	'1'=> 'All Role',
	    	'0'=> 'Selected Role',
    	]" 
        attributes='required="required"' />

    <div style="display: {{ isset($row) && $row->show_all=='1' ? 'none' : 'block'  }};" id="rowArea">

        @if( (isset($row) && $row->show_all=='1' )  )
        <x-form.select name="roles[]" label='Role'
            :selected="isset($roles) ? $roles : []" 
            :values="\App\Models\Role::where('status', '1')->pluck('name','id')"
            attribute='multiple="multiple" required="required" disabled="disabled"'
            :row="$row??null" />
        @else
        <x-form.select name="roles[]" label='Role'
            :selected="isset($roles) ? $roles : []" 
            :values="\App\Models\Role::where('status', '1')->pluck('name','id')"
            attribute='multiple="multiple" required="required"'
            :row="$row??null" />
        @endif

    </div>

@endsection