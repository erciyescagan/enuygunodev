<?php


namespace App\api;



use Symfony\Component\Console\Exception\RuntimeException;

class ReaderFactory
{
    public static function createTodolistReader($providername)
    {
        $tdlReader = null;
        if($providername == 'Provider1'){
            $tdlReader = new Provider1Reader();
        }
        else if($providername == 'Provider2'){
            $tdlReader = new Provider2Reader();
        }else{
            throw new RuntimeException(sprintf('There is no provider registered with the "%s" provider name.', $providername));
        }

        return $tdlReader;
    }
}