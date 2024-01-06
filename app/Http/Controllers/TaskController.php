<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

use DB;
use Carbon\Carbon; 

class TaskController extends Controller
{
	public function index()
	{
		$tasks = Task::all()->sortBy('status');
		return view('tasks',compact('tasks'));
	}

	public function store(Request $request)
	{
		Task::create(['description' => $request->input('description')]);
		return back();
	}

	public function destroy(Request $request)
	{
		$id = $request->input('id') ;
		DB::table('tasks')->whereId($id)->delete();	
		return back();
	}

	public function check(Request $request)
	{
		$id = $request->input('id') ;
		$status = $request->input('status') ? false : true ;

		DB::table('tasks')
			->whereId($id)
			->update([	
				'status' => $status,
				'updated_at' => Carbon::now() 
			]);	
		return back();
	}
}
