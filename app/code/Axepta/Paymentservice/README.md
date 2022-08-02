## Axepta PaymentService Module

Il modulo è di proprietà di Axepta, il servizio di
pagamento utilizzato per i pagamenti tramite carta di credito.
E' aggiornato all'ultima versione disponibile per Magento 2.3.x

### Source code

#### Modifica stato dell'ordine
Per venire incontro alle esigenze di workflow è stato necessario
modificare il controller Axepta\Paymentservice\Controller\Payment
per consentire che lo stato dell'ordine, pagato tramite carta di credito,
venga impostato su `paid[processing]`
