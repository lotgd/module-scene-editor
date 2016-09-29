<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class SceneEditorCommand {
    private $g;

    public function __constructor(Game $g)
    {
        $this->g = $g;
    }

    abstract public function execute(InputInterface $input, OutputInterface $output);
}
