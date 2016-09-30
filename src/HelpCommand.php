<?php
declare(strict_types=1);

namespace LotGD\Modules\SceneEditor;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class HelpCommand extends SceneEditorCommand {
    public function execute(InputInterface $input, OutputInterface $output, array $argv)
    {
        $output->writeln($this->command->getAboutLine());
        $output->writeln("Edit the hierarchy of scenes in the current LotGD database.");
        $output->writeln("");

        $table = new Table($output);
        $table
            ->setStyle("compact")
            ->setRows([
                [' <option=bold>help</>', 'Display this help message'],
                [' <option=bold>new</>', 'Create a new scene'],
                [' <option=bold>show</>', 'Show all the scenes and their parent/child relationships'],
                [' <option=bold>show </><option=underscore>ID</>', 'Show details for scene with specified ID'],
                [' <option=bold>show</> <option=underscore>template</>', 'Show all scenes withe specified template'],
                [' <option=bold>edit</> <option=underscore>ID</>', 'Edit scene with specified ID'],
                [' <option=bold>delete</> <option=underscore>ID</> ...', 'Delete scenes(s) with specified ID(s)'],
                [' <option=bold>move</> <option=underscore>srcID</> <option=underscore>destID</>', 'Move the source scene to be a child of the destination scene'],
            ]);
        $table->render();
        $output->writeln("");
    }
}
