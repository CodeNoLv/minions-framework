<?php

	class View
	{
	    protected $data = array();
	    protected $blocks = array();

	    /*
            Description: Set value which will be available at view
            Parameters: required (String) $key - variable's name which will be available in view
            			required (String) $value - varibale's value which will be avaiable in view
            Return: null
        */
	    public function setValue($key, $value)
	    {
	        $this->data[$key] = $value;
	    }

	    /*
            Description: Set view block which will be printend in template
            Parameters: required (String) $key - view block's name which will be available in template
            			required (String) $file - view block's filename which will be avaiable in template
            Return: null
        */
	    public function setBlock($name, $file)
	    {
	        $this->blocks[$name] = $file;
	    }

	    /*
            Description: Set's and prints templates view blocks and variables
            Parameters: null
            Return: null
        */
	    public function output()
	    {
	        extract($this->data);
	       
			foreach ($this->blocks as $name => $file)
			{
				$filePath = $GLOBALS["applicationPath"] . "/views/" . $file . ".php";
				ob_start();
		        include $filePath;
		        ${$name} = ob_get_contents();
		        ob_end_clean();
			}

	        ob_start();
	        include($GLOBALS["applicationPath"] . '/views/template.php');
	        $template = ob_get_contents();
	        ob_end_clean();
	        echo $template;
	    }
	}
?>