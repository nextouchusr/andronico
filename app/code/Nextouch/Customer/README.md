## Nextouch Customer Module

Il modulo è stato sviluppato con l'intento di gestire
le modifiche effetuate all'anagrafica clienti e alla gestione
degli indirizzi cliente

#### Aggiunta nuovi attributi
Tramite il sistema Patch di Magento, sono stati aggiunti
i seguenti attributi:

* `fiscal_code`: indica il codice fiscale del cliente
* `floor`: indica a che piano abita il cliente
* `invoice_type`: indica se il tipo di documento che il cliente desidera (Ricevuta, Fattura)
* `lift`: indica se l'abitazione del cliente è provvista di ascensore
* `limited_traffic_zone`: indica se la zona del cliente è a traffico limitato
* `mobile_phone`: indica il cellulare del cliente
* `pec`: indica l'email PEC del cliente
* `sdi_code`: indica il codice SDI del cliente da comunicare all'agenzia delle entrate
* `stair`: indifica se l'abitazione del cliente è provvista di scale
* `privacy_policy_accepted`: indica se il cliente ha accettato gli accordi di Privacy Policy
* `customer_sync_failures`: indica il numero di volte che è fallita la sincronizzazione dell'anagrafica cliente con il sistema ERP

#### Definizione modelli di dati
Sono stati estesi i models: `Customer` e `Address` per leggere/scrivere
i nuovi attributi sopra elencati

#### Visualizzazione dei termini e condizioni
Per visualizzare i termini e condizioni che il cliente deve accettare
prima di poter completare la registrazione, è stato esteso la classe Block `Agreements`
