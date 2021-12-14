<?php

namespace Core\Repositories;


interface BaseRepositoryInterface
{
    public function setConnection($connection);
    public function find(array $arrCondition, $fields = null, $sort = null, $limit = -1, $offset = 0, $distinct = null);
    /**
     * Count model with condition and
     * @param array $arrCondition
     * @return mixed
     */
    public function findCount(array $arrCondition);

    
    public function findOne(array $arrCondition,$sort = null);

    /**
     * Insert new model
     * @param array $arrParam
     * @return mixed
     */
    public function insert(array $arrParam);

    /**
     * Update exist model
     * @param array $arrParam
     * @param $id
     * @return null
     */
    public function update(array $arrParam, $id);

    /**
     * Update exist model
     * @param array $arrParam
     * @param $arrCondition
     * @return null
     */
    public function updateByCondition(array $arrParam, array $arrCondition,$limit=1);

    /**
     * Try dispatch job
     * @param $job
     * @param $queueName
     */
    // public function dispatchJob($job, $queueName);
    // public function pushNotification($message, $channel, $type = 'redis');

}
