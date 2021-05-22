<?php

namespace App\Http\Controllers;

use App\Console\Commands\UsageCommand;
use App\Events\SchedulerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Spatie\ShortSchedule\ShortSchedule;

class SystemInfoController extends Controller
{
    /**
     * Determine whether the specified file exists in the specified path, if it does not exist, create it
     * @param string $fileName filename
     * @param string $content file content
     * @return string returns the file path
     */
    private function getFilePath($fileName, $content)
    {
        $path = dirname(__FILE__) . "\\$fileName";
        if (!file_exists($path)) {
            file_put_contents($path, $content);
        }
        return $path;
    }

    /**
     * Get cpu usage vbs file generation function
     * @return string returns the vbs file path
     */
    private function getCupUsageVbsPath()
    {
        return $this->getFilePath(
            'cpu_usage.vbs',
            "On Error Resume Next
       Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\")
       WScript.Echo(objProc.LoadPercentage)"
        );
    }

    /**
     * Get total memory and available physical memory JSON vbs file generation function
     * @return string returns the vbs file path
     */
    private function getMemoryUsageVbsPath()
    {
        return $this->getFilePath(
            'memory_usage.vbs',
            "On Error Resume Next
       Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
       Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
       For Each objOS in colOS
         Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
       Next"
        );
    }

    /**
     * Get CPU usage
     * @return Number
     */
    public function getCpuUsage()
    {
        $path = $this->getCupUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        return $usage[0];
    }

    /**
     * Get an array of memory usage
     * @return array
     */
    public function getMemoryUsage()
    {
        $path = $this->getMemoryUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        $memory = json_decode($usage[0], true);
        $memory['usage'] = Round((($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / $memory['TotalVisibleMemorySize']) * 100);
        return $memory;
    }
    public function DiskInfo(){
        $psPath = "powershell.exe";
        //        your .ps1 (powershell path)
        $psDIR = "E:\\workspace\\SystemHealthMonitoring\\system-utilization-tools\\system-utilization-server\\resources\\assets\\";
        $psScript = "logicalDisk.ps1";
        $runScript = $psDIR. $psScript;
        $runCMD = $psPath." ".$runScript." 2>&1";
        exec($runCMD,$out,$ret);
        return $out;

    }
    public function CpuInfo(){
        $psPath = "powershell.exe";
        //        your .ps1 (powershell path)
        $psDIR = "E:\\workspace\\SystemHealthMonitoring\\system-utilization-tools\\system-utilization-server\\resources\\assets\\";
        $psScript = "CpuInfo.ps1";
        $runScript = $psDIR. $psScript;
        $runCMD = $psPath." ".$runScript." 2>&1";
        exec($runCMD,$out,$ret);
        $name = $out[3];
        return response()->json([
            'name' => $name,
        ]);
    }
    public function MemoryInfo(){
        $psPath = "powershell.exe";
        //        your .ps1 (powershell path)
        $psDIR = "E:\\workspace\\SystemHealthMonitoring\\system-utilization-tools\\system-utilization-server\\resources\\assets\\";
        $psScript = "MemoryInfo.ps1";
        $runScript = $psDIR. $psScript;
        $runCMD = $psPath." ".$runScript." 2>&1";
        exec($runCMD,$out,$ret);
        $name = $out[3];
        $space = $out[5];
        return response()->json([
            'name' => $name,
            'space' => $space
        ]);
    }
    public function GetAccount(){
        $psPath = "powershell.exe";
//        your .ps1 (powershell path)
        $psDIR = "E:\\workspace\\SystemHealthMonitoring\\system-utilization-tools\\system-utilization-server\\resources\\assets\\";
        $psScript = "account.ps1";
        $runScript = $psDIR. $psScript;
        $runCMD = $psPath." ".$runScript." 2>&1";
        exec($runCMD,$out,$ret);
        $name =  $out;
        return response()->json([
            'name' => $name,
        ]);
    }

}

