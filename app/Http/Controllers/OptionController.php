<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;

use App\Models\Option;
use App\Models\File;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OptionController extends Controller
{
	public function index()
	{
		$tasks = Task::all();
		
		return view('tasks',compact('tasks'));
	}

	public function destroy(Request $request)
	{
		$id = $request->input('id');
		Option::whereId($id)->delete();
		return back()->with(['success'=> 'Aposta apagada!']);
	}

	public function store(Request $request)
	{
		// separa palpites e codifica para json
		$guesses = trim($request->input('guesses'));
		$list = explode(" ",$guesses) ;

		// ordenar lista
		sort($list);
		$json_list = json_encode($list);

		// Join the words back into a string
		$ordered_text = implode(" ", $list);

		// Usuário
		$user_id = trim($request->input('user_id'));

		Option::insert([
			'user_id' => $user_id ,
			'row' => $json_list ,
			'created_at' => Carbon::now() ,
			'updated_at' => Carbon::now() ,
		]);

		return back()->with(['success'=> 'Palpite: ' . $ordered_text]);
	}

	/* Store a newly created resource in storage.
	*
	* @param  \App\Http\Requests\StoreFilesRequest  $request
	* @return \Illuminate\Http\Response
	*/
	function store_file ( Request $request ) {

		//dd($_REQUEST);
		$user_id = $request->user_id ;

		/* Cria diretório caso não exista */
		$destinationPath = public_path('/recibos');
		if (!file_exists($destinationPath)) {
			mkdir($destinationPath, 0777, true);
		}

		if($request->hasfile('filenames'))
		{
			foreach($request->file('filenames') as $file)
			{
	   	   		// Define um aleatório para o arquivo baseado no timestamps atual
			  	$name = time() . '_'.$file->getClientOriginalName();
				$img = Image::make($file->path());

			  	//$img->save($destinationPath . '/' . $name ) ;
				$img->resize(400,400, function ($constraint) {
					$constraint->aspectRatio();
				})->save($destinationPath.'/'.$name);
			
				File::insert([
					'name' => $name,
					'path' => $destinationPath,
					'original' => $file->getClientOriginalName() ,
					'user_id' => $user_id ,
					'created_at' => Carbon::now() ,
				 	'updated_at' => Carbon::now() 
				]);
			}
		}
	  
	   	return back()->with('success','Arquivos salvos com sucesso!');
   }

   public function check(Request $request)
   {
	   // separa palpites e codifica para json
	   $id = $request->input('id');

	   Option::whereId($id)
	   		->update(
				[
					'checked' => true ,
		   			'updated_at' => Carbon::now() ,
	   			]
		);
	  

	   return back()->with(['success'=> 'Palpite confirmado']);
   }

   	public function find()
   	{
		$guesses = $_GET['guesses'];
		$total = Option::query()->whereJsonContains('row',$guesses)->count();

		return json_encode(['total' => $total]);
	}
}
