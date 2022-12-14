<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
class PaymentService
{
    public function createVNP($order_code, $total_price, $request = null)
    {
        $vnp_Url = config('common.vnp_sandbox');
        $vnp_Returnurl = optional($request)->return_url ?? config('common.vnp_returnUrl'); // config return url not ipn url, fe config
        $vnp_TmnCode = config('common.vnp_TmnCode');
        $vnp_HashSecret = config('common.vnp_HashSecret');

        $vnp_TxnRef = $order_code;
        $vnp_OrderInfo = 'Thanh toán đơn hàng ' . $order_code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $total_price * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'VNPAY';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = array(
            'code' => '00', 'message' => 'success', 'vnp_url' => $vnp_Url
        );

        return response()->json($returnData);
    }

    public function checkTransVNP($order)
    {
        $vnp_Url = 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction';
        $vnp_TmnCode = config('common.vnp_TmnCode');
        $vnp_RequestId = time();
        $vnp_IpAddr = request()->ip();
        $vnp_Locale = 'vn';
        $vnp_OrderInfo = 'Thanh toán đơn hàng ' . $order->code;
        $vnp_HashSecret = config('common.vnp_HashSecret');
        $inputData = array(
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Command" => "refund",
            "vnp_TransactionType" => '02',
            "vnp_Amount" => $order->payments()->latest()->first()->amount,
            "vnp_CreateBy" => "linhloi2k2@gmail.com",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_TxnRef" => $order->code,
            "vnp_TransactionNo" => $order->last_payment()->transaction_no,
            "vnp_TransactionDate" => (int) str_replace(["-"," ",":"], "", $order->last_payment()->created_at),
            "vnp_CreateDate" => (int) date("YmdHis")
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach($inputData as $key => $value) {
            $hashdata .= $value . '|';
        }

        $hashdata = $inputData['vnp_RequestId'] . "|" . $inputData['vnp_Version'] . "|" . $inputData['vnp_Command'] . "|" . $inputData['vnp_TmnCode'] . "|" . $inputData['vnp_TransactionType'] . "|" . $inputData['vnp_TxnRef'] . "|" . $inputData['vnp_Amount'] . "|" . $inputData['vnp_TransactionNo'] . "|" . $inputData['vnp_TransactionDate'] . "|" . $inputData['vnp_CreateBy'] . "|" . $inputData['vnp_CreateDate'] . "|" . $inputData['vnp_IpAddr'] . "|" . $inputData['vnp_OrderInfo'];
        $checksum = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        $inputData['vnp_SecureHash'] = $checksum;

        $client = new GuzzleClient();

        $req = $client->post($vnp_Url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($inputData)
        ]);

        dd($req->getBody()->__toString());

        return $vnp_Url;
    }
}
