@extends('CMS::layouts.form')

@section('form')

    <x-form.checkbox name="status" label='Aktif' :row="$row??null" />

    <x-form.input name="path" label="Path Url" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />
    
    <x-form.editor name="content" :row="$row??null" attribute='required="required" minlength="2"' />
    
@endsection