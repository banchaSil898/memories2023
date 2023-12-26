<?php

namespace UDAdsManager\Core\DFPAdSize;

class DeserializeMultiSize
{
    public function __invoke(string $json): array
    {
        $multi_size = [];
        $data = json_decode($json, true);
        if (empty($data)) {
            return $multi_size;
        }

        if (! is_array($data) || (array_keys($data) !== range(0, count($data) - 1))) {
            return $multi_size;
        }

        foreach ($data as $value) {
            if (is_array($value) && (array_keys($value) === range(0, count($value) - 1)) && count($value) === 2) {
                $multi_size[] = new FixedSize($value[0], $value[1]);
            } elseif ($value === 'fluid') {
                $multi_size[] = new FluidSize();
            }
        }

        return $multi_size;
    }
}
