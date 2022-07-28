## Nextouch Tax Module

Il modulo si occupa dell'aggiunta, tramite Patch Magento,
della lista di classi/tariffe/regole tassazione

### Source code

#### Aggiunta classi/tariffe/regole tassazione
Per gestire l'aggiunta delle classi/tariffe/regole di tassazione, sono state
create tre Patch differenti: `InsertTaxClassList`, `InsertTaxRateList` e `InsertTaxRuleList`.

A queste Patch, è stato necessario aggiungere delle classi servizio per recuperare:

* La classe di tassazione in base al nome (`TaxClassRepositoryInterface`)
* La tariffa di tassazione in base al codice (`TaxRateRepositoryInterface`)
