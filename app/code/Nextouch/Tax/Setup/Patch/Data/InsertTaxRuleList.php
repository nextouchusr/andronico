<?php
declare(strict_types=1);

namespace Nextouch\Tax\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Tax\Api\Data\TaxRuleInterfaceFactory;
use Magento\Tax\Api\TaxRuleRepositoryInterface;
use Magento\Tax\Model\ClassModel;
use Nextouch\Tax\Api\TaxClassRepositoryInterface;
use Nextouch\Tax\Api\TaxRateRepositoryInterface;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

class InsertTaxRuleList implements DataPatchInterface
{
    private TaxRuleInterfaceFactory $taxRuleFactory;
    private TaxRuleRepositoryInterface $taxRuleRepository;
    private TaxRateRepositoryInterface $taxRateRepository;
    private TaxClassRepositoryInterface $taxClassRepository;

    public function __construct(
        TaxRuleInterfaceFactory $taxRuleFactory,
        TaxRuleRepositoryInterface $taxRuleRepository,
        TaxRateRepositoryInterface $taxRateRepository,
        TaxClassRepositoryInterface $taxClassRepository
    ) {
        $this->taxRuleFactory = $taxRuleFactory;
        $this->taxRuleRepository = $taxRuleRepository;
        $this->taxRateRepository = $taxRateRepository;
        $this->taxClassRepository = $taxClassRepository;
    }

    public static function getDependencies(): array
    {
        return [
            InsertTaxRateList::class,
            InsertTaxClassList::class,
        ];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        each(function (array $data) {
            $taxRule = $this->taxRuleFactory->create();
            $taxRule->setCode($data['code']);
            $taxRule->setTaxRateIds($this->extractTaxRateIds($data['tax_rates']));
            $taxRule->setCustomerTaxClassIds($this->extractCustomerTaxClassIds($data['customer_tax_classes']));
            $taxRule->setProductTaxClassIds($this->extractProductTaxClassIds($data['product_tax_classes']));
            $taxRule->setPriority($data['priority']);
            $taxRule->setPosition($data['position']);

            $this->taxRuleRepository->save($taxRule);
        }, $this->getTaxRuleList());

        return $this;
    }

