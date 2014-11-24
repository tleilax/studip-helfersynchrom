<?php
require 'bootstrap.php';

/**
 * Helfersynchrom.class.php
 *
 * ...
 *
 * @author  Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @version 1.0
 */

class Helfersynchrom extends StudIPPlugin implements SystemPlugin
{
    public function __construct()
    {
        parent::__construct();

        $navigation = new AutoNavigation(_('Helfersynchrom'));
        $navigation->setURL(PluginEngine::GetURL($this, array(), 'show'));
        Navigation::addItem('admin/config/helfersynchrom', $navigation);
    }

    public function perform($unconsumed_path)
    {
        StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        StudipAutoloader::addAutoloadPath(__DIR__ . '/classes');

        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }
}
