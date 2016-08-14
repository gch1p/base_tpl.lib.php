<?php

class tpl {

    protected $varsStack = [[]];
    protected $templateDir;

    public function __construct($templateDir) {
        $this->templateDir = $templateDir;
    }

    public function set($arg1, $arg2 = null) {
        if (is_array($arg1)) {
            foreach ($arg1 as $key => $value) {
                $this->varsStack[0][$key] = $value;
            }
        } elseif ($arg2 !== null) {
            $this->varsStack[0][$arg1] = $arg2;
        }
    }

    public function fetch($template, array $vars = []) {
        global $config;

        if (!$this->templateDir) throw new Exception('templateDir is not set');

        $template = $this->templateDir.'/'.$template;
        if (!file_exists($template)) throw new Exception($template.' does not exist!');

        if (!empty($vars)) $this->varsStack[] = $vars;

        foreach ($this->varsStack as $_vars) {
            if (!empty($_vars)) extract($_vars);
        }

        ob_start();

        include $template;

        array_pop($this->varsStack);

        return ob_get_clean();
    }

    public function display($template, array $vars = []) {
        echo $this->fetch($template, $vars);
    }

}
