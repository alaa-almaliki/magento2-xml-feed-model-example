<?php

namespace Alaa\OrderFeedExample\Model;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Api\Data\OrderInterface;

class OrderDataProvider
{
    /**
     * @var DirectoryList
     */
    private $directoryList;

    public function __construct(DirectoryList $directoryList)
    {
        $this->directoryList = $directoryList;
    }

    /**
     * @var OrderInterface
     */
    protected $order;

    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getSourceData()
    {
        $data['order'] =  $this->order->getData();
        $items = [];
        foreach ($this->order->getItems() as $item) {
            if ($item->getParentItemId()) {
                $item = $item->getParentItem();
            }
            if (in_array($item->getItemId(), $items)) {
                continue;
            }
            $items[$item->getItemId()] = $item->getItemId();

            $data['items'][] = $item->getData();
        }

        $data['payment'] = $this->order->getPayment()->getData();
        return $data;
    }

    public function getOutputFile()
    {
        return $this->directoryList->getPath('var')  . '/order/import/' . $this->order->getIncrementId() . '.xml';
    }
}