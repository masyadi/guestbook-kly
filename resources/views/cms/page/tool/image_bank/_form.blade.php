<div class="row">
    <div class="col-md-5 input-caption">
        <form method="POST" action="{{Helper::CMS('image-bank')}}" class="validate-form" id="imgbank-form-upload">

            <x-form.input name="title" attributes='required="required" minlength="2" maxlength="125"' />

            <div class="row">
                <div class="col-md-6">
                    <x-form.input name="event" attributes='required="required" minlength="2" maxlength="75"' />
                </div>
                <div class="col-md-6">
                    <x-form.date_time name="date" :row="$row??null" :default="date('m/d/Y')" attributes='required="required"' />
                </div>
            </div>
            
            <x-form.input name="location" attributes='maxlength="255"' />

            <div class="row">
                <div class="col-md-6">
                    <x-form.input name="photographer" attributes='maxlength="75"' default="{{Auth::guard('cms')->user()->name}}" />
                </div>
                <div class="col-md-6">
                    <x-form.input name="copyright" attributes='maxlength="75"' default="{{config('app.name')}}" />
                </div>
            </div>
            
            <div class="caption">
                <x-form.textarea name="caption" attributes='required="required" minlength="2" maxlength="255"' />
            </div>

            <x-form.textarea name="keywords" attributes='maxlength="255"' />

            <input type="hidden" name="parent" value="{{ request('parent', 0) }}" />
            <input type="hidden" name="folder" value="{{ Helper::page() }}" />
            <input type="hidden" name="action" value="upload" />
            @csrf()
        </form>
    </div>
    <div class="col-md-7 input-photo">
        
        <div id="dropzone">
            <form action="{{Helper::CMS('image-bank')}}" class="dropzone needsclick" id="imagebank-uploader">    
              <div class="dz-message needsclick">
                {{__('Drop files here or click to upload.')}}<br />
                <span class="note needsclick">{{ __('You can upload multiple files') }}</span>
              </div>
              @csrf()
              <input type="hidden" name="action" value="upload_temp" />
            </form>
        </div>

    </div>
</div>