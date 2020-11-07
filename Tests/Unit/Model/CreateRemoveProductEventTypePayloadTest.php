<?php

declare(strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\Tests\Unit\Model;

use Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface;
use PHPUnit\Framework\TestCase;
use Assert\InvalidArgumentException;
use Akeneo\Pim\Enrichment\Component\Category\Model\Category;
use Akeneo\Pim\Structure\Component\Model\Attribute;
use Akeneo\Pim\Structure\Component\Model\Family;
use Akeneo\Pim\Enrichment\Component\Product\Model\ProductModel;
use Akeneo\Pim\Enrichment\Component\Product\Model\Product;
use Frmoda\Bundle\EventsApiBundle\Model\CreateRemoveProductEventTypePayload;
use Frmoda\Bundle\EventsApiBundle\Model\GenericRemoveEntityEventInterface;

class CreateRemoveProductEventTypePayloadTest extends TestCase
{
    /**
     * @test
     */
    public function createsPayload(): void
    {
        $removeProductEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): ProductInterface
            {
                return (new Product())->setIdentifier('test-123');
            }
        };

        $expectedPayload = ['identifier' => 'test-123'];

        $actualPayload = (new CreateRemoveProductEventTypePayload())->__invoke($removeProductEvent);
        self::assertSame($expectedPayload, $actualPayload);
    }

    /**
     * @test
     * @dataProvider notSupportedEventsDataProvider
     *
     * @param $event
     */
    public function throwsInvalidArgumentExceptionIfSubjectDoesNotContainsIdentifierProperty($event): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new CreateRemoveProductEventTypePayload())->__invoke($event);
    }

    /**
     * @return array
     */
    public function notSupportedEventsDataProvider(): array
    {
        $removeCategoryEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Category
            {
                return new Category();
            }
        };

        $removeAttributeEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Attribute
            {
                return new Attribute();
            }
        };

        $removeFamilyEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): Family
            {
                return new Family();
            }
        };

        $removeProductModelEvent = new class implements GenericRemoveEntityEventInterface {
            public function getSubject(): ProductModel
            {
                return new ProductModel();
            }
        };

        return [
            [$removeCategoryEvent],
            [$removeAttributeEvent],
            [$removeFamilyEvent],
            [$removeProductModelEvent]
        ];
    }
}
