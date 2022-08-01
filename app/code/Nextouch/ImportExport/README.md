## Nextouch ImportExport Module

Il modulo è stato sviluppato per gestire l'importazione dei dati
dal sistema AT3 a Magento

### Source code

#### Importazione attributi ECAT e AT3 (_template.csv_)
L'importazione degli attributi avviene mediante file csv _template.csv_.
Il file è composto dalle seguenti informazioni così mappate su Magento:

* `CodiceTemplate`: corrisponde al codice interno di AT3 per gli attribute set
Viene memorizzato su Magento per consentire l'aggiornamento futuro dei dati
* `DescrizioneTemplate`: su Magento corrisponde al nome dell'attribute set
Per evitare omonimie al nome è stato aggiunto un codice incrementale
* `CodiceCaratteristica`: corrisponde al codice interno di AT3 per gli attributi
Viene memorizzato su Magento per consentire l'aggiornamento futuro dei dati
I codici che iniziano con `wins_*` sono gli attributi provenienti da ECAT
  I codici che iniziano con `c_*` sono gli attributi creati dal team Nextouch
* `DescrizioneCaratteristica`: su Magento corrisponde al nome dell'attribute
* `TipoCaratteristica`: consente di conoscere il tipo di dato che viene inviato
Può assumere i seguenti valori:
  * `Numerico`: memorizzato come dato "Price" su Magento per consentire la ricerca per range
  * `Testo libero`: memorizzato come dato "Text" su Magento
  * `Valore`: memorizzato come dato "Select" su Magento
* `Ordinamento`: l'ordine con cui l'attributo apparirà all'interno dell'attribute set
* `CodiceValore`: nel caso di attributo "Valore" corrisponde al valore dell'opzione selezionabile.
Viene memorizzato su Magento per consentire l'aggiornamento futuro dei dati
* `DescrizioneValore`: su Magento corrisponde alla descrizione dell'opzione dell'attributo

Per l'importazione degli attributi sono stati realizzati i seguenti servizi:

* `WinsAttributeDataProvider`: si occupa di leggere/validare il file csv e mapparlo su DTOs
* `WinsAttributeDataImport`: si occupa di creare/modificare gli attribute set/attributi e opzioni valore
* `AttributeMapperFactory`: in base alla tipologia di attributo, istanzia un attributo prodotto Magento

#### Importazione brand (_marchi.csv_)
Il marchio di un prodotto viene trattato come un attributo "speciale".
Esso viene importato separatamente rispetto agli altri attributi.
Il file in oggetto viene importato per popolare la lista dell'attributo `brand`.
Successivamente, con l'importazione del file _prodotti.csv_,
viene recuperato tramite la colonna `manufacturer` il codice marchio da associare al prodotto.
Il file è composto dalle seguenti informazioni così mappate su Magento:

* `codiceMarchio`: corrisponde al codice interno di AT3.
Viene memorizzato su Magento per consentire l'aggiornamento futuro dei dati
e l'associazione del brand al prodotto.
* `descrizioneMarchio`: su Magento corrisponde al nome del brand.
* `brandOnline`: valore ignorato in fase di import
* `online`: valore ignorato in fase di import

Per l'importazione dei marchi sono stati realizzati i seguenti servizi:
* `WinsBrandDataProvider`: si occupa di leggere/validare il file csv e mapparlo su DTOs
* `WinsBrandDataImport`: si occupa di creare/modificare i brand su Magento

#### Importazione prodotti (_prodotti.csv_)
L'importazione dei prodotti avviene mediante file csv _prodotti.csv_.
Il file presenta un struttura simile a quella di Magento, in modo tale
da facilitare la creazione/modifica dei prodotti. All'interno del file
sono state aggiunte delle colonne per la gestione dei prodotti:

* `category_paths`: contiene l'alberatura dei codici categorie di AT3
* `manufacturer`: corrisponde al codice brand da associare al prodotto
* `alternative_code`: corrisponde al codice prodotto di AT3
* `fast_est_type`: corrisponde al codice tipo prodotto di FastEst
* `selectable_carrier`: indica il corriere associato al prodotto.
Esso viene utilizzato in fase di visualizzazione dei metodi di spedizione
nella pagina di checkout, in base all'algoritmo definito con il team Nextouch
* `is_pickupable`: indica se il prodotto è disponibile per il "Ritiro in negozio" e il "Pick&Pay"
* `enable_rma`: indica se il prodotto è rendibile
* `is_returnable_in_store`: indica se il prodotto è rendibile in negozio
* `street_line_delivery_price`: indica il costo di spedizione in caso di consegna filo strada
* `floor_delivery_price`: indica il costo di spedizione in caso di consegna al piano

