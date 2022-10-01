<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Alert;

class PaymetController extends Controller
{
    public function Pay(Request $request)
    {
        abort_unless(Gate::allows('pay_access'), 403);
        $rules = [
            'customer_name' => 'required', 'max:80',
            'customer_email' => 'required', 'max:120',
            'customer_mobile' => 'required', 'number', 'max:40',
        ];
        $request->validate($rules);

        $json =  [
            "locale" => "es_CO",
            "auth" => [
                "auth" => [
                    "login" => env('BASE_LOGIN'),
                    "tranKey" => env('BASE_SECRETKEY'),
                    "nonce" => "NjE0OWVkODgwYjNhNw==",
                    "seed" => "2021-09-21T09:34:48-05:00"
                ]
            ],
            "payer" => [
                "document" => "1122334455",
                "documentType" => "CC",
                "name" => "John",
                "surname" => "Doe",
                "company" => "Evertec",
                "email" => "johndoe@app.com",
                "mobile" => "+5731111111111",
                "address" => [
                    "street" => "Calle falsa 123",
                    "city" => "Medellín",
                    "state" => "Poblado",
                    "postalCode" => "55555",
                    "country" => "Colombia",
                    "phone" => "+573111111111"
                ]
            ],
            "buyer" => [
                "document" => "1122334455",
                "documentType" => "CC",
                "name" => "John",
                "surname" => $request->customer_name,
                "company" => "Evertec",
                "email" => $request->customer_email,
                "mobile" => $request->customer_mobile,
                "address" => [
                    "street" => "Calle falsa 123",
                    "city" => "Medellín",
                    "state" => "Poblado",
                    "postalCode" => "55555",
                    "country" => "Colombia",
                    "phone" => "+573111111111"
                ]
            ],
            "payment" => [
                "reference" => "12345",
                "description" => "Prueba de pago",
                "amount" => [
                    "currency" => "COP",
                    "total" => 2000,
                    "taxes" => [
                        [
                            "kind" => "valueAddedTax",
                            "amount" => 1000,
                            "base" => 0
                        ]
                    ],
                    "details" => [
                        [
                            "kind" => "discount",
                            "amount" => 1000
                        ]
                    ]
                ],
                "allowPartial" => false,
                "shipping" => [
                    "document" => "1122334455",
                    "documentType" => "CC",
                    "name" => "John",
                    "surname" => "Doe",
                    "company" => "Evertec",
                    "email" => "johndoe@app.com",
                    "mobile" => "+5731111111111",
                    "address" => [
                        "street" => "Calle falsa 123",
                        "city" => "Medellín",
                        "state" => "Poblado",
                        "postalCode" => "55555",
                        "country" => "Colombia",
                        "phone" => "+573111111111"
                    ]
                ],
                "items" => [
                    [
                        "sku" => "12345",
                        "name" => "product_1",
                        "category" => "physical",
                        "qty" => "1",
                        "price" => 1000,
                        "tax" => 0
                    ]
                ],
                "fields" => [
                    [
                        "keyword" => "test_field_value",
                        "value" => "test_field",
                        "displayOn" => "approved"
                    ]
                ],
                "recurring" => [
                    "periodicity" => "D",
                    "interval" => "1",
                    "nextPayment" => "2019-08-24",
                    "maxPeriods" => 1,
                    "dueDate " => "2019-09-24",
                    "notificationUrl " => "https://checkout.placetopay.com"
                ],
                "subscribe" => false,
                "dispersion" => [
                    [
                        "agreement" => "1299",
                        "agreementType" => "MERCHANT",
                        "amount" => [
                            "currency" => "USD",
                            "total" => 200
                        ]
                    ]
                ],
                "modifiers" => [
                    [
                        "type" => "FEDERAL_GOVERNMENT",
                        "code" => 17934,
                        "additional" => [
                            "invoice" => "123345"
                        ]
                    ]
                ]
            ],
            "subscription" => [
                "reference" => "12345",
                "description" => "Ejemplo de descripción",
                "fields" => [
                    "keyword" => "1111",
                    "value" => "lastDigits",
                    "displayOn" => "none"
                ]
            ],
            "fields" => [
                [
                    "keyword" => "processUrl",
                    "value" => "https://checkout.redirection.test/session/1/a592098e22acc709ec7eb30fc0973060",
                    "displayOn" => "none"
                ]
            ],
            "paymentMethod" => "visa",
            "expiration" => "2019-08-24T14:15:22Z",
            "returnUrl" => "https://commerce.test/return",
            "cancelUrl" => "https://commerce.test/cancel",
            "ipAddress" => "127.0.0.1",
            "userAgent" => "PlacetoPay Sandbox",
            "skipResult" => false,
            "noBuyerFill" => false,
            "type" => "checkin"
        ];


        $client = new Client();
        $res = $client->request('POST', env('BASE_URL_CREATE'),
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($json),
            ]
        );
        $response = $res->getBody()->getContents();
        $data = json_decode($response);
        //dd($res->getBody()->getContents(),$data->status->message);



        if($res->getStatusCode() == 200) {
            $order = Order::create(
                [
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_mobile' => $request->customer_mobile,
                    'status' => 'CREATED'
                ]);
                return redirect()->route('admin.home')->with('success',$data->status->message);
        }else{
            return redirect()->route('admin.home')->with('erroor',$data->status->message);
        }
    }


    public function create()
    {
        return view('admin.pay.create');
    }

    public function Approval($id)
    {

        abort_unless(Gate::allows('pay_access'), 403);
        $json =  [
            "auth" => [
                  "login" => env('BASE_LOGIN'),
                  "tranKey" => env('BASE_SECRETKEY'),
                  "nonce" => "NjE0OWVkODgwYjNhNw==",
                  "seed" => "2021-09-21T09:34:48-05:00"
               ],
            "internalReference" => 1
         ];
         $client = new Client();
         $res = $client->request('POST', env('BASE_URL_PAY'),
             [
                 'headers' => ['Content-Type' => 'application/json'],
                 'body' => json_encode($json),
             ]
         );
         $response = $res->getBody()->getContents();
         $data = json_decode($response);
         $compra = Order::find($id);

         if($res->getStatusCode() == 200) {

            $compra->status = 'PAYED';
            $compra->update();
         }else{
            $compra->status = 'REJECTED';
            $compra->update();
         }

        return redirect()->route('admin.home')->with('success',$data->status->status);

    }

    public function payDatatable()
    {
        abort_unless(Gate::allows('pay_access'), 403);
        return DataTables()
        ->of(Order::all())
        ->addColumn('btn', function($item){
            if($item->status == 'CREATED'){
                return view('admin.pay.acciones',['data' => $item])->render();
            }
        })
        ->rawColumns(['btn'])
        ->make(true);
    }
}
