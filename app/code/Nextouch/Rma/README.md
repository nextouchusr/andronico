## Nextouch Rma Module

Il modulo si occupa di aggiungere delle logiche applicative per la
gestione dei resi cliente

### Source code

#### Definizione modelli di dati
Sono stati estesi i models: `Rma` per l'aggiunta
di alcune logiche di business per la gestione dei resi cliente

#### Richiesta rimborso Findomestic
Nell'area di backoffice, una volta indicati i prodotti approvati per il reso,
è stato reso visibile un pulsante per effettuare una richiesta di rimborso a Findomestic.
Verrà effettuata a Findomestic una chiamata API pari all'importo
dei prodotti approvati per il rimborso.

#### Blocco resi per ordini consegnati più di 14 giorni fa
Per consentire il reso dei soli prodotti consegnati più di 14 giorni fa
è stato realizzato un plugin `CanRequestRma` che tramite parametro di configurazione
verifica la data di completamento dell'ordine, se è maggiore di 14 giorni
non consente di aprire la schermata di richiesta reso nell'area cliente

#### Invio richiesta di reso a Wins
Quando viene creata una richiesta di reso dall'area cliente, viene dispatchato
l'evento `return_submit_success`, il quale, tramite l'observer `create_new_wins_return`,
invia un richiesta di reso cliente ad AT3

#### Plugins aggiunti

**CanRequestRma**

Blocca la possibilità di effettuare un reso per gli ordini completati (consegnati)
più di 14 giorni fa

**ChangeReturnSubmitUrl**

Modifica l'url del controller da richiamare quando si effettua una richiesta
di reso come cliente guest

**AddRmaExtensionAttributes**

Aggiunge al reso i seguenti extension attributes:

* `reason_text`
* `condition_text`
* `resolution_text`
