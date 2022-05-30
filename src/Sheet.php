<?php

/**
 * This file is part of the bugloos/export-bundle project.
 * (c) Bugloos <https://bugloos.com/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bugloos\ExportBundle;

use Bugloos\ExportBundle\Contracts\ColumnFormattingInterface;
use Bugloos\ExportBundle\Contracts\ColumnWidthsInterface;
use Bugloos\ExportBundle\Contracts\CustomStartCellInterface;
use Bugloos\ExportBundle\Contracts\ShouldAutoSizeInterface;
use Bugloos\ExportBundle\Contracts\StylesInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * @author Mojtaba Gheytasi <mjgheytasi@gmail.com | mojtaba.g@bugloos.com>
 */
class Sheet
{
    private Worksheet $worksheet;

    private object $exportable;

    public function __construct(Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;
    }

    public function export($sheetExport)
    {
        $this->open($sheetExport);

        $this->fromCollection($sheetExport);

        $this->close($sheetExport);
    }

    public function disconnect()
    {
        $this->worksheet->disconnectCells();
        unset($this->worksheet);
    }

    private function open($sheetExport)
    {
        $this->exportable = $sheetExport;

        $this->worksheet->setTitle($sheetExport->title());

        $startCell = $sheetExport instanceof CustomStartCellInterface ?
            $sheetExport->startCell() : null;

        $this->append($sheetExport->headings(), $startCell);
    }

    private function fromCollection($sheetExport)
    {
        $this->appendRows($sheetExport->collection(), $sheetExport);
    }

    private function appendRows(iterable $rows, $sheetExport)
    {
        $rows = array_map(function ($row) use ($sheetExport) {
            return $sheetExport->map($row);
        }, $rows);

        $startCell = $sheetExport instanceof CustomStartCellInterface ?
            $sheetExport->startCell() : null;

        $this->append($rows, $startCell);
    }

    private function append(array $rows, string $startCell = null)
    {
        if (null === $startCell) {
            $startCell = 'A1';
        }

        if ($this->hasRows()) {
            $startCell = self::getColumnFromCoordinate($startCell).($this->worksheet->getHighestRow() + 1);
        }

        $this->worksheet->fromArray($rows, null, $startCell);
    }

    private function hasRows(): bool
    {
        $startCell = 'A1';

        if ($this->exportable instanceof CustomStartCellInterface) {
            $startCell = $this->exportable->startCell();
        }

        return $this->worksheet->cellExists($startCell);
    }

    private function close($sheetExport)
    {
        $this->exportable = $sheetExport;

        if ($sheetExport instanceof ColumnFormattingInterface) {
            foreach ($sheetExport->columnFormats() as $column => $format) {
                $this->formatColumn($column, $format);
            }
        }

        if ($sheetExport instanceof ShouldAutoSizeInterface) {
            $this->autoSize();
        }

        if ($sheetExport instanceof ColumnWidthsInterface) {
            foreach ($sheetExport->columnWidths() as $column => $width) {
                $this->worksheet->getColumnDimension($column)
                    ->setAutoSize(false)
                    ->setWidth($width)
                ;
            }
        }

        if ($sheetExport instanceof StylesInterface) {
            $styles = $sheetExport->styles($this->worksheet);

            if (\is_array($styles)) {
                foreach ($styles as $coordinate => $coordinateStyles) {
                    if (is_numeric($coordinate)) {
                        $coordinate = 'A'.$coordinate.':'.$this->worksheet->getHighestColumn($coordinate).$coordinate;
                    }

                    $this->worksheet->getStyle($coordinate)->applyFromArray($coordinateStyles);
                }
            }
        }
    }

    private function autoSize()
    {
        foreach ($this->buildColumnRange('A', $this->worksheet->getHighestDataColumn()) as $col) {
            $dimension = $this->worksheet->getColumnDimension($col);

            if (-1 == $dimension->getWidth()) {
                $dimension->setAutoSize(true);
            }
        }
    }

    private function buildColumnRange(string $lower, string $upper): \Generator
    {
        ++$upper;

        for ($i = $lower; $i !== $upper; ++$i) {
            yield $i;
        }
    }

    private function formatColumn(string $column, string $format)
    {
        $this->worksheet
            ->getStyle($column.'1:'.$column.$this->worksheet->getHighestRow())
            ->getNumberFormat()
            ->setFormatCode($format)
        ;
    }

    private static function getColumnFromCoordinate(string $coordinate): string
    {
        return preg_replace('/[0-9]/', '', $coordinate);
    }
}
