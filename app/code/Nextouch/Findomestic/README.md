## Nextouch Findomestic Module

Il modulo è stato sviluppato per gestire il pagamento "Findomestic"

### Source code

#### Creazione richiesta di finanziamento
E' stato creato il servizio `InstallmentManagementInterface` per la
creazione di una richiesta di finanziamento. Il servizio genera
un `applyUrl` che viene utilizzato per reindirizzare il cliente
alla pagina di finanziamento Findomestic
Vedere servizio `InstallmentManagementInterface` metodo `create`

#### Pre-approvazione richiesta di finanziamento
Una volta che il cliente ha compilato il form per la richiesta di finanziamento,
viene richiamata da Findomestic una nostra API di notifica, la quale comunica
che il cliente ha terminato la procedura di compilazione form.
Solo alla ricezione di questa notifica viene creato su Magento l'ordine.
Vedere servizio `NotificationProcessorInterface` metodo `notify`

#### Approvazione richiesta di finanziamento

Una volta che Findomestic approva la richiesta di finanziamento
viene inviata una notifica, la quale consente il pagamento e la creazione
della fattura su Magento.
Vedere servizio `NotificationProcessorInterface` metodo `notify`

#### Negazione richiesta di finanziamento
Qualora Findomestic neghi la richiesta di finanziamento viene inviata una notifica,
la quale porta alla cancellazione dell'ordine su Magento,
impostando lo stato dell'ordine su `canceled[canceled]`.
Vedere servizio `NotificationProcessorInterface` metodo `notify`

#### Attivazione richiesta di finanziamento
Quando l'ordine viene spedito dai magazzini Esprinet viene richiamata
l'API di Findomestic per l'attivazione della richiesta di finanziamento, così
da far partire il piano rateale solo al momento della spedizione dell'ordine.
Vedere servizio `InstallmentManagementInterface` metodo `activate`

#### Invio email quando un reso ordine viene approvato / rifiutato
Quando un reso ordine Findomestic viene approvato / rifiutato, viene
inviata all'operatore un email che lo notifica di quanto è accaduto.
Vedere servizio `NotificationProcessorInterface` metodo `notify`

#### Lista observers creati
Per gestire la comunicazione tra Magento e Findomestic sono stati realizzati
degli observer che si occupano di chiamare le API del gateway di pagamento
per l'invio / conferma dei dati d'ordine

**ActivateOrderInstallment**

Attiva il finanziamento per l'ordine quando quest'ultimo viene spedito

**SetFindomesticAdditionalInfo**

Esegue il salvataggio di `findomestic_application_id` e `findomestic_issuer_installment_id`
recuperandoli dal carrello quando quest'ultimo viene trasformato in un ordine

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**ActivateOrderInstallment**

Attiva il finanziamento per l'ordine quando quest'ultimo viene spedito

**CancelOrderInstallment**

Viene cancellata la richiesta di finanziamento quando l'ordine viene annullato

**WrapInstallmentNotificationRequest**

Poiché su Magento il body di una request deve essere obbligatoriamente wrappato all'interno
di un unico oggetto, questo plugin consente di effettuare una modifica alla request
per soddisfare questa richiesta di Magento
