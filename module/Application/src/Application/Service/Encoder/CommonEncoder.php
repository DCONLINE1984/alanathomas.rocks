<?php

/* 
 * The common encoder: all encoders extend from this one
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Service\Encoder;

abstract class CommonEncoder
{
    /**
     * Holds the primary key of the table
     * @var string
     */
    public $primaryKey = "id";
    
    /**
     * Holds the table name
     * @var string
     */
    protected $table = "";
    
    /**
     * Holds the db fields and their mapping
     * @var string
     */
    public $fields = array();
    
    /**
     * The constructor
     */
    public function __construct()
    {
    }
    
    /**
     * Encode the result set into a collection of models
     * @param  object   $resultSet         The result set to hydrate with
     * @param  object   $modelToHydrate    The model to hydrate
     * @return boolean|\Application\Model\BlogPost
     */
    public function encode($resultSet,
                           $modelToHydrate)
    {
        $collection = array();
        if($resultSet->count()==0)
            return false;
        foreach($resultSet as $row){
            foreach($this->fields as $key=>$value){
                if(isset($row[$value])){
                    $row[$key] = $row[$value];
                }
            }
            array_push($collection,
                       new $modelToHydrate($row));
        }
        return $collection;
    }
    
    /**
     * Map an array so it matches the db input fields
     * @param  array $params
     * @return array
     */
    public function map($params)
    {
        $newParams = array();
        foreach($params as $key=>$value){
            if(isset($this->fields[$key])){
                $newParams[$this->fields[$key]] = $value;
            }
        }
        return $newParams;
    }
}