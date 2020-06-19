<?php

namespace App\Core\Http;

use App\Core\Domain\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $service;
    // Variable Global para el Estatus de respuesta exitosa.
    public $successStatus = 200;
    // Variable Global para el codigo de NO AUTORIZADO.
    public $unauthorizedStatus = 401;
    // Variable Global para el Estatus de respuesta erronea.
    public $errorStatus = 400;

    /**
     * Controller constructor
     *
     * @param BaseService $service
     */
    public function __construct(BaseService $service) {
        $this->service = $service;
    }

    /**
     * Function to show a model object
     *
     * @param Request $request
     * @param integer $id
     * @param array|null $relations
     * @return JsonResponse
     */
    public function detail(Request $request,int $id) : JsonResponse
    {
       $model = $this->service->getOne($id, $request->query('relations', $this->relationsNames()));
       return $this->response($model, 200);
    }

    /**
     * Function to show a model array
     *
     * @param Request $request
     * @param array|null $relations
     * @return void
     */
    public function index(Request $request) : JsonResponse
    {
        $models = $this->service->getAll($request->query(),$request->query('relations', $this->relationsNames()))->toArray();
        return $this->response($models, 200);
    }

    /**
     * Function to make rules for store
     *
     * @param void
     * @return array
     */
    protected function rulesForStore() : array
    {
        return $this->service->getModel()->rulesForStore();
    }

    /**
     * Function to make rules for store an array of models
     *
     * @param void
     * @return array
     */
    protected function rulesForBulkInsert() : array
    {
        return $this->service->getModel()->rulesForBulkInsert();
    }

    /**
     * Function to make rules for update
     *
     * @param void
     * @return array
     */
    protected function rulesForUpdate() : array
    {
        return $this->service->getModel()->rulesForUpdate();
    }

    /**
     * Function to return default relations
     *
     * @param void
     * @return array
     */
    public function relationsNames()
    {
        return $this->service->getModel()->relationsNames();
    }
    /**
     * Function to store a model on database
     *
     * @param Request $request
     * @param array|null $validations
     * @return void
     */
    public function store(Request $request, ?array $validations = null) : JsonResponse
    {
        $this->validate($request, $this->rulesForStore());
        $model = $this->service->add($request->all());
        return $this->response($model, 200);
    }

    /**
     * Function to store an array of models on database
     *
     * @param Request $request
     * @param array|null $validations
     * @return void
     */
    public function bulkInsert(Request $request, ?array $validations = null) : JsonResponse
    {
        $this->validate($request, $this->rulesForBulkInsert());
        $result = $this->service->bulkInsert($request->all());
        return $this->response(['result' => $result], 200);
    }

    /**
     * Function to edit a model on database
     *
     * @param Request $request
     * @param integer $id
     * @param array|null $validations
     * @return void
     */
    public function update(Request $request,int $id, ?array $validations = null) : JsonResponse
    {
        $this->validate($request, $this->rulesForUpdate());
        $model = $this->service->update($id, $request->all());
        return $this->response($model, 200);
    }

    public function response($response = null, int $status) : JsonResponse
    {
        return $response != null ? response()->json($response, $status) : response()->json( $status);
    }

    /**
     * Function to delete a model
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function destroy(Request $request, int $id)
    {
        $this->service->delete($id);
        return $this->response(null,200);
    }

    public function getService()
    {
       return $this->service;
    }
}
