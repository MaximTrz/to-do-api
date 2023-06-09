<?php


namespace System;


use Psr\Log\AbstractLogger;

/**
 * Class Logger
 * @package App
 * Класс для ведения логов
 */
class Logger extends AbstractLogger
{
    protected $fileName;

    public function __construct($fileName)
    {
        if (!file_exists($fileName)){
            file_put_contents($fileName, '');
        }
        $this->fileName = $fileName;
    }

    public function getDate(){
        return date("d-m-Y H:i:s");
    }

    public function interpolate($message, array $context = array()): string
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    public function log($level, $message, array $context = [])
    {
        $context += ['date'=>$this->getDate()];
        $message = $this->interpolate($message, $context);
        $text = $level.': '.$message.PHP_EOL;
        file_put_contents($this->fileName, $text, FILE_APPEND | LOCK_EX);
    }
}