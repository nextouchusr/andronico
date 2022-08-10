## Nextouch InStorePayment Module

Il modulo è stato sviluppato per gestire il pagamento
"Ritiro in negozio" utilizzato per selezionare la modalità
di "Pick&Pay"

#### Aggiunto metodo di pagamento
E' stata seguita la documentazione di Magento, per aggiungere
il nuovo metodo di pagamento. Esso verrà visualizzato solo nel
caso in cui il cliente ha selezionato "In Store Pickup" come metodo di spedizione

#### Pulsante "Prenota e ritira" nella pagina di dettaglio prodotto
Nella pagina di dettaglio prodotto viene visualizzato il pulsante "Prenota e ritira"
se per il prodotto è abilitata l'opzione "Is Pickupable".
Al click del pulsante, il carrello viene pulito prima di aggiungere il prodotto
e il cliente viene indirizzato alla pagina di checkout.
Per motivi di flusso Magento - ERP, è necessario che nel carrello
ci sia soltanto un prodotto per poter abilitare l'opzione di "Ritiro in negozio"
