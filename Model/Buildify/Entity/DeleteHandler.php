<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity;

use Magento\Framework\Exception\CouldNotDeleteException;
use Psr\Log\LoggerInterface;

class DeleteHandler
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Repository $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Repository $repository,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * @param int $id
     * @param string $type
     */
    public function delete($id, $type)
    {
        try {
            $this->repository->delete($id, $type);
        } catch (CouldNotDeleteException $e) {
            $this->logger->error($e->getMessage());
        }
    }
}