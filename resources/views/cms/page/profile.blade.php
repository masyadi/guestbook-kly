@extends('CMS::layouts.form')

@section('form')

    <div class="row">
        <div class="col-md-3">
            <x-form.image name="photo" webcam="1" :label="__('Foto Profile')" :row="$row" :dataValue="$row->name" :hideinput="true" :desc_placeholder="$row->name" :onlyupload="true" />
        </div>
        <div class="col-md-9">
            <x-form.input name="name" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />

            <x-form.input name="email" type="email" :row="$row??null" attributes='required="required" minlength="2" maxlength="150"' />

            <x-form.input name="phone" :row="$row??null" attributes='minlength="10" maxlength="15"' />

            <x-form.input name="password" type="password" attributes='placeholder="password"' />
            <x-form.input name="repassword" type="password" :label="__('Ulangi Password')" attributes='placeholder="password"' />

            @if( isset($row->id) )
                <small class="help">*kosongkan password jika tidak ingin mengubah</small>
            @endif
        </div>
    </div>

@endsection