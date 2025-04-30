<?php

namespace Modules\Appointment\Exports;

use App\UserMeta;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentUser;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Auth;

class AppointmentUserExportPerSheet implements FromQuery, WithMapping, WithHeadings,WithEvents
{
    use Exportable;
    private $appointment_ids = [];
    private $sheetName = "";
    private $dayTime = "";
    public function __construct(String $sheetName, String $titleExellPage,array $appointment_ids)
    {
        $this->appointment_ids = $appointment_ids;
        $this->sheetName = $sheetName;
        $this->dayTime = $titleExellPage;
    }

    public function headingRow(): int
    {
        return 2;
    }
    public function headings(): array
    {
        return [[
            $this->dayTime
       ],[
            "#",
            "وضعیت",
            "ثبت کننده",
            "نوع نوبت",
            "نام کاربر",
            "شهر",
            "شماره موبایل",
            "بخش",
            "پزشک",
            "تاریخ",
            "از ساعت",
            "تا ساعت",
            "طریقه آشنایی",
            "خدمات",
            "توضیحات",
            " کد معرف / زیبایار",
            "شماره دوم"
        ] ];
    }

    /**
     * @param AppointmentUser $appointmentUser
     * @return array
     */
    public function map($appointmentUser): array
    {
        $status = "تایید شده";
        if ($appointmentUser->status == AppointmentUser::STATUS_CANCELED) {
            $status = "کنسل شده";
        } elseif ($appointmentUser->status == AppointmentUser::STATUS_PENDING) {
            $status = "در انتظار";
        }
        elseif ($appointmentUser->status == AppointmentUser::STATUS_DELETED) {
            $status = "حذف شده";
        }
        elseif ($appointmentUser->status == AppointmentUser::STATUS_PENDING_CALL) {
            $status = "تلفنی بدون وقت";
        }
        return [
            $appointmentUser->id,
            $status,
            (optional($appointmentUser->agent)->full_name ?? ''),
            ($appointmentUser->kind == AppointmentUser::KIND_MAIN_APPOINTMENT) ? "نوبت اصلی" : "نوبت بین مریض",
            (optional($appointmentUser->user)->full_name ?? ''),
            $appointmentUser->user->address,
            $appointmentUser->user->mobile,
            $this->chanePartName(($appointmentUser->appointmentPart->title  ?? ''), $appointmentUser->r_part),
            //$appointmentUser->appointmentPart->title,
            (optional($appointmentUser->doctor)->full_name ?? ''),
            jdate('l j F', jmktime(0, 0, 0, $appointmentUser->month, $appointmentUser->day, $appointmentUser->year)),
            substr($appointmentUser->time_from, 0, -3),
            substr($appointmentUser->time_to, 0, -3),
            (intval($appointmentUser->user->acquainted)) ? Appointment::$ACQUAINTED[$appointmentUser->user->acquainted] : $appointmentUser->user->acquainted,
            (isset($appointmentUser->detail[UserMeta::SERVICE_DETAIL]) ? $appointmentUser->detail[UserMeta::SERVICE_DETAIL] : ' ' ),
            (isset($appointmentUser->detail[UserMeta::SERVICE]) ? ($appointmentUser->detail[UserMeta::SERVICE] == 'undefined' ? '' : $appointmentUser->detail[UserMeta::SERVICE]) : ' ' ),
            (isset($appointmentUser->detail[AppointmentUser::IDENTIFIERـCODE]) ? $appointmentUser->detail[AppointmentUser::IDENTIFIERـCODE] : ' ' ),
            (isset($appointmentUser->detail[AppointmentUser::SECOND_MOBILE]) ? $appointmentUser->detail[AppointmentUser::SECOND_MOBILE] : ' ' )

        ];
    }

    private function chanePartName($title , $partName)
    {

        $partTimeOut = '';
        if (count($partName) > 0 && is_assoc($partName)) {

            $partTimeOut = ' - [ ';
            $i = 0;
            foreach ($partName as $name => $time) {
                $i++;
                $partTimeOut .= $name ;
                if($i < count($partName)) $partTimeOut .= ' - ';
            }
            $partTimeOut .=' ]';
        }
        return $title . $partTimeOut;
    }

    public function query()
    {
        return AppointmentUser::withTrashed(Auth::user()->viewTrasheed())->whereIn('id',$this->appointment_ids)->orderBy('doctor_id','DESC')->orderBy('appointment_part_id','DESC')->orderBy('time_from','ASC');
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setTitle($this->sheetName);
                $event->sheet->getDelegate()->setRightToLeft(true);
                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setAutoSize(true);
                $event->sheet->getDelegate()->getStyle('A1:Q1')->applyFromArray(
                    [
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
                    ]
                );
                $event->sheet->getDelegate()->mergeCells('A1:Q1');
                $event->sheet->getDelegate()->getStyle('A2:Q2')->applyFromArray(
                    [
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
                    ]
                );
                $appointments = AppointmentUser::withTrashed(Auth::user()->viewTrasheed())->whereIn('id',$this->appointment_ids)->orderBy('doctor_id','DESC')->orderBy('appointment_part_id','DESC')->orderBy('time_from','ASC')->get();
                $counter = 2;
                foreach ($appointments as $appointmetn){
                    $counter++;
                    $bg = "FFFFFFFF";
                    $fontColor = "FF000000";
                    //$appointmetn = $appointments->where('id',$id)->first();
                    if($appointmetn->status == AppointmentUser::STATUS_CANCELED){
                        $bg = "FFfadbd6";
                        $fontColor = "FFbc2d16";
                    }elseif($appointmetn->status == AppointmentUser::STATUS_DELETED) {
                        $bg = "FFfdf0d4";
                        $fontColor = "FFbc2d16";
                    }elseif($appointmetn->status == AppointmentUser::STATUS_PENDING){
                        $bg = "FFfdf0d4";
                        $fontColor = "FFc88a0a";
                    }elseif($appointmetn->status == AppointmentUser::STATUS_OK && $appointmetn->kind == AppointmentUser::KIND_MAIN_APPOINTMENT){
                        $bg = "FFD9F1DC";
                        $fontColor = "FF3f9532";
                    }else{
                        $bg = "FFcaddf5";
                        $fontColor = "FF1f57a1";
                    }
                    $event->sheet->getDelegate()->getStyle('A'.$counter.':Q'.$counter.'')->applyFromArray(
                        [
                            'font' => [
                                'color' => [
                                    'argb' => $fontColor,
                                ],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => $bg,
                                ],
                            ],
                        ]
                    );
                }
            },
        ];
    }

}
