<?php

namespace Alaa\OrderFeedExample\Model\Setup;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Serialize\Serializer\Json;

class SerializedFieldArray
{
    /**
     * @var Json
     */
    private $json;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * SerializedFieldArray constructor.
     * @param Json $json
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     */
    public function __construct(Json $json, ScopeConfigInterface $scopeConfig, WriterInterface $configWriter)
    {
        $this->json = $json;
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    /**
     * @param $path
     * @param array $value
     * @param string $scope
     * @param int $scopeId
     */
    public function addConfig($path, array $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
    {
        $this->configWriter->save($path, $this->json->serialize($value), $scope, $scopeId);
    }
}