<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StorePostRequest;
use App\Imports\ContactsImport;
use App\Exports\ContactExport;
Use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(){

       $products =auth()->user()->product;

       return response()->json([
           'Success'=>true,
           'Data'=> $products
        ],200);
    }

    public function show($id){
        $product = auth()->user()->products()->find($id);

        if(!$product){
            return response()->json([
                'Success'=>false,
                'Data'=> 'Product with id'.$id.'not found',
            ],400);
        }

        return response()->json([
            'Success'=>true,
            'Data'=> $product->toArray(),
        ],400);
    }


    public function store(Request $request){

        $product = Product::create([
            'user_id' =>auth()->user()->id,
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return new ProductResource($product);
        
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product){
             return response()->json([
                    'success'=>false,
                     'data'=>'Product with id'.$id.'not found',
                 ],500);
    }

        $product->fill($request->all());
        $product->save();
        
        if($product){
        return response()->json([
            'Success'=>true,
            'Message'=>'Product updated successfully',
            'data' => $product
            ],200);
        }else{
        return response()->json([
            'Success'=>false,
            'Message'=>'Product could not be updated',
            ],500);
        }

        $product = Product::update([
            'user_id' =>auth()->user()->id,
            'name' => $request->name,
            'price' => $request->price,

        ]);

        return new ProductResource($product); 
    }

    public function destroy($id){
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'Success'=>false,
                'Message'=>'Product with id'.$id.'not found'
            ],400);
        }if($product->delete()){
            return response()->json([
                'Success'=>true,
                'Message'=>'Product deleted successfully'
            ]);
        }else{
            return response()->json([
                'Success'=>false,
                'Message'=>'Product could not be deleted'
            ],500);
        }
            
    }
    public function import(Request $request)
    { 
        $products = Excel::toCollection(new ContactsImport, $request->file('test'));
        
            foreach ($products[0] as $product){
                $validate = Validator::make([
                    'user_id'=>$product[1],
                    'name'   =>$product[2],
                    'price'  =>$product[3],
                ],[
                    'user_id'=>'required',
                    'name'   => 'required',
                    'price'  => 'required',
                ]); 

                $check = Product::where('name','=',$product[2]->first());
                if(!isset($product[6])){
                    continue;
                }
                else{
                    if ($check && $product[7] == "update"){
                        Product::where('name',$product[2]->update([
                            'name' =>$product[1],
                        ]));
                    }
                        if(!$check && $product[6] == "create"){
                            if($validate->fails()){
                                continue;
                            } else{
                                Product::create([
                                    'user_id'=>$product[1],
                                    'name'   =>$product[2],
                                    'price'  =>bcrypt($product[3])
                                ]);
                            }
                        }
                        if($check && $product[6] == "delete"){
                            Product::where('name',$product[2])->delete();
                        }
                        } 
                    }
                return back();
            }

    public function export()
    {
        return Excel::download(new ContactsExport,'products.xlsx');
    }
}




