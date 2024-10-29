<?php


namespace App\Http\ECH;


use Illuminate\Support\Facades\DB;

class SqlCodeGenerator
{
    private $tableName;

//    public function __construct($model)
//    {
//        $this->setTableName($model);
//    }

    public function setTableName($model)
    {
        $this->tableName = $model->getTable();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function tableGenerate()
    {
        return DB::table($this->tableName);
    }


    public function rawSql($sql)
    {
        return DB::select(DB::raw($sql));
    }

}
