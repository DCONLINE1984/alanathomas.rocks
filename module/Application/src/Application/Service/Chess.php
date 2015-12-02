<?php

/* 
 * The chess controller
 * @author    Dean Clow
 * @email     <dclow@blackjackfarm.com>
 * @copyright 2014 Dean Clow
 */

namespace Application\Service;
use \Application\Service\CommonService;

class Chess extends CommonService
{
    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        $this->table = 'chess';
    }
    
    public function createDataGridDatasource($rs)
    {
        $finalDatasource = array();
        foreach($rs as $row){
            $date = new \DateTime($row['date']);
            $row['date'] = $date->format('d M Y');
            $row['edit'] = '<a href="/chessgames/edit/'.$row['id'].'">Edit</a>';
            $row['delete'] = '<a onclick="return confirm(\'Are you sure?\');" href="/chessgames/delete/'.$row['id'].'">Delete</a>';
            $row['play'] = '<a href="/chessgames/show/'.$row['id'].'">Play</a>';
            $finalDatasource[] = $row;
        }
        return $finalDatasource;
    }
}