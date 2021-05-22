Get-CimInstance Win32_PhysicalMemory | Select-Object -Property Manufacturer*
(Get-CimInstance Win32_PhysicalMemory | Measure-Object -Property capacity -Sum).sum /1gb
