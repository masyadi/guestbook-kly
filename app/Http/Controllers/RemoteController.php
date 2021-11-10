<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class RemoteController extends Controller
{
	function index()
	{
		if( method_exists($this, '_'.\Str::camel(request('act'))) )
		{
			return $this->{'_'.\Str::camel(request('act'))}();
		}
		else return abort(404);
	}

	/** 
	 * LOOKING FOR IMAGEBANK by keywords
	**/
	private function _imgBankKeywords()
	{
		return $this->_imgBankData('keywords');
	}

	/** 
	 * LOOKING FOR IMAGEBANK by location
	**/
	private function _imgBankLocation()
	{
		return $this->_imgBankData('location');
	}

	/** 
	 * LOOKING FOR IMAGEBANK by location
	**/
	private function _imgBankEvent()
	{
		return $this->_imgBankData('event');
	}
	/** 
	 * LOOKING FOR IMAGEBANK by location
	**/
	private function _imgBankPhotographer()
	{
		return $this->_imgBankData('photographer');
	}
	/** 
	 * LOOKING FOR IMAGEBANK by location
	**/
	private function _imgBankCopyright()
	{
		return $this->_imgBankData('copyright');
	}

	/** 
	 * LOOKING FOR IMAGEBANK by keywords
	**/
	private function _imgBankData($field)
	{
		$rows = \App\Models\ImageBank::distinct($field)->select($field)->whereNotNull($field)->where(function($q) use($field) {
			if( request('q') )
			{
				$q->orWhere($field, 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy($field)->get();

		$ret = [
			['id'=>request('q'), 'name'=>request('q')]
		];

		if( $rows )
		{
			foreach( $rows as $r )
			{
				$ret[] = ['id'=>$r->{$field}, 'name'=>$r->{$field}];
			}
		}

		return response()->json($ret);
	}

	/** 
	 * LOOKING FOR CITY by name and country code
	**/
	private function _lookupLocation()
	{
		$rows = \App\Models\Location::selectRaw('master_id as id, '.( in_array('kota',request('type')) ? 'CONCAT(type, " ", name)' : 'name' ).' as name')
				->whereIn('type', request('type', []))
				->where(function($q){
					if( request('q') )
					{
						$q->orWhere('name', 'LIKE', '%'.request('q').'%');
					}
				})
				->orderBy('name');

		if(request('parent') != null){
			$rows->where('parent_id', request('parent', 0));
		}

		return response()->json($rows->get());
	}

	/** 
	 * LOOKING FOR Tag by name
	**/
	private function _lookupTag()
	{
		$rows = \App\Models\Tag::select('id', 'title as name')->where('status', '<>', '-1')->where(function($q){
			if( request('q') )
			{
				$q->orWhere('title', 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy('title');

		if( request('type') )
		{
			if( in_array(request('type'), config('tags.company_tag')) )
			{
				$rows->where('company_id',  session('company.company.id'));
			}

			$rows->where('type', request('type'));


			//ALLOWED FREE TAGGING
			if( in_array(request('type'), config('tags.free_tag')) )
			{
				if( $rows->count()==0 )
				{
					return response()->json([
						['id'=> config('tags.free_tag_prefix').request('q'), 'name'=>ucwords(request('q'))]
					]);
				}
			}
		}

		return response()->json($rows->get());
	}

	/** 
	 * LOOKING FOR Pendanaan by name
	**/
	private function _lookupPendanaan()
	{
		return $this->_lookupMaster(\App\Models\MstPendanaan::class);
	}

	/** 
	 * LOOKING FOR Rekanan by name
	**/
	private function _lookupRekanan()
	{
		return $this->_lookupMaster(\App\Models\MstRekanan::class);
	}

	/** 
	 * LOOKING FOR Distributor by name
	**/
	private function _lookupDistributor()
	{
		return $this->_lookupMaster(\App\Models\MstDistributor::class, ['id', 'nama as name'], false);
	}

	/** 
	 * LOOKING FOR Merk by name
	**/
	private function _lookupType()
	{
		$rows = \App\Models\MstDistributorType::with('reldistributor')->select('*', 'label as name')->where('status', '<>', '-1')->where(function($q){
			if( request('q') )
			{
				$q->orWhere('label', 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy('label');

		return response()->json($rows->get());
	}

	/** 
	 * LOOKING FOR Barang by name
	**/
	private function _lookupBarang()
	{
		$rows = $this->_lookupMaster(\App\Models\MstBarang::class, ['id', 'parent', 'nama as name', 'jenis'], true, false);
		
		$rows = $rows->with(['relparent'])->get();

		if( $rows->count()>0 )
		{
			foreach( $rows as $k=>$r )
			{
				$r['label'] = ucwords(strtolower(implode(' > ', array_filter(array_reverse(\Helper::_getBarangName($r))))));

				$rows[$k] = $r;
			}
		}
		else
		{
			$rows = [
				['id'=> config('tags.free_tag_prefix').request('q'), 'name'=>ucwords(request('q')), 'label'=>'']
			];	
		}

		return response()->json($rows);
	}

	private function _lookupMaster($model, $select=['id', 'nama as name'], $useCompany=true, $json=true)
	{
		$rows = $model::select($select)->where('status', '<>', '-1')->where(function($q){
			if( request('q') )
			{
				$q->orWhere('nama', 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy('nama');

		if( $useCompany )
		{
			if( $c = session('company.company.id') ) $rows->whereIn('company_id', [0, $c]);
		}

		if( !$json ) return $rows;

		if( $rows->count()==0 )
		{
			return response()->json([
				['id'=> config('tags.free_tag_prefix').request('q'), 'name'=>ucwords(request('q'))]
			]);
		}

		return response()->json($rows->get());
	}

	/** 
	 * LOOKING FOR Company by name
	**/
	private function _lookupCompany()
	{
		$rows = \App\Models\Company::select('id', 'name')->where('status', '<>', '-1')->where(function($q){
			if( request('q') )
			{
				$q->orWhere('name', 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy('name');

		return response()->json($rows->get());
	}

	/** 
	 * LOOKING FOR Room by name
	**/
	private function _lookupRoom()
	{
		$rows = \App\Models\MstRoom::with(['relparent'])->where(function($q){
			if( request('q') )
			{
				$q->orWhere('nama', 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy('nama');

		$ret = [];
		if( $rows = $rows->get() )
		{
			foreach( $rows as $r )
			{
				$ret[] = [
					'id'=> $r->id,
					'name'=> \Helper::getRoomName($r)
				];
			}
		}
		
		return response()->json($ret);
	}

	/** 
	 * LOOKING FOR USER
	**/
	private function _lookupUser()
	{
		$rows = \App\User::selectRaw("id, CONCAT(name,' &lt;',email,'&gt;') as name")->where(function($q){
			if( request('q') )
			{
				$q->orWhere('name', 'LIKE', '%'.request('q').'%');
				$q->orWhere('email', 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy('name');

		if( $c= session('company.company.id') )
		{
			$rows->whereHas('relcompany', function($q) use($c) {
				$q->where('company_id', $c);
			});
		}

		return response()->json($rows->get());
	}

	/** 
	 * LOOKING FOR PRODUCTS
	**/
	private function _lookupProduct()
	{
		$rows = \App\Models\Product::selectRaw("id, name")->where('parent_id', 0)->where(function($q){
			if( request('q') )
			{
				$q->orWhere('name', 'LIKE', '%'.request('q').'%');
				$q->orWhere('description', 'LIKE', '%'.request('q').'%');
			}
		})
		->limit(25)->orderBy('name');

		return response()->json($rows->get());
	}

	/** 
	 * GET USER NOTIF
	**/
	private function _getCount()
	{
		$user = \Helper::user();
		$notifUnread = \DB::table('notifications')->where('notifiable_id', $user->id)->whereNull('read_at')->count();

		return [
			'notif'=> \Helper::formatNumber($notifUnread),
		];
	}

	/** 
	 * GET DASHBOARD
	**/
	private function _getDashboard()
	{
		$pref = \DB::getTablePrefix();
	}


	private function _getPageBlog()
	{
		return response()->json([
			'page'=> \Helper::formatNumber(\App\Models\Page::where('status', '<>', '-1')->count()),
			'blog'=> \Helper::formatNumber(\App\Models\Blog::where('status', '<>', '-1')->count()),
			'row_blog'=> \App\Models\Blog::where('status', '<>', '-1')->orderBy('id', 'DESC')->with(['relcategory', 'relauthor'])->limit(5)->get(),
			'row_page'=> \App\Models\Page::where('status', '<>', '-1')->orderBy('id', 'DESC')->with(['relauthor'])->limit(5)->get(),
		]);
	}

	/** 
	 * HELP
	**/
	private function _getHelp()
	{
		$ret = ['sts'=>false, 'content'=>__('Mohon maaf, bantuan belum tersedia')];

		if( $p = request('page') )
		{
			if( $c = \App\Models\Help::where('path', $p)->first() )
			{
				$ret = ['sts'=>true, 'content'=>$c->content, 'last_update'=>\Helper::formatDate($c->update_at, 'd M Y H:i')];
			}
		}

		return response()->json($ret);
	}

	/** 
	 * AUTOSAVE
	**/
	private function _autoSave()
	{
		$ret = ['status'=>false, 'content'=>'Failed to save'];

		$file = storage_path('autosave');
		$file.= '/'.request('page').'_'.\Auth::user()->id.'.json';

		if( !file_exists(storage_path('autosave')) ) mkdir(storage_path('autosave'), 0777, true);

		if( request('id') )
		{
			//delete old data
			if(file_exists($file)) unlink($file);
		}

		if(\File::put($file, json_encode(request()->all())))
		{
			$ret = ['status'=>true, 'content'=>'Auto saved'];
		}

		return response()->json($ret);
	}

	private function _resetAutoSave()
	{
		$ref = request()->header('referer');

		\Session::forget('autosaved_state');

		$page = \Helper::page($ref);

		\Helper::deleteAutoSave($page);

		return back();
	}

	private function _generateReport()
	{
		return request('source').'&'.http_build_query(request()->except('_token'));
	}

	private function _youtubeThumbnail()
	{
		return \Helper::getYoutubeThumbnail(request('url'), request('quality'));
	}

	private function _changeLocale()
	{
		$language = request('locale', config('app.locale'));
		session(['selected_locale' => $language]);
	}

	private function _getSchedule()
	{
		$rows = \App\Models\TrainerSchedule::with(['trainer'])->where('status', '1')->get();

		return $rows;
	}
}