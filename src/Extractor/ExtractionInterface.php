<?php

namespace Abacaphiliac\Extractor;

interface ExtractionInterface
{
    /**
     * @return \Traversable|mixed[]
     */
    public function extract();
}
