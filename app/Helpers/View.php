<?php
namespace App\Helpers;

Trait View {
    
    /**
     * Label Verified
	**/
	static function verified($verified_at)
	{
	   return ' <i class="fa fa-check-circle text-info" title="'.__('Verified at: '.self::formatDate($verified_at)).'"></i>';
	}

	static function action($row, Array $customAction = [], bool $defaultActions = true)
    {
		$actions = [];
		$_defaultActions = [
			'edit' => '<a href="'. self::CMS(self::page().'/'.$row->id.'/edit'.(config('_redirect_params') ? '?'.http_build_query(config('_redirect_params')):'')) .'" class="btn btn-sm btn-default btn-edit" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil"></i> </a>',
			'delete' => '<a href="'. self::CMS(self::page().'/'.$row->id.(config('_redirect_params') ? '?'.http_build_query(config('_redirect_params')):'')) .'" class="btn btn-sm btn-default action-del text-danger btn-delete" data-toggle="tooltip" title="Delete"> <i class="fa fa-trash"></i> </a>',
		];
		
		if ($customAction)
		{
			if (isset($customAction[0]) && is_array($customAction[0]))
			{
				foreach ($customAction as $index => $item)
				{
					if (isset($item['title']))
					{
						$class = isset($item['class']) ? ($item['class'] .' ') : null;
						$class .= isset($item['label']) ? 'btn-'. \Str::slug($item['label']) : null;
						$url = $item['url'] ?? self::CMS(self::page().'/'.$row->id.'/'. ($item['path_url'] ?? ''));
						$link = '<a href="'. $url .'" class="btn btn-sm btn-default '. $class .'" data-toggle="tooltip" '. (isset($item['label']) ? 'title="'. $item['label'] .'"' : null) .''. ($item['attribute'] ?? '') .'>'. $item['title'] .' </a>';
						$actions[\Str::slug($item['title'], '_')] = $link;
					}
				}
			}
			else
			{
				$item = $customAction;

				if (isset($item['title']))
				{
					$class = isset($item['class']) ? ($item['class'] .' ') : null;
					$class .= isset($item['label']) ? 'btn-'. \Str::slug($item['label']) : null;
					$url = $item['url'] ?? self::CMS(self::page().'/'.$row->id.'/'. ($item['path_url'] ?? ''));
					$link = '<a href="'. $url .'" class="btn btn-sm btn-default '. $class .'" data-toggle="tooltip" '. (isset($item['label']) ? 'title="'. $item['label'] .'"' : null) .''. ($item['attribute'] ?? '') .'>'. $item['title'] .' </a>';
					$actions[\Str::slug($item['title'], '_')] = $link;
				}
			}
		}

		if( $defaultActions )
		{
			$actions = array_merge($actions, $_defaultActions);
		}
		
		$action = implode(' ', $actions);
		
		return view('components.index.action', compact('row','action'));
	}
	
	static function status($row, $data=null)
    {
		if( !$data )
		{
			$data = [
				'0' => '<span data-toggle="tooltip" title="'.__('Tidak Aktif').'"><i class="fa fa-remove text-danger"></i></span>',
				'1' => '<span data-toggle="tooltip" title="'.__('Aktif').'"><i class="fa fa-check text-success"></i></span>',
			];
		} 

		return view('components.index.status', compact('row', 'data'));
	}

	static function orderStatus($row, $data=null)
    {
		if( !$data )
		{
			$data = [
				'pending' => '<span class="label label-warning" data-toggle="tooltip" title="'.__('Pending').'">Pending</span>',
				'packing process' => '<span class="label label-info" data-toggle="tooltip" title="'.__('Proses Packing').'">Proses Packing</span>',
				'shipped' => '<span class="label label-primary" data-toggle="tooltip" title="'.__('Dikirimkan').'">Dikirimkan</span>',
				'delivered' => '<span class="label label-success" data-toggle="tooltip" title="'.__('Selesai').'">Selesai</span>',
				'cancelled' => '<span class="label label-danger" data-toggle="tooltip" title="'.__('Batal').'">Batal</span>',
			];
		}

		return view('components.index.status', compact('row', 'data'));
	}

	static function paymentStatus($row, $data=null)
    {
		if( !$data )
		{
			$data = [
				'pending' => '<span class="label label-warning" data-toggle="tooltip" title="'.__('Pending').'">Pending</span>',
				'success' => '<span class="label label-success" data-toggle="tooltip" title="'.__('Lunas').'">Lunas</span>',
				'fail' => '<span class="label label-danger" data-toggle="tooltip" title="'.__('Pembayaran Tidak Valid').'">Tidak Valid</span>',
			];
		}

		return view('components.index.status', compact('row', 'data'));
	}
	
	static function getRoomName($r, $sparator=' &gt; ')
	{
		return implode($sparator, array_reverse(self::_getRoomName($r)));
	}

	static function _getRoomName($r)
	{
		switch( self::val($r, 'type') )
		{
			case 'gedung': $icon = 'fa fa-building'; break;
			case 'ruang': $icon = 'fa fa-h-square'; break;
			case 'kamar': $icon = 'fa fa-home'; break;
			case 'bed': $icon = 'fa fa-bed'; break;
			default: $icon = 'fa fa-circle-o'; break;
		}
		
		$ret = ['<i class="'.$icon.'"></i> '.$r->nama];

		if( isset($r->relparent) )
		{
			$ret = array_merge($ret, self::_getRoomName($r->relparent));
		}

		return $ret;
	}
	static function _getBarangName($r)
	{
		$ret = [$r->nama];

		if( isset($r->relparent) )
		{
			$ret = array_merge($ret, self::_getBarangName($r->relparent));
		}

		return $ret;
	}

}