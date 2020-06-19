<?php

namespace App\Core\Data;

use App\Exceptions\DatabaseException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
/**
 * Base repository
 */
abstract class BaseRepository implements Repository
{
    use Paginable;
    /**
     * Get model by ID
     *
     * @param integer $id
     * @return Model|null
     */
    function getByID(int $id, array $relations = null): Model
    {
        $model = $this->getModel();
        $query = $this->getRelationships($model->newQuery(), $relations);
        $object = $query->find($id);
        if($object == null) {
            throw new DatabaseException(
                trans($this->getTransFileName().'.not_found', ['id'=> $id]), 404);
        }
        return $object;
    }

    /**
     * Get model with softdelete by ID
     *
     * @param integer $id
     * @return Model|null
     */
    function getTrashByID(int $id, array $relations = null): ?Model
    {
        $model = $this->getModel();
        $query = $this->getRelationships($model->newQuery(), $relations);
        return $query->withTrashed()->find($id);
    }

    /**
     * Get model by filters
     *
     * @param array $filters
     * @return Model|null
     */
    function getByFilters(array $filters): ?Model
    {
        $model = $this->getModel();
        $query = $this->getQuery($model, $filters);
        $object = $query->first();
        $filters = [];
        return $object;
    }

    /**
     * Get all models
     *
     * @return Collection|Paginator
     */
    function getAll(array $filters = null, ?array $relations = null)
    {
        $model = $this->getModel();
        $query = $this->getQuery($model, $filters);
        return $this->getAllWithQuery($filters, $relations, $query);
    }

    public function buildQueryAll(array $filters = null, ?array $relations = null)
    {
        $model = $this->getModel();
        $query = $this->getQuery($model, $filters);
        $query = $this->getRelationships($query, $relations);
        $this->setPerPage($filters);
        $this->setPaginable($filters);
        if ($this->showTrashed($filters))
        {
            $query = $query->withTrashed();
        }
        return $query;
    }

    public function buildQuery(array $filters = null, ?array $relations = null, $query)
    {
        $model = $this->getModel();
        $query = $this->getQueryWithQuery($model, $filters, $query);
        $query = $this->getRelationships($query, $relations);
        $this->setPerPage($filters);
        $this->setPaginable($filters);
        if ($this->showTrashed($filters))
        {
            $query = $query->withTrashed();
        }
        return $query;
    }

    public function getAllWithQuery(array $filters = null, ?array $relations = null, $query)
    {
        $model = $this->getModel();
        $query = $this->getQueryWithQuery($model, $filters, $query);
        $query = $this->getRelationships($query, $relations);
        $this->setPerPage($filters);
        $this->setPaginable($filters);
        if ($this->showTrashed($filters))
        {
            $query = $query->withTrashed();
        }
        return $this->getPaginationOrData($query);
    }

    public function setPerPage(array $filters = null)
    {
        $this->itemsPerPage = $filters != null && array_key_exists('per_page',$filters) ?
            $filters['per_page'] : $this->itemsPerPage;
    }

    public function setPaginable(array $filters = null)
    {
        $this->withPagination = $filters != null && array_key_exists('paginable',$filters) ?
        ($filters['paginable']== 'true' ? true : false) : $this->withPagination;
    }

    public function showTrashed(array $filters = null)
    {
        $withTrashed = $filters != null && array_key_exists('with_trashed',$filters) ?
        ($filters['with_trashed']== 'true' ? true : false) : false;
        return $withTrashed;
    }

    /**
     * Get only active models list
     *
     * @return Collection|Paginator
     */
    function getOnlyActives(array $filters = null, ?array $relationships = null)
    {
        $model = $this->getModel();
        if(!Arr::has($filters,'search')) 
        {
            $query = $this->getQuery($model, $filters);
        }else {
            $query = $this->getSearchQuery($model, $filters);
        }
        $query = $this->getRelationships($query, $relationships);

        return $this->getPaginationOrData($query);
    }

    /**
     * Create or update a model
     *
     * @param array $identifiers
     * @param array $attributes
     * @return Model
     */
    function createOrUpdate(array $attributes, array $identifiers = null): Model
    {
        $model = $this->getModel();
        if ($identifiers == null) 
        {
            return $model->create($attributes);
        }

        return $model->updateOrCreate($identifiers, $attributes);
    }

    function bulkInsert(array $models)
    {
        $model = $this->getModel();
        return $model->insert($models);
    }

    /**
     * Delete a model
     *
     * @param integer $id
     * @return boolean
     */
    function delete(int $id): bool
    {
        $model = $this->getModel();
        return $model->find($id)->delete();
    }

    /**
     * Restore a model
     *
     * @param integer $id
     * @return boolean
     */
    function restore(int $id): bool
    {
        $model = $this->getModel();
        return $model->withTrashed()->find($id)->restore();
    }

    /**
     * Get query builder
     *
     * @param Model $model
     * @param array|null $filters
     * @return Builder
     */
    protected function getQuery(Model $model, ?array $filters): Builder
    {
        $query = $model->newQuery();
        return $this->getQueryWithQuery($model, $filters, $query);

    }

    protected function getQueryWithQuery(Model $model, ?array $filters, $query) : Builder
    {
        if ($filters != null) 
        {
            $keys = array_intersect($model->filters ?? [], array_keys($filters));

            foreach ($keys as $key) {
                if ($model->filtersOperators != null && array_key_exists($key, $model->filtersOperators)) 
                {
                    $query->where($key, $model->filtersOperators[$key], $filters[$key]);
                } else if ($filters[$key] != null) {
                    $query->where($key, 'like', '%' . $filters[$key] . '%');
                }
            }


        }
        return $query;
    }

    /**
     * Get search query builder
     *
     * @param Model $model
     * @param array|null $filters
     * @return Builder
     */
    private function getSearchQuery(Model $model, ?array $filters): Builder
    {
        // Create new query
        $query = $model->newQuery();


        // Check if filters exists
        if ($filters != null) {
            // Get valid filter keys
            $keys = $model->filters;


            foreach ($keys as $key) {
                if($key == 'fullname'){
                    $names = explode(" ", $filters['search']);
                    $this->searchOnUserFullName($names,$query);
                }
                else {
                    $query->orWhere($key, 'like', '%' . $filters['search'] . '%');
                }
            }
        }
        return $query;
    }

    private function searchOnUserFullName($names, $query) {
        $query->orWhere(function($query) use ($names) {
            $query->whereIn('name', $names);
        })->orWhere(function($query) use ($names) {
            $query->whereIn('lastname', $names);
        })->orWhere(function($query) use ($names) {
            $query->whereIn('second_lastname', $names);
        });
    }
    /**
     * Get pagination or collection
     *
     * @param Builder $query
     * @return Paginator|Collection
     */
    private function getPaginationOrData(Builder $query)
    {
        // Check if paginations is enabled
        if ($this->withPagination) {
            // Add pagination to query builder
            return $query->simplePaginate($this->itemsPerPage);
        }

        // Return collection
        return $query->get();
    }

    /**
     * Get model relationships
     *
     * @param Model $model
     * @param array|null $relationships
     * @return Builder
     */
    private function getRelationships(Builder $query, ?array $relationships): Builder
    {
        // Check if relationships exists
        if ($relationships != null && !empty($relationships)) {
            return $query->with($relationships);
        }

        // Otherwise return the model
        return $query;
    }
}
