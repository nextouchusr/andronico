## Nextouch Gls Module

Il modulo è stato sviluppato per permettere l'aggiunta
e l'integrazione del servizio di spedizione "Gls" con la
piattaforma "https://sellsend.gls-italy.com/"

#### Aggiunto metodo di spedizione
E' stata seguita la documentazione di Magento, per aggiungere
il nuovo metodo di spedizione

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**FilterGlsOrderList**

La piattaforma di Gls effettua un import di tutti gli ordini,
senza distinguere quelli che sono spedibili tramite FastEst/Dhl/Gls.
Per ovviare a questo problema, è stato realizzato questo plugin che filtra
gli ordini Gls in base all'utente che ha richiamato l'API di Magento, ovvero `gls-sellandsend`
