<?php
declare(strict_types=1);

namespace Nextouch\Tax\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Tax\Api\Data\TaxRateInterfaceFactory;
use Magento\Tax\Api\TaxRateRepositoryInterface;
use function Lambdish\Phunctional\each;

class InsertTaxRateList implements DataPatchInterface
{
    private TaxRateInterfaceFactory $taxRateFactory;
    private TaxRateRepositoryInterface $taxRateRepository;

    public function __construct(
        TaxRateInterfaceFactory $taxRateFactory,
        TaxRateRepositoryInterface $taxRateRepository
    ) {
        $this->taxRateFactory = $taxRateFactory;
        $this->taxRateRepository = $taxRateRepository;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * @throws LocalizedException
     */
    public function apply(): self
    {
        each(function (array $data) {
            $taxRate = $this->taxRateFactory->create();
            $taxRate->setCode($data['code']);
            $taxRate->setTaxCountryId($data['country_id']);
            $taxRate->setTaxRegionId($data['region_id']);
            $taxRate->setZipIsRange($data['zip_is_range']);
            $taxRate->setTaxPostcode($data['post_code']);
            $taxRate->setRate($data['rate']);

            $this->taxRateRepository->save($taxRate);
        }, $this->getTaxRateList());

        return $this;
    }

    private function getTaxRateList(): array
    {
        return [
            [
                'code' => '004', // Aliq. Iva 4%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 4.00,
            ],
            [
                'code' => '005', // Aliq. Iva 5%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 5.00,
            ],
            [
                'code' => '010', // Aliq. Iva 10%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 10.00,
            ],
            [
                'code' => '022', // Aliq. Iva 22%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 22.00,
            ],
            [
                'code' => '300', // Fuori Campo Iva
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '301', // Oper. contr. minimi art.27,co.1 e 2, DL 98/2011+art.1 co.100, legge n. 244/2007
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '302', // Escluso art.2 (cessioni denaro,crediti,aziende)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '303', // Escluso art.3 (es. diritti d'autore)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '304', // Escluso art.4 (oper. non commerciali)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '305', // Escluso art.5 (es. co.co.co., assoc.partec.)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '306', // F.C.I art.7-ter (fatt.vendita servizi a impresa extra UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '307', // F.C.I art.7-ter (fatt.vendita servizi a p.iva UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '308', // Esente art.10,n.18 (prestazioni sanitarie)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '309', // Esente art.10,n.1/9 (operaz. non rientranti nell'attività propria dell'impresa)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '310', // Esente art.10
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '311', // Non imp. art.8,co.1,lett.a (triangolazione nazionale)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '312', // Non imp. art.8,co.2 (acquisti interni con lettera intento)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '313', // Non imp. art.8,co.2 (acquisti intra con lettera intento)+art.42,co.2 DL331
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '314', // Non imp.art.9,co.1 (serv. internaz. diretti esportazione esenti da bollo)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '315', // Escluso art.15 (int. mora, antic.in nome p/conto, imball.rendere,sconti natura)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '316', // Art.17,co.6, lett. a-bis +Art.10, n. 8 bis/ter (cessioni fabbricati)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '317', // Non imp. art.74-Ter (Agenzie di viaggio)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '318', // Non imp. art.14, legge n. 49/87 (Cessioni a ONG) + art. 8, co. 1, lett. b-bis
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '319', // Esente art.10,n.27-quinquies (beni con iva totalmente indetr.)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '320', // Importazioni art. 8 2^c, 68 l.a, 8 bis, 9 2^c
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '321', // Esente art.10, n.11 (oro da investimento)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '322', // Non imp. art.8 bis (cessione di navi e altre operazioni assimilate all'esport.)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '323', // Non imp. art.8,co.1,lett.b (esportaz.trasporto cessionario non residente)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '324', // Non imp. art.8,co.1,lett.c (esportazione indiretta con lettera d'intento)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '325', // Non imp. art.8,co.1,lett.a (esportaz. diretta fuori UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '326', // Escluso art.26 (note variazioni senza iva)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '327', // Altri acquisti non imponibili
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '328', // Operazioni effettuate con/dai terremotati
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '329', // Non imp. art.9, co. 2 (servizi con lettera d'intento)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '330', // F.C.I art.7-quater (fatt.vendita servizi a p.iva UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '331', // F.C.I art.7-quater (fatt.vendita servizi a impresa extra UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '332', // F.C.I art.7-quinquies (fatt.vendita servizi a p.iva UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '333', // F.C.I art.7-quinquies (fatt.vendita servizi a impresa extra UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '334', // Art.36-bis (dispensa adempimenti per operazioni esenti)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '335', // Art.17,co.3 (vendite del rappres.fiscale di non residente)+risol.n.89/E 25/8/10
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '336', // Non imp. art.36 DL n.41/95 (Regime del margine)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '337', // Esente art.19, co.3, lett.a-bis(operaz.attive art.10 nn.da 1 a 4 con extracom.)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '338', // Non imp. art.38-Quater (cessioni a viaggiatori extrac.)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '339', // F.C.I artt. 7 e seguenti senza diritto alla detrazione (art.19,co. 3, lett.b)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '340', // F.C.I art.7septies/sexies(fatt.vendita servizi a privato non resid.in deroga)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '341', // Non imp.art.41 D.L.331/93 (cessioni intra)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '342', // Non imp.art.42 e 40 co. 2 D.L.331/93 (acq. intra beni)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '343', // Non imp. art.8,co.1,lett.b-bis (cessioni finalità umanitarie)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '344', // F.C.I art.7-bis (cessione di beni art. 7bis - UE)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '345', // Non imp. art.9,co.1 (servizi internazionali assoggettati a bollo)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '346', // Esente art. 124, co. 2, DL 34/2020 (beni COVID-19)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '347', // Esente art.1,commi 452 e 453 L.n.178/2020 (vaccini e diagnosi/tamponi Covid)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '348', // Non imp. art. 41, co. 1, lett. b (vendite a distanza a privati UE oltre la sogli
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '350', // Non imp. art.50 bis, co. 4, lett. g, D.L. n. 331/93 (depositi iva)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '351', // Non imp. art.50 bis, co. 4, lett. f, D.L. n. 331/93 (depositi iva)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '352', // Non imp. art.50 bis, co. 4, lett. c/i, D.L. n. 331/93 (depositi iva)+lett.e/h
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '353', // Non imp. art.50 bis, co. 4, lett. a,b,e,h, D.L. n. 331/93 (depositi iva)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '354', // Operazioni contr.forfetari (art.1 co.54-89,legge n.190/14 e succ.modif/integr.)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '355', // Cessioni gratuite all'esportazione
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '356', // Operazioni OSS e IOSS
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '358', // Non imp. art.58,co.1 D.L.331/93 (triangolaz. nazion. operazioni intracomun.)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '367', // Escluso art. 8, co. 35, legge n. 67/88 (rimborso oneri distacco del personale)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '368', // Non imp. art.68 escl. lett. a (importaz.non soggette a IVA)+art.67 lett.a
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '369', // Art.74-ter,co8(provv.interm.con rappr.ag.viaggi UE-autof.art.7DM n.340 30/7/99)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '370', // F.C.I art.7-bis (no territorialità cessione beni)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '371', // Non imp.art.71 (RSM)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '372', // Non imp.art.72 (Accordi internazionali)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '373', // Non imp. art.71 (Vaticano)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '374', // Non imp.art.74,co.1-2 (tabacchi, quotidiani,..)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '375', // Art.74 co.7,8 (rottami e metalli ferrosi e non)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '376', // Art.17, co.5 (materiale oro e argento)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '377', // Art.17, co.6,lett.a (prestaz.settore edile subappalto)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '378', // Art.74-ter,co.8(provv.interm.rappr.da ag.viaggi fuoriUE-autof.)+art.9,co1,n7bis
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '379', // Art.17, co.6,lett.b (cessioni telefoni cellulari)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '380', // Art.17,co.6,lett.c(cess.dispos.circuito integr.microproc.unità centrali elabor)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '381', // Art.17, co.6,lett.a-ter (pulizia,demolizione,installaz. impianti,completamento)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '382', // Art.17, co.6,lett.d-bis,d-ter,d-quater (cessione gas/energia elettrica)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 0.00,
            ],
            [
                'code' => '404', // Acqu. Rit. 4%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 4.00,
            ],
            [
                'code' => '405', // Acqu. Rit. 5%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 5.00,
            ],
            [
                'code' => '410', // Acqu. Rit. 10%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 10.00,
            ],
            [
                'code' => '422', // Acqu. Rit. 22%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 22.00,
            ],
            [
                'code' => '579', // Rev.charge Acq. Iva 22% Art.17, co.6,lett.b (cessioni telefoni cellulari)
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 22.00,
            ],
            [
                'code' => '604', // Iva 4% Indetr.
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 4.00,
            ],
            [
                'code' => '605', // Iva 5% Indetr.
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 5.00,
            ],
            [
                'code' => '610', // Iva 10% Indetr.
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 10.00,
            ],
            [
                'code' => '622', // Iva 22% Indetr.
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 22.00,
            ],
            [
                'code' => '704', // Iva 4% Indetr. 50%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 4.00,
            ],
            [
                'code' => '705', // Iva 5% Indetr. 50%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 5.00,
            ],
            [
                'code' => '710', // Iva 10% Indetr. 50%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 10.00,
            ],
            [
                'code' => '722', // Iva 22% Indetr. 50%
                'country_id' => 'IT',
                'region_id' => null,
                'zip_is_range' => false,
                'post_code' => '*',
                'rate' => 22.00,
            ],
        ];
    }
}
