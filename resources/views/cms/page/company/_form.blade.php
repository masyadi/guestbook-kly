<div class="row">
	<div class="col-md-9">
		<fieldset>
			<legend><label>{{__('Informasi Rumah Sakit')}}</label></legend>
			
			<x-form.checkbox name="status" label='Aktif' :row="$row??null" attributes='checked="checked"' />

			<x-form.input name="name" label="Nama Rumah Sakit" :row="$row??null" attributes='placeholder="Nama Rumah Sakit" required="required" maxlength="125"' />			
			<x-form.input name="judul" label="Judul Aplikasi" :row="$row??null" attributes='placeholder="Sistem Informasi Pemeliharaan Alat Kesehatan" required="required" maxlength="125"' />			
			
			<x-form.input name="kode_rs" label="Kode ASPAK" :row="$row??null" attributes='placeholder="Kode Rumah Sakit" required="required" maxlength="20"' />			
	    	
	    </fieldset>
	</div>

	<div class="col-md-3">
		<x-form.image name="logo" :label="__('Logo')" :row="$row??null" :dataValue="isset($row->name)?$row->name:'noname'" :hideinput="true" :desc_placeholder="isset($row->name)?$row->name:'noname'" :onlyupload="true" />
	</div>
	
</div>
