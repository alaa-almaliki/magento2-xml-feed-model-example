<?php

return [
    'order' => 'mapper/mapped_fields/order', // order is the parent node of the xml document
    'order.customer' => 'mapper/mapped_fields/customer', // order/customer is the xml path to the customer node in the document
    'order.customer.customer_address' => 'mapper/mapped_fields/customer_address', // order/customer/customer_address
    'order.items' => '', // order/items contains children item nodes
    'order.items.item' => 'mapper/mapped_fields/item', // order/items/item contains item nodes
];