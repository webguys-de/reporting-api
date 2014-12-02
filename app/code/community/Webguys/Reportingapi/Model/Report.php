<?php

class Webguys_Reportingapi_Model_Report extends Mage_Core_Model_Abstract
{
    protected function getReportDir()
    {
        return Mage::getBaseDir('var') . DS . 'report' . DS;
    }

    /**
     * @return array
     */
    public function getReports()
    {
        $reports = array();

        foreach (glob($this->getReportDir().'*') as $report) {
            $reports[] = array(
                'id' => basename($report),
                'created' => filemtime($report),
                'size' => filesize($report)
            );
        }

        return $reports;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function getReport($id)
    {
        if (!$id || !is_numeric($id)) {
            return false;
        }

        $path = $this->getReportDir().$id;

        if (file_exists($path)) {
            if ($content = file_get_contents($path)) {
                return unserialize($content);
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteReport($id)
    {
        if (!$id || !is_numeric($id)) {
            return false;
        }

        $path = $this->getReportDir().$id;

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }
}