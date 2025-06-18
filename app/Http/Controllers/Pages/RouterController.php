<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Helpers\Mikrotik;
use App\Models\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouterController extends Controller
{
    public function connect(Request $request) {
        $router = Router::find($request->id);
        $connected = Mikrotik::connect($router);
        if ($connected) {
            session(['router' => $router->host]);
            return [
                'success' => true,
                'message' => 'Connected successfull.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Cannot connected to the router.'
            ];
        }
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

    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'data' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'The request sent is invalid.',
            ];
        }

        $router = Router::firstWhere('host', session('router'));
        foreach($request->data as $data) {
            $router->{$data['name']} = $data['value'];
        }
        $router->save();

        return [
            'success' => true,
            'message' => 'The router has been update.'
        ];
    }
}
