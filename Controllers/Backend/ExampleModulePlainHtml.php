<?php
declare(strict_types=1);

/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Join;
use Shopware\Components\CSRFWhitelistAware;
use Shopware\Components\Model\ModelRepository;
use Shopware\Models\Article\Supplier;
use Shopware\Models\Article\SupplierRepository;
use Shopware\Models\Config\Form;
use Shopware\Models\Emotion\Emotion;
use Shopware\Models\Emotion\Repository as EmotionRepository;

class Shopware_Controllers_Backend_ExampleModulePlainHtml extends Enlight_Controller_Action implements CSRFWhitelistAware
{
    private ?SupplierRepository $supplierRepository = null;

    private ?EmotionRepository $emotionRepository = null;

    private ?ModelRepository $formRepository = null;

    public function preDispatch(): void
    {
        $this->get('template')->addTemplateDir(__DIR__ . '/../../Resources/views/');
    }

    public function postDispatch(): void
    {
        $csrfToken = $this->container->get('backendsession')->offsetGet('X-CSRF-Token');
        $this->View()->assign(['csrfToken' => $csrfToken]);
    }

    public function indexAction(): void
    {
    }

    public function listAction(): void
    {
        $filter = [];
        $sort = [['property' => 'supplier.name']];
        $limit = 25;
        $offset = 0;

        $query = $this->getSupplierRepository()->getListQuery($filter, $sort, $limit, $offset);
        $total = $this->getModelManager()->getQueryCount($query);
        $suppliers = $query->getArrayResult();

        $this->View()->assign(['suppliers' => $suppliers, 'totalSuppliers' => $total]);
    }

    public function emotionAction(): void
    {
    }

    public function getEmotionAction(): void
    {
        $this->Front()->Plugins()->Json()->setRenderer();

        $limit = $this->Request()->getParam('limit');
        if ($limit !== null) {
            $limit = (int) $limit;
        }
        $offset = (int) $this->Request()->getParam('start', 0);
        $filter = $this->Request()->getParam('filter');
        $filterBy = $this->Request()->getParam('filterBy');
        $categoryId = $this->Request()->getParam('categoryId');

        $query = $this->getEmotionRepository()->getListingQuery($filter, $filterBy, $categoryId);

        $query->setFirstResult($offset)->setMaxResults($limit);

        /** @var ResultStatement $statement */
        $statement = $query->execute();
        $emotions = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->View()->assign(['emotions' => $emotions]);
    }

    public function configAction(): void
    {
        $repository = $this->getFormRepository();

        /** @var Shopware_Components_Auth $auth */
        $auth = $this->get('auth');
        $locale = $auth->getIdentity()->locale;
        $filter = [['property' => 'id', 'value' => 133]];

        $builder = $repository->createQueryBuilder('form')
            ->select(['form', 'element', 'value', 'elementTranslation', 'formTranslation'])
            ->leftJoin('form.elements', 'element')
            ->leftJoin('form.translations', 'formTranslation', Join::WITH, 'formTranslation.localeId = :localeId')
            ->leftJoin(
                'element.translations',
                'elementTranslation',
                Join::WITH,
                'elementTranslation.localeId = :localeId'
            )
            ->leftJoin('element.values', 'value')
            ->setParameter('localeId', $locale->getId());

        $builder->addOrderBy((array) $this->Request()->getParam('sort', []))
            ->addFilter($filter);

        $data = $builder->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        foreach ($data['elements'] as &$values) {
            foreach ($values['translations'] as $array) {
                if ($array['label'] !== null) {
                    $values['label'] = $array['label'];
                }
                if ($array['description'] !== null) {
                    $values['description'] = $array['description'];
                }
            }
        }
        unset($values);

        $this->View()->assign(['data' => $data]);
    }

    public function createSubWindowAction(): void
    {
    }

    public function getWhitelistedCSRFActions(): array
    {
        return ['index'];
    }

    private function getSupplierRepository(): SupplierRepository
    {
        if ($this->supplierRepository === null) {
            $this->supplierRepository = $this->getModelManager()->getRepository(Supplier::class);
        }

        return $this->supplierRepository;
    }

    private function getFormRepository(): ModelRepository
    {
        if ($this->formRepository === null) {
            $this->formRepository = $this->getModelManager()->getRepository(Form::class);
        }

        return $this->formRepository;
    }

    private function getEmotionRepository(): EmotionRepository
    {
        if ($this->emotionRepository === null) {
            $this->emotionRepository = $this->getModelManager()->getRepository(Emotion::class);
        }

        return $this->emotionRepository;
    }
}
