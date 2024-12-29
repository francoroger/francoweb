<?php

namespace App\Exports;

use App\Catalogacao;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CheckListExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Catalogacao::query();

        if (!empty($this->request->get('idproduto'))) {
            $query->where('idproduto', $this->request->get('idproduto'));
        }
        if (!empty($this->request->get('idmaterial'))) {
            $query->where('idmaterial', $this->request->get('idmaterial'));
        }
        if (!empty($this->request->get('idfornec'))) {
            $query->where('idfornec', $this->request->get('idfornec'));
        }
        if (!empty($this->request->get('referencia'))) {
            $query->where('referencia', 'like', '%' . $this->request->get('referencia') . '%');
        }
        if (!empty($this->request->get('status'))) {
            $query->where('status', $this->request->get('status'));
        }
        if (!empty($this->request->get('status_check'))) {
            $query->where('status_check', $this->request->get('status_check'));
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Produto',
            'Material',
            'Fornecedor',
            'Referência',
            'Peso',
            'Quantidade',
            'Status',
            'Status Check',
            'Data Cadastro',
            'Data Atualização'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->produto->descricao ?? '',
            $row->material->descricao ?? '',
            $row->fornecedor->nome ?? '',
            $row->referencia,
            $row->peso,
            $row->quantidade,
            $row->status,
            $row->status_check,
            $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : '',
            $row->updated_at ? $row->updated_at->format('d/m/Y H:i:s') : ''
        ];
    }
}
