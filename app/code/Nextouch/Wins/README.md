## Nextouch Wins Module

Il modulo si occupa della comunicazione dei dati
dal sistema Magento ad AT3 e viceversa

### Source code

#### Invio anagrafiche cliente
Quando viene creata/modificata un'anagrafica cliente, viene chiamata
l'API di Wins per l'aggiornamento dei dati. Il servizio che si occupa
di questo è `CustomerRepositoryInterface`. Nel caso in cui la trasmissione
dovesse fallire, questa viene ritentata per un massimo di dieci volte tramite un'operazione schedulata

#### Aggiornamento stato ordine
Quando viene effettuato un aggiornamento dello stato dell'ordine, questo
viene comunicato ad AT3 tramite il servizio `OrderManagementInterface`.
Il suddetto servizio viene utilizzato anche per inviare ad AT3 le informazioni
di "ID Transazione Pagamento" e "Tracking Link"

#### Creazione ordine su AT3
Alla creazione di un ordine su Magento viene chiamata, tramite il servizio `OrderRepositoryInterface`,
l'API di Wins per la trasmissione dell'ordine. Nel caso in cui la trasmissione
dovesse fallire, questa viene ritentata per un massimo di dieci volte tramite un'operazione schedulata

#### Verifica giacenza realtime
In fase di checkout, prima della creazione dell'ordine, viene verificata
la giacenza in magazzino di ciascun prodotto aggiunto al carrello.
Nel caso in cui la giacenza non è disponibile viene notificato il cliente
con un apposito popup. La giacenza viene verificata tramite il servizio `ProductSalableQtyManagementInterface`

#### Creazione reso su AT3
Alla creazione di un reso su Magento viene chiamata, tramite il servizio `RmaRepositoryInterface`,
l'API di Wins per la trasmissione del reso. Nel caso in cui la trasmissione
dovesse fallire, questa viene ritentata per un massimo di dieci volte tramite un'operazione schedulata

#### Lista observers creati
Per gestire la comunicazione tra Magento e AT3 sono stati realizzati
degli observer che si occupano di chiamare le API di Wins per
l'aggiornamento dei dati riguardanti: ordini, resi, clienti ecc.

**CreateCustomer**

Alla registrazione di un nuovo cliente vengono inviati
i dati dell'anagrafica ad AT3

**CreateNewOrder**

Alla creazione di un ordine su Magento, all'atterraggio della pagina success del checkout,
viene inviata un richiesta all'API di Wins per la creazione dell'ordine

**CreateNewReturn**

Alla creazione di un reso su Magento, una volta che il cliente ha compilato il form di restituzione merce,
viene inviata una richiesta all'API di Wins per la creazione del reso

**UpdateCustomer**

Alla modifica dei dati cliente vengono inviati i dati
aggiornati dell'anagrafica ad AT3

**ConfirmParkedDelivery**

In caso di ordine il cui pagamento avviene successivamente (es. Bonifico, Findomestic),
conferma la spedizione FastEst precedentemente in stato "Parcheggiata"

**CreateNewDelivery**

Al termine del processo di checkout viene inviata una richiesta a FastEst
per la creazione di una spedizione per l'ordine appena completato.
Nel caso il pagamento dell'ordine avverà successivamente alla data di creazione,
lo stato della spedizione viene messo in "Parcheggiata"

**CreateNewOrder**

Nel momento in cui viene compilata la richiesta di finanziamento su Findomestic,
viene inviata una richiesta di creazione ordine su AT3

**ChangeOrderStatusToCanceled**

Nel momento in cui la pratica viene declinata dal sistema di Findomestic,
l'ordine viene spostato in stato "Canceled" su Magento

**ChangeOrderStatusToPaid**

Nel momento in cui la pratica viene confermata dal sistema di Findomestic,
l'ordine viene spostato in stato "Paid" su Magento

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**ValidateQuoteSalableQty**

Verifica la giacenza in realtime prima di reindirizzare il cliente
alla pagina di richiesta finanziamento per Findomestic

**FilterPickupLocations**

Fixa un errore che non consentiva di caricare all'apertura della modale
gli store visibili per effettuare il Pick&Pay

**ValidateQuoteSalableQty**

Verifica la giacenza in realtime prima che l'ordine venga creato su Magento

#### Servizi creati
Di seguito la lista dei servizi creati per la comunicazione tra
Magento ed AT3 e viceversa:

**CreateOrUpdateCustomer**

Consente di creare/modificare l'anagrafica cliente su AT3

**OrderActionInterface**

Interfaccia che, in base all'implementazione, consente di aggiornare
lo stato dell'ordine di Pick&Pay / Pickup@Store.
AT3 invia via FTP un file TXT contenente un codice che corrisponde
allo stato dell'ordine di AT3:

* `A`: l'ordine è stato accettato dallo store manager
* `R`: l'ordine è stato rigettato dallo store manager
* `S`: l'ordine è stato ritirato dal cliente
* `N`: l'ordine non è stato ritirato dal cliente

**CustomOptionsProcessor**

Per AT3 i servizi vanno specificati nella loro API come dei normali prodotti
e non come custom options. Questo servizio consente di raggiungere questo risultato.

**AttachInvoiceToOrder**

Allega la fattura ricevuta da AT3 all'ordine presente su Magento

**CreateNewOrder**

Inva l'ordine creato su Magento al sistema AT3

**UpdateInStoreOrder**

Si occupa di istanziare ed eseguire l'implementazione di `OrderActionInterface`
corrispondente al file .TXT inviato da AT3 per l'ordine di Pick&Pay / Pickup@Store
