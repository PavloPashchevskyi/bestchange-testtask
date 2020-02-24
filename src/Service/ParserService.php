<?php
declare(strict_types=1);

namespace App\Service;

use Exception;

class ParserService
{
    /** @const array */
    private const NEEDED_CSV_COLUMNS = [
        'sending_currency_id' => 0,
        'receiving_currency_id' => 1,
        'sending_rate' => 3,
        'receiving_rate' => 4,
    ];

    public function parse(string $fileToParse): array
    {
        $handle = fopen($fileToParse, 'rt');
        if ($handle === false) {
            throw new Exception('Unable to open file with rates "'.$fileToParse.'"');
        }

        $result = [];
        $i = 0;
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            foreach (self::NEEDED_CSV_COLUMNS as $columnName => $j) {
                $result[$i][$columnName] = $data[$j];
            }
            $i++;
        }

        fclose($handle);

        return $result;
    }
}