    private function getTaxRuleList(): array
    {
        return [
            [
                'code' => 'Aliq. Iva 4%',
                'tax_rates' => ['004'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['4'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Aliq. Iva 5%',
                'tax_rates' => ['005'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['5'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Aliq. Iva 10%',
                'tax_rates' => ['010'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['10'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Aliq. Iva 22%',
                'tax_rates' => ['022'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['22'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Fuori Campo Iva',
                'tax_rates' => ['300'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['300'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Oper. contr. minimi art.27,co.1 e 2, DL 98/2011+art.1 co.100, legge n. 244/2007',
                'tax_rates' => ['301'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['301'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Escluso art.2 (cessioni denaro,crediti,aziende)',
                'tax_rates' => ['302'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['302'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Escluso art.3 (es. diritti d\'autore)',
                'tax_rates' => ['303'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['303'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Escluso art.4 (oper. non commerciali)',
                'tax_rates' => ['304'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['304'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Escluso art.5 (es. co.co.co., assoc.partec.)',
                'tax_rates' => ['305'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['305'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-ter (fatt.vendita servizi a impresa extra UE)',
                'tax_rates' => ['306'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['306'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-ter (fatt.vendita servizi a p.iva UE)',
                'tax_rates' => ['307'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['307'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art.10,n.18 (prestazioni sanitarie)',
                'tax_rates' => ['308'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['308'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art.10,n.1/9 (operaz. non rientranti nell\'attività propria dell\'impresa)',
                'tax_rates' => ['309'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['309'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art.10',
                'tax_rates' => ['310'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['310'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8,co.1,lett.a (triangolazione nazionale)',
                'tax_rates' => ['311'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['311'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8,co.2 (acquisti interni con lettera intento)',
                'tax_rates' => ['312'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['312'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8,co.2 (acquisti intra con lettera intento)+art.42,co.2 DL331',
                'tax_rates' => ['313'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['313'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp.art.9,co.1 (serv. internaz. diretti esportazione esenti da bollo)',
                'tax_rates' => ['314'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['314'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Escluso art.15 (int. mora, antic.in nome p/conto, imball.rendere,sconti natura)',
                'tax_rates' => ['315'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['315'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17,co.6, lett. a-bis +Art.10, n. 8 bis/ter (cessioni fabbricati)',
                'tax_rates' => ['316'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['316'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.74-Ter (Agenzie di viaggio)',
                'tax_rates' => ['317'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['317'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.14, legge n. 49/87 (Cessioni a ONG) + art. 8, co. 1, lett. b-bis',
                'tax_rates' => ['318'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['318'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art.10,n.27-quinquies (beni con iva totalmente indetr.)',
                'tax_rates' => ['319'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['319'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Importazioni art. 8 2^c, 68 l.a, 8 bis, 9 2^c',
                'tax_rates' => ['320'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['320'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art.10, n.11 (oro da investimento)',
                'tax_rates' => ['321'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['321'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8 bis (cessione di navi e altre operazioni assimilate all\'esport.)',
                'tax_rates' => ['322'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['322'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8,co.1,lett.b (esportaz.trasporto cessionario non residente)',
                'tax_rates' => ['323'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['323'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8,co.1,lett.c (esportazione indiretta con lettera d\'intento)',
                'tax_rates' => ['324'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['324'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8,co.1,lett.a (esportaz. diretta fuori UE)',
                'tax_rates' => ['325'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['325'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Escluso art.26 (note variazioni senza iva)',
                'tax_rates' => ['326'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['326'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Altri acquisti non imponibili',
                'tax_rates' => ['327'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['327'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Operazioni effettuate con/dai terremotati',
                'tax_rates' => ['328'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['328'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.9, co. 2 (servizi con lettera d\'intento)',
                'tax_rates' => ['329'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['329'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-quater (fatt.vendita servizi a p.iva UE)',
                'tax_rates' => ['330'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['330'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-quater (fatt.vendita servizi a impresa extra UE)',
                'tax_rates' => ['331'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['331'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-quinquies (fatt.vendita servizi a p.iva UE)',
                'tax_rates' => ['332'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['332'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-quinquies (fatt.vendita servizi a impresa extra UE)',
                'tax_rates' => ['333'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['333'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.36-bis (dispensa adempimenti per operazioni esenti)',
                'tax_rates' => ['334'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['334'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17,co.3 (vendite del rappres.fiscale di non residente)+risol.n.89/E 25/8/10',
                'tax_rates' => ['335'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['335'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.36 DL n.41/95 (Regime del margine)',
                'tax_rates' => ['336'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['336'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art.19, co.3, lett.a-bis(operaz.attive art.10 nn.da 1 a 4 con extracom.)',
                'tax_rates' => ['337'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['337'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.38-Quater (cessioni a viaggiatori extrac.)',
                'tax_rates' => ['338'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['338'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I artt. 7 e seguenti senza diritto alla detrazione (art.19,co. 3, lett.b)',
                'tax_rates' => ['339'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['339'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7septies/sexies(fatt.vendita servizi a privato non resid.in deroga)',
                'tax_rates' => ['340'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['340'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp.art.41 D.L.331/93 (cessioni intra)',
                'tax_rates' => ['341'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['341'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp.art.42 e 40 co. 2 D.L.331/93 (acq. intra beni)',
                'tax_rates' => ['342'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['342'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.8,co.1,lett.b-bis (cessioni finalità umanitarie)',
                'tax_rates' => ['343'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['343'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-bis (cessione di beni art. 7bis - UE)',
                'tax_rates' => ['344'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['344'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.9,co.1 (servizi internazionali assoggettati a bollo)',
                'tax_rates' => ['345'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['345'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art. 124, co. 2, DL 34/2020 (beni COVID-19)',
                'tax_rates' => ['346'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['346'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Esente art.1,commi 452 e 453 L.n.178/2020 (vaccini e diagnosi/tamponi Covid)',
                'tax_rates' => ['347'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['347'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art. 41, co. 1, lett. b (vendite a distanza a privati UE oltre la sogli',
                'tax_rates' => ['348'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['348'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.50 bis, co. 4, lett. g, D.L. n. 331/93 (depositi iva)',
                'tax_rates' => ['350'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['350'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.50 bis, co. 4, lett. f, D.L. n. 331/93 (depositi iva)',
                'tax_rates' => ['351'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['351'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.50 bis, co. 4, lett. c/i, D.L. n. 331/93 (depositi iva)+lett.e/h',
                'tax_rates' => ['352'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['352'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.50 bis, co. 4, lett. a,b,e,h, D.L. n. 331/93 (depositi iva)',
                'tax_rates' => ['353'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['353'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Operazioni contr.forfetari (art.1 co.54-89,legge n.190/14 e succ.modif/integr.)',
                'tax_rates' => ['354'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['354'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Cessioni gratuite all\'esportazione',
                'tax_rates' => ['355'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['355'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Operazioni OSS e IOSS',
                'tax_rates' => ['356'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['356'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.58,co.1 D.L.331/93 (triangolaz. nazion. operazioni intracomun.)',
                'tax_rates' => ['358'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['358'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Escluso art. 8, co. 35, legge n. 67/88 (rimborso oneri distacco del personale)',
                'tax_rates' => ['367'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['367'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.68 escl. lett. a (importaz.non soggette a IVA)+art.67 lett.a',
                'tax_rates' => ['368'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['368'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.74-ter,co8(provv.interm.con rappr.ag.viaggi UE-autof.art.7DM n.340 30/7/99)',
                'tax_rates' => ['369'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['369'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'F.C.I art.7-bis (no territorialità cessione beni)',
                'tax_rates' => ['370'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['370'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp.art.71 (RSM)',
                'tax_rates' => ['371'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['371'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp.art.72 (Accordi internazionali)',
                'tax_rates' => ['372'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['372'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp. art.71 (Vaticano)',
                'tax_rates' => ['373'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['373'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Non imp.art.74,co.1-2 (tabacchi, quotidiani,..)',
                'tax_rates' => ['374'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['374'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.74 co.7,8 (rottami e metalli ferrosi e non)',
                'tax_rates' => ['375'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['375'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17, co.5 (materiale oro e argento)',
                'tax_rates' => ['376'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['376'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17, co.6,lett.a (prestaz.settore edile subappalto)',
                'tax_rates' => ['377'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['377'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.74-ter,co.8(provv.interm.rappr.da ag.viaggi fuoriUE-autof.)+art.9,co1,n7bis',
                'tax_rates' => ['378'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['378'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17, co.6,lett.b (cessioni telefoni cellulari)',
                'tax_rates' => ['379'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['379'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17,co.6,lett.c(cess.dispos.circuito integr.microproc.unità centrali elabor)',
                'tax_rates' => ['380'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['380'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17, co.6,lett.a-ter (pulizia,demolizione,installaz. impianti,completamento)',
                'tax_rates' => ['381'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['381'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Art.17, co.6,lett.d-bis,d-ter,d-quater (cessione gas/energia elettrica)',
                'tax_rates' => ['382'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['382'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Acqu. Rit. 4%',
                'tax_rates' => ['404'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['404'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Acqu. Rit. 5%',
                'tax_rates' => ['405'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['405'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Acqu. Rit. 10%',
                'tax_rates' => ['410'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['410'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Acqu. Rit. 22%',
                'tax_rates' => ['422'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['422'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Rev.charge Acq. Iva 22% Art.17, co.6,lett.b (cessioni telefoni cellulari)',
                'tax_rates' => ['579'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['579'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 4% Indetr.',
                'tax_rates' => ['604'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['604'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 5% Indetr.',
                'tax_rates' => ['605'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['605'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 10% Indetr.',
                'tax_rates' => ['610'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['610'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 22% Indetr. ',
                'tax_rates' => ['622'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['622'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 4% Indetr. 50%',
                'tax_rates' => ['704'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['704'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 5% Indetr. 50%',
                'tax_rates' => ['705'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['705'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 10% Indetr. 50%',
                'tax_rates' => ['710'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['710'],
                'priority' => 0,
                'position' => 0,
            ],
            [
                'code' => 'Iva 22% Indetr. 50%',
                'tax_rates' => ['722'],
                'customer_tax_classes' => ['Retail Customer'],
                'product_tax_classes' => ['722'],
                'priority' => 0,
                'position' => 0,
            ],
        ];
    }

    private function extractTaxRateIds(array $taxRates): array
    {
        return map(function (string $code) {
            return $this->taxRateRepository->getByCode($code)->getId();
        }, $taxRates);
    }

    private function extractCustomerTaxClassIds(array $customerTaxClasses): array
    {
        return map(function (string $name) {
            return $this->taxClassRepository->getByName($name, ClassModel::TAX_CLASS_TYPE_CUSTOMER)->getId();
        }, $customerTaxClasses);
    }

    private function extractProductTaxClassIds(array $productTaxClasses): array
    {
        return map(function (string $name) {
            return $this->taxClassRepository->getByName($name, ClassModel::TAX_CLASS_TYPE_PRODUCT)->getId();
        }, $productTaxClasses);
    }
}
