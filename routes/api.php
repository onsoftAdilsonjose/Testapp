<?php

use App\Http\Controllers\API\FilterController;
use App\Http\Controllers\API\Filterpaise;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\BibliotecaController;
use App\Http\Controllers\BlocuserComdividasController;
use App\Http\Controllers\BoletimController;
use App\Http\Controllers\Center\AppCenterController;
use App\Http\Controllers\ConfirmacaoController;
use App\Http\Controllers\CursoDisciplinaClasseController;
use App\Http\Controllers\DadosUteisController;
use App\Http\Controllers\FaltaController;
use App\Http\Controllers\InformacaoDaEscolaController;
use App\Http\Controllers\InformacoesdePagamentoController;
use App\Http\Controllers\ListaUsuariosController;
use App\Http\Controllers\Lista\Turma\EstudanteTurmaController;
use App\Http\Controllers\Lista\Turma\ProfessorTurmaController;
use App\Http\Controllers\MensalidadeController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProcessoDisciplinarCotroller;
use App\Http\Controllers\Password;
use App\Http\Controllers\RegisterContoller;
use App\Http\Controllers\UPDATEINFO\Registroestudantesemsalacontroller;
use App\Http\Controllers\UPDATEINFO\TrocarEstudanteSalaClasseController;
use App\Http\Controllers\UPDATEINFO\NAOMTRICULADO\matriculrnaomatriculadocontroller;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\Reset\ResetPassword;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TransationPaymentController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\Users\Encarregado\EncarregadoController;
use App\Http\Controllers\Users\Encarregado\FinacaController;
use App\Http\Controllers\Users\Encarregado\EncarregadoCondutaController;
use App\Http\Controllers\Users\Encarregado\AreiaAcademicaController;
use App\Http\Controllers\Users\Estudante\FinancasEstudanteController;
use App\Http\Controllers\Users\Estudante\EstudanteController;
use App\Http\Controllers\Users\Estudante\NotaEstudanteController;
use App\Http\Controllers\Users\Estudante\EstudanteDisciplinaController;
use App\Http\Controllers\Users\Estudante\AnoLectivoEstudanteLogadoController;
use App\Http\Controllers\Users\Estudante\EstudanteBoletimController;
use App\Http\Controllers\Users\Estudante\EstudanteBibliotecaController;
use App\Http\Controllers\Users\Estudante\EstudanteCondutaController;
use App\Http\Controllers\Users\Estudante\EstudanteFaltaController;
use App\Http\Controllers\Users\Estudante\EstudanteDadosestudanteController;
use App\Http\Controllers\Users\Estudante\EstudanteConsultarpagamentosController;
use App\Http\Controllers\Users\Estudante\EstudantePagamentoServicoController;
use App\Http\Controllers\Users\Estudante\EstudanteDividasEmultasController;

use App\Http\Controllers\Users\Funcionario\FuncionarioController;
use App\Http\Controllers\Users\EditarUsersControllers;

use App\Http\Controllers\Users\Professor\ProfessorController;
use App\Http\Controllers\Users\Professor\ProfessorFilterController;
use App\Http\Controllers\Users\Professor\ProfessorConsultarController;

use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\QuadrodeHonraController;
use App\Http\Middleware\LicenseValidationMiddleware;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Propinas\Pagamento\PagmentodeMensalidadeController;
use App\Http\Controllers\Propinas\Pagamento\CancelarpagamentoController;
use App\Http\Controllers\Admin\Relatorio\PropinaspagasController;
use App\Http\Controllers\Admin\Relatorio\DividasController;
use App\Http\Controllers\Admin\Relatorio\PrevisaoController;
 



use App\Http\Controllers\Barcode\ScanController;

Route::post('/whatsapp', [WhatsAppController::class, 'handle']);


//Route::middleware(['checkUserOnline'])->post('login', [AuthController::class,'login']);
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
//unica rota sem acesso de

///// Teste Controller
Route::get('store', [App\Http\Controllers\TestController::class, 'sendSms']);
Route::post('processData', [App\Http\Controllers\TestController::class, 'processData']);

///////////--Biblioteca data-------////////////////////
Route::post('biblioteca/store', [BibliotecaController::class, 'store']);
Route::get('biblioteca', [BibliotecaController::class, 'index']);
///////////--Biblioteca data-------////////////////////


