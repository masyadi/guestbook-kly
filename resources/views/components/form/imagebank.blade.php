@if( Helper::page() != 'image-bank' )
<div class="modal fade" id="modalImageBankPopup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    
            <div class="modal-content">
                <div class="color-line"></div>
                <div class="modal-header text-center">
                    <h4 class="modal-title">{{ __('IMAGE BANK') }}</h4>
                    <small class="font-bold">{{ __('You can choose an existing image, or you can upload a new one') }}.</small>
                </div>
                <div class="modal-body" style="background: #f4f4f4; padding: 0;">
                    
                    <div class="hpanel">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-img-bank"> {{__('Image Bank')}} </a></li>
                            <li class=""><a data-toggle="tab" href="#tab-img-upload">{{__('Upload Image')}}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-img-bank" class="tab-pane active">
                                <div class="panel-body">
                                    <div id="imagebank-list"></div>
                                </div>
                            </div>
                            <div id="tab-img-upload" class="tab-pane">
                                <div class="panel-body" style="border: none;">
                                    @include('CMS::page.tool.image_bank._form')
                                    <hr>
                                    <button type="button" class="btn btn-primary" disabled="disabled" id="imgbank-button-upload"><i class="fa fa-upload"></i> {{__('Upload')}}</button>    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
            
    </div>
</div>


<div class="modal fade" id="modal-webcam" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header text-center">
                <h4 class="modal-title">{{__('KAMERA')}}</h4>
                <small class="font-bold">{{__('izinkan kamera Anda untuk mengambil gambar')}}</small>
            </div>
            <div class="modal-body" style="padding: 0">
                <div id="camera-take-picture"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal">{{__('Tutup')}}</button>
                <button type="button" class="btn btn-primary btn-take-picture">{{__('Ambil Foto')}}</button>
                <button type="button" style="display: none" class="btn btn-default repeat-camera">{{__('Ambil Ulang')}}</button>
                <button type="button" style="display: none" class="btn btn-primary save-camera">{{__('Simpan')}}</button>
            </div>
        </div>
    </div>
</div>

<style>
    #camera-take-picture,
    #camera-take-picture canvas,
    #camera-take-picture video {
        width: 100%!important;
        height: auto!important;
    }
</style>

@endif