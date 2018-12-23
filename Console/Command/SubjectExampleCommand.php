<?php
/**
 * @copyright 2018 Alaa Al-Maliki <alaa.almaliki@gmail.com>
 * @license   MIT
 */

declare(strict_types=1);

namespace Alaa\XmlFeedModelExample\Console\Command;

use Alaa\XmlFeedModel\Model\MappedSubjectBuilder;
use Alaa\XmlFeedModel\Model\Subject;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Api\OrderRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubjectExampleCommand extends Command
{
    const ARGUMENT_ORDER_ID = 'order-id';

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var MappedSubjectBuilder
     */
    protected $mappedSubjectBuilder;
    /**
     * @var DirectoryList
     */
    protected $directoryList;
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleDirReader;
    /**
     * @var \Alaa\XmlFeedModel\Model\XmlConverter
     */
    protected $xmlConverter;

    /**
     * SubjectExampleCommand constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param MappedSubjectBuilder $mappedSubjectBuilder
     * @param DirectoryList $directoryList
     * @param \Magento\Framework\Module\Dir\Reader $moduleDirReader
     * @param \Magento\Framework\App\State $state
     * @param \Alaa\XmlFeedModel\Model\XmlConverter $xmlConverter
     * @param string|null $name
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        MappedSubjectBuilder $mappedSubjectBuilder,
        DirectoryList $directoryList,
        \Magento\Framework\Module\Dir\Reader $moduleDirReader,
        \Magento\Framework\App\State $state,
        \Alaa\XmlFeedModel\Model\XmlConverter $xmlConverter,
        $name = null
    ) {
        parent::__construct($name);
        $state->setAreaCode('frontend');
        $this->orderRepository = $orderRepository;
        $this->mappedSubjectBuilder = $mappedSubjectBuilder;
        $this->directoryList = $directoryList;
        $this->moduleDirReader = $moduleDirReader;
        $this->xmlConverter = $xmlConverter;
    }

    protected function configure()
    {
        $this->setName('order:export:generate')
            ->setDescription('Generate Order Export')
            ->addOption(self::ARGUMENT_ORDER_ID, null, 2, '', 0);
        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderId = $input->getOption(self::ARGUMENT_ORDER_ID);
        try {
            $order = $this->orderRepository->get($orderId);
            $orderSubject = new Subject('order');
            $orderSubject->setData($order->getData());
            $orderSubject->addAttribute($orderSubject->getNodeName(), 'account', '111');

            $customerSubject = new Subject('customer');
            $customerSubject->setData($order->getData());
            $customerSubject->addAttribute($customerSubject->getNodeName(), 'id', (string) $order->getCustomerId());

            $customerAddress = new Subject('customer_address');
            $customerAddress->setData($order->getBillingAddress()->getData());

            $itemsSubject = new Subject('items');
            foreach ($order->getItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                $itemsSubject->addChild(new Subject('item', $item->getData()));
            }


            $orderSubject->addChild($customerSubject);
            $customerSubject->addChild($customerAddress);
            $orderSubject->addChild($itemsSubject);

            $file = $this->moduleDirReader->getModuleDir('etc', 'Alaa_XmlFeedModelExample'). '/order_mapped_fields.php';
            $mappedSubject = $this->mappedSubjectBuilder->build($file, $orderSubject);
            $xml = $this->xmlConverter->convert($mappedSubject);
            $xml->asXML('var/subject.xml');
        } catch (\Exception $e) {
            $output->writeln('Can not export order.');
            $output->writeln($e->getMessage());
        }
    }
}