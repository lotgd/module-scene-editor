<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use LotGD\Core\Game;

abstract class SceneEditorCommand {
    protected $command;
    protected $g;

    public function __construct(ScenesCommand $command, Game $g)
    {
        $this->command = $command;
        $this->g = $g;
    }

    abstract public function execute(InputInterface $input, OutputInterface $output, array $argv);
}