L'importazione dei prodotti avviene sfruttando la funzionalità di
importazione schedulata già disponibile sulla versione di Magento Commerce

#### Importazione caratteristiche (_caratteristiche.csv_)
Il file consente di importare i valori delle caratteristiche/attributi associati a ciascun prodotto.
Il file è composto dalle seguenti informazioni così mappate su Magento:

* `CodiceProdotto`: corrisponde allo SKU del prodotto importato
* `CodiceEcatDM`: corrisponde al codice interno del prodotto su ECAT
* `CodiceCaratteristica`: corrisponde al codice interno di AT3 per gli attributi
* `DescrizioneCaratteristica`: su Magento corrisponde al nome dell'attribute
* `Valore`: corrisponde al valore dell'attributo associato al prodotto
* `CodiceValore`: nel caso di attributo "Valore" corrisponde al valore dell'opzione selezionabile

Per l'importazione delle caratteristiche sono stati realizzati i seguenti servizi:
* `WinsFeatureSetDataProvider`: si occupa di leggere/validare il file csv e mapparlo su DTOs
* `WinsFeatureSetDataImport`: si occupa di assegnare il valore degli attributi ai prodotti
* `AttributeValueMapper`: si occupa di mappare il valore della caratteristica al valore di attributo presente su Magento

#### Importazione giacenze (_giacenze.csv_)
L'importazione della giacenza avviene mediante file csv _giacenze.csv_.
Il file presenta un struttura simile a quella di Magento, in modo tale
da facilitare l'aggiornamento della giacenza dei prodotti.

L'aggiornamento della giacenza avviene sfruttando la funzionalità di
importazione schedulata già disponibile sulla versione di Magento Commerce

#### Importazione immagini prodotto (_immagini.csv_)
L'importazione delle immagini avviene mediante file csv _immagini.csv_.
Il file presenta un struttura simile a quella di Magento, in modo tale
da facilitare l'aggiornamento delle immagini dei prodotti.

L'aggiunta delle immagini avviene sfruttando la funzionalità di
importazione schedulata già disponibile sulla versione di Magento Commerce

#### Importazione video prodotto (_video.csv_)
Il file consente di importare i video presenti per ciascun prodotto (se presenti).
Il file è composto dalle seguenti informazioni così mappate su Magento:

* `sku`: indica lo SKU del prodotto presente su Magento
* `sku_alternative`: indica il codice del prodotto interno su AT3
* `titolo`: corrisponde al titolo del video
* `url`: corrisponde all'url del video presente su YouTube

Per l'importazione dei video sono stati realizzati i seguenti servizi:
* `WinsVideoDataProvider`: si occupa di leggere/validare il file csv e mapparlo su DTOs
* `WinsVideoDataImport`: si occupa di associare il video ai prodotti
* `VideoGalleryMapper`: consente di mappare il video presente sul file csv con un oggetto media gallery di Magento

#### Operazioni schedulate
L'importazione dei file csv avviene seguendo un ordine ben preciso che
è il seguente:

1. Importazione degli attribute set e degli attributi
2. Importazione dei marchi e relativi codici
3. Importazione prodotti
4. Importazione delle caratteristiche prodotto (ovvero i valori impostati per ciascun attributo prodotto)
5. Importazione delle giacenze dei prodotti
6. Importazione delle immagini dei prodotti
7. Importazione dei video dei prodotti

Per eseguire l'import viene chiamata la funzione `run()` del servizio `WinsEntityDataOperationCombiner`.
Il servizio verifica se all'interno dell'area FTP sono presenti i file semaforo: `PROCEDI_AT.txt` e `PROCEDI_ECAT.txt`,
se sono presenti esegue in successione l'importazione dei dati seguendo l'ordine sopra descritto

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**PrepareCategoryUrlPathsValue**

Per importare l'alberatura dei codici di categoria viene prima effettuato
uno sluggify delle categorie. Questo consente di recuperare successivamente tramite slug
la categoria e assegnargli un `external_category_id`

**AssociateExternalCategoryId**

Tramite lo slug viene recuperata la categoria prodotto e assegnato il codice
interno di AT3

**PrepareIsReturnableValue**

Poiché non risultava possibile utilizzare il nome `is_returnable` all'interno del file
"_prodotti.csv_", è stato necessario utilizzare un workaround per assegnare il valore
al prodotto

**PrepareProductBrandValue**

Si occupa di recuperare tramite la colonna `manufacturer` il brand
su Magento e assegnarlo al prodotto
