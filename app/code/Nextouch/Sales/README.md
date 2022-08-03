## Nextouch Sales Module

Il modulo si occupa di aggiungere delle logiche applicative per la
gestione degli ordini cliente

### Source code

#### Definizione modelli di dati
Sono stati estesi i models: `Order`, `OrderItem`, `OrderAddress`,
`Shipment` e `ShipmentItem` per l'aggiunta di alcune logiche di business per la gestione dei resi cliente

**Order**

Sono stati aggiunti nuovi campi all'ordine per gestire il flusso definito
con la proprietà Nextouch:

* `completed_at`: indica la data di consegna dell'ordine, utile impedire la richiesta di reso
per gli ordini creati più di 14 giorni fa
* `delivery_information`: data/ora scelta dal cliente per la consegna dell'ordine
* `order_sync_failures`: indica il numero di volte che è fallita la sincronizzazione dell'ordine cliente con il sistema ERP
* `shipping_sync_failures`: indica il numero di volte che è fallita la sincronizzazione della spedizione cliente con la piattaforma del corriere
* `is_parked`: indica se la spedizione per l'ordine è in stato "Parcheggiata"
* `invoice_pdf_file`: file PDF contenente la fattura del cliente
* `findomestic_application_id`: identificativo Findomestic per la richiesta di finanziamento
* `findomestic_issuer_installment_id`: identificativo Findomestic per il finanziamento accordato
* `findomestic_application_status`: stato della pratica Findomestic

**OrderItem**

Contiene i metodi per recuperare i servizi presenti nell'ordine e selezionati dal cliente
in fase di acquisto

**OrderAddress**

Contiene i campi aggiuntivi aggiunti per il model `Customer` (vedi README.md del modulo `Nextouch_Customer`)

**Shipment**

Contiene i metodi per recuperare il model `Order` customizzato

**ShipmentItem**

Contiene i metodi per recuperare i models `OrderItem` e `Shipment` customizzati

#### Aggiunti nuovi stati ordine
Per gestire correttamente il flusso definito con il cliente Nextouch,
sono stati creati degli stati ordine aggiuntivi:

* `paid`: viene assegnato quando un ordine viene pagato
* `shipped`: viene assegnato quando un ordine viene spedito
* `accepted`: viene assegnato quando un ordine di "Pick&Pay" viene accettato dallo store manager
* `in_delivery`: viene assegnato quando un ordine è "In Consegna"
* `complete`: viene assegnato quando un ordine è "Consegnato"

#### API per modifiche stato ordine
Sono state realizzate delle nuove API  per consentire ai corrieri (FastEst)
di effettuare un aggiornamento degli stati ordine

**Set InDelivery Status By Increment ID**

```http
  POST /V1/orders/:incrementId/inDeliveryByIncrementId
```

| Class | Method | Description                                  |
| :-------- | :-------- |:---------------------------------------------|
| `OrderManagementInterface` | `inDeliveryByIncrementId` | Imposta a "in_delivery" lo stato dell'ordine |

**Set Complete Status By Increment ID**

```http
  POST /V1/orders/:incrementId/deliverByIncrementId
```

| Class | Method | Description                               |
| :-------- | :-------- |:------------------------------------------|
| `OrderManagementInterface` | `deliverByIncrementId` | Imposta a "complete" lo stato dell'ordine |

#### Cambio stato ordine in "Paid" al pagamento dell'ordine

Quando l'ordine viene pagato l'observer `change_order_status_to_paid` modifica
lo stato direttamente in `paid[processing]`

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**DispatchCompleteOrderEvent**

Quando l'ordine è in stato `shipped[complete]`, e viene cambiato in `complete[complete]`,
viene dispatchato l'evento `sales_order_status_change_to_complete`,
per consentire l'aggiornamento dello stato dell'ordine anche su AT3

**SaveOrderCompletionDate**

Imposta la data di completamento (consegna) dell'ordine in modo tale
da non consentire il reso nel caso di ordine completato più di 14 giorni fa
