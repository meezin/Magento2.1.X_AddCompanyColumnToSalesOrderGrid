<?php namespace Kbethos\CompanyColumn\Plugins;

use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;

class AddColumnsSalesOrderGridCollection
{
    private $messageManager;
    private $collection;

    public function __construct(MessageManager $messageManager,
        SalesOrderGridCollection $collection
    ) {

        $this->messageManager = $messageManager;
        $this->collection = $collection;
    }

    public function aroundGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        \Closure $proceed,
        $requestName
    ) {
        $result = $proceed($requestName);

        if ($requestName === "sales_order_grid_data_source") {
            $select = $result->getSelect();
            
            $select->join(
                ["soa" => "sales_order_address"],
                'main_table.entity_id = soa.parent_id AND soa.address_type="shipping"',
                array('company')
            )
                ->distinct();
        }

        return $result;
    }
}