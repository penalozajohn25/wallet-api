<?php

namespace App\Http\Controllers;
use Mail;
use App\Client;
use App\Wallet;
use App\Payment;
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

    public function getClients($document) {
        $client = Client::where('document', '=', $document)->first();
        return $client;
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

    public function paymentsWallet(Request $request) {

        $document = $request->document;
        $count = $request->count;

        if (!$document) {
            return response()->json(['menssage' => 'document is required', 'status' => '500'], 500);
        }

        if (!$count) {
            return response()->json(['menssage' => 'count is required', 'status' => '500'], 500);
        }

        $wallet = $this->getWallet($document);
        $client = $this->getClients($document);
        //die($client);
        $n = count($wallet);
        if($n < 0) {
            return response()->json(['menssage' => 'wallet not found', 'status' => '500'], 500);
        }

        $c = count($client);
        if($c < 0) {
            return response()->json(['menssage' => 'client not found', 'status' => '500'], 500);
        }

        $balance = $wallet->balance;
 
        if($count > $balance) {
            return response()->json(['menssage' => 'insufficient balance', 'status' => '500'], 500);
        }

        $token = $this->generarCodigo(6);
        $id_sesion = $this->generarCodigo(6);

        //die($client->email);
        $data = array('id_sesion'=>$id_sesion, 'token'=>$token);
        $subject = "confirmación de la compra";
        $for = $client->email;
        $message = "Para confirmar su pago ingrese la siguiente información id_sesion:".$id_sesion." token:".$token;
        Mail::send('mail',$data, function($msj) use($subject,$for){
            $msj->from("wallet@gmail.com","Payments");
            $msj->subject($subject);
            $msj->to($for);
        });

        $wallet = Payment::create([
            'whallet_id' => $wallet->id,
            'token' => $token,
            'id_sesion' => $id_sesion,
            'status' => 1
        ]);

        return response()->json(['menssage' => 'Payment created successfully', 'status' => '200'], 200);

       // die($token." ". $id_sesion);
    }

    public function generarCodigo($longitud) {
        $key = '';
        $pattern = '1234567890';
        $max = strlen($pattern)-1;
        for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
        return $key;
    }

    public function confirmPayments(Request $request) {
        $id_sesion = $request->id_sesion;
        $token = $request->token;

        if (!$id_sesion) {
            return response()->json(['menssage' => 'id_sesion is required', 'status' => '500'], 500);
        }

        if (!$token) {
            return response()->json(['menssage' => 'token is required', 'status' => '500'], 500);
        }

        $payment = Payment::where('id_sesion', '=', $id_sesion)
            ->where('token', '=', $token)
            ->first();
        $n = count($payment);
        if($n > 0){
            $payment->update([
                'status' => 2,
            ]);
            return response()->json(['menssage' => 'Payment confirmed successfully', 'status' => '200'], 200);

        } else {
            return response()->json(['menssage' => 'id_sesion or token incorrect', 'status' => '500'], 500);
        }
    }

    public function balanceWallet($document){
        //die($document);
        if (!$document) {
            return response()->json(['menssage' => 'document is required', 'status' => '500'], 500);
        }
        //$wallet = $this->getWallet($document);
        $wallet = Wallet::select('balance')
            ->from('wallets')
            ->where('document_client', '=', $document)->get();
        $n = count($wallet);
        if ($n > 0) {
            return $wallet;
        } else {
            return response()->json(['menssage' => 'wallet not found', 'status' => '500'], 500);
        }
    }
}
