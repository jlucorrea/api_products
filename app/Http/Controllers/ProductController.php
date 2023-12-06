<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    // Listar todos los productos con su marca asociada.
    public function index()
    {
        try {
            $products = Product::with(['brand'])->get();

            return response()->json([
                'status' => 'success',
                'data' => $products,
                'message' => 'Data obtenida con exito',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error obtener la data',
                'error' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Registrar y actualizar producto
    public function store(ProductRequest $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'brand_id' => 'required',
            ]);
            
            $id = $request->input('id');

            $product = Product::firstOrNew(['id' => $id]);

            $product->name = $validatedData['name'];
            $product->description = $validatedData['description'];
            $product->price = $validatedData['price'];
            $product->brand_id = $validatedData['brand_id'];
            
            $product->save();

            
            return response()->json([
                'status' => 'success',
                'message' => $id ? 'Producto actualizado' : 'Producto registrada',
                'data' => $product
            ], Response::HTTP_CREATED);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $id ? 'Error al actualizar' : 'Error al registar',
                'error' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Eliminar producto por su id
    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Producto eliminada exitosamente.',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al intentar eliminar el producto.',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
