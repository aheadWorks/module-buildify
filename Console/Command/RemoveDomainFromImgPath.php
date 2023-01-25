<?php
namespace Aheadworks\Buildify\Console\Command;

use Aheadworks\Buildify\Api\EntityFieldManagementInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RemoveDomainFromImgPath
 * @package Aheadworks\Buildify\Console\Command
 */
class RemoveDomainFromImgPath extends Command
{
    /**
     * @var EntityFieldManagementInterface
     */
    private $entityFieldManagement;

    /**
     * @var State
     */
    private $state;

    /**
     * @param EntityFieldManagementInterface $entityFieldManagement
     * @param State $state
     */
    public function __construct(
        EntityFieldManagementInterface $entityFieldManagement,
        State $state
    ) {
        parent::__construct();
        $this->entityFieldManagement = $entityFieldManagement;
        $this->state = $state;
    }

    /**
     * Configure this command
     */
    protected function configure()
    {
        $this->setName('aheadworks:buildify:remove-domain-from-img-path')
            ->setDescription('Remove domain from image path for compatibility between Magento websites');

        parent::configure();
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        $this->state->setAreaCode(Area::AREA_ADMINHTML);
        $output->writeln(__('Starting update data')->getText());

        $this->entityFieldManagement->removeDomainFromImgPath();

        $output->writeln(__(sprintf('Update complete in %s', round(microtime(true) - $start, 2)))->getText());
    }
}
