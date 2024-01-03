<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\DisciplinaParaClasse;
use App\Models\Estudante_x_Ano_x_Classe;
use App\Models\Mensalidade;
use App\Models\Notas;
use App\MyCustomFuctions\Customised;
use App\MyCustomFuctions\MatricularEstudante;
use App\MyCustomFuctions\Pagamento;
use App\MyCustomFuctions\RoleFuc;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Estudante_x_Ano_x_ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



// $Aluno = DB::table('livrode_notas')
// ->join('users', 'users.id', '=', 'livrode_notas.studentID')
// //->where('role_user.role_id', '=', 4)
// ->select('users.id','users.reg_Numero','users.primeiro_nome','users.ultimo_nome','users.dataofbirth')
// ->get();
















        // $turma = DB::table('turmas')->select('id', 'nomeTurma')->get();
        // $sala = DB::table('salas')->select('id', 'nomeSala')->get();
        // $periodo = DB::table('periodos')->select('id', 'nomePeriodo')->get();
        // $classe = DB::table('classes')->select('id', 'classe_name')->get();
        // $curso = DB::table('curso')->select('id', 'nomeCurso')->get();
        // $anolectivo = DB::table('ano_lectivos')->select('id', 'ano_lectivo', 'fim', 'inicio')->first();
        // return response()->json([
        //     'Aluno' => $Aluno,
        //     'turma' => $turma,
        //     'sala' => $sala,
        //     'periodo' => $periodo,
        //     'classe' => $classe,
        //     'curso' => $curso,
        //     'anolectivo' => $anolectivo
        // ]);
    }





    public function store(Request $request)
    {


 


        $anolectivo = DB::table('ano_lectivos')->select('id')->first();

        $Aluno = DB::table('users')->where(['reg_Numero'=> $request->reg_Numero])->select('id')->first();

       


        MatricularEstudante::MatricularOrconfirmar($Aluno->id,$request->periodoID,$request->classeID,$request->cursoID,$request->salaID,$request->turmaID);

        $getTodasDisciplinas = MatricularEstudante::getTodasDisciplinas($anolectivo->id,$request->classeID,$request->periodoID,$request->turmaID,$request->salaID,$request->cursoID);

        MatricularEstudante::DisciplinaParaAluno($getTodasDisciplinas,$Aluno->id);

        $mensalidadeId = Pagamento::SingleStudentDetalhes($request->classeID,$anolectivo->id,$Aluno->id);

       
  


        $estudante = MatricularEstudante::EstudanteDeAserConfirmado($Aluno->id);
        $encarregado = MatricularEstudante::Encarregado_unico($Aluno->id);

        $dadosCademico = MatricularEstudante::ConfirmacaodeAluno($mensalidadeId);

        $dadosCademico->estudanteid = $Aluno->id;


     $dadosPessoais = [
       'nomeCompleto' =>$estudante->nomeCompleto,
        'dataDenascimento' => $estudante->dataofbirth,
        'numeroDocumento' => $estudante->numeroDoDocumento,
        'contact' => $estudante->numeroDotelefone,
        'genero' => Customised::Genero($estudante->genero_id),
        'nacionalidade' =>Customised::Paises($estudante->pais),
        'processo'=>$estudante->reg_Numero
        ];

        $encarregados = [
        'nomeCompleto' => $encarregado->nomeCompleto,
        'contact' => $encarregado->numeroDotelefone,
        'email' => $encarregado->email,
        'nacionalidade' => 'Angolano',
        ];
 



     
            return response()->json([
             'dadosPessoais'=>$dadosPessoais,
             'dadosCademico'=> $dadosCademico,
            'encarregados'=>$encarregados,
            ], 200);
    
       
       
    }



 



    public function show($id)
    {
        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::join('users', 'users.id', '=', 'estudante_x_ano_x_classe.student_id')
            ->join('curso', 'curso.id', '=', 'estudante_x_ano_x_classe.Curso_id')
            ->join('periodos', 'periodos.id', '=', 'estudante_x_ano_x_classe.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'estudante_x_ano_x_classe.Turma_id')
            ->join('salas', 'salas.id', '=', 'estudante_x_ano_x_classe.Sala_id')
            ->join('classes', 'classes.id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'estudante_x_ano_x_classe.Anolectivo_id')
            ->select('users.primeiro_nome', 'users.ultimo_nome', 'users.reg_Numero', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo')
            ->get();

        $professor = Estudante_x_Ano_x_Classe::join('disciplinaparaclasse', 'disciplinaparaclasse.Classe_id', '=', 'estudante_x_ano_x_classe.Classe_id')
            ->join('periodos', 'periodos.id', '=', 'disciplinaparaclasse.Periodo_id')
            ->join('turmas', 'turmas.id', '=', 'disciplinaparaclasse.Turma_id')
            ->join('salas', 'salas.id', '=', 'disciplinaparaclasse.Sala_id')
            ->join('classes', 'classes.id', '=', 'disciplinaparaclasse.Classe_id')
            ->join('users', 'users.id', '=', 'disciplinaparaclasse.Professor_id')
            ->join('tipodesciplina', 'tipodesciplina.id', '=', 'disciplinaparaclasse.TipodeDisciplina_id')
            ->join('disciplinas', 'disciplinas.id', '=', 'disciplinaparaclasse.Disciplina_id')
            ->join('curso', 'curso.id', '=', 'disciplinaparaclasse.Curso_id')
            ->join('ano_lectivos', 'ano_lectivos.id', '=', 'disciplinaparaclasse.Anolectivo_id')
            ->select('tipodesciplina.TipoNome as DisciplinaTipo', 'disciplinas.nomeDisciplina', 'users.primeiro_nome', 'users.ultimo_nome', 'users.email', 'curso.nomeCurso', 'periodos.nomePeriodo', 'salas.nomeSala', 'classes.classe_name', 'turmas.nomeTurma', 'ano_lectivos.ano_lectivo')
            ->find($id);

        if (!$Estudante_x_Ano_x_Classe && $professor) {
            return response()->json(['error' => 'Disciplina não encontrada.'], 404);
        }

        return response()->json(['professor' => $professor, 'EstudantesDaClasse' => $Estudante_x_Ano_x_Classe]);
    }






    public function update(Request $request, $id)
    {
        $existingRecord = Estudante_x_Ano_x_Classe::where('student_id', $request->student_id)
            ->where('Anolectivo_id', $request->Anolectivo_id)
            ->where('Classe_id', $request->Classe_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRecord) {
            return response()->json(['errors' => 'Este Aluno já está matriculado no corrente ano letivo'], 422);
        }

        $validatedData = $request->validate([
            'student_id' => 'required|integer',
            'Periodo_id' => 'required|integer',
            'Turma_id' => 'required|integer',
            'Sala_id' => 'required|integer',
            'Classe_id' => 'required|integer',
            'Curso_id' => 'required|integer',
            'Anolectivo_id' => 'required|integer'
        ]);

        $Estudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::find($id);

        if (!$Estudante_x_Ano_x_Classe) {
            return response()->json(['error' => 'Disciplina não encontrada.'], 404);
        }

        // $Estudante_x_Ano_x_Classe->student_id = $validatedData['student_id'];
        // $Estudante_x_Ano_x_Classe->Periodo_id = $validatedData['Periodo_id'];
        // $Estudante_x_Ano_x_Classe->Turma_id = $validatedData['Turma_id'];
        // $Estudante_x_Ano_x_Classe->Sala_id = $validatedData['Sala_id'];
        // $Estudante_x_Ano_x_Classe->Classe_id = $validatedData['Classe_id'];
        // $Estudante_x_Ano_x_Classe->Curso_id = $validatedData['Curso_id'];
        // $Estudante_x_Ano_x_Classe->Anolectivo_id = $validatedData['Anolectivo_id'];
        // $Estudante_x_Ano_x_Classe->save();

        return response()->json(['Estudante Para Classe' => $Estudante_x_Ano_x_Classe, 'success' => 'Estudante atualizado com sucesso'], 200);
    }





    public function delete($id)
    {
        $deleteEstudante_x_Ano_x_Classe = Estudante_x_Ano_x_Classe::find($id);

        if (!$deleteEstudante_x_Ano_x_Classe) {
            return response()->json(['error' => 'Estudante Para Classe não encontrado'], 404);
        }

        $deleteEstudante_x_Ano_x_Classe->delete();
        return response()->json(['message' => 'Estudante Para Classe excluído com sucesso'], 200);
    }
}
