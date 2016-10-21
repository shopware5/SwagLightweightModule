<?php

use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Join;
use Shopware\Components\CSRFWhitelistAware;
use Shopware\Models\Article\Repository as ArticleRepo;
use Shopware\Models\Article\SupplierRepository;
use Shopware\Models\Emotion\Repository as EmotionRepo;
use Shopware\Models\Form\Repository as FormRepo;

/*
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Shopware_Controllers_Backend_ExampleModulePlainHtml extends Enlight_Controller_Action implements CSRFWhitelistAware
{
    /**
     * @var ArticleRepo
     */
    protected $supplierRepository = null;

    /**
     * Emotion repository. Declared for an fast access to the emotion repository.
     *
     * @var EmotionRepo
     * @access private
     */
    public static $emotionRepository = null;

    /**
     * @var FormRepo
     */
    protected $formRepository = null;

    public function preDispatch()
    {
        $this->get('template')->addTemplateDir(__DIR__ . '/../../Resources/views/');
    }

    public function postDispatch()
    {
        $csrfToken = $this->container->get('BackendSession')->offsetGet('X-CSRF-Token');
        $this->View()->assign([ 'csrfToken' => $csrfToken ]);
    }

    /**
     * Internal helper function to get access to the form repository.
     *
     * @return SupplierRepository
     */
    private function getSupplierRepository()
    {
        if ($this->supplierRepository === null) {
            $this->supplierRepository = $this->getModelManager()->getRepository('Shopware\Models\Article\Supplier');
        }

        return $this->supplierRepository;
    }

    /**
     * @return FormRepo
     */
    private function getFormRepository()
    {
        if ($this->formRepository === null) {
            $this->formRepository = $this->getModelManager()->getRepository('Shopware\Models\Config\Form');
        }

        return $this->formRepository;
    }

    /**
     * Helper function to get access on the static declared repository
     *
     * @return EmotionRepo
     */
    protected function getEmotionRepository()
    {
        if (self::$emotionRepository === null) {
            self::$emotionRepository = $this->getModelManager()->getRepository('Shopware\Models\Emotion\Emotion');
        }

        return self::$emotionRepository;
    }

    public function indexAction()
    {
    }

    public function listAction()
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

    public function emotionAction()
    {
    }

    public function getEmotionAction()
    {
        $this->Front()->Plugins()->Json()->setRenderer();

        $limit = $this->Request()->getParam('limit', null);
        $offset = $this->Request()->getParam('start', 0);
        $filter = $this->Request()->getParam('filter', null);
        $filterBy = $this->Request()->getParam('filterBy', null);
        $categoryId = $this->Request()->getParam('categoryId', null);

        $query = $this->getEmotionRepository()->getListingQuery($filter, $filterBy, $categoryId);

        $query->setFirstResult($offset)->setMaxResults($limit);

        /**@var $statement PDOStatement */
        $statement = $query->execute();
        $emotions = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->View()->assign(['emotions' => $emotions]);
    }

    public function configAction()
    {
        $repository = $this->getFormRepository();

        $user = Shopware()->Auth()->getIdentity();
        /** @var $locale \Shopware\Models\Shop\Locale */
        $locale = $user->locale;
        $filter = [['property' => 'id', 'value' => 133]];

        /** @var $builder \Shopware\Components\Model\QueryBuilder */
        $builder = $repository->createQueryBuilder('form')
            ->select(['form', 'element', 'value', 'elementTranslation', 'formTranslation'])
            ->leftJoin('form.elements', 'element')
            ->leftJoin('form.translations', 'formTranslation', Join::WITH, 'formTranslation.localeId = :localeId')
            ->leftJoin('element.translations', 'elementTranslation', Join::WITH, 'elementTranslation.localeId = :localeId')
            ->leftJoin('element.values', 'value')
            ->setParameter("localeId", $locale->getId());

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

            if (!in_array($values['type'], ['select', 'combo'])) {
                continue;
            }
        }

        $this->View()->assign(['data' => $data]);
    }

    public function createSubWindowAction()
    {
    }

    public function getWhitelistedCSRFActions()
    {
        return ['index'];
    }
}
