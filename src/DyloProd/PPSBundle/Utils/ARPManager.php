<?php

namespace DyloProd\PPSBundle\Utils;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ARPManager
{
    public static function getMacAddress($ip)
    {
        $cmd = "arp-scan --interface=eth0 --localnet | grep ".$ip." | cut -f2 -s";
        
        $process = new Process($cmd);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $macaddress = str_replace("\n","",$process->getOutput());
        
        return $macaddress;
    }
}