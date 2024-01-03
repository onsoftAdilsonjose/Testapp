<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Biblioteca;
use App\Helpers\Docs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class BibliotecaController extends Controller
{
    









        public function index()
    {
 
		$Biblioteca = DB::table('biblioteca')
		->join('curso', 'curso.id', '=', 'biblioteca.Curso_id')
		->join('classes', 'classes.id', '=', 'biblioteca.Classe_id')
		->join('users', 'users.id', '=', 'biblioteca.userid')
		->select('biblioteca.id as id','livronome','nomeCurso','classe_name', 'author', 'book_pdf',
		DB::raw("CONCAT(primeiro_nome, ' ', ultimo_nome) AS AdicionouLivro"))
		->get();

        return response()->json(['Biblioteca' => $Biblioteca]);

    }



  public function store(Request $request){




		$validatedData = $request->validate([
		'livronome' => 'required|string|max:255',
		'Disciplina_id' => 'required|integer',
		'Classe_id' => 'required|integer',
		'Curso_id' => 'required|integer',
		'author' => 'required|string|max:255',
		'book_pdf' => 'required|max:2048',
		]);


       $classepath = Docs::classepath($request->Classe_id);



		$bookName = time() . '.' . $request->book_pdf->getClientOriginalExtension();
		$bookPath = $bookName;
		$request->book_pdf->storeAs('public/products', $bookName);
		$livro =  asset('storage/products/' . $bookPath);

		$book = Biblioteca::create([
		'livronome' => $validatedData['livronome'],
		'userid' => Auth::id(),
		'Disciplina_id' => $validatedData['Disciplina_id'],
		'Classe_id' => $validatedData['Classe_id'],
		'Curso_id' => $validatedData['Curso_id'],
		'author' => $validatedData['author'],
		'book_pdf' => $livro, // Save the relative path to the image in the database
		]);

		$book_pdf = Docs::showlivroaftersave($book->id);
		return response()->json($book_pdf, 200);



  }














}
