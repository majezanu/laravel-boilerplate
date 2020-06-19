<?php

namespace App\Core\Domain;

use App\Exceptions\ApiException;
use App\Exceptions\AuthorizationException;
use App\Exceptions\DatabaseException;
use App\Core\Data\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    private $repository;

    public $successStatus = 200;
    // Variable Global para el codigo de NO AUTORIZADO.
    public $unauthorizedStatus = 401;
    // Variable Global para el Estatus de respuesta erronea.
    public $errorStatus = 400;

    public function __construct(BaseRepository $repository) {
        $this->repository = $repository;
    }

    public function doBeforeAdd($data)
    {
        return $data;
    }

    public function add(array $data)
    {
        try {
            DB::beginTransaction();
            $data = $this->doBeforeAdd($data);
            $object = $this->repository->createOrUpdate($data);
            $result = $this->doAfterAdd($object, $data);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $this->errorStatus);
        }
    }

    public function doAfterAdd(Model $object, array $data)
    {
        return $object;
    }

    public function bulkInsert(array $models)
    {
        try {
            DB::beginTransaction();
            $models = $this->repository->bulkInsert($models);
            DB::commit();
            return $models;
        } catch (Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $this->errorStatus);
        }
    }

    public function doBeforeUpdate(Model $object, array $data)
    {
        return $data;
    }

    public function update(int $id, array $data): Model
    {
        $object = $this->getOne($id);
        DB::beginTransaction();
        $data = $this->doBeforeUpdate($object, $data);
        $object = $this->repository->createOrUpdate(
            $data,
            ['id' => $id]
        );
        $result = $this->doAfterUpdate($object, $data);
        DB::commit();
        return $this->repository->getByID($result->id);
    }

    public function doAfterUpdate(Model $object, array $data) : Model
    {
        return $object;
    }

    public function doBeforeDelete(Model $object)
    {

    }

    public function delete(int $id) : void
    {
        $object = $this->getOne($id);
        $this->doBeforeDelete($object);
        $this->repository->delete($object->id);
    }

    public function getOne(int $id, ?array $relations = null) : Model
    {
        $object = $this->repository->getByID($id,$relations);
        if ($object == null) {
            throw new DatabaseException(trans('database.not_found'), 404);
        }
        $object->creator;
        $object->editor;
        return $object;
    }

    public function getOneByFilters(array $filters) : ?Model
    {
        return $this->repository->getByFilters($filters);
    }

    public function getOneByFiltersSimple(array $filters) : ?Model
    {
        return $this->repository->getAll($filters)->first();
    }

    public function getAll(array $filters = [],?array $relations = null)
    {
        $objects = $this->repository->getAll($filters, $relations);
        $objects->each(function($model) {
            $model->creator;
            $model->editor;
        });
        return $objects;
    }

    public function getAllSimple(array $filters = [],?array $relations = null)
    {
        $objects = $this->repository->getAll($filters, $relations);
        return $objects;
    }

    public function getAllWithQuery(array $filters = [],?array $relations = null, $query)
    {
        $objects = $this->repository->getAllWithQuery($filters, $relations, $query);
        return $objects;
    }

    public function buildQueryAll(array $filters = [],?array $relations = null)
    {
        return $this->repository->buildQueryAll($filters, $relations);
    }

    public function buildQuery(array $filters = [],?array $relations = null, $query)
    {
        return $this->repository->buildQuery($filters, $relations, $query);
    }

    public function getRepository() : BaseRepository
    {
        return $this->repository;
    }
    public function getModel() : Model
    {
        return $this->getRepository()->getModel();
    }
}
