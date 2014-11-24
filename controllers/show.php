<?php
class ShowController extends StudipController {

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base.php'));
        
        Synchronizer::setDefaultDirectory($this->plugin->getPluginPath() . '/synchronizables');
        
        $this->synchronizer = Synchronizer::getInstance();
    }

    public function index_action()
    {
        $this->objects = $this->synchronizer->getObjects();
        if (empty($this->objects)) {
            $this->latest_version = $this->synchronizer->getLatestStudipVersion();
        }
    }
    
    public function copy_action()
    {
        $version = Request::get('version');

        $objects = $this->synchronizer->getObjects(null, 0, $version);
        foreach ($objects as $object) {
            $new_object = clone $object;
            $new_object->updateInfo(Studip::version(), $object->installation_id ?: Studip::id());
            $new_object->store();
        }
        PageLayout::postMessage(MessageBox::success(_('Die Inhalte wurden kopiert.')));
        $this->redirect('show');
    }

    // customized #url_for for plugins
    function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    } 
}
