<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Command;

use App\User\Application\UseCase\GetBearerToken\GetBearerTokenCommand;
use App\User\Application\UseCase\GetBearerToken\GetBearerTokenCommandHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:get-bearer-token')]
final class GetBearerToken extends Command
{
    public const string GRANT_ADMIN_OPTION = 'testAdmin';
    public const string GRANT_USER_OPTION = 'testUser';

    public const string DEFAULT_DESCRIPTION = 'Generates a Bearer Token for user with provided login';

    public function __construct(
        private GetBearerTokenCommandHandler $getBearerTokenCommandHandler
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription(self::DEFAULT_DESCRIPTION)
            ->addOption(self::GRANT_ADMIN_OPTION, null, InputOption::VALUE_NONE, 'Grant user "testAdmin" role')
            ->addOption(self::GRANT_USER_OPTION, null, InputOption::VALUE_NONE, 'Grant user "testUser" role')
            ->addArgument(
                'login',
                InputArgument::REQUIRED,
                'The login of the user.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $grantType = $this->determineGranTypeInput($input);

            $getBearerTokenCommand = new GetBearerTokenCommand(
                (string) $input->getArgument('login'),
                $grantType
            );

            $bearerToken = $this->getBearerTokenCommandHandler->handle($getBearerTokenCommand);

            $output->writeln("User access token: {$bearerToken}");
            $output->writeln("User grant type: {$grantType}");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln($this->errorMessageAssembler($e->getMessage()));

            return Command::FAILURE;
        }
    }

    private function determineGranTypeInput(InputInterface $input): string
    {
        $grantAdminOption = $input->getOption(self::GRANT_ADMIN_OPTION);
        $grantUserOption = $input->getOption(self::GRANT_USER_OPTION);

        if ($grantAdminOption && $grantUserOption) {
            throw new \LogicException('Error: You can only use one option at a time: either --testAdmin or --testUser.');
        }

        if ($grantAdminOption) {
            return self::GRANT_ADMIN_OPTION;
        }

        return self::GRANT_USER_OPTION;
    }

    private function errorMessageAssembler(string $message): string
    {
        return '<error>' . $message . '</error>';
    }
}
