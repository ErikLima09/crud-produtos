<?php

namespace App\Http\Controllers\Api;

use App\Models\Produto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', null);

        $query = Produto::with('categoria');

        $query = $query->orderBy('id', 'desc');

        if ($search) {
            $query = $query->where('nome', 'like', "%{$search}%")
                           ->orWhere('descricao', 'like', "%{$search}%");
        }
        
        if ($perPage == -1) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'          => 'required|string|max:50',
            'descricao'     => 'nullable|string|max:200',
            'preco'         => 'required|numeric|min:0',
            'validade'      => 'required|date|after_or_equal:today',
            'imagem'        => 'nullable|image',
            'categoria_id'  => 'required|exists:categorias,id',
        ]);

        $filename = null;
        if ($request->hasFile('imagem')) {
            try {
                $filename = $request->file('imagem')->store('produtos', 'public');
            } catch (\Exception $e) {
                return response()->json(['erro' => 'Falha ao salvar imagem: ' . $e->getMessage()], 500);
            }
        }
        
        $produto = Produto::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'validade' => $request->validade,
            'imagem' => $filename,
            'categoria_id' => $request->categoria_id
        ]);

        return response()->json($produto, 201);
    }

    public function show(Produto $produto)
    {
        return $produto->load('categoria');
    }

    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'nome'          => 'required|max:50',
            'descricao'     => 'nullable|max:200',
            'preco'         => 'required|numeric|min:0',
            'validade'      => 'required|date|after_or_equal:today',
            'imagem'        => 'nullable|image|max:2048',
            'categoria_id'  => 'required|exists:categorias,id',
        ]);

        $dados = $request->only(['nome', 'descricao', 'preco', 'validade', 'categoria_id']);

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem);
            }

            try {
                $filename = $request->file('imagem')->store('produtos', 'public');
                $dados['imagem'] = $filename;
            } catch (\Exception $e) {
                return response()->json(['erro' => 'Falha ao salvar imagem: ' . $e->getMessage()], 500);
            }
        }

        $produto->update($dados);

        return $produto;
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return response()->json(['mensagem' => 'Produto deletado com sucesso']);
    }
}
