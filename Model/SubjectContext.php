<?php

namespace Alaa\OrderFeedExample\Model;

use Alaa\XmlFeedModel\Model\SubjectFactory;

class SubjectContext
{
    /**
     * @var SubjectFactory
     */
    private $subjectFactory;

    public function __construct(SubjectFactory $subjectFactory)
    {
        $this->subjectFactory = $subjectFactory;
    }

    /**
     * @return \Alaa\XmlFeedModel\Model\Subject
     */
    public function getRoot()
    {
        return $this->subjectFactory->create(
            ['nodeName' => 'orders',]
        );
    }

    /**
     * @return \Alaa\XmlFeedModel\Model\Subject
     */
    public function getOrder()
    {
        return $this->subjectFactory->create(
            ['nodeName' => 'order', 'configPath' => 'order_export/settings/order/custom_attributes']
        );
    }

    /**
     * @return \Alaa\XmlFeedModel\Model\Subject
     */
    public function getCustomer()
    {
        return $this->subjectFactory->create(
            ['nodeName' => 'customer', 'configPath' => 'order_export/settings/customer/custom_attributes']
        );
    }

    /**
     * @return \Alaa\XmlFeedModel\Model\Subject
     */
    public function getPayment()
    {
        return $this->subjectFactory->create(
            ['nodeName' => 'payment', 'configPath' => 'order_export/settings/payment/custom_attributes']
        );
    }

    /**
     * @return \Alaa\XmlFeedModel\Model\Subject
     */
    public function getOrderLine()
    {
        return $this->subjectFactory->create(
            ['nodeName' => 'order_line', 'configPath' => 'order_export/settings/order_line/custom_attributes']
        );
    }
}