## Nextouch Theme Module

Il modulo si occupa di aggiungere le classi Block per la visualizzazione
di alcune informazioni persolizzate lato Front-end

### Source code

#### Pagina di dettaglio prodotto
Per la pagina di dettaglio prodotto sono stati creati quattro Block per
la visualizzazione delle seguenti informazioni:

* Barra sticky per la visualizzazione del prodotto `Catalog\Product\View.php`
* Blocco per la visualizzazione della gallery `Catalog\Product\View.php`
* Blocco informativo "Facilità d'uso" `Catalog\Product\View\EaseOfUse.php`
* Blocco informativo "Scelta ecologica" `Catalog\Product\View\EcoChoice.php`
* Lista attributi raggruppati per AttributeGroup `Catalog\Product\View\Attributes.php`

#### Pagina lista prodotti
Per la pagina di lista prodotti è stato creato un Block per
la visualizzazione delle seguenti informazioni:

* Percentuale di sconto applicata al prodotto `Catalog\Product\ListProduct.php`

#### Pagina carrello
Per la pagina di carrello sono stati creati due Block per
la visualizzazione delle seguenti informazioni:

* Prezzo scontato accanto al prezzo originale tagliato per via dello sconto `Checkout\Cart\Item\Renderer.php`
* Lista dei servizi Nextouch selezionati dal cliente, con la possibilità di eliminarli `Checkout\Cart\Item\Renderer.php`
* Sezione "Offerte per te", visualizzata recuperando in maniera randomica dei prodotti in offerta `Checkout\Cart\Offerts.php`
* Sezione "Offerte combinate", visualizzata recuperando i prodotti con un'offerta correlati ai prodotti presenti in carrello `Checkout\Cart\Offerts.php`

#### Megamenu
La gestione delle categorie visualizzate all'interno del megamenu è realizzata
attraverso la classe Block `Html\Topmenu.php`

#### Plugins aggiunti
Per estendere il comportamento di Magento, sono stati
realizzati i seguenti plugins:

**AddAttributeExplanationField**

Consente di visualizzare la "i" informativa accanto al nome del filtro
nella pagina di "Lista prodotti". Le informazioni vengono aggiunte
mediante il campo "Attribute explanation" presente all'interno di ciascun attributo
dell'area backoffice

**EditAvailableSortOptions**

Rimuove il filtro di ordinamento "Position" dalla pagina di "Lista prodotti"
e nella pagina di "Risultati di ricerca"

**AppendAttributeUnitsLabel**
Aggiunge l'unità di misura accanto al valore dell'attributo nella pagina di dettaglio prodotto

**AddGrandTotalData**
Permette di visualizzare nel minicart il totale ordine invece del subtotale
