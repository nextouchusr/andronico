<?php
declare(strict_types=1);

namespace Nextouch\Tax\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Tax\Api\Data\TaxClassInterfaceFactory;
use Magento\Tax\Api\TaxClassRepositoryInterface;
use Magento\Tax\Model\ClassModel;
use function Lambdish\Phunctional\each;

class InsertTaxClassList implements DataPatchInterface
{
    private const TAXABLE_GOODS_CLASS_ID = 2;
    private const VAT_022_CLASS_ID = 4;
    private const VAT_22_CLASS_ID = 5;

    private TaxClassInterfaceFactory $taxClassFactory;
    private TaxClassRepositoryInterface $taxClassRepository;

    public function __construct(
        TaxClassInterfaceFactory $taxClassFactory,
        TaxClassRepositoryInterface $taxClassRepository
    ) {
        $this->taxClassFactory = $taxClassFactory;
        $this->taxClassRepository = $taxClassRepository;
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
        $this->removeDefaultTaxClassList();

        each(function (array $data) {
            $taxClass = $this->taxClassFactory->create();
            $taxClass->setClassName($data['name']);
            $taxClass->setClassType($data['type']);

            $this->taxClassRepository->save($taxClass);
        }, $this->getTaxClassList());

        return $this;
    }

    private function removeDefaultTaxClassList(): void
    {
        try {
            $this->taxClassRepository->deleteById(self::TAXABLE_GOODS_CLASS_ID);
            $this->taxClassRepository->deleteById(self::VAT_022_CLASS_ID);
            $this->taxClassRepository->deleteById(self::VAT_22_CLASS_ID);
        } catch (LocalizedException $e) {
            // Do nothing
        }
    }

