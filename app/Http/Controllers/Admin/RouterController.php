<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\Mikrotik;
use App\Models\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouterController extends Controller
{
    public function view(Request $request) {
        $data = [
            'menu' => 'routers',
            'routers' => Router::get(),
        ];
        return view('admin.router', $data);
    }

    public function add(Request $request) {
        
        if ($request->isMethod('get')) {
            $data = [
                'menu' => 'routers',
            ];
            return view('admin.router-add', $data);
        }

        $validator = Validator::make($request->all(), [
            'host' => 'required|unique:routers|ip',
            'user' => 'required',
            'pass' => 'required',
            'name' => 'required|unique:routers',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $router = new Router();
        $router->host = $request->host;
        $router->user = $request->user;
        $router->pass = $request->pass;
        $router->name = $request->name;
        $router->currency = $request->currency;
        $router->phone = $request->phone;
        $router->save();

        return redirect('/routers');
    }

    public function edit(Router $router, Request $request) {
        if ($request->isMethod('get')) {
            $data = [
                'menu' => 'routers',
                'router' => $router,
            ];
            return view('admin.router-edit', $data);
        }

        $validator = Validator::make($request->all(), [
            'host' => 'required|ip',
            'user' => 'required',
            'pass' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Router::where('host', $request->host)->where('id', '!=', $router->id)->first()) 
        {
            return back()->withErrors([
                'host' => 'Host is already in use.'
            ])->withInput();
        }

        if (Router::where('name', $request->name)->where('id', '!=', $router->id)->first()) 
        {
            return back()->withErrors([
                'name' => 'Name is already in use.'
            ])->withInput();
        }

        $router->host = $request->host;
        $router->user = $request->user;
        $router->pass = $request->pass;
        $router->name = $request->name;
        $router->currency = $request->currency;
        $router->phone = $request->phone;
        $router->save();

        $data = [
            'menu' => 'routers',
            'router' => $router,
            'success' => 'Changes have been saved.',
        ];
        return view('admin.router-edit', $data);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'The request sent is invalid.',
            ];
        }

        $router = Router::findOr($request->id, function() {
            return [
                'success' => false,
                'message' => 'Router not found.',
            ];
        });
        $router->delete();

        return [
            'success' => true,
            'message' => 'The router has been successfully deleted',
        ];
    }

    public function connect(Request $request) {
        $router = Router::find($request->id);
        $connect = Mikrotik::connect($router->id);
        return ($connect) 
            ? [
                'success' => true,
                'message' => 'Connected'
            ]
            : [
                'success' => false,
                'message' => 'Cannot connected to router '.$router->name
            ];
    }
}
