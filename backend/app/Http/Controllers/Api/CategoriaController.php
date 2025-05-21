<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{

    public function index()
    {
        return Categoria::select('id', 'nome', 'created_at', 'updated_at')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100'
        ]);

        $categoria = Categoria::create([
            'nome' => $request->nome
        ]);

        return response()->json($categoria, 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100'
        ]);

        $dados = $request->only(['nome']);

        $categoria->update($validated);
        return $categoria;
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return response()->json(['mensagem' => 'Categoria deletada com sucesso.']);
    }
}
