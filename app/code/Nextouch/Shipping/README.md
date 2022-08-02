## Nextouch Shipping Module

Il modulo si occupa di dispatchare un evento custom quando viene
spedito un ordine

### Source code

#### Disptach evento "sales_order_ship_*"
Per consentire che il finanziamento Findomestic venga attivato
solo al momento della spedizione, è stato sovrascritto il controller
`Magento\Shipping\Controller\Adminhtml\Order\Shipment\Save`
