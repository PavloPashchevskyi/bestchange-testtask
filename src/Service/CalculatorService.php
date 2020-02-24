<?php
declare(strict_types=1);

namespace App\Service;

class CalculatorService
{
    /**
     * @param array $data
     * @return array
     */
    public function calculateProfitableRate(array $data): array
    {
        uasort($data, function (array $a, array $b) {
            return ($a['receiving_rate'] > $b['receiving_rate']) ? 0 :
                (($a['receiving_rate'] < $b['receiving_rate']) ? 1 : -1);
        });

        $key = array_key_first($data);
        return $data[$key];
    }

    /**
     * @param array $data
     * @param int $sendingCurrencyId
     * @param int $receivingCurrencyId
     * @return array
     */
    public function getByCurrencies(array $data, int $sendingCurrencyId, int $receivingCurrencyId): array
    {
        return array_filter($data, function (array $item) use ($sendingCurrencyId, $receivingCurrencyId) {
            return ($item['sending_currency_id'] == $sendingCurrencyId &&
                $item['receiving_currency_id'] == $receivingCurrencyId);
        });
    }

    /**
     * @param array $rows
     * @param array $filters
     * @return array
     */
    public function filter(array $rows, array $filters = []): array
    {
        $filteredArray = $rows;

        if (array_key_exists('sending_currency_id', $filters)) {
            $filteredArray = array_filter($filteredArray, function (array $row) use ($filters) {
                return $row['sending_currency_id'] == $filters['sending_currency_id'];
            });
        }
        if (array_key_exists('receiving_currency_id', $filters)) {
            $filteredArray = array_filter($filteredArray, function (array $row) use ($filters) {
                return $row['receiving_currency_id'] == $filters['receiving_currency_id'];
            });
        }

        return $filteredArray;
    }
}
