@if( session('autosaved_state') )
<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>{{__('Auto Save!')}}</strong> {{__('Data ini otomatis terisi karena dari auto save')}}.
    {{__('Jika Anda akan mengosongkan isian dibawah ini, silakan klik ')}} 
    <a href="{{ Helper::CMS('remote?act=reset-autosave') }}">{{__('di sini')}}</a>
  </div>
@endif