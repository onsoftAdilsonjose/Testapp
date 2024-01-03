<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
* @OA\Info(
*      version="0.4.0",
*      title="Sistema de Gestão Escolar Api",
*      description="A gestão escolar com API envolve a integração de sistemas e aplicativos para otimizar a administração escolar, permitindo a troca de dados e informações de forma eficiente entre diferentes componentes do ambiente educacional, como registros acadêmicos, matrículas, sistemas de pagamento e comunicação com pais e alunos.",
*      @OA\Contact(
*          email="adilson2012jose@gmail.com"
*      ),
*     @OA\License(
*         name="Apache 2.0",
*         url="https://www.apache.org/licenses/LICENSE-2.0.html"
*     )
* )
* 
*/
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
