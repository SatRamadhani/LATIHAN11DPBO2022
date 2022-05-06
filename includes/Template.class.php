<?php

class Template
{
    // File handling variable.
    var $filename = '';
    var $content = '';

    // Constructor.
    function Template($filename = '')
    {
        // Read / implode file for screen.
        $this->filename = $filename;
        $this->content = implode('', @file($filename));
    }

    // Clear code that should be changed (IDK, I can't understand this part).
    function clear()
    {
        $this->content = preg_replace("/DATA_[A-Z|_|0-9]+/", "", $this->content);
    }

    // Write file content.
    function write()
    {
        // Clear data and print file content to screen.
        $this->clear();
        print $this->content;

    }

    // Get file content.
    function getContent()
    {
        // Clear data and get file content.
        $this->clear();
        return $this->content;
    }

    // Replace string (just don't touch it as long as it works).
    function replace($old = '', $new = '')
    {
        if(is_int($new))
        {
            // Replace from integer.
            $value = sprintf("%d", $new);
        }
        elseif(is_float($new))
        {
            // Replace from float.
            $value = sprintf("%f", $new);
        }
        elseif(is_array($new))
        {
            // Replace from array.
            $value = '';
            foreach( $new as $item)
            {
                $value .= $item. '';
            }

        }
        else
        {
            // Default replace.
            $value = $new;
        }

        // Replace content.
        $this->content = preg_replace("/$old/",  $value, $this->content);

    }
}


?>