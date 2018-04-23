<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\BenchmarkResult;
use App\DTO\Website;
use App\DTO\WebsiteCollection;
use App\DTO\WebsiteResult;
use App\Exception\InvalidUrlException;
use App\Service\Benchmark;
use App\Service\ResultsReporter;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BenchmarkCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Benchmark
     */
    protected $benchmarkService;

    /**
     * @var ResultsReporter
     */
    protected $resultsReporter;

    public function __construct(Benchmark $benchmarkService, ResultsReporter $resultsReporter)
    {
        $this->benchmarkService = $benchmarkService;
        $this->resultsReporter = $resultsReporter;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:benchmark')
            ->setDescription(<<<'EOF'
Tests your website and compares it with the competitors. Currently, only HTTP response time is being checked
EOF
            )
            ->addArgument('url', InputArgument::REQUIRED, 'Your website\'s URL')
            ->addArgument('competitors', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Other websites');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $competitorsModel = new WebsiteCollection(
            array_map(function (string $url) {
                return new Website($url);
            }, $input->getArgument('competitors'))
        );

        try {
            $result = $this->benchmarkService->benchmark(new Website($input->getArgument('url')), $competitorsModel);
            $this->resultsReporter->report($result);
            $this->printResult($result, $input, $output);
        } catch (InvalidUrlException $e) {
            $this->handleInvalidUrlException($e);
        }
    }

    protected function printResult(BenchmarkResult $result, InputInterface $input, OutputInterface $output): void
    {
        $output->writeln(sprintf('Benchmark results (%s):', $result->getCreatedAt()->format(\DateTime::ATOM)));
        $table = new Table($output);
        $table
            ->setHeaders(['URL', 'Result', 'Unit']);

        $table->addRow($this->getRow($result->getMainWebsiteResult()));

        foreach ($result->getWebsitesResults() as $websitesResult) {
            $table->addRow($this->getRow($websitesResult));
        }

        $table->render();
    }

    protected function getRow(WebsiteResult $websiteResult): array
    {
        return [
            $websiteResult->getWebsite()->getUrl(),
            $websiteResult->getResult()->getValue(),
            $websiteResult->getResult()->getUnit(),
        ];
    }

    protected function handleInvalidUrlException(InvalidUrlException $exception): void
    {
        if (null === $this->logger) {
            return;
        }

        $this->logger->error(
            sprintf('Invalid URLs provided: %s', $exception->getViolations())
        );
    }
}
