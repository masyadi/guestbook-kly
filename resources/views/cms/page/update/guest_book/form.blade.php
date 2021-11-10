@extends('CMS::layouts.form')

@section('form')
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <x-form.input name="first_name" :label="__('First Name')" :row="$row??null" attributes='required="required" minlength="2" maxlength="75"' />
                </div>
                <div class="col-md-6">
                    <x-form.input name="last_name" :label="__('Last Name')" :row="$row??null" attributes='required="required" minlength="2" maxlength="75"' />
                </div>
            </div>
            <x-form.input name="organization" :label="__('Organization')" :row="$row??null" attributes='required="required" minlength="2" maxlength="75"' />

            <x-form.select 
				name="province"
                label="Province"
                attribute='required="required"'
                class="form-control province-option"
				:values="\App\Models\Location::where('type', 'province')->get()->pluck('nama', 'kode')"
				:row="$row??null" />

            <x-form.select 
				name="city"
                label="City"
                class="form-control city-option"
                attribute='required="required" data-selected="{{ isset($row) ? $row->city : null }}"' />

            <x-form.textarea name="address" label="Address" :default="$row->address??null" attributes='required="required" minlength="3"' />
        </div>
    </div>
@endsection