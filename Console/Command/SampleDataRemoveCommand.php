<?php declare(strict_types=1);

namespace TMajka\Promotion\Console\Command;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SampleDataRemoveCommand extends Command
{
    private const COMMAND_NAME = 'promotions:sampledata:remove';

    private const COMMAND_DESCRIPTION = 'Remove data for the promotion module.';

    public function __construct(private ResourceConnection $resourceConnection) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESCRIPTION);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messageStart = sprintf('Executing %s command.', self::COMMAND_NAME);
        $output->writeln(sprintf('<info>%s</info>', $messageStart));

        try {
            $connection = $this->resourceConnection->getConnection();

            $connection->delete($connection->getTableName('tmajka_promotion'), []);
            $output->writeln(sprintf('<info>Data removed from the %s table.</info>', 'tmajka_promotion'));

            $connection->delete($connection->getTableName('tmajka_promotion_group'), []);
            $output->writeln(sprintf('<info>Data removed from the %s table.</info>', 'tmajka_promotion_group'));

            $output->writeln('<info>Sample data removed successfully.</info>');

        } catch (\Exception $e) {
            $message = sprintf(
                'An error occurred while executing the command %s: %s',
                self::COMMAND_NAME,
                $e->getMessage()
            );
            $output->writeln(sprintf('<error>%s</error>', $message));

            return Cli::RETURN_FAILURE;
        }

        $output->writeln(
            sprintf(
                '<info>Ending executing %s command.</info>',
                self::COMMAND_NAME,
            )
        );

        return Cli::RETURN_SUCCESS;
    }
}
