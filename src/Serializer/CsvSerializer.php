<?php

namespace Zus1\Serializer\Serializer;

use Zus1\Serializer\Interface\SerializerInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Zus1\Serializer\Trait\InteractWithSerializerEvent;

class CsvSerializer implements SerializerInterface
{
    use InteractWithSerializerEvent;
 
    private string $className;
    
    public function serialize(array|LengthAwarePaginator $normalized): string
    {
        $data = $this->getData($normalized);

        $titleLine = '';
        $contentLine = '';

        $this->convertToLines($data, $contentLine, $titleLine);

        $titleLine = $this->sanitizeTitleLine($titleLine);

        return $this->withEventDispatch($titleLine.PHP_EOL.$contentLine, $this->className);
    }
    
    public function setSubjectClassName(string $className): void
    {
        $this->className = $className;
    }

    private function getData(array|LengthAwarePaginator $normalized): array
    {
        if($normalized instanceof LengthAwarePaginator) {
            return $normalized->all();
        }

        return $normalized;
    }

    private function convertToLines(array $data, &$contentLine, &$titleLine, ?string $titlePrefix = '', bool $lineBrake = true): void
    {
        foreach ($data as $key => $value) {
            if(!is_array($value)) {
                if($this->isFirstRecursion($titleLine) === true) {
                    if($titlePrefix === '') {
                        $titleLine .= $key.',';
                    } else {
                        $titleLine .= (is_numeric($titlePrefix) === false ? $titlePrefix.'.'.$key : $key).',';
                    }
                }

                $contentLine.= $value.',';

                continue;
            }

            if(!$this->isSubArrays($value)) {
                $this->convertToLines(
                    data: $value,
                    contentLine:$contentLine,
                    titleLine:$titleLine,
                    titlePrefix: $titlePrefix !== '' ? sprintf('%s.%s', $titlePrefix, $key): $key,
                    lineBrake: is_int($key)
                );

                continue;
            }

            foreach ($value as $nestedKey => $nestedSubArray) {
                $this->convertToLines(
                    data: $nestedSubArray,
                    contentLine: $contentLine,
                    titleLine: $titleLine,
                    titlePrefix: $titlePrefix !== '' ? sprintf('%s.%s.%s', $titlePrefix, $key, $nestedKey) :
                        sprintf('%s.%s', $key, $nestedKey),
                    lineBrake: is_int($key)
                );
            }
        }

        if($lineBrake === true) {
            $contentLine = substr($contentLine, 0, -1);
            $contentLine .= PHP_EOL;
        }

    }

    private function isFirstRecursion(string $titleLine): bool
    {
        $lineArr = explode(',', $titleLine);

        return array_unique($lineArr) === $lineArr;
    }

    private function sanitizeTitleLine(string $titleLine): string
    {
        $lineArr = explode(',', $titleLine);

        if(array_unique($lineArr) === $lineArr) {
            return substr($titleLine, 0, -1);
        }

        return substr(implode(',', array_unique($lineArr)), 0, -1);
    }

    private function isSubArrays(array $data): bool
    {
        return array_filter($data, 'is_array') === $data;
    }
}
