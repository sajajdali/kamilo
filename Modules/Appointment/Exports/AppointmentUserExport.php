<?php

namespace Modules\Appointment\Exports;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AppointmentUserExport implements WithMultipleSheets,Responsable
{
    use Exportable;
    private $sheetsContainer = [];
    private $fileName = 'export.xlsx';
    public function __construct()
    {
        $this->sheetsContainer = func_get_args();
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->sheetsContainer[0] as $sheet){
            $sheets[] = new AppointmentUserExportPerSheet($sheet["title"],$sheet["titleExellPage"],$sheet["items"]);
        }
        return $sheets;
    }
}