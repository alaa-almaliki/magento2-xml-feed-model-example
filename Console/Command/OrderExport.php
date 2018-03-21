<?php

namespace Alaa\OrderFeedExample\Console\Command;

use Alaa\OrderFeedExample\Model\Feed;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Sales\Api\OrderRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

class OrderExport extends Command
{
    const ARGUMENT_ORDER_ID = 'order_id';

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var Feed
     */
    private $feed;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        State $state,
        Feed $feed,
        DirectoryList $directoryList,
        $name = null
    ) {
        parent::__construct($name);
        $state->setAreaCode('frontend');
        $this->orderRepository = $orderRepository;
        $this->feed = $feed;
        $this->directoryList = $directoryList;
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
            Assert::greaterThan($orderId, 0);
            $order = $this->orderRepository->get($orderId);
            $this->feed->getDataProvider()->setOrder($order);
            $this->feed->generate();
            $output->writeln('Order Export done');
        } catch (\Exception $e) {
            $output->writeln('Can not export order.');
            $output->writeln($e->getMessage());
        }
    }

}