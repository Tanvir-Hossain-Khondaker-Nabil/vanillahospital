<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/21/2019
 * Time: 4:29 PM
 */

namespace App\Http\Traits;


use Illuminate\Support\Facades\DB;

trait AccountTypeTrait
{
    public function select_sub_heads($page=10)
    {
        $query = DB::table("account_types")->select('a.*', 'b.display_name as parent_name')
            ->from('account_types as a')
            ->whereIn('a.parent_id',function($query){
                $query->select('id')
                    ->from('account_types')
                    ->whereIn('parent_id',function($query){
                        $query->select('id')->from('account_types')->whereNull('parent_id');
                    });
            })
            ->leftJoin('account_types as b','b.id','=','a.parent_id');

            if( $page > 0)
                $query = $query->paginate($page);

        return $query;
    }

    public function select_heads($page=10)
    {
        $query = DB::table("account_types")->select('a.*', 'b.display_name as parent_name')
            ->from('account_types as a')
            ->whereIn('a.parent_id',function($query){
                $query->select('id')->from('account_types')->whereNull('parent_id');
            })
            ->leftJoin('account_types as b','b.id','=','a.parent_id');

            if( $page > 0)
                $query = $query->paginate($page);

        return $query;

    }

}
