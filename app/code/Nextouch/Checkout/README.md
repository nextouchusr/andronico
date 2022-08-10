## Nextouch Checkout Module

Il modulo è stato sviluppato per estendere il comportamento
del checkout di Magento

#### Rimozione servizio dal carrello

E' stata aggiunta la possibilità di rimuovere un servizio
aggiunto al prodotto direttamente dalla pagina del carrello, tramite
il controller `RemoveQuoteItemService`

#### Pulizia carrello al click del pulsante "Prenota a ritira"

Al click del pulsante "Prenota e ritira", il carrello viene
pulito e aggiunto il prodotto selezionato. Se l'utente è loggato,
verrà indirizzato alla pagina di checkout per la scelta del negozio di ritiro.
In caso contrario verrà indirizzato alla pagina di checkout per l'inserimento
delle informazioni personali. La funzionalità viene gestita tramite il controller `Clear`

#### Selezione pagamento di default

Al click del pulsante "Fast checkout" il cliente viene
indirizzato alla pagina di checkout con il metodo di pagamento
"PayPal" già selezionato. Nel caso di click sul pulsante "Prenota e ritira",
verrà selezionato "Pagamento in negozio" come metodo di pagamento.
La funzionalità viene gestita impostando un cookie nel browser e forzando il metodo di default
tramite il servizio `DefaultPaymentMethodConfigProvider`

#### Plugins aggiunti

Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**RenderDeliveryByAppointmentFields**

Consente di visualizzare la sezione "Seleziona data di consegna"
solo nel caso in cui è stato selezionato il servizio "Consegna su appuntamento"

**CalculateShippingMethodPrice**

Calcola il prezzo della spedizione in base all'algoritmo
definito dal team Nextouch

**FilterPaymentMethodsBasedOnCart**

Filtra i metodi di pagamento visualizzati in base al metodo di spedizione selezionato dal cliente.
Nel caso di spedizione tramite corriere, i pagamenti abilitati saranno:

* Bonifico bancario
* PayPal Express
* Carta di credito
* Findomestic

Nel caso di ritiro in negozio, i pagamenti abilitati saranno:

* Pagamento in negozio (Pick&Pay)
* PayPal Express (Ritiro in negozio con pagamento anticipato)
* Carta di credito (Ritiro in negozio con pagamento anticipato)
