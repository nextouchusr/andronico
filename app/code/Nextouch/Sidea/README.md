## Nextouch Sidea Module

Il modulo si occupa della comunicazione tra Magento e il sistema
Marketing Cloud di Sidea

### Source code

#### Invio carrelli abbandonati
Sono stati creati i modelli e i servizi necessari per l'invio della lista
dei carrelli abbandonati a Sidea. L'invio dei dati avviene ogni giorno a mezzanotte
e funziona tramite operazione schedulata. Il job `SendAbandonedCarts` recupera
la lista dei carrelli abbandonati di ieri e li invia al sistema di Marketing Cloud.

Il modulo è provvisto di una sezione di configurazione nell'area backoffice di Magento
"Stores -> Configuration -> Nextouch -> Sidea"
