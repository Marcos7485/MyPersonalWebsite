<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoadProjectRequest;
use App\Http\Requests\ProjectRequest;
use App\Models\Projects;
use App\Services\ProjectSrv;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectController extends Controller
{
    protected $ProjectSrv;

    public function __construct(ProjectSrv $ProjectSrv)
    {
        $this->ProjectSrv = $ProjectSrv;
    }

    public function createProject(ProjectRequest $request)
    {


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
        $project->mantenimiento = $request->mantenimiento;
        $project->hash = $hash;
        $project->save();

        $link = route('leave-review', $hash);
        // https://dragonrojosoftware.com/reviewCliente/PLXJSNMC799SA732
        return response()->json(['message' => 'success'], 200);
    }


    public function welcome()
    {
        return Inertia::render('cliente/welcome');
    }

    public function load(LoadProjectRequest $request)
    {
        $project = $request->code;

        return response()->json([
            'message' => 'success',
            'redirect' => route('cliente.panel', ['project' => $project]),
        ]);
    }

    public function panel($code){

        $project = Projects::where('hash', $code)->where('active', 1)->first();

        return Inertia::render('cliente/panel', ['project' => $project]);
    }
}
