<?php

namespace Modules\Appointment\Exports;

use App\UserMeta;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Appointment\Entities\AppointmentUser;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ConfirmedAppointmentUserExport implements FromCollection, WithMapping, WithHeadings, WithEvents, Responsable
{
    use Exportable;

    private $appointments;
    private $title;
    private $rowNumber = 0;

    public function __construct(Collection $appointments, $title)
    {
        $this->fileName = 'confirmed-appointments.xlsx';
        $this->appointments = $appointments;
        $this->title = $title;
    }

    public function collection(): Collection
    {
        return $this->appointments;
    }

    public function headings(): array
    {
        return [[
            $this->title,
        ], [
            'ردیف',
            'کد نوبت',
            'زیباجو',
            'کد ملی',
            'شماره پرونده',
            'موبایل',
            'شماره دوم',
            'معرف',
            'بخش',
            'پزشک',
            'تاریخ',
            'ساعت لیست',
            'ساعت اصلی',
            'وضعیت ساعت',
            'خدمت / سرویس لیست',
            'خدمت / سرویس اصلی',
            'وضعیت سرویس',
            'کامنت',
            'ثبت کننده تایید',
            'زمان تایید',
        ]];
    }

    public function map($appointment): array
    {
        $this->rowNumber++;
        $detail = is_array($appointment->detail) ? $appointment->detail : [];
        $originalTimeFrom = substr($appointment->time_from, 0, 5);
        $confirmedTimeFrom = $appointment->confirmed_time_from ? substr($appointment->confirmed_time_from, 0, 5) : $originalTimeFrom;
        $originalServiceName = $detail[UserMeta::SERVICE_DETAIL] ?? ($detail[UserMeta::SERVICE] ?? '');
        $confirmedServiceName = $appointment->confirmed_service_name ?: $originalServiceName;

        return [
            $this->rowNumber,
            $appointment->id,
            optional($appointment->user)->full_name ?: '',
            optional($appointment->user)->national_code ?: '',
            optional($appointment->user)->medical_record_number
                ?: ($detail[AppointmentUser::MEDICAL_RECORD_NUMBER] ?? ''),
            optional($appointment->user)->mobile ?: '',
            $detail[AppointmentUser::SECOND_MOBILE] ?? '',
            $detail[AppointmentUser::IDENTIFIERـCODE] ?? '',
            $this->chanePartName(optional($appointment->appointmentPart)->title ?: '', $appointment->r_part),
            optional($appointment->doctor)->full_name ?: '',
            jdate('l j F Y', jmktime(0, 0, 0, $appointment->month, $appointment->day, $appointment->year)),
            $confirmedTimeFrom,
            $originalTimeFrom,
            ($appointment->confirmed_time_from && $confirmedTimeFrom !== $originalTimeFrom) ? 'تغییر کرده' : '',
            $confirmedServiceName,
            $originalServiceName,
            ($appointment->confirmed_service_name && $appointment->confirmed_service_name !== $originalServiceName) ? 'تغییر کرده' : '',
            $appointment->confirmed_comment ?: '',
            optional($appointment->confirmedBy)->full_name ?: '',
            $appointment->confirmed_at ? jdate('H:i | Y/m/d', $appointment->confirmed_at->timestamp, '', '', 'en') : '',
        ];
    }

    private function chanePartName($title, $partName)
    {
        $partTimeOut = '';
        if (is_array($partName) && count($partName) > 0 && is_assoc($partName)) {
            $partTimeOut = ' - [ ';
            $i = 0;
            foreach ($partName as $name => $time) {
                $i++;
                $partTimeOut .= $name;
                if ($i < count($partName)) {
                    $partTimeOut .= ' - ';
                }
            }
            $partTimeOut .= ' ]';
        }

        return $title . $partTimeOut;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setTitle('لیست تایید شده‌ها');
                $sheet->setRightToLeft(true);
                foreach (range('A', 'T') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->mergeCells('A1:T1');
                $sheet->getStyle('A1:T1')->applyFromArray($this->headerStyle());
                $sheet->getStyle('A2:T2')->applyFromArray($this->headerStyle());

                $rowCount = $this->appointments->count() + 2;
                if ($rowCount >= 3) {
                    $sheet->getStyle('A3:T' . $rowCount)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    for ($row = 3; $row <= $rowCount; $row++) {
                        $sheet->getStyle('A' . $row . ':T' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'FFD9F1DC',
                                ],
                            ],
                            'font' => [
                                'color' => [
                                    'argb' => 'FF3f9532',
                                ],
                            ],
                        ]);
                    }
                }
            },
        ];
    }

    private function headerStyle()
    {
        return [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF3bafda',
                ],
            ],
        ];
    }
}
