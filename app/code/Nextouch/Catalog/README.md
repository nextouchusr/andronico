## Nextouch Catalog Module

Il modulo è stato sviluppato con l'intento di gestire
le modifiche effetuate al catalogo prodotti e/o categorie

### Source code

#### Aggiunta nuovi attributi
Tramite il sistema Patch di Magento, sono stati aggiunti
i seguenti attributi:

* `alternative_code`: il codice interno utilizzato dall'ERP per il prodotto
* `brand`: il brand associato al prodotto
* `ease_of_use`: per gestire la sezione "Facilità d'uso" della pagina di dettaglio prodotto
* `eco_choice`: editor per "Scelta ecologica" della pagina di dettaglio prodotto
* `eco_choice_level`: il livello di "Scelta ecologica" per la pagina di dettaglio prodotto
* `external_category_id`: il codice interno utilizzato dall'ERP per la categoria
* `fast_est_type_id`: il codice interno utilizzato da FastEst per il prodotto
* `floor_delivery_price`: il prezzo per la consegna al piano del prodotto
* `is_pickupable`: indica se il prodotto è disponibile per il Pick&Pay
* `is_recommended`: indica se il prodotto è raccomandato dal team Nextouch
* `is_returnable_in_store`: indica se il prodotto è rendibile in store
* `selectable_carrier`: il corriere utilizzato per la spedizione del prodotto
* `street_line_delivery_price`: il prezzo per la consegna in strada del prodotto

#### Definizione modelli di dati
Sono stati estesi i models: `Product` e `Category` per leggere/scrivere
i nuovi attributi sopra elencati

#### API per Decision Tree
Sono state realizzate delle nuove API  per consentire al Decision Tree
il recupero degli attributi prodotto.

**Get User Defined Attributes**

```http
  GET /V1/products/attribute-sets/:attributeSetId/user-defined-attributes
```

| Class | Method | Description |
| :-------- | :-------- | :------- |
| `ProductAttributeManagementInterface` | `getUserDefinedAttributes` | Restituisce gli attributi definiti dall'operatore |

**Get Filterable Attributes**

```http
  GET /V1/products/filterable-attributes
```

| Class | Method | Description |
| :-------- | :-------- | :------- |
| `ProductAttributeManagementInterface` | `getFilterableAttributes` | Restituisce gli attributi utilizzati nella layered navigation |

#### Informazioni extra per customizable options
Per comunicare correttamente a FastEst quali sono i servizi selezionati
per i prodotti dell'ordine, è stato necessario estendere la `GetOrderOptions`
tramite il servizio `CustomOptionExtraInfoProcessorInterface`.
Il servizio aggiunge delle informazioni extra (sku e price) che vengono utilizzate
per comprendere se un determinato servizio Nextouch è stato selezionato per il prodotto presente nell'ordine.
I servizi Nextouch sono tutti codificati come customizable options a cui è stato associato uno SKU.
Lo SKU ci consente di conoscere il servizio selezionato
per poi comunicarlo a FastEst tramite le loro API

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**PrepareAdditionalOrderOptions**

Aggiunge le informazioni extra per i customizable options
dei prodotti presenti in un ordine

**RemoveSkuAppendedByCustomOptions**

Di default Magento, quando si seleziona un customizable option,
concatena allo SKU del prodotto, lo SKU dell'opzione selezionata:

Es.:

* SKU prodotto: `AT-221000000`
* SKU servizio selezionato: `0001`
* SKU prodotto ordine: `AT-221000000-0001`

Per la corretta comunicazione dei prodotti all'ERP, è stato necesssario
rimuovere dallo SKU del prodotto ordine, lo SKU del servizio selezionato

**AddImageToCompareProductsPlugin**

Aggiunge l'immagine al prodotto inserito nella lista del "Compara"

**LimitProductsToCompare**

Limita a tre il numero dei prodotti che è possibile comparare

**AddProductSkuToOptions**

Aggiunge le informazioni extra ai custom options del prodotto
