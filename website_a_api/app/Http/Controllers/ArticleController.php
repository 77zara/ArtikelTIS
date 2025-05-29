<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import Validator

class ArticleController extends Controller
{
    public function index()
    {
        // Mengambil semua artikel
        $articles = Article::orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $articles], 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        // Membuat artikel baru
        try {
            $article = Article::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Article created successfully', 'data' => $article], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to create article: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        // Mencari artikel berdasarkan ID
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['status' => 'error', 'message' => 'Article not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $article], 200);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255', // sometimes: hanya validasi jika ada di request
            'content' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        // Mencari artikel berdasarkan ID
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['status' => 'error', 'message' => 'Article not found'], 404);
        }

        // Update artikel
        try {
            // Hanya update field yang ada di request
            if ($request->has('title')) {
                $article->title = $request->input('title');
            }
            if ($request->has('content')) {
                $article->content = $request->input('content');
            }
            $article->save();
            
            return response()->json(['status' => 'success', 'message' => 'Article updated successfully', 'data' => $article], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update article: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        // Mencari artikel berdasarkan ID
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['status' => 'error', 'message' => 'Article not found'], 404);
        }

        // Hapus artikel
        try {
            $article->delete();
            return response()->json(['status' => 'success', 'message' => 'Article deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete article: ' . $e->getMessage()], 500);
        }
    }
}