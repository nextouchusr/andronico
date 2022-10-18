## Nextouch Inventory Module

Il modulo si occupa dell'aggiunta, tramite Patch Magento,
della lista di Stock e Source

### Source code

#### Aggiunta lista sources
Per gestire l'aggiunta delle source su Magento, è stata creata la Patch
`InsertSourceList`. Allo stato attuale vengono aggiunti due source:

* `ecommerce`: che corrisponde al negozio online
* `MB1`: che corrisponde al negozio di Monza

#### Aggiunta lista stocks
L'ecommerce presenta un unico stock (magazzino) composto, allo stato attuale, da due negozi:

* Store online
* Negozio sito a Monza
