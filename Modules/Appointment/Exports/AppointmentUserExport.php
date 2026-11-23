<?php

namespace Modules\Appointment\Exports;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AppointmentUserExport implements Export, WithMultipleSheets, Responsable
{
    use Exportable;
    private $sheetsContainer = [];
    public function __construct()
    {
        $this->fileName = 'export.xlsx';
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
