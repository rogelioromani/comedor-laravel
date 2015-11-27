<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;

class UsersController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$users = User::filterAndPaginate($request->get('nombre'));
		
		return view('admin.users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.users.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	//Si pasamos CreareUserRequest usamos el FormValidation que creamos
	public function store(CreateUserRequest $request)
	{	
		/*
		 * Forma antigua de validacion pero valida y vigente
		 *
		 *		$data = $request->all();
		 *		$rules = array(
		 *			'first_name' => 'required',
		 *			'last_name'  => 'required', 
		 *			'email'      => 'required',
		 *			'password'   => 'required',
		 *			'type'       => 'required'
		 *		);
		 *	
		 *		$v = \Validator::make($data, $rules);
		 *		
		 *		if ($v->fails())
		 *		{
		 *			return redirect()->back()
		 *				->withErrors($v->errors())
		 *				->withInput($request->except('password'));
		 *		}
		 */

		/*
		 * Formas Nuevas de validacion
		 * Con la funcion validate
		 *
		 * $rules = array(
		 * 	'first_name' => 'required',
		 *  	'last_name'  => 'required', 
		 *  	'email'      => 'required',
		 *  	'password'   => 'required',
		 *  	'type'       => 'required'
		 *  );
		 * $this->validate($request, $rules);
		 */

		//La tercera forma de validacion es con los FormValidation
		$user = new User($request->all());
		$user->created_at = date('Y-m-d H:i:s');
		$user->updated_at = date('Y-m-d H:i:s');
		$user->save();

		return redirect()->route('admin.users.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::findOrFail($id);
		return view('admin.users.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(EditUserRequest $request, $id)
	{
		$user = User::findOrFail($id);
		$user->fill($request->all());
		$user->save();
		return redirect()->route('admin.users.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request)
	{
		$user = User::findOrFail($id);
		$user->delete();

		$message = $user->full_name.' fue eliminado de los registros';

		if ($request->ajax())
		{
			return response()->json([
				'id'      => $user->id,
				'message' => $message
			]);
		}

		Session::flash('message', $message);

		return redirect()->route('admin.users.index');
	}

}
