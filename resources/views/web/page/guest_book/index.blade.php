@extends('WEB::layouts.main')

@section('content')
    <div class="container">
        <form action="{{ url(Helper::page()) }}" method="post" class="ajax-request" data-success-redirect="{{ url(Helper::page()) }}">
            @csrf
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <x-form.input name="first_name" attributes='required="required" minlength="2" maxlength="75"' />
                    </div>
                    <div class="col-md-6">
                        <x-form.input name="last_name" attributes='required="required" minlength="2" maxlength="75"' />
                    </div>
                </div>

                <x-form.input name="organization" attributes='required="required" minlength="2" maxlength="100"' />

                <x-form.select 
                    name="province"
                    label="Province"
                    attribute='required="required"'
                    class="form-control province-option"
                    :values="\App\Models\Location::where('type', 'province')->get()->pluck('nama', 'kode')" />

                <x-form.select 
                    name="city"
                    label="City"
                    class="form-control city-option"
                    attribute='required="required"' />

                <x-form.textarea name="address" label="Address" attributes='required="required" minlength="3"' />
                
                <x-form.button type="submit" label="Send" />
            </div>
        </form>
    </div>
@endsection