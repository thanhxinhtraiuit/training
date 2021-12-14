<?php

namespace Core\Repositories;

use Core\Models\BaseModel;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(BaseModel $baseModel)
    {
        $this->model = $baseModel;
    }

    // public function find($id)
    // {
    //     return $this->model->find($id);
    // }

    public function setConnection($connection){
        $this->model->setConnection($connection);
    }

    function _buildGroupQuery($query,$operator, array $arrDataQuery){


        if (count($arrDataQuery)) {

            return $query = $query->{$operator}(function ($query) use ($arrDataQuery) {


                foreach ($arrDataQuery as $rowquery) {

                    if(isset($rowquery['operator']) &&
                    isset($rowquery['data']) &&
                    $rowquery['operator'] &&
                    is_array($rowquery['data'])){

                        $operator = $rowquery['operator'];

                        $dataQuery = $rowquery['data'];

                        if(count($dataQuery) == 3){

                            list($temp1,$temp2,$temp3) = $dataQuery;

                            $query = $query->{$operator}($temp1,$temp2,$temp3);

                        }elseif(count($dataQuery) == 2){

                            list($temp1,$temp2) = $dataQuery;

                            $query = $query->{$operator}($temp1,$temp2);
                        } elseif($operator == 'whereRaw' && count($dataQuery) == 1) {
                            list($temp1) = $dataQuery;
                            $query = $query->{$operator}($temp1);
                        }
                    }
                }

                return $query;

            });

        }

        return $query;
    }

    function _buildQuery($query, array $arrDataQuery){


        if (count($arrDataQuery) && isset($arrDataQuery[0]['operator']) ) {

            foreach ($arrDataQuery as $rowquery) {

                if(isset($rowquery['groupdataquery']) &&  $rowquery['groupdataquery']){

                    // only where/orWhere
                    if($rowquery['operator'] == 'where' || $rowquery['operator'] == 'orWhere' || $rowquery['operator'] == 'whereRaw'){
                        $query = $this->_buildGroupQuery($query,$rowquery['operator'],$rowquery['groupdataquery']);
                    }

                }else if( isset($rowquery['operator']) &&
                    isset($rowquery['data']) && 
                    $rowquery['operator'] && is_array($rowquery['data'])){

                    $operator = $rowquery['operator'];
                    $dataQuery = $rowquery['data'];

                    if ($operator == 'groupBy') {
                        list($temp1) = $dataQuery;
                        $query = $query->{$operator}($temp1);
                    } else if(count($dataQuery) == 3){
                        list($temp1,$temp2,$temp3) = $dataQuery;
                        $query = $query->{$operator}($temp1,$temp2,$temp3);
                    }elseif(count($dataQuery) == 2){
                        list($temp1,$temp2) = $dataQuery;
                        $query = $query->{$operator}($temp1,$temp2);
                    } elseif (count($dataQuery) == 1 && $operator == 'whereRaw') {
                        list($temp1) = $dataQuery;
                        $query = $query->{$operator}($temp1);
                    }
                }
            }

        }elseif(is_array($arrDataQuery)){

            $query = $query->where($arrDataQuery);
        }

        return $query;
    }

    public function find(array $arrCondition, $fields = null, $sort = null, $limit = -1, $offset = 0, $distinct = null){

        $query = $this->model;

        // Chẹck has condition
        if (is_array($arrCondition)) {
            $query = $this->_buildQuery( $query, $arrCondition);

        }

        // Has field
        if (is_array($fields)) {
            $query->select($fields);
        }

        // Has sort
        if (is_array($sort)) {
            $field = $sort['field'];
            $dir = isset($sort['dir']) ? $sort['dir'] : 'desc';
            if(is_array($field)){
                foreach ($field as $sortField){
                    $query = $query->orderBy($sortField, $dir);
                }
            }else{
                $query = $query->orderBy($field, $dir);
            }

        }

        // Has limit
        if ($limit > 0) {
            $query = $query->take($limit);
        }

        // Has offset
        if ($offset > 0) {
            $query = $query->skip($offset);
        }

        // Execute query
        return $query->get();
    }



    public function findCount(array $arrCondition){

        $query = $this->model;

        // Chẹck has condition
        if (is_array($arrCondition)) {
             $query = $this->_buildQuery( $query, $arrCondition);
        }

        return $query->count();
    }


    /**
     * Find model by condition
     * @param array $arrCondition
     * @return mixed
     */
    public function findOne(array $arrCondition,$sort=null){

        $query = $this->model;

        if (is_array($arrCondition)) {
             $query = $this->_buildQuery( $query, $arrCondition);
        }

        // Has sort
        if (is_array($sort) && isset($sort['field']) && $sort['field']) {
            $field = $sort['field'];
            $dir = isset($sort['dir']) ? $sort['dir'] : 'desc';
            $query = $query->orderBy($field, $dir);
        }

        if (isset($sort['is_locked']) && $sort['is_locked']) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    /**
     * Insert new model
     * @param array $arrParam
     * @return mixed
     */
    public function insert(array $arrParam){
        return $this->model->create($arrParam);
    }

    /**
     * Update exist model
     * @param array $arrParam
     * @param $id
     * @return null
     */
    public function update(array $arrParam, $id){

        $model = $this->model->where($this->model->getKeyName(), $id)->first();

        if(!$model){
            return null;
        }
        // TODO: For logging model before update

        $model->fill($arrParam)->save();

        return $model;
    }

    /**
     * Update exist model
     * @param array $arrParam
     * @param $arrCondition
     * @return null
     */
    public function updateByCondition(array $arrParam, array $arrCondition, $limit = 1){

        $query = $this->model;

        if (is_array($arrCondition)) {
             $query = $this->_buildQuery($query, $arrCondition);
        }

        // Execute query
        $total = $query->count();

        if( $total && $total <= $limit && $arrParam ){

            $query->update($arrParam);

            return $total;
        }

        return false;


    }

    /**
     * Try dispatch job
     * @param $job
     * @param $queueName
     */
    // public function dispatchJob($job, $queueName){
    //     // Try to dispatch job 10 times
    //     $loopIndex = 0;
    //     do{
    //         $dispatchRes = dispatch($job->onQueue($queueName));
    //         if($dispatchRes){
    //             break;
    //         }
    //         $loopIndex++;
    //         Log::error(json_encode(['queue' => $queueName, 'job' => $job, 'result' => $dispatchRes, 'loop' => $loopIndex]));
    //         sleep(0.1);
    //     }while($loopIndex < 2);
    // }

    // public function pushNotification($message, $channel, $type = 'redis'){
    //     if(is_array($message)){
    //         $message = json_encode($message);
    //     }
    //     Redis::publish($channel, $message);
    // }

    public function delete($id){
        $model = $this->model->where($this->model->getKeyName(), $id)->first();
        $deleted = $model->delete();

        return true;
    }
}
