<?php
namespace Aheadworks\Buildify\Controller\Adminhtml\Builder\Revision;

use Aheadworks\Buildify\Api\RevisionRepositoryInterface;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Delete
 * @package Aheadworks\Buildify\Controller\Adminhtml\Builder\Revision
 */
class Delete extends BackendAction
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var RevisionRepositoryInterface
     */
    private $revisionRepository;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param RevisionRepositoryInterface $revisionRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        RevisionRepositoryInterface $revisionRepository
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->revisionRepository = $revisionRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['error' => true, 'message' => __('Invalid response data.')];

        $data = $this->getRequest()->getPostValue();
        if (is_array($data) && isset($data['revision_id'])) {
            try {
                $revisionId = $data['revision_id'];

                $this->revisionRepository->deleteById($revisionId);

                $result = ['error' => false, 'content' => ''];
            } catch (LocalizedException $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            } catch (\Exception $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            }
        }

        return $resultJson->setData($result);
    }
}
