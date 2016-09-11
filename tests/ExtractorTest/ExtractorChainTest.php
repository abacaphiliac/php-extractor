<?php

namespace AbacaphiliacTest\Extractor;

use Abacaphiliac\Extractor\ExtractionInterface;
use Abacaphiliac\Extractor\ExtractorChain;

/**
 * @covers \Abacaphiliac\Extractor\ExtractorChain
 */
class ExtractorChainTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractFromEmptyChain()
    {
        $sut = new ExtractorChain();
        
        $actual = $sut->extract();
        
        self::assertCount(0, $actual);
    }
    
    public function testDefaultExtraction()
    {
        $sut = new ExtractorChain([
            $firstExtractor = $this->getMockBuilder(ExtractionInterface::class)->getMock(),
            $secondExtractor = $this->getMockBuilder(ExtractionInterface::class)->getMock(),
        ]);
        
        $firstExtractor->expects(self::any())->method('extract')->will(self::returnValue([
            'Foo' => 'Bar',
            'Bar' => 'Foo',
        ]));
        
        $secondExtractor->expects(self::any())->method('extract')->will(self::returnValue([
            'Foo' => 'FooBar',
            'Fizz' => 'Buzz',
        ]));
        
        $actual = $sut->extract();
        
        self::assertArraySubset(
            [
                'Foo' => 'FooBar',
                'Bar' => 'Foo',
                'Fizz' => 'Buzz',
            ],
            $actual
        );
    }
    
    public function testExtractNonRecursively()
    {
        $sut = new ExtractorChain([
            $firstExtractor = $this->getMockBuilder(ExtractionInterface::class)->getMock(),
            $secondExtractor = $this->getMockBuilder(ExtractionInterface::class)->getMock(),
        ], false);

        $firstExtractor->expects(self::any())->method('extract')->will(self::returnValue([
            'Foo' => [
                'Fizz' => 'Buzz',
            ],
        ]));

        $secondExtractor->expects(self::any())->method('extract')->will(self::returnValue([
            'Foo' => 'Bar',
        ]));

        $actual = $sut->extract();

        self::assertArraySubset(
            [
                'Foo' => 'Bar',
            ],
            $actual
        );
    }
    
    public function testExtractTraversable()
    {
        $sut = new ExtractorChain([
            $firstExtractor = $this->getMockBuilder(ExtractionInterface::class)->getMock(),
            $secondExtractor = $this->getMockBuilder(ExtractionInterface::class)->getMock(),
        ]);

        $firstExtractor->expects(self::any())->method('extract')->will(self::returnValue(new \ArrayIterator([
            'Foo' => [
                'Fizz' => 'Buzz',
            ],
        ])));

        $secondExtractor->expects(self::any())->method('extract')->will(self::returnValue(new \ArrayIterator([
            'Foo' => [
                'Fizz' => 'Bar',
            ],
        ])));

        $actual = $sut->extract();

        self::assertArraySubset(
            [
                'Foo' => [
                    'Fizz' => 'Bar',
                ],
            ],
            $actual
        );
    }

    /**
     * @expectedException \Abacaphiliac\Extractor\Exception\UnexpectedValueException
     */
    public function testInvalidExtraction()
    {
        $sut = new ExtractorChain([
            $extractor = $this->getMockBuilder(ExtractionInterface::class)->getMock(),
        ]);

        $extractor->expects(self::any())->method('extract')->will(self::returnValue(new \stdClass()));

        $sut->extract();
    }
}
