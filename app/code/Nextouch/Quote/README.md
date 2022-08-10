## Nextouch Quote Module

Il modulo si occupa di aggiungere delle logiche applicative per la
gestione del carrello cliente

### Source code

#### Definizione modelli di dati
Sono stati estesi i models: `Address`, `Cart` e `CartItem` per l'aggiunta
di alcune logiche di business per la gestione del carrello cliente

**AddressInterface**
Contiene gli stessi campi aggiunti per l'indirizzo cliente
(vedi il README del Customer module)

**CartInterface**
Contiene i campi per leggere/scrivere le informazioni di "Application ID"
e "Issuer Installment ID" relative a Findomestic e i metodi per verificare
quale corriere visualizzare per il carrello (FastEst, Dhl o Gls)

**CartItemInterface**
Contiene il metodo per verificare se è stato selezionato il servizio
"Consegna su appuntamento" in modo tale da visualizzare/nascondere la sezione
"Seleziona data consegna"

#### Carrello riservato
E' stata aggiunta una classe servizio `CartReservationRepositoryInterface`
per recuperare un carrello in base al `reserved_order_id`
