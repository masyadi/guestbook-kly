<?php

namespace App\Excel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TableExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithCustomStartCell, WithDrawings, WithEvents, WithColumnFormatting
{
    use Exportable;

    public $setting;
    public $mapping;
    public $mappingNumb;
    public $rowStart;
    private $columnFormats = [];

    public function __construct($query, array $setting=null, callable $mapping=null)
    {
        $this->query = $query;
        $this->setting = $setting;
        $this->mapping = $mapping;
        $this->mappingNumb = 0;
        $this->rowStart = $this->setting['noheader'] ? 1 : 5;
    }

    public function setColumnFormats(Array $columnFormats)
    {
        $this->columnFormats = $columnFormats;
    }

    public function headings(): array
    {        
        return \Helper::val($this->setting, 'header');
    }

    /**
    * @var Invoice $invoice
    */
    public function map($row): array
    {
        $mapping = $this->mapping;

        $this->mappingNumb++;

        return $mapping($this->mappingNumb, $row);
    }

    public function startCell(): string
    {
        return 'A'.$this->rowStart;
    }

    public function drawings()
    {
        $drawing = new Drawing();

        $drawing->setName('Logo');
        $drawing->setDescription('logo');
        $drawing->setPath($this->setting['logo']);
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');

        if( $this->setting['noheader'] )
        {
            $drawing->setHeight(0);
        }

        return $drawing;
    }

    public function query()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        // return [
        //     'E' => NumberFormat::FORMAT_TEXT,
        // ];

        return $this->columnFormats;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {

                $event->writer->getProperties()
                            ->setCreator(config('app.name'))
                            ->setLastModifiedBy(config('app.name'))
                            //->setTitle("Office 2007 XLSX Test Document")
                            //->setSubject("Office 2007 XLSX Test Document")
                            /*->setDescription(
                                "Test document for Office 2007 XLSX, generated using PHP classes."
                            )
                            ->setKeywords("office 2007 openxml php")
                            ->setCategory("Test result file")*/;
            },
            AfterSheet::class    => function(AfterSheet $event) {

                $event->sheet->setShowGridlines(false);
                $event->sheet->getPageMargins()
                        ->setLeft(0.1)
                        ->setRight(0.1)
                        ->setTop(0.1)
                        ->setBottom(0.1)
                        ->setHeader(0);

                if($title = \Helper::val($this->setting, 'title'))
                {
                    $event->sheet->setTitle($title);
                    $event->sheet->getHeaderFooter()
                        ->setOddFooter('&L&B' . $event->sheet->getTitle() . '&RPage &P of &N');
                }
                
                if(\Helper::val($this->setting, 'orientation')) $event->sheet->setOrientation(\Helper::val($this->setting, 'orientation'));

                if($h = \Helper::val($this->setting, 'header'))
                {
                    $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($h));
                    $endRow = $this->mappingNumb + $this->rowStart;

                    $event->sheet->styleCells('A'.$this->rowStart.':'.$endCol.$this->rowStart , [
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            ],
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'DEDEDE',
                            ]
                        ],
                    ]);

                    $event->sheet->styleCells('A'.($this->rowStart+1).':'.$endCol.$endRow , [
                        'font' => [
                            'bold' => false,
                            'size' => 9,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                    if( !$this->setting['noheader'] )
                    {
                        $event->sheet->mergeCells('B1:'.$endCol.'2');
                        
                        $company = config('app.name');
                        $subtitle = config('app.title');

                        $event->sheet->getCell('B1')->setValue(str_repeat(' ', 5).$company);
                        $event->sheet->getCell('B3')->setValue(str_repeat(' ', 9).$subtitle);
                        
                        $event->sheet->styleCells('B1' , [
                            'font' => [
                                'bold' => false,
                                'wrapText' => false,
                                'size' => 18,
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ]
                        ]);
                        $event->sheet->styleCells('B3' , [
                            'font' => [
                                'bold' => false,
                                'wrapText' => false,
                                'size' => 10,
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ]
                        ]);
                    }
                    
                }
            },
        ];
    }

}
