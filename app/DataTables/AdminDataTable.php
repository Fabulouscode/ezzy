<?php

namespace App\DataTables;

use App\Models\Admin;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Auth;

class AdminDataTable extends DataTable
{

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action',function($selected)
                {
                    $data = '';
                    $data .= '<a href="'.url('admin/users/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    if(Auth::guard('admin')->user()->id != $selected->id){
                         $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    
                    return $data;
                })
            ->rawColumns(['action'])
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AdminDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Admin $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('admindatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy([0,'DESC'])
                    ->buttons(
                        Button::make('create'),
                        // Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->data('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('name'),
            Column::make('email'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Admin_' . date('YmdHis');
    }
}
