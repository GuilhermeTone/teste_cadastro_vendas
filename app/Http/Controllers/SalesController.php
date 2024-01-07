<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SalesProduct;
use App\Models\Product;
use App\Models\SalesAddress;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct()
    {

        $this->validation = [
            'produtos' => ['required', 'string'],
            'data_venda' => ['required', 'string'],
            'cep' => ['required'],
            'uf' => ['required', 'string'],
            'cidade' => ['required', 'string'],
            'bairro' => ['required', 'string'],
            'rua' => ['required', 'string'],
            'numero' => ['required'],
        ];

        $this->feedback = [
            'produtos.required' => 'Produto é um campo obrigatório.',
            'data_venda.required' => 'Data venda é um campo obrigatório.',
            'cep.required' => 'CEP é um campo obrigatório.',
            'uf.required' => 'UF é um campo obrigatório.',
            'cidade.required' => 'Cidade é um campo obrigatório.',
            'bairro.required' => 'Bairro é um campo obrigatório.',
            'rua.required' => 'Rua é um campo obrigatório.',
            'numero.required' => 'Numero é um campo obrigatório.',
        ];
    }
    /**
     * Clientes a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $request->validate($this->validation, $this->feedback);

            $produto = Product::where('reference', $request->get('produtos'))->first();

            $salesProduct = SalesProduct::create([
                    'product_id' => $produto['id'],
                    'amount' => 1, 
                    'price_at_sale' => $produto['price'],
                    'date_sale' => $request->get('data_venda'),
                ]);

            $salesProduct = SalesAddress::create([
                'sales_product_id' => $salesProduct->id,
                'cep' => $request->get('cep'),
                'state' => $request->get('uf'),
                'city' => $request->get('cidade'),
                'district' => $request->get('bairro'),
                'street' => $request->get('rua'),
                'number' => $request->get('numero'),
                'complement' => $request->get('complemento') ?? null,
            ]);

            return true;

        } catch (\Illuminate\Validation\ValidationException $e) {
            
            return response()->json(['error' => $e->errors()], 422);

        }
    }

    /**
     * Display the specified resource.
     */
    public function getSales()
    {
        try {

            $salesProducts = SalesProduct::selectRaw('
                    sales_products.id,
                    products.name,
                    sales_products.price_at_sale,
                    DATE_FORMAT(sales_products.date_sale, "%d/%m/%Y") AS date_sale,
                    IFNULL(GROUP_CONCAT(DISTINCT suppliers.name SEPARATOR ", "), "Sem fornecedor cadastrado para este produto") AS supplier,
                    MAX(
                        CONCAT_WS(", ", 
                            sales_addresses.cep, 
                            sales_addresses.state,
                            sales_addresses.city,
                            sales_addresses.district,
                            sales_addresses.street,
                            sales_addresses.number,
                            sales_addresses.complement
                        )
                    ) as address
            ')
            ->join('products', 'sales_products.product_id', '=', 'products.id')
            ->join('sales_addresses', 'sales_products.id', '=', 'sales_addresses.sales_product_id')
            ->leftJoin('suppliers', 'products.id', '=', 'suppliers.product_id')
            ->groupBy('sales_products.id')
            ->get();

            return response()->json($salesProducts);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json(['error' => 'Nenhuma venda Encontrada'], 404);

        }
        
    }

}
