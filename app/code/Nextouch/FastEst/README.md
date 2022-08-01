## Nextouch FastEst Module

Il modulo è stato sviluppato per permettere l'aggiunta
e l'integrazione del servizio di spedizione "FastEst" con la
piattaforma "https://transitpoint.us/"

### Source code

#### Recupero slot di appuntamento disponibili per la consegna
Sono stati creati i modelli e i servizi necessari per il recupero delle date
di consegna disponibili da visualizzare nella pagina di checkout, qualora l'utente
abbia selezionato il servizio "Consegna su appuntamento".
Queste informazioni vengono recuperate mediante dal Front-end mediante
l'endpoint `/V1/carriers/fast_est/appointments/available-slots`

#### Creazione/conferma spedizione ordine
Sono stati creati i modelli e i servizi necessari per la creazione di una spedizione
FastEst. Come da workflow, nel caso di ordine pagato tramite Bonifico / Findomestic,
la spedizione viene creata in stato "Parcheggiata" e confermata successivamente alla
conferma del pagamento da parte di AT3 (nel caso di bonifico) e
da parte di Findomestic (nel caso di finanziamento).
La creazione della spedizione a FastEst avviene al dispatch dell'evento
`checkout_onepage_controller_success_action`. L'observer `create_new_delivery`
si occupa di inviare a FastEst una richiesta di spedizione completa di tutte
le informazioni relative all'ordine e dei servizi selezionati per ciascun prodotto.

#### Invio link di tracking ad AT3
Immediatamente alla creazione della spedizione FastEst (anche in caso di stato "Parcheggiata")
viene inviato ad AT3 il link di tracking della spedizione.

#### Operazioni schedulate
Per garantire una maggiore sicurezza di comunicazione tra Magento e FastEst
sono stati realizzati due job per il rinvio della creazione spedizione e la conferma
di una spedizione "Parcheggiata"

**RetryOrderDelivery**

Recupera tutti gli ordini che non sono in stato "Parcheggiata" e per cui è avvenuto
un errore di comunicazione tra Magento e FastEst e ritenta l'invio della creazione
della spedizione

**RetryConfirmParkedDelivery**

Recupera tutti gli ordini che sono in stato "Parcheggiata" e per cui è avvenuto
un errore di comunicazione tra Magento e FastEst e ritenta la conferma
della spedizione

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**ConfirmParkedDelivery**

Alla creazione della fattura su Magento,
conferma la spedizione precedentemente in stato "Parcheggiata"
N.B: Non saranno presenti più di una fattura per ordine.
Ad ogni ordine corrisponderà una sola fattura e una sola spedizione.