    private function getTaxClassList(): array
    {
        return [
            [
                'name' => '004', // Aliq. Iva 4%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '005', // Aliq. Iva 5%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '010', // Aliq. Iva 10%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '022', // Aliq. Iva 22%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '300', // Fuori Campo Iva
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '301', // Oper. contr. minimi art.27,co.1 e 2, DL 98/2011+art.1 co.100, legge n. 244/2007
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '302', // Escluso art.2 (cessioni denaro,crediti,aziende)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '303', // Escluso art.3 (es. diritti d'autore)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '304', // Escluso art.4 (oper. non commerciali)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '305', // Escluso art.5 (es. co.co.co., assoc.partec.)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '306', // F.C.I art.7-ter (fatt.vendita servizi a impresa extra UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '307', // F.C.I art.7-ter (fatt.vendita servizi a p.iva UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '308', // Esente art.10,n.18 (prestazioni sanitarie)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '309', // Esente art.10,n.1/9 (operaz. non rientranti nell'attività propria dell'impresa)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '310', // Esente art.10
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '311', // Non imp. art.8,co.1,lett.a (triangolazione nazionale)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '312', // Non imp. art.8,co.2 (acquisti interni con lettera intento)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '313', // Non imp. art.8,co.2 (acquisti intra con lettera intento)+art.42,co.2 DL331
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '314', // Non imp.art.9,co.1 (serv. internaz. diretti esportazione esenti da bollo)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '315', // Escluso art.15 (int. mora, antic.in nome p/conto, imball.rendere,sconti natura)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '316', // Art.17,co.6, lett. a-bis +Art.10, n. 8 bis/ter (cessioni fabbricati)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '317', // Non imp. art.74-Ter (Agenzie di viaggio)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '318', // Non imp. art.14, legge n. 49/87 (Cessioni a ONG) + art. 8, co. 1, lett. b-bis
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '319', // Esente art.10,n.27-quinquies (beni con iva totalmente indetr.)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '320', // Importazioni art. 8 2^c, 68 l.a, 8 bis, 9 2^c
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '321', // Esente art.10, n.11 (oro da investimento)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '322', // Non imp. art.8 bis (cessione di navi e altre operazioni assimilate all'esport.)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '323', // Non imp. art.8,co.1,lett.b (esportaz.trasporto cessionario non residente)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '324', // Non imp. art.8,co.1,lett.c (esportazione indiretta con lettera d'intento)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '325', // Non imp. art.8,co.1,lett.a (esportaz. diretta fuori UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '326', // Escluso art.26 (note variazioni senza iva)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '327', // Altri acquisti non imponibili
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '328', // Operazioni effettuate con/dai terremotati
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '329', // Non imp. art.9, co. 2 (servizi con lettera d'intento)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '330', // F.C.I art.7-quater (fatt.vendita servizi a p.iva UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '331', // F.C.I art.7-quater (fatt.vendita servizi a impresa extra UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '332', // F.C.I art.7-quinquies (fatt.vendita servizi a p.iva UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '333', // F.C.I art.7-quinquies (fatt.vendita servizi a impresa extra UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '334', // Art.36-bis (dispensa adempimenti per operazioni esenti)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '335', // Art.17,co.3 (vendite del rappres.fiscale di non residente)+risol.n.89/E 25/8/10
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '336', // Non imp. art.36 DL n.41/95 (Regime del margine)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '337', // Esente art.19, co.3, lett.a-bis(operaz.attive art.10 nn.da 1 a 4 con extracom.)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '338', // Non imp. art.38-Quater (cessioni a viaggiatori extrac.)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '339', // F.C.I artt. 7 e seguenti senza diritto alla detrazione (art.19,co. 3, lett.b)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '340', // F.C.I art.7septies/sexies(fatt.vendita servizi a privato non resid.in deroga)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '341', // Non imp.art.41 D.L.331/93 (cessioni intra)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '342', // Non imp.art.42 e 40 co. 2 D.L.331/93 (acq. intra beni)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '343', // Non imp. art.8,co.1,lett.b-bis (cessioni finalità umanitarie)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '344', // F.C.I art.7-bis (cessione di beni art. 7bis - UE)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '345', // Non imp. art.9,co.1 (servizi internazionali assoggettati a bollo)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '346', // Esente art. 124, co. 2, DL 34/2020 (beni COVID-19)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '347', // Esente art.1,commi 452 e 453 L.n.178/2020 (vaccini e diagnosi/tamponi Covid)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '348', // Non imp. art. 41, co. 1, lett. b (vendite a distanza a privati UE oltre la sogli
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '350', // Non imp. art.50 bis, co. 4, lett. g, D.L. n. 331/93 (depositi iva)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '351', // Non imp. art.50 bis, co. 4, lett. f, D.L. n. 331/93 (depositi iva)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '352', // Non imp. art.50 bis, co. 4, lett. c/i, D.L. n. 331/93 (depositi iva)+lett.e/h
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '353', // Non imp. art.50 bis, co. 4, lett. a,b,e,h, D.L. n. 331/93 (depositi iva)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '354', // Operazioni contr.forfetari (art.1 co.54-89,legge n.190/14 e succ.modif/integr.)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '355', // Cessioni gratuite all'esportazione
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '356', // Operazioni OSS e IOSS
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '358', // Non imp. art.58,co.1 D.L.331/93 (triangolaz. nazion. operazioni intracomun.)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '367', // Escluso art. 8, co. 35, legge n. 67/88 (rimborso oneri distacco del personale)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '368', // Non imp. art.68 escl. lett. a (importaz.non soggette a IVA)+art.67 lett.a
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '369', // Art.74-ter,co8(provv.interm.con rappr.ag.viaggi UE-autof.art.7DM n.340 30/7/99)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '370', // F.C.I art.7-bis (no territorialità cessione beni)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '371', // Non imp.art.71 (RSM)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '372', // Non imp.art.72 (Accordi internazionali)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '373', // Non imp. art.71 (Vaticano)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '374', // Non imp.art.74,co.1-2 (tabacchi, quotidiani,..)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '375', // Art.74 co.7,8 (rottami e metalli ferrosi e non)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '376', // Art.17, co.5 (materiale oro e argento)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '377', // Art.17, co.6,lett.a (prestaz.settore edile subappalto)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '378', // Art.74-ter,co.8(provv.interm.rappr.da ag.viaggi fuoriUE-autof.)+art.9,co1,n7bis
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '379', // Art.17, co.6,lett.b (cessioni telefoni cellulari)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '380', // Art.17,co.6,lett.c(cess.dispos.circuito integr.microproc.unità centrali elabor)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '381', // Art.17, co.6,lett.a-ter (pulizia,demolizione,installaz. impianti,completamento)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '382', // Art.17, co.6,lett.d-bis,d-ter,d-quater (cessione gas/energia elettrica)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '404', // Acqu. Rit. 4%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '405', // Acqu. Rit. 5%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '410', // Acqu. Rit. 10%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '422', // Acqu. Rit. 22%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '579', // Rev.charge Acq. Iva 22% Art.17, co.6,lett.b (cessioni telefoni cellulari)
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '604', // Iva 4% Indetr.
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '605', // Iva 5% Indetr.
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '610', // Iva 10% Indetr.
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '622', // Iva 22% Indetr.
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '704', // Iva 4% Indetr. 50%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '705', // Iva 5% Indetr. 50%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '710', // Iva 10% Indetr. 50%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
            [
                'name' => '722', // Iva 22% Indetr. 50%
                'type' => ClassModel::TAX_CLASS_TYPE_PRODUCT,
            ],
        ];
    }
}
