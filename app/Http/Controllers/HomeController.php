<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Combination;
use App\Models\Option;
use App\Models\File;

use DB;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		// id do usuário logado
		$id = Auth::id();

		// Total de palpites
		$total = Option::count();

		// Lista todos os usuários
		$users = User::orderBy(DB::raw('id='.$id), 'desc')->orderBy('name')->get();

		$options = [] ;
		foreach ( $users as $u ) {
			$opts = Option::select('id','created_at','row','checked',DB::raw("to_char(created_at, 'dd/mm/yyyy hh24:mi:ss') AS created_date"))
						->where('user_id','=',$u->id)
						->orderBy('created_at','desc')
						->get();

			foreach ( $opts as &$o ) {
				$data = json_decode($o->row);
				sort($data);
				$o->data = $data ;
			}

			if ( count($opts) != 0 ) {
				$percent = number_format((float)( ( 100 * count($opts) ) / $total ), 2, ',', '');
			}
			else {
				$percent = 0 ;
			}
			array_push($options,[
				'user_id' => $u->id,
				'name' => $u->name,
				'guesses' => $opts,
				'parcial' => count($opts),
				'percent'=> $percent
			]);
		}

		// Carreag aimagens por usuário
		$images = File::select("*",DB::raw("CONCAT(path,name) AS file"))->where('user_id',$id)->get();

		return view('home',compact('users','options','images','total'));
	}
}