///////////--Processo Disciplinar -------////////////////////
Route::get('processodisciplinar', [ProcessoDisciplinarCotroller::class, 'index']);
Route::post('storeprocessodisciplinar', [ProcessoDisciplinarCotroller::class, 'storeprocesso']);

///////////--Processo Disciplinar -------////////////////////


///////////--Processo Disciplinar -------////////////////////
Route::post('EstudanteFaltas', [FaltaController::class, 'Falta']);
///////////--Processo Disciplinar -------////////////////////



///////////--quadro de Honra -------////////////////////
Route::get('QuadrodeHonra/anolectivo/{ano}/classe/{classid}', [QuadrodeHonraController::class, 'Todasnotas']);
///////////--quadro de Honra -------////////////////////




Route::middleware(['auth:api', 'check_usertype:Admin'])->get('ContaBancaria', [DadosUteisController::class, 'ContaBancaria']);
Route::middleware(['auth:api', 'check_usertype:Admin'])->get('MetodePagamento', [DadosUteisController::class, 'MetodePagamento']);
Route::middleware(['auth:api'])->get('trimestrefilter', [DadosUteisController::class, 'trimestrefilter']);




Route::get('ProcuararEstudante', [TransationPaymentController::class, 'findEstudantes'])->middleware('auth:api', 'check_usertype:Admin');
Route::get('/EstudanteDetalhes/Classe/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [TransationPaymentController::class, 'DetalhesPayment'])->middleware('auth:api', 'check_usertype:Admin');
Route::post('EstudantePayment', [PagmentodeMensalidadeController::class, 'EstudantePayment'])->middleware('auth:api', 'check_usertype:Admin');
Route::post('CancelarPagamento', [CancelarpagamentoController::class, 'CancelarPagamento'])->middleware('auth:api', 'check_usertype:Admin');



 
/*---------- Inicio API Routes Todos os Usuarios Terao acesso ao logout refresh token update password----------*/


Route::middleware(['auth:api'])->post('logout', [AuthController::class, 'logout']);
Route::middleware(['auth:api'])->post('refresh', [AuthController::class, 'refresh']);
Route::middleware(['auth:api'])->post('Passwordupdate', [Password::class, 'Passwordupdate']);
Route::middleware(['auth:api'])->post('updateContacto', [Password::class, 'updateContacto']);

Route::middleware(['auth:api'])->get('UserRoles', [AuthController::class, 'UsersRoles']);
/*----- ----- Fim API Routes odos os Usuarios Terao acesso ao logout refresh token update password----------------*/



Route::group(['middleware' => ['auth:api', 'check_usertype:Admin,Funcionario'], 'prefix' => 'Admin'], function () {

    Route::post('Register', [RegisterContoller::class, 'createUserWithRole']);
    Route::post('RegisterEstudante', [RegisterContoller::class, 'RegistrarEstudante']);
    Route::post('RegistrarEstudanteSemClasse', [Registroestudantesemsalacontroller::class, 'RegistrarEstudanteSemClasse']);
    Route::post('ActualizarDadosUsuarios/{id}', [Registroestudantesemsalacontroller::class, 'ActualizarDadosUsuarios']);
    Route::get('EstudanteInfor/{reg_Numero}', [Registroestudantesemsalacontroller::class, 'EstudanteInfor']);


///Editar Usuario e tambem a pagar usuarios Bloqueiar usuario
Route::post('editarUsersinfo/{id}', [EditarUsersControllers::class, 'EditarUsersinfo']);
Route::delete('delteUsersinfo/{id}', [EditarUsersControllers::class, 'softdelteUsersinfo']);
Route::post('bloqueiar/{id}', [EditarUsersControllers::class, 'bloqueiar']);
Route::get('getUsersinfo/{id}', [EditarUsersControllers::class, 'geTUsersinfo']);

///Editar Usuario e tambem a pagar usuarios

    Route::post('Matricularestudantenaomatriculado/{reg_Numero}', [matriculrnaomatriculadocontroller::class, 'EstudanteNaoMatriculadoMatricular']);



Route::post('TrocarEstudanteClasseSala/Estudante/{estudanteid}/Classe/{classeid}/AnoLectivo/{anolectivo}', [TrocarEstudanteSalaClasseController::class, 'TrocarEstudante']);



    Route::post('RegisterEncarregado', [RegisterContoller::class, 'RegistrarEncarregado']);

    // start Mensalidade
    Route::get('mensalidades', [MensalidadeController::class, 'mensalidades']);
    Route::post('storemensalidades', [MensalidadeController::class, 'storemensalidades']);
    //end Mensalidade


    Route::get('UserRoles', [RoleController::class, 'UsersRoles']);
    ////UserRoles

    Route::get('MatriculaOrConfirmacao/Classe/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [BlocuserComdividasController::class, 'BlockDividas']);

    Route::get('ConfirmacaoEstudante/{Reg}', [ConfirmacaoController::class, 'ConfirmarGet']);







    ////filter api///////
    Route::get('classesApi', [FilterController::class, 'classesApi']);
    Route::get('cursoApi/{cursoapId}', [FilterController::class, 'cursoApi']);
    Route::get('periodoApi/{periodoapId}/curso/{cursoid}', [FilterController::class, 'periodoApi']);
    Route::get('salaApi/{salaApiId}/curso/{cursoid}/periodo/{periodoid}/turma/{turmaid}', [FilterController::class, 'salaApi']);
    Route::get('turmaApi/{turmaApiId}/curso/{cursoid}/periodo/{periodoid}', [FilterController::class, 'turmaApi']);
    Route::get('cursodisciplina/curso/{cursoid}/classe/{classeId}', [FilterController::class, 'cursodisciplinaclasseApi']);
    Route::get('AnolectivoApi', [FilterController::class, 'AnolectivoApi']);
    Route::get('MesesApi/anolectivo/{anolectivoID}', [FilterController::class, 'MesesApi']);
    ////filter api///////





    Route::post('CursoDisciplinaClasse/store', [CursoDisciplinaClasseController::class, 'store']);
    Route::get('CursoDisciplinaClasse/index', [CursoDisciplinaClasseController::class, 'index']);
    Route::get('CursoDisciplinaClasse/show/{id}', [CursoDisciplinaClasseController::class, 'show']);
    Route::get('CursoDisciplinaClasse/update/{id}', [CursoDisciplinaClasseController::class, 'update']);
    Route::delete('CursoDisciplinaClasse/destroy/{id}', [CursoDisciplinaClasseController::class, 'destroy']);


    
    //Relatorios de Pagamentos
    Route::get('todosRelatorio', [RelatorioController::class, 'todosRelatorio']);
    Route::get('unicoRelatorio/{unicoRelatorioId}', [RelatorioController::class, 'unicoRelatorio']);
    Route::get('relatoriodePagamento/anolectivoID/{anolectivoID}/studentID/{studentID}', [RelatorioController::class, 'relatoriodePagamento']);
    Route::get('FacturaUnicadoEstudante/{Paymentid}', [RelatorioController::class, 'FacturaUnicadoEstudante']);
    //Relatorios de Pagamentos



    /////Lista  Usuarios
    Route::get('listarUsuarios', [ListaUsuariosController::class, 'listarUsuarios']);
    Route::get('listaEncarregado', [ListaUsuariosController::class, 'listaEncarregado']);
    Route::get('listaProfessores', [ListaUsuariosController::class, 'listaProfessores']);
    Route::get('listarFuncionarios', [ListaUsuariosController::class, 'listarFuncionarios']);





    Route::get('matriculados', [ListaUsuariosController::class, 'matriculados']);
    Route::get('Naomatriculados', [ListaUsuariosController::class, 'Naomatriculados']);






    //Inicio Relatorio de Pagamento de Propinas 


    Route::post('propinaspagas', [PropinaspagasController::class, 'propinaspagas']);
    Route::post('estudantesdividas', [DividasController::class, 'EstudantesDividas']);
    Route::post('previsao', [PrevisaoController::class, 'Previsaodepagamento']);

    //Fim Relatorio de Pagamento de Propinas 








    //InformacaoDaEscolaController

    Route::get('informacoesEscola', [App\Http\Controllers\InformacaoDaEscolaController::class, 'View']);
    Route::post('create-or-update', [App\Http\Controllers\InformacaoDaEscolaController::class, 'creatOrupdate']);
    //InformacaoDaEscolaController


    //InformacaoDe Pagamento
    Route::get('InformacoesdePagamento', [InformacoesdePagamentoController::class, 'InformacoesdePaga']);
    Route::post('storeInformacoesdePagamento', [InformacoesdePagamentoController::class, 'storeInformacoesdePagamento']);
    //InformacaoDe Pagamento


    //////
    //Route::resource('transportes', TransportController::class);
    ////










    //Inicio Boletim Certificados sao todos elaborados aqui neste controller
    

     Route::get('Servico', [ServicoController::class, 'index']);



    Route::get('BoletimDeNotasprimeiro/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [BoletimController::class, 'BoletimDeNotasprimeiro']);
    Route::get('BoletimDeNotassegundo/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [BoletimController::class, 'BoletimDeNotassegundo']);
    Route::get('BoletimDeNotasterceiro/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [BoletimController::class, 'BoletimDeNotasterceiro']);
    Route::get('DeclaracaoComNotas/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [BoletimController::class, 'DeclaracaoComNotas']);
    Route::get('DeclaracaoSemNotas/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [BoletimController::class, 'DeclaracaoSemNotas']);
    Route::get('GuiadeTransferencia/{classeId}/AnoLectivo/{anolectivoID}/Estudante/{studentID}', [BoletimController::class, 'GuiadeTransferencia']);
    //Fim Boletim Certificados sao todos elaborados aqui neste controller




    /*------------------------ Inicio API Routes Adicionar permissao ao um unico Usuario----------------------------*/
    Route::post('addPermissionToUser', [App\Http\Controllers\UserPermissionController::class, 'addPermissionToUser']);
    Route::get('removePermission/{userId}/FromUser/{permissionId}', [App\Http\Controllers\UserPermissionController::class, 'removePermissionFromUser']);
    Route::get('showUserPermissions/{userId}', [App\Http\Controllers\UserPermissionController::class, 'showUserPermissions']);
    /*------------------------ Fim API Routes Adicionar permissao ao um unico Usuario----------------------------*/


    /*------------------------ Inicio API Routes Adicionar permissao ao Grupo de Usuario----------------------------*/
    Route::post('addPermissionToRole', [App\Http\Controllers\PermissionRoleController::class, 'addPermissionToRole']);
    Route::get('removePermission/{roleId}/FromRole/{permissionId}', [App\Http\Controllers\PermissionRoleController::class, 'removePermissionFromRole']);
    Route::get('showRolePermissions/{userId}', [App\Http\Controllers\PermissionRoleController::class, 'showRolePermissions']);
    /*------------------------ Fim API Routes Adicionar permissao ao Grupo de Usuario----------------------------*/


    /*------------------------ Inicio API Routes Criar Editar Deletar permissao ----------------------------*/
    Route::get('Permission', [App\Http\Controllers\PermissionController::class, 'index']);
    Route::get('Permission/{id}', [App\Http\Controllers\PermissionController::class, 'show']);
    Route::post('StorePermission', [App\Http\Controllers\PermissionController::class, 'StorePermission']);
    Route::post('ActualizarPermission/{id}', [App\Http\Controllers\PermissionController::class, 'ActualizarPermission']);
    Route::delete('ApagarPermission/{id}', [App\Http\Controllers\PermissionController::class, 'destroy']);
    /*------------------------ Fim API Routes Criar Editar Deletar permissao----------------------------*/



    /*------------------------ Inicio API Routes Para Inserir Ano Lectivo----------------------------*/
    Route::get('/verAnolectivo', [App\Http\Controllers\AnoLectivoController::class, 'index']);
    Route::get('/verUnicoAnolectivo/{id}', [App\Http\Controllers\AnoLectivoController::class, 'show']);
    Route::post('/storeAnolectivo', [App\Http\Controllers\AnoLectivoController::class, 'store']);
    Route::post('/updateAnolectivo/{id}', [App\Http\Controllers\AnoLectivoController::class, 'update']);
    Route::delete('/deleteAnolectivo/{id}', [App\Http\Controllers\AnoLectivoController::class, 'delete']);
    /*----------------------------------- Fim API Routes AnoLectivo-----------------------------------*/


    /*------------------------ Inicio API Routes Para Inserir Classes----------------------------*/
    Route::get('/verClasse', [App\Http\Controllers\ClassesController::class, 'index']);
    Route::get('/verUnicoClasse/{id}', [App\Http\Controllers\ClassesController::class, 'show']);
    Route::post('/storeClasse', [App\Http\Controllers\ClassesController::class, 'store']);
    Route::post('/updateClasse/{id}', [App\Http\Controllers\ClassesController::class, 'update']);
    Route::delete('/deleteClasse/{id}', [App\Http\Controllers\ClassesController::class, 'delete']);
    /*----------------------------------- Fim API Routes Classes-----------------------------------*/

    /*------------------------ Inicio API Routes Para Inserir Curso----------------------------*/
    Route::get('/verCurso', [App\Http\Controllers\CursoController::class, 'index']);
    Route::get('/verUnicoCurso/{id}', [App\Http\Controllers\CursoController::class, 'show']);
    Route::post('/storeCurso', [App\Http\Controllers\CursoController::class, 'store']);
    Route::post('/updateCurso/{id}', [App\Http\Controllers\CursoController::class, 'update']);
    Route::delete('/deleteCurso/{id}', [App\Http\Controllers\CursoController::class, 'delete']);
    /*----------------------------------- Fim API Routes Curso-----------------------------------*/

    /*------------------------ Inicio API Routes Para Inserir Turma----------------------------*/
    Route::get('/verTurma', [App\Http\Controllers\TurmaController::class, 'index']);
    Route::get('/verUnicoTurma/{id}', [App\Http\Controllers\TurmaController::class, 'show']);
    Route::post('/storeTurma', [App\Http\Controllers\TurmaController::class, 'store']);
    Route::post('/updateTurma/{id}', [App\Http\Controllers\TurmaController::class, 'update']);
    Route::delete('/deleteTurma/{id}', [App\Http\Controllers\TurmaController::class, 'delete']);
    /*----------------------------------- Fim API Routes Turma-----------------------------------*/

    /*------------------------ Inicio API Routes Para Inserir Periodo----------------------------*/
    Route::get('/verPeriodo', [App\Http\Controllers\PeriodoController::class, 'index']);
    Route::get('/verUnicoPeriodo/{id}', [App\Http\Controllers\PeriodoController::class, 'show']);
    Route::post('/storePeriodo', [App\Http\Controllers\PeriodoController::class, 'store']);
    Route::post('/updatePeriodo/{id}', [App\Http\Controllers\PeriodoController::class, 'update']);
    Route::delete('/deletePeriodo/{id}', [App\Http\Controllers\PeriodoController::class, 'delete']);
    /*----------------------------------- Fim API Routes Periodo-----------------------------------*/

    /*------------------------ Inicio API Routes Para Inserir Sala----------------------------*/
    Route::get('/verSala', [App\Http\Controllers\SalaController::class, 'index']);
    Route::get('/verUnicoSala/{id}', [App\Http\Controllers\SalaController::class, 'show']);
    Route::post('/storeSala', [App\Http\Controllers\SalaController::class, 'store']);
    Route::post('/updateSala/{id}', [App\Http\Controllers\SalaController::class, 'update']);
    Route::delete('/deleteSala/{id}', [App\Http\Controllers\SalaController::class, 'delete']);
    /*----------------------------------- Fim API Routes Sala-----------------------------------*/

    /*------------------------ Inicio API Routes Para Inserir Disciplina----------------------------*/
    Route::get('/verDisciplina', [App\Http\Controllers\DisciplinaController::class, 'index']);
    Route::get('/verUnicoDisciplina/{id}', [App\Http\Controllers\DisciplinaController::class, 'show']);
    Route::post('/storeDisciplina', [App\Http\Controllers\DisciplinaController::class, 'store']);
    Route::post('/updateDisciplina/{id}', [App\Http\Controllers\DisciplinaController::class, 'update']);
    Route::delete('/deleteDisciplina/{id}', [App\Http\Controllers\DisciplinaController::class, 'delete']);
    /*----------------------------------- Fim API Routes Disciplina-----------------------------------*/



    /*------------------------ Inicio API Routes Para Inserir Tipo de Disciplina ----------------------------*/
    Route::get('/verTipoDisciplina', [App\Http\Controllers\TipoDisciplinaController::class, 'index']);
    Route::post('/storeTipoDisciplina', [App\Http\Controllers\TipoDisciplinaController::class, 'store']);
    Route::post('/updateTipoDisciplina/{id}', [App\Http\Controllers\TipoDisciplinaController::class, 'update']);
    Route::delete('/deleteTipoDisciplina/{id}', [App\Http\Controllers\TipoDisciplinaController::class, 'delete']);
    /*----------------------------------- Fim API Routes Tipo de Disciplina-----------------------------------*/



    /*------------------------ Inicio API Routes Para Inserir Disciplina Para Classe ----------------------------*/
    Route::get('/verDisciplinaParaClasse', [App\Http\Controllers\DisciplinaParaClasseController::class, 'index']);
    Route::get('/verUnicoDisciplina/{id}', [App\Http\Controllers\DisciplinaParaClasseController::class, 'show']);
    Route::post('/storeDisciplinaParaClasse', [App\Http\Controllers\DisciplinaParaClasseController::class, 'store']);
    Route::post('/updateDisciplinaParaClasse/{id}', [App\Http\Controllers\DisciplinaParaClasseController::class, 'update']);
    Route::delete('/deleteDisciplinaParaClasse/{id}', [App\Http\Controllers\DisciplinaParaClasseController::class, 'delete']);
    /*----------------------------------- Fim API Routes Disciplina Para Classe-----------------------------------*/


 

    /*------------------------ Inicio API Routes Para Inserir ver Estudante Para Classe ----------------------------*/
    Route::get('/verEstudanteParaClasse', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'index']);
    Route::get('/verUnicoEstudante/{id}', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'show']);
    Route::post('/storeEstudanteParaClasse', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'store']);
    Route::post('/updateEstudanteParaClasse/{id}', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'update']);
    Route::delete('/deleteEstudanteParaClasse/{id}', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'delete']);
    /*----------------------------------- Fim API Routes Estudante Para Classe-----------------------------------*/




    /*------------------------ Inicio API Routes Para Inserir Notas e alunos ----------------------------*/
    Route::get('/NotasAluno', [NotasController::class, 'index']);
    Route::get('/VerNotas/Classe/{classeId}/Anolectivo/{anolectivoID}/Estudante/{studentID}', [NotasController::class, 'VerNotas']);
    Route::get('/VerNotasEstudante/anolectivoID/{anolectivoID}/turmaID/{turmaID}/periodoID/{periodoID}/cursoID/{cursoID}/classeID/{classeID}',[NotasController::class, 'VerNotasEstudante']);
    Route::post('/storeNotas', [NotasController::class, 'storeNotas']);
    Route::get('/NotasParaPauta/anolectivoID/{anolectivoID}/turmaID/{turmaID}/periodoID/{periodoID}/cursoID/{cursoID}/classeID/{classeID}',[NotasController::class, 'NotasParaPauta']);



    // Route::post('/storeEstudanteParaClasse', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'store']);
    // Route::post('/updateEstudanteParaClasse/{id}', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'update']);
    // Route::delete('/deleteEstudanteParaClasse/{id}', [App\Http\Controllers\Estudante_x_Ano_x_ClasseController::class, 'delete']);
    /*----------------------------------- Fim API Routes Notas e alunos-----------------------------------*/

    Route::get('/ResetPassword/{RegistrationNumber}', [ResetPassword::class, 'ResetPassword']);










    Route::get('todosPaises', [Filterpaise::class, 'getPaises']);
    Route::get('todosProvincia/{paisId}', [Filterpaise::class, 'getProvincia']);
    Route::get('todosMunicipio/{provinciaId}', [Filterpaise::class, 'getMunicipio']);




    Route::post('pais', [Filterpaise::class, 'Pais']);
    Route::post('provincia', [Filterpaise::class, 'Provincia']);
    Route::post('municipio', [Filterpaise::class, 'Municipio']);


    Route::post('paisesProvinciaMunicipio', [Filterpaise::class, 'paisesProvinciaMunicipio']);



















    Route::get('classesparalista/periodoID/{periodoID}/anolectivoID/{anolectivoID}', [EstudanteTurmaController::class, 'classesdelista']);
    Route::get('estudanteparalista/Anolectivo_id/{Anolectivo_id}/Periodo_id/{Periodo_id}/Turma_id/{Turma_id}/Sala_id/{Sala_id}/Curso_id/{Curso_id}/Classe_id/{Classe_id}', [EstudanteTurmaController::class, 'listadeclasseestudante']);



    Route::get('listadeprofessores/Anolectivo_id/{Anolectivo_id}/Periodo_id/{Periodo_id}/Turma_id/{Turma_id}/Sala_id/{Sala_id}/Curso_id/{Curso_id}/Classe_id/{Classe_id}', [ProfessorTurmaController::class, 'listadeprofessores']);
});




Route::group(['middleware' => ['auth:api', 'check_usertype:Professor'], 'prefix' => 'Professor'], function () {

////Filtro de Api Professor 
Route::get('profanolectivo', [ProfessorFilterController::class, 'proffilteranolectivo']);
Route::get('proffclasse/anolectivo/{anolectivo}', [ProfessorFilterController::class, 'proffilterclasse']);
Route::get('proffiltercurso/anolectivo/{anolectivo}/classe/{classe}', [ProfessorFilterController::class, 'proffiltercurso']);
Route::get('proffilterperiodo/anolectivo/{ano}/classe/{classe}/curso/{curso}', [ProfessorFilterController::class, 'proffilterperiodo']);
Route::get('proffilterturma/anolectivo/{ano}/classe/{classe}/curso/{curso}/periodo/{periodo}', [ProfessorFilterController::class, 'proffilterturma']);
Route::get('proffiltersala/anolectivo/{ano}/classe/{classe}/curso/{curso}/periodo/{periodo}/turma/{turma}', [ProfessorFilterController::class, 'proffiltersala']);
Route::get('proffdisciplina/anolectivo/{ano}/classe/{classe}/curso/{curso}/periodo/{periodo}/turma/{turma}', [ProfessorFilterController::class, 'proffdisciplina']);
////Filtro de Api Professor 



Route::get('/peresrofvtudante/anolectivoID/{anolectivoID}/turmaID/{turmaID}/periodoID/{periodoID}/cursoID/{cursoID}/classeID/{classeID}',[ProfessorController::class, 'profVerNotasEstudante']);
Route::get('/VerNotas/Classe/{classeId}/Anolectivo/{anolectivoID}/Estudante/{studentID}', [ProfessorController::class, 'proferNotass']);
Route::post('/professorstoreNotas', [ProfessorController::class, 'ProfessorstoreNotas']);
Route::post('storeFaltas', [ProfessorController::class,'storeFaltas']);
Route::get('consultarnotas/anolectivo/{anolectivoID}/turna/{turmaID}/periodo/{periodoID}/curso/{cursoID}/classe/{classeID}/disciplina/{disciplinaid}',[ProfessorController::class, 'proffconsultarnotas']);
Route::get('/alunoatraso/disciplinas/{disciplinaid}/anolectivo/{ano}/classe/{classid}', [ProfessorController::class, 'professorDisciplinaspendentes']);
Route::get('/profconsultarfaltas/{anolectivoID}/turna/{turmaID}/periodo/{periodoID}/curso/{cursoID}/classe/{classeID}/disciplina/{disciplinaid}', [ProfessorController::class, 'professoreconsultarfaltas']);






});







Route::group(['middleware' => ['auth:api', 'check_usertype:Estudante'], 'prefix' => 'Estudante'], function () {
    Route::get('dadosdoestudantelogado', [EstudanteDadosestudanteController::class,'dadosdoestudante']);
    Route::get('/vernotas/anolectivo/{ano}', [NotaEstudanteController::class, 'Estudantenotas']);
    Route::get('/vernotas/historico/', [NotaEstudanteController::class, 'Historicos']);
    Route::get('/estudantefilter', [AnoLectivoEstudanteLogadoController::class, 'EstudanteFilter']);
    Route::get('/boletim/anolectivo/{ano}/trimestre/{trimestre}', [EstudanteBoletimController::class, 'Boletim']);
    Route::get('/disciplinas/anolectivo/{ano}', [EstudanteDisciplinaController::class, 'EstudaTodasDisciplina']);
    Route::get('/disciplinas/gradecurricular', [EstudanteDisciplinaController::class, 'Gradecurricular']);
    Route::get('/disciplinas/pendentes/anolectivo/{ano}', [EstudanteDisciplinaController::class, 'Disciplinaspendentes']);
    Route::get('/consultarsaldo', [FinancasEstudanteController::class, 'ConsultarSaldo']);
    Route::get('/biblioteca/anolectivo/{anolectivo}/disciplina/{disciplinaid}', [EstudanteBibliotecaController::class, 'BibliotecaEstudante']);
    Route::get('/condutaestudante/anolectivo/{anolectivo}', [EstudanteCondutaController::class, 'EstudanteCondutaanual']);
    Route::get('/estudanteconsultarfaltas/anolectivo/{anolectivo}', [EstudanteFaltaController::class, 'consultarfaltas']);
    Route::get('/ConsultarPagamento/anolectivo/{anolectivoid}', [EstudanteConsultarpagamentosController::class, 'ConsultarPagamento']);
    Route::post('/ExtratoFinaceiro', [EstudanteConsultarpagamentosController::class, 'ExtratoFinaceiro']);
    Route::get('/PagamentoPropinasEservico', [EstudantePagamentoServicoController::class, 'PagamentoPropinasEservico']); 
    Route::get('/MultasePrazos/anolectivo/{anolectivoid}', [EstudanteDividasEmultasController::class, 'MultasePrazos']);
});

















Route::group(['middleware' => ['auth:api', 'check_usertype:Encarregado'], 'prefix' => 'Encarregado'], function () {


Route::get('/filhoEstudantefilter/Estudante/{AnoLectivo}', [EncarregadoController::class, 'EncarregadofilhosFilter']);
Route::get('/filhosAnolectivoFilter/Estudante', [EncarregadoController::class, 'EncarregadoFilhosAnolectivoFilter']);
Route::get('/vernotas/Estudante/{studentID}/AnoLectivo/{anolectivoID}', [AreiaAcademicaController::class, 'Encarregado_vernotas']);
Route::get('/vernotas/Estudante/{studentID}/historico', [AreiaAcademicaController::class, 'Encarregado_vernotas_historico']);
Route::get('/boletimestral/Estudante/{studentID}/Anolectivo/{Anolectivo}/trimestre/{trimestre}', [AreiaAcademicaController::class,'BoletimEncarregado']);
Route::get('/disciplinas/Estudante/{studentID}/anolectivo/{ano}', [AreiaAcademicaController::class,'EstudaTodasDisciplinaEncarregado']);
Route::get('/disciplinas/gradecurricular/Estudante/{studentID}', [AreiaAcademicaController::class, 'EncarregadoGradecurricular']);
Route::get('/disciplinas/pendentes/Estudante/{studentID}/anolectivo/{ano}', [AreiaAcademicaController::class, 'EncarregadoDisciplinaspendentes']);








///nao esta na documentacao do api
Route::get('/MultasePrazos/anolectivo/{anolectivoid}/estudante/{estudanteid}', [FinacaController::class, 'MultasePrazosEncarregado']);
Route::get('relatoriodePagamento/anolectivo/{anolectivoID}/studentID/{studentID}', [FinacaController::class, 'relatoriodePagamentoEncarregado']);
/// end termina 
Route::get('/consultarsaldo/Estudante/{studentID}', [AreiaAcademicaController::class, 'EncarregadoConsultarSaldo']);
Route::get('/encarregadoconsultarfaltas/anolectivo/{anolectivo}/estudante/{id}', [EncarregadoCondutaController::class, 'encarregadoconsultarfaltas']);
Route::get('/condutaestudante/anolectivo/{anolectivo}/estudante/{id}', [EncarregadoCondutaController::class, 'EncarregadoCondutaanual']);
Route::post('/ExtratoFinaceiro/anolectivo/{anolectivo}/estudante/{id}', [AreiaAcademicaController::class, 'encarregadoExtratoFinaceiro']);
Route::get('/ConsultarPagamento/anolectivo/{anolectivo}/estudante/{id}', [AreiaAcademicaController::class, 'encaregadoConsultarPagamento']);








});


Route::group(['middleware' => ['auth:api', 'check_usertype:Funcionario'], 'prefix' => 'Funcionario'], function () {
});




Route::middleware('auth:api')->post('update-password', [ResetPassword::class, 'updatePassword']);
///// alterar apalavra passe do usuario
Route::post('ValidarLicense', [AppCenterController::class, 'ValidarLicense'])->withoutMiddleware([LicenseValidationMiddleware::class, 'auth:api']);

/////validar license






Route::post('Scanestudante', [ScanController::class, 'Scanestudante'])->name('scanestudante')->withoutMiddleware([LicenseValidationMiddleware::class, CheckUserType::class, 'auth:api']);
Route::get('aprovado', [ScanController::class, 'aprovado'])->name('aprovado')->withoutMiddleware([LicenseValidationMiddleware::class, CheckUserType::class, 'auth:api']);

Route::get('testarfunctions', [ScanController::class, 'testarfunctions'])->name('testarfunctions')->withoutMiddleware([LicenseValidationMiddleware::class, CheckUserType::class, 'auth:api']);

