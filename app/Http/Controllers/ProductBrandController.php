<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
    
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\BrandRequest;

class ProductBrandController extends Controller
{
    // Listar todos las marcas.
    public function index()
    {
        try {
            $brands = ProductBrand::all();

            return response()->json([
                'status' => 'success',
                'data' => $brands,
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

    // Registrar y actualizar marca
    public function store(BrandRequest $request)
    {
        try {

            $validatedData = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $id = $request->input('id');

            $brand = ProductBrand::firstOrNew(['id' => $id]);

            $brand->name = $validatedData['name'];
            $brand->save();

            
            return response()->json([
                'status' => 'success',
                'message' => $id ? 'Marca actualizado' : 'Marca registrada',
                'data' => $brand
            ], Response::HTTP_CREATED);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $id ? 'Error al actualizar' : 'Error al registar',
                'error' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Eliminar marca
    public function deleteBrand($id)
    {
        try {
            $brand = ProductBrand::findOrFail($id);

            if ($brand->products()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se puede eliminar la marca porque tiene productos asociados.',
                ], Response::HTTP_BAD_REQUEST);
            }

            $brand->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Marca eliminada exitosamente.',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al intentar eliminar la marca.',
                'error' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
    