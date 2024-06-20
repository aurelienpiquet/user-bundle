<?php

declare(strict_types=1);

namespace Apb\UserBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class MJMLService
{
    public function __construct(
        private LoggerInterface $logger,
        private Environment     $twig,
        private string          $mjmlBinary,
        private string          $nodeBinary
    ) {
    }

    public function getVersion(): string
    {
        $command = [$this->nodeBinary, $this->mjmlBinary, '--version'];

        $process = new Process($command);
        $process->mustRun();

        preg_match('#\d\.\d+\.\d+#', $process->getOutput(), $match);

        return $match[0] ?? 'unknown';
    }

    public function render(string $template, mixed $args = []): string
    {
        //try {
            $view = $this->twig->render($template, $args);
        //} catch (LoaderError | RuntimeError | SyntaxError $e) {
        //    $this->logger->error($e->getMessage());

        //    return '';
        //}

        $command = [$this->nodeBinary, $this->mjmlBinary, '-i', '-s', '--config.minify', 'true'];

        $process = new Process($command);
        $process->setInput($view);
        $process->mustRun();

        return $process->getOutput();
    }
}
