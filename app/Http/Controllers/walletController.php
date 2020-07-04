<?php

namespace App\Http\Controllers;

use App\Client;
use App\Wallet;
use Illuminate\Http\Request;

class walletController extends Controller
{
    //
    public function addClients(Request $request) {
        $document = $request->document;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;

        if (!$document) {
            return response()->json(['menssage' => 'document is required', 'status' => '500'], 500);
        }

        if (!$name) {
            return response()->json(['menssage' => 'name is required', 'status' => '500'], 500);
        }

        if (!$email) {
            return response()->json(['menssage' => 'mail is required', 'status' => '500'], 500);
        }

        if (!$phone) {
            return response()->json(['menssage' => 'phone es requerido', 'status' => '500'], 500);
        }

        $client = Client::create($request->all());
        return response()->json(['menssage' => 'Client created successfully', 'status' => '200'], 200);

    }

    public function addWallet(Request $request) {
        $document = $request->document;

        if (!$document) {
            return response()->json(['menssage' => 'document is required', 'status' => '500'], 500);
        }

        $documentValid = Client::where('document', '=', $document)->first();
        $n = count($documentValid);

        if ($n > 0) {
            $wallet = Wallet::create([
                'document_client' => $document,
                'balance' => 0,
            ]);

            return response()->json(['menssage' => 'Wallet created successfully', 'status' => '200'], 200);
        } else {
            return response()->json(['menssage' => 'document not found', 'status' => '500'], 500);
        }

    }

    public function getWallet($document) {
        $wallet = Wallet::where('document_client', '=', $document)->first();
        return $wallet;
    }

    public function rechargeWallet(Request $request) {
        $document = $request->document;
        $balance = $request->balance;

        if (!$document) {
            return response()->json(['menssage' => 'document is required', 'status' => '500'], 500);
        }

        if (!$balance) {
            return response()->json(['menssage' => 'balance is required', 'status' => '500'], 500);
        }

        $wallet = $this->getWallet($document);
        //die($wallet->balance);
        $balance = $wallet->balance + $balance;
        $n = count($wallet);
        if ($n > 0) {
            $wallet->update([
                'balance' => $balance,
            ]);
            return response()->json(['menssage' => 'Wallet update successfully', 'status' => '200'], 200);

        } else {
            return response()->json(['menssage' => 'wallet not found', 'status' => '500'], 500);
        }
        
    }
}
