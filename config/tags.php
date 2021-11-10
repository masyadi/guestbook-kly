<?php

return [
	'type'=> [
	    // 'REGULAR'=>'REGULAR', 
	    // 'BLOG_CATEGORY'=>'BLOG_CATEGORY',
		// 'PRODUCT' => 'Product'
	],
	'free_tag_prefix'=> ':^:-:#:NEW:*:-:^:',
	'free_tag'=> [
		'REGULAR', 
		'BARANG_KATEGORI', 'BARANG_TEKNOLOGI', 'BARANG_FUNGSI',
		'INV_MERK', 'INV_NEGARA', 'INV_KEPEMILIKAN',
		'POSITION'
	],
	'company_tag'=> [
		'INV_KEPEMILIKAN'
	],
	'kondisi_barang'=> [
	    'baik'=>'BAIK', 
	    'rusak'=>'RUSAK',
	    'rusak_ringan'=>'RUSAK RINGAN'
	],
	'jenis_barang'=> [
	    'inventaris'=>'INVENTARIS', 
	    'habis_pakai'=>'HABIS PAKAI',
	    'tak_berwujud'=>'ASSET TIDAK BERWUJUD'
	],
	'jenis_lokasi'=> [
	    'gedung'=>'INSTALASI', 
	    'ruang'=>'RUANGAN', 
	    'kamar'=>'KAMAR', 
	    'bed'=>'BED', 
	],
	'simbada'=> [
        ['name'=>'Barang', 'tipe'=>'barang'],
        ['name'=>'Lokasi', 'tipe'=>'lokasi'],
        ['name'=>'Pemilik', 'tipe'=>'pemilik'],
        ['name'=>'Anggaran', 'tipe'=>'anggaran']
    ]
];