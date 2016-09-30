<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use LotGD\Core\Models\Scene;

class MoveCommand extends SceneEditorCommand {
    public function execute(InputInterface $input, OutputInterface $output, array $argv)
    {
        if (count($argv) < 3) {
            $output->writeln("<error>move: too few arguments</error>");
            return;
        } else if (count($argv) > 3) {
            $output->writeln("<error>move: too many arguments</error>");
            return;
        }

        $srcId = $argv[1];
        $destId = $argv[2];

        $src = $this->g->getEntityManager()->getRepository(Scene::class)->find($srcId);
        $dest = $this->g->getEntityManager()->getRepository(Scene::class)->find($destId);

        if ($src == null) {
            $output->writeln("<error>move: could not find scene with id=$srcId</error>");
            return;
        }
        if ($dest == null) {
            $output->writeln("<error>move: could not find scene with id=$destId</error>");
            return;
        }
        if ($dest->hasChildren() && $dest->getChildren()->contains($src)) {
            $output->writeln("<error>move: scene id=$srcId already a child of scene id=$destId</error>");
            return;
        }

        $src->setParent($dest);
        $src->save($this->g->getEntityManager());
        $output->writeln("<fg=green>Moved id=$srcId to be a child of id=$destId:</>");
        $this->command->printScenes($output,[$src, $dest], false);
    }
}
