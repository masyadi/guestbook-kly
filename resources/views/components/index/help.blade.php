<div class="modal fade" id="modalHelp" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header text-center">
                <h4 class="modal-title">{{__('Bantuan')}}</h4>
                <small class="font-bold">{{__('Di sini Anda bisa mengetahui cara bagaimana menggunakan fitur ini')}}.</small>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h3><i class="fa fa-refresh fa-spin"></i> Loading...</h3>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Tutup')}}</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-body img,
    .modal-body iframe {
        max-width: 100%;
    }
</style>