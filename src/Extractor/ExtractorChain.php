<?php

namespace Abacaphiliac\Extractor;

use Abacaphiliac\Extractor\Exception\UnexpectedValueException;
use Zend\Stdlib\ArrayUtils;

class ExtractorChain implements ExtractionInterface
{
    /** @var ExtractionInterface[] */
    private $extractors = [];
    
    /** @var bool */
    private $mergeRecursively;

    /**
     * PendoOptionsProviderChain constructor.
     * @param ExtractionInterface[] $extractors
     * @param bool $mergeRecursively
     */
    public function __construct(array $extractors = [], $mergeRecursively = true)
    {
        array_walk($extractors, [$this, 'addExtractor']);
        $this->mergeRecursively = $mergeRecursively;
    }

    /**
     * @param ExtractionInterface $extractor
     */
    private function addExtractor(ExtractionInterface $extractor)
    {
        $this->extractors[] = $extractor;
    }

    /**
     * @return mixed[]
     * @throws \Abacaphiliac\Extractor\Exception\UnexpectedValueException
     */
    public function extract()
    {
        $dataSets = array_map(function (ExtractionInterface $extractor) {
            $data = $extractor->extract();
            
            if ($data instanceof \Traversable) {
                $data = iterator_to_array($data);
            }
            
            if (!is_array($data)) {
                throw new UnexpectedValueException(sprintf(
                    'Extractor type `%s` returned a `%s` instead of an `array`',
                    get_class($extractor),
                    is_object($data) ? get_class($data) : gettype($data)
                ));
            }

            return $data;
        }, $this->extractors);
        
        if (!$dataSets) {
            return [];
        }
        
        if (!$this->mergeRecursively) {
            return array_merge(...$dataSets);
        }
        
        $result = [];
        foreach ($dataSets as $data) {
            // Zend's function will overwrite keys with new values upon collision, as opposed to PHP's native
            // array_merge_recursive function which will generate a new array of merged values for that key.
            $result = ArrayUtils::merge($result, $data);
        }
        
        return $result;
    }
}
