<?php

namespace Alaa\OrderFeedExample\Model;

use Alaa\XmlFeedModel\Model\XmlConverterInterface;

class Feed
{
    /**
     * @var SubjectContext
     */
    private $context;

    /**
     * @var XmlConverterInterface
     */
    private $converter;

    /**
     * @var OrderDataProvider
     */
    private $orderDataProvider;

    public function __construct(OrderDataProvider $orderDataProvider, SubjectContext $context, XmlConverterInterface $converter)
    {

        $this->context = $context;
        $this->converter = $converter;
        $this->orderDataProvider = $orderDataProvider;
    }

    public function getDataProvider()
    {
        return $this->orderDataProvider;
    }


    public function generate()
    {
        $sourceData = $this->orderDataProvider->getSourceData();

        $subject = $this->context->getRoot();
        $order = $this->context->getOrder()->setData($sourceData['order']);
        $order->addCallback('currency', function (array $data) {
            return $data['base_currency_code'];
        });
        $customer = $this->context->getCustomer()->setData($sourceData['order']);
        $customer->addCallback('name', function (array $data) {
            return strtoupper($data['customer_firstname'] . ' ' . $data['customer_lastname']);
        });
        $payment = $this->context->getPayment()->setData($sourceData['payment']);
        $items = [];
        foreach ($sourceData['items'] as $orderItem) {
            $items[] = $this->context
                ->getOrderLine()
                ->setData($orderItem);
        }

        $order->addSubjects($items, 'lines');
        $order->addSubject($customer);
        $order->addSubject($payment);
        $subject->addSubject($order);

        $this->converter->toXml($subject)
            ->withProlog()
            ->addNamespace('xsi', 'https://www.namespace.com')
            ->addNamespace('xmlns', 'https://www.mynamespace.com')
            ->save("var/order/import/{$sourceData['order']['entity_id']}.xml");
    }
}