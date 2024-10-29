<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExpenseTransactionCategoriesDataTable;
use App\DataTables\IncomeTransactionCategoriesDataTable;
use App\DataTables\TransactionCategoriesDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a Expense Transaction Category listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expense_category_list(ExpenseTransactionCategoriesDataTable $dataTable)
    {
        return $dataTable->render('admin.transaction_categories.expense');
    }

    /**
     * Display a income Transaction Category listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function income_category_list(IncomeTransactionCategoriesDataTable $dataTable)
    {
        return $dataTable->render('admin.transaction_categories.income');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
