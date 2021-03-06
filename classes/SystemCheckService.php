<?php
/*
 * @version $Id:  $
 * 
 * Copyright 2017 Christoph M. Becker
 * Copyright 2019 Holger Irmler
 *
 * This file is part of Maintenance_XH.
 *
 * Maintenance_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Maintenance_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Maintenance_XH. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Maintenance;

class SystemCheckService
{
    /**
     * @var string
     */
    private $pluginFolder;

    /**
     * @var array
     */
    private $lang;

    public function __construct()
    {
        global $pth, $plugin_tx;

        $this->pluginFolder = "{$pth['folder']['plugins']}maintenance";
        $this->lang = $plugin_tx['maintenance'];
    }

    /**
     * @return object[]
     */
    public function getChecks()
    {
        global $pth;
        
        return array(
            $this->checkXhVersion('1.7.0'),
            $this->checkPhpVersion('5.3.0'),
            //$this->checkExtension('dom'),
            //$this->checkExtension('libxml'),
            //$this->checkExtension('zlib'),
            $this->checkWritability("$this->pluginFolder/css/"),
            $this->checkWritability("$this->pluginFolder/languages/"),
            $this->checkWritability($pth["folder"]["downloads"]),
            $this->checkReadability("$this->pluginFolder/html/maintenance.html")
        );
    }

    /**
     * @param string $version
     * @return object
     */
    private function checkPhpVersion($version)
    {
        $state = version_compare(PHP_VERSION, $version, 'ge') ? 'success' : 'fail';
        $label = sprintf($this->lang['syscheck_phpversion'], $version);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }

    /**
     * @param string $extension
     * @param bool $isMandatory
     * @return object
     */
    private function checkExtension($extension, $isMandatory = true)
    {
        $state = extension_loaded($extension) ? 'success' : ($isMandatory ? 'fail' : 'warning');
        $label = sprintf($this->lang['syscheck_extension'], $extension);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }

    /**
     * @param string $version
     * @return object
     */
    private function checkXhVersion($version)
    {
        $state = version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'ge') ? 'success' : 'fail';
        $label = sprintf($this->lang['syscheck_xhversion'], $version);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }

    /**
     * @param string $folder
     * @return object
     */
    private function checkWritability($folder)
    {
        $state = is_writable($folder) ? 'success' : 'warning';
        $label = sprintf($this->lang['syscheck_writable'], $folder);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }
    
    /**
     * @param string $folder
     * @return object
     */
    private function checkReadability($file)
    {
        $state = is_readable($file) ? 'success' : 'fail';
        $label = sprintf($this->lang['syscheck_readable'], $file);
        $stateLabel = $this->lang["syscheck_$state"];
        return (object) compact('state', 'label', 'stateLabel');
    }
}