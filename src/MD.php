<?php

declare(strict_types=1);

namespace BrainCore;

class MD
{
    public static function fromArray(array $data, int $start = 0): string
    {
        $md = '';
        $iterationHeaders = $start;
        foreach ($data as $key => $value) {
            if (! is_int($key)) {
                if ($md !== '') {
                    $md .= PHP_EOL;
                }
                $header = str_repeat('#', $iterationHeaders + 1);
                $md .= $header . " " . ucfirst($key) . PHP_EOL;
                $iterationHeaders++;
            }
            if ($value) {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        $subValue = is_array($subValue)
                            ? static::fromArray($subValue, $iterationHeaders)
                            : $subValue;
                        if (is_int($subKey)) {
                            $md .= "- $subValue" . PHP_EOL;
                        } else {
                            $md .= "- $subKey: $subValue" . PHP_EOL;
                        }
                    }
                } else {
                    $md .= $value . PHP_EOL;
                }
            }
        }
        return trim($md);
    }
}
