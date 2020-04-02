<?php

namespace App\Processors;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Slim\Http\Response;

abstract class ObjectProcessor extends Processor
{
    /** @var Model $class */
    protected $class;
    protected $primaryKey = 'id';


    /**
     * @return Response
     */
    public function put()
    {
        try {
            /** @var Model $record */
            $record = new $this->class();
            $record->fill($this->getProperties());
            $check = $this->beforeSave($record);
            if ($check !== true) {
                return $this->failure($check);
            }
            $record->save();
            $record = $this->afterSave($record);

            return $this->success($this->prepareRow($record));
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
    }


    /**
     * @return Response
     */
    public function patch()
    {
        if (!$id = $this->getProperty($this->primaryKey)) {
            return $this->failure('You should provide an ID of record');
        }
        if (!$record = $this->class::query()->find($id)) {
            return $this->failure('Could not find the record');
        }
        try {
            $record->fill($this->getProperties());
            $check = $this->beforeSave($record);
            if ($check !== true) {
                return $this->failure($check);
            }
            $record->save();
            $record = $this->afterSave($record);

            return $this->success($this->prepareRow($record));
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
    }


    /**
     * @param $record
     *
     * @return bool|string
     */
    protected function beforeSave($record)
    {
        return ($record instanceof Model)
            ? true
            : 'Could not save the object';
    }


    /**
     * @param Model $record
     *
     * @return Model
     */
    protected function afterSave($record)
    {
        return $record;
    }


    /**
     * @return Response
     */
    public function get()
    {
        /** @var Model $class */
        $class = new $this->class();
        /** @var Builder $c */
        $c = $class->query();
        if ($id = $this->getProperty($this->primaryKey)) {
            $c = $this->beforeGet($c);
            if ($record = $c->find($id)) {
                $data = $this->prepareRow($record);

                return $this->success($data);
            }

            return $this->failure('Could not find the record with id ' . $id);
        }
        $c = $this->beforeCount($c);
        if ($limit = $this->getProperty('limit', 20)) {
            $page = $this->getProperty('page', 1);
            $total = $c->count();
            // Maybe useful for queries with GROUP BY
            /*$total = $this->container->db->table($this->container->db->raw("({$c->toSql()}) as sub"))
                ->mergeBindings($c->getQuery())
                ->count();*/
            $c->forPage($page, $limit);
        }
        $c = $this->afterCount($c);
        $query = $c->getQuery();
        if (empty($query->{$query->unions ? 'unionOrders' : 'orders'}) && $sort = $this->getProperty('sort', '')) {
            $c->orderBy($class->getTable() . '.' . $sort, $this->getProperty('dir') == 'desc' ? 'desc' : 'asc');
        }
        $rows = [];
        foreach ($c->get() as $object) {
            $rows[] = $this->prepareRow($object);
        }

        return $this->success([
            'total' => isset($total)
                ? $total
                : count($rows),
            'rows' => $rows,
        ]);
    }


    /**
     * Add conditions before get an object by id
     *
     * @param Builder $c
     *
     * @return mixed
     */
    protected function beforeGet($c)
    {
        return $c;
    }


    /**
     * Add joins and search filter
     *
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        return $c;
    }


    /**
     * Add selects to query after total count
     *
     * @param Builder $c
     *
     * @return Builder
     */
    protected function afterCount($c)
    {
        return $c;
    }


    /**
     * @param Model $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        return $object->toArray();
    }


    /**
     * @param Model $record
     *
     * @return bool
     */
    protected function beforeDelete($record)
    {
        return ($record instanceof Model)
            ? true
            : 'Could not delete the object';
    }


    /**
     * @return Response
     */
    public function delete()
    {
        if (!$id = $this->getProperty($this->primaryKey)) {
            return $this->failure('You should specify an id of item');
        }
        /** @var Model $record */
        if (!$record = $this->class::query()->find($id)) {
            return $this->failure('Could not fond the item');
        }
        $check = $this->beforeDelete($record);
        if ($check !== true) {
            return $this->failure($check);
        }
        try {
            $record->delete();

            return $this->success();
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }
    }
}