<?php

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Join;
use Shopware\Components\CSRFWhitelistAware;

/*
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Shopware_Controllers_Backend_ExampleModulePlainHtml extends Enlight_Controller_Action implements CSRFWhitelistAware
{
    /**
     * @var \Shopware\Models\Site\Repository
     */
    protected $supplierRepository = null;

    /**
     * Emotion repository. Declared for an fast access to the emotion repository.
     *
     * @var \Shopware\Models\Emotion\Repository
     * @access private
     */
    public static $emotionRepository = null;

    /**
     * @var \Shopware\Models\Form\Repository
     */
    protected $formRepository = null;

    /**
     * Internal helper function to get access to the form repository.
     *
     * @return \Shopware\Models\Article\Repository
     */
    private function getSupplierRepository()
    {
        if ($this->supplierRepository === null) {
            $this->supplierRepository = $this->getModelManager()->getRepository('Shopware\Models\Article\Article');
        }

        return $this->supplierRepository;
    }

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
     * @return null|Shopware\Models\Emotion\Repository
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
        $filter = null;
        $sort = [['property' => 'name']];
        $limit = 25;
        $offset = 0;

        $query = $this->getSupplierRepository()->getSupplierListQuery($filter, $sort, $limit, $offset);
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
            ->select(array('form', 'element', 'value', 'elementTranslation', 'formTranslation'))
            ->leftJoin('form.elements', 'element')
            ->leftJoin('form.translations', 'formTranslation', Join::WITH, 'formTranslation.localeId = :localeId')
            ->leftJoin('element.translations', 'elementTranslation', Join::WITH, 'elementTranslation.localeId = :localeId')
            ->leftJoin('element.values', 'value')
            ->setParameter("localeId", $locale->getId());

        $builder->addOrderBy((array) $this->Request()->getParam('sort', array()))
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

            if (!in_array($values['type'], array('select', 'combo'))) {
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
