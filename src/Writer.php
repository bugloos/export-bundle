<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle;

use Bugloos\ExportBundle\Contracts\MultipleSheetsInterface;
use Bugloos\ExportBundle\Contracts\PropertiesInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
class Writer
{
    protected Spreadsheet $spreadsheet;

    public function export($export, string $writerType)
    {
        $this->open($export);

        $sheetExports = [$export];

        if ($export instanceof MultipleSheetsInterface) {
            $sheetExports = $export->sheets();
        }

        foreach ($sheetExports as $sheetExport) {
            $this->addNewSheet()->export($sheetExport);
        }

        return $this->write($writerType);
    }

    public function open($export): self
    {
        $this->spreadsheet = new Spreadsheet();

        $this->spreadsheet->disconnectWorksheets();

        $this->handleDocumentProperties($export);

        return $this;
    }

    public function write(string $writerType)
    {
        $this->spreadsheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($this->spreadsheet, $writerType);

        ob_start();
        $writer->save('php://output');
        $output = ob_get_clean();

        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);

        return $output;
    }

    public function addNewSheet(): Sheet
    {
        return new Sheet($this->spreadsheet->createSheet());
    }

    protected function handleDocumentProperties($export)
    {
        $properties = [];

        if ($export instanceof PropertiesInterface) {
            $properties = array_merge($properties, $export->properties());
        }

        $props = $this->spreadsheet->getProperties();

        foreach (array_filter($properties) as $property => $value) {
            $methodName = 'set'.ucfirst($property);

            $props->{$methodName}($value);
        }
    }
}
