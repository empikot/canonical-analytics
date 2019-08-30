<?php

namespace App\IO\Services;

use Illuminate\Support\Collection;
use League\Csv\Writer;
use SplTempFileObject;

class CsvFileCreator
{
    /**
     * @param Collection $collection
     * @return Writer
     * @throws \League\Csv\Exception
     */
    public function getFile(Collection $collection): Writer
    {
        $csv = $this->prepareNewCsvWriter();
        $this->writeHeadersToCsv($collection, $csv);
        $this->writeCollectionElementsToCsv($collection, $csv);
        return $csv;
    }

    /**
     * @return Writer
     * @throws \League\Csv\Exception
     */
    private function prepareNewCsvWriter(): Writer
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setDelimiter(',');
        $csv->setEnclosure('"');
        $csv->setEscape('\\');
        return $csv;
    }

    /**
     * @param Collection $collection
     * @param Writer $csv
     */
    private function writeHeadersToCsv(Collection $collection, Writer $csv)
    {
        $collection->first(function ($element) use ($csv) {
            $csv->insertOne(array_keys($this->readElementAsArray($element)));
            return true;
        });
    }

    /**
     * @param Collection $collection
     * @param Writer $csv
     */
    private function writeCollectionElementsToCsv(Collection $collection, Writer $csv)
    {
        $collection->each(function ($element) use ($csv) {
            $csv->insertOne($this->readElementAsArray($element));
        });
    }

    /**
     * @param mixed $element
     * @return array
     */
    private function readElementAsArray($element): array
    {
        if (!is_array($element)) {
            return $element->toArray();
        }
        return $element;
    }
}
