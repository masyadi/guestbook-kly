<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus tanggal setelahnya :date.',
    'after_or_equal' => ':attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa array.',
    'before' => ':attribute harus tanggal sebelumnya :date.',
    'before_or_equal' => ':attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => ':attribute harus diantara :min dan :max.',
        'file' => ':attribute harus diantara :min dan :max kilobyte.',
        'string' => ':attribute harus diantara :min dan :max karakter.',
        'array' => ':attribute harus ada diantara :min dan :max item.',
    ],
    'boolean' => ':attribute harus benar atau salah.',
    'confirmed' => ':attribute konfirmasi tidak cocok.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_equals' => ':attribute harus tanggal yang sama dengan :date.',
    'date_format' => ':attribute tidak sesuai dengan format :format.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus :digits digit.',
    'digits_between' => ':attribute harus diantara :min dan :max digit.',
    'dimensions' => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => ':attribute memiliki nilai duplikat.',
    'email' => ':attribute harus alamat e-mail yang valid.',
    'ends_with' => ':attribute harus diakhiri dengan salah satu following: :values.',
    'exists' => 'Yang dipilih :attribute tidak valid.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute harus punya nilai.',
    'gt' => [
        'numeric' => ':attribute harus lebih besar dari :value.',
        'file' => ':attribute harus lebih besar dari :value kilobyte.',
        'string' => ':attribute harus lebih besar dari :value karakter.',
        'array' => ':attribute harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'file' => ':attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'string' => ':attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array' => ':attribute harus ada :value item atau lebih.',
    ],
    'image' => ':attribute harus berupa gambar.',
    'in' => 'Yang dipilih :attribute tidak valid.',
    'in_array' => ':attribute tidak ada di :other.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'ip' => ':attribute harus alamat IP yang valid.',
    'ipv4' => ':attribute harus alamat IPv4 yang valid.',
    'ipv6' => ':attribute harus alamat IPv6 yang valid.',
    'json' => ':attribute harus berupa string JSON yang valid.',
    'lt' => [
        'numeric' => ':attribute harus kurang dari :value.',
        'file' => ':attribute harus kurang dari :value kilobyte.',
        'string' => ':attributeharus kurang dari :value karakter.',
        'array' => ':attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'file' => ':attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string' => ':attribute harus kurang dari atau sama dengan :value karakter.',
        'array' => ':attribute tidak boleh lebih dari :value item.',
    ],
    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'file' => ':attribute maksimal :max kilobyte.',
        'string' => ':attribute maksimal :max karakter.',
        'array' => ':attribute maksimal :max item.',
    ],
    'mimes' => ':attribute harus berupa file type: :values.',
    'mimetypes' => ':attribute harus berupa file type: :values.',
    'min' => [
        'numeric' => ':attribute minimal harus :min.',
        'file' => ':attribute minimal harus :min kilobyte.',
        'string' => ':attribute minimal harus :min karakter.',
        'array' => ':attribute minimal harus :min item.',
    ],
    'not_in' => 'Yang dipilih :attribute tidak valid.',
    'not_regex' => ':attribute format tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => 'Kata sandi salah.',
    'present' => ':attribute harus ada.',
    'regex' => ':attribute format tidak valid.',
    'required' => ':attribute harus diisi.',
    'required_if' => ':attribute harus diisi jika :other adalah :value.',
    'required_unless' => ':attribute harus diisi kecuali :other ada di :values.',
    'required_with' => ':attribute harus diisi jika :values ada.',
    'required_with_all' => ':attribute harus diisi jika ada :values.',
    'required_without' => ':attribute harus diisi jika :values tidak ada.',
    'required_without_all' => ':attribute harus diisi jika tidak ada dari :values yang ada.',
    'same' => ':attribute dan :other harus cocok.',
    'size' => [
        'numeric' => ':attribute harus :size.',
        'file' => ':attribute harus :size kilobytes.',
        'string' => ':attribute harus :size characters.',
        'array' => ':attribute harus mengandung :size item.',
    ],
    'starts_with' => ':attribute harus dimulai dengan salah satu dari berikut ini: :values.',
    'string' => ':attribute harus berupa string.',
    'timezone' => ':attribute harus zona waktu yang valid.',
    'unique' => ':attribute sudah pada sistem.',
    'uploaded' => ':attribute gagal mengunggah.',
    'url' => ':attribute format tidak valid.',
    'uuid' => ':attribute harus UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'Nama',
        'phone' => 'Telepon',
        'message' => 'Pesan',
        'password' => 'Kata sandi',
        'items.*.shipping.name' => 'Nama pengiriman',
        'items.*.shipping.phone' => 'Telepon',
        'items.*.shipping.provinsi_id' => 'ID provinsi',
        'items.*.shipping.kota_id' => 'ID kota',
        'items.*.shipping.kecamatan_id' => 'ID kecamatan',
        'items.*.shipping.desa' => 'Desa',
        'items.*.shipping.alamat' => 'Alamat',
    ],
];