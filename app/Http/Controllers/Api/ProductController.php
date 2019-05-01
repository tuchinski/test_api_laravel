<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\API\ApiError;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    public function index(){
        // $data = ['data' => $this->product->all()];
        $data = ['data' => $this->product->paginate(10)];
        return response()->json($data);
        // return $this->product->all();
    }

    public function show($id){
        $product = $this->product->find($id);

        if(! $product){
            $data = ['data'=>['msg' => 'Produto não encontrado']];
            return response()->json($data, 404 );
        }

        $data = ['data' => $product];
        return response()->json($data);
    }

    public function store(Request $request){
        try {
            $productData = $request->all();
            // dd($productData);
            $this->product->create($productData);
            $return = ['data' => ['msg'=> 'Produto criado com sucesso!']];
            return response()->json([$return,201]);

        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiError::errorMessage('Erro ao realizar operação cadastro', 1010));
        }
    }

    public function update(Request $request, $id){
        try {
            $productData = $request->all();
            $product = $this->product->find($id);
            $product->update($productData);

            $return = ['data' => ['msg'=> 'Produto atualizado com sucesso!']];
            return response()->json([$return,201]);

        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011));
            }
            return response()->json(ApiError::errorMessage('Erro ao realizar operação atualização', 1011));
        }
    }

    public function delete(Product $id){
        try {
            $id->delete();
            $data = ['data' =>['msg' => 'Produto: ' . $id->name . ' removido com sucesso!']];
            return response()->json($data, 200);
        } catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012));
            }
            return response()->json(ApiError::errorMessage('Erro ao realizar operação remoção', 1012));
        }
    }

}
