<?php declare(strict_types=1);

namespace Shopware\Api\Customer\Event\CustomerGroupDiscount;

use Shopware\Api\Customer\Struct\CustomerGroupDiscountSearchResult;
use Shopware\Context\Struct\ApplicationContext;
use Shopware\Framework\Event\NestedEvent;

class CustomerGroupDiscountSearchResultLoadedEvent extends NestedEvent
{
    public const NAME = 'customer_group_discount.search.result.loaded';

    /**
     * @var CustomerGroupDiscountSearchResult
     */
    protected $result;

    public function __construct(CustomerGroupDiscountSearchResult $result)
    {
        $this->result = $result;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): ApplicationContext
    {
        return $this->result->getContext();
    }
}