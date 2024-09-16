<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Projects;
use App\Services\ProjectSrv;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectController extends Controller
{
    protected $ProjectSrv;

    public function __construct(ProjectSrv $ProjectSrv)
    {
        $this->ProjectSrv = $ProjectSrv;
    }

    public function createProject(ProjectRequest $request){


        $hash = $this->ProjectSrv->HashGen();

        $project = new Projects();
        $project->cliente = $request->cliente;
        $project->tipo = $request->tipo;
        $project->pago_entrada = $request->pago_entrada;
        $project->pago_final = $request->pago_final;
        $project->valor_total = $request->valor_total;
        $project->plazo = $request->plazo;
        $project->progreso = '0';
        $project->finalizado = null;
        $project->hash = $hash;
        $project->save();

        $link = route('leave-review', $hash);
// https://dragonrojosoftware.com/reviewCliente/PLXJSNMC799SA732
        return response()->json(['message' => 'success'], 200);
    }
}
