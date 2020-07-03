<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class walletController extends Controller
{
    //
    public function addClients(Request $request){
        $document = $request->document;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
 
        if(!$document){
         return response()->json(['menssage' => 'document is required', 'status' => '500'], 500);
        }
 
        if(!$name){
         return response()->json(['menssage' => 'name is required', 'status' => '500'], 500);
        }
 
        if(!$email){
         return response()->json(['menssage' => 'mail is required', 'status' => '500'], 500);
        }
 
        if(!$phone){
         return response()->json(['menssage' => 'phone es requerido', 'status' => '500'], 500);
        }
 
        $client = Client::create($request->all());
        return response()->json(['menssage' => 'Client created successfully', 'status' => '200'], 200);  

     }
}
