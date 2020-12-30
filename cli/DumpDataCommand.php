<?php

namespace Grav\Plugin\Console;

use Grav\Common\Grav;
use Grav\Common\GravTrait;
use Grav\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Grav\Plugin\TimelinePlugin\Content;
use Grav\Plugin\TimelinePlugin\Data;
use Grav\Framework\Cache\Adapter\FileStorage;

/**
 * Class DumpDataCommand
 *
 * @package Grav\Plugin\Timeline
 * @author  Ole Vik <git@olevik.net>
 * @license MIT
 * @since   v1.1.0
 */
class DumpDataCommand extends ConsoleCommand
{
    /**
     * Command definitions
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName("dump")
            ->setDescription("Generates and stores data.")
            ->setHelp('The <info>dump</info>-command generates and stores data.')
            ->addArgument(
                'route',
                InputArgument::REQUIRED,
                'The route to the page'
            )
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'The type of data to dump, either nodestructure, uml or markdown'
            )
            ->addArgument(
                'target',
                InputArgument::OPTIONAL,
                'Overrides target-option from plugin-config'
            )
            ->addOption(
                'echo',
                'e',
                InputOption::VALUE_NONE,
                'Outputs result directly'
            );
    }

    /**
     * Generates schema definitions for event types
     *
     * @return void
     */
    protected function serve()
    {
        $config = Grav::instance()['config']->get('plugins.timeline');
        $res = Grav::instance()['locator'];
        $route = $this->input->getArgument('route');
        $target = $this->input->getArgument('target');
        $echo = $this->input->getOption('echo');
        if (!empty($target)) {
            $config['cache'] = $target;
        }
        $type = $this->input->getArgument('type');
        $this->output->writeln('<info>Generating data</info>');
        try {
            $content = new Content('date', 'asc');
            $tree = $content->buildTree($route);
            switch ($type) {
                case 'nodestructure':
                    $file = 'nodestructure.json';
                    $data = Data::getNodeStructure($tree);
                    break;
                case 'uml':
                    $file = 'timeline.uml';
                    $data = Data::getUMLSyntax($tree);
                    break;
                case 'markdown':
                    $file = 'timeline.md';
                    $data = Data::getMarkdownOutput($tree);
                    break;
                default:
                    $file = 'timeline.uml';
                    $data = Data::getUMLSyntax($tree);
            }
            if ($echo) {
                echo $data;
            } else {
                $target = array(
                    'persist' => $res->findResource('user://') . '/data/timeline',
                    'transient' => $res->findResource('cache://') . '/timeline',
                    'native' => $res->findResource('user://') . '/plugins/timeline/data'
                );
                $location = $target[$config['cache']];
                $Storage = new FileStorage($location);
                if ($Storage->doHas($file)) {
                    $Storage->doDelete($file);
                }
                $Storage->doSet($file, $data, 0);
                $this->output->writeln('<info>Saved to ' . $location . '/' . $file . '.</info>');
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
