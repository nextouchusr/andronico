## Nextouch Eav Module

Il modulo è stato sviluppato per gestire l'import degli
attributi ECAT - AT3. Il modulo presenta principalmente
la definizione di modelli e servizi per recuperare gli attributi
ECAT - AT3

#### Aggiunta nuovi attributi
Tramite il sistema Patch di Magento, sono stati aggiunti
i seguenti attributi:

* `external_set_id`: il codice interno di AttributeSet dell'ERP (CodiceTemplate nel file template.csv)
* `external_option_id`: il codice interno di AttributeOption dell'ERP (CodiceValore nel file template.csv)

#### Definizione modelli di dati
Sono stati estesi i models: `AttributeSet` e `AttributeOption` per leggere/scrivere
i nuovi attributi sopra elencati

#### Raggruppamento degli attributi nella pagina dettaglio prodotto
Per visualizzare nella pagina di dettaglio prodotto gli attributi
raggruppati in base al gruppo di appartenenza è stata realizzato
il servizio `AttributeGroupRepository`

#### Recupero attribute option in base al codice dell'ERP
Per recuperare l'attribute option in base al codice provenienti
dall'ERP (CodiceValore) è stata realizzata l'API `getByExternalOptionId` presente in
`AttributeOptionManagementInterface`

#### Recupero attribute set in base al codice dell'ERP
Per recuperare l'attribute set in base al codice provenienti
dall'ERP (CodiceTemplate) è stata realizzata l'API `getByExternalSetId` presente in
`AttributeSetRepositoryInterface`
