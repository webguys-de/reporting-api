<?php

class Webguys_Reportingapi_ReportController extends Mage_Api_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this;
    }

    protected function checkPermission()
    {
        /** @var $http Mage_Core_Helper_Http */
        $http = Mage::helper('core/http');
        list($user, $pass) = $http->authValidate();

        $validUser = Mage::getStoreConfig('reportingapi/auth/username');
        $validPass = Mage::getStoreConfig('reportingapi/auth/password');

        if ($user !== $validUser || $pass !== $validPass) {
            $http->authFailed();
        }
    }

    public function listAction()
    {
        $this->checkPermission();

        /** @var $reportModel Webguys_Reportingapi_Model_Report */
        $reportModel = Mage::getSingleton('webguys_reportingapi/report');
        $reportList = $reportModel->getReports();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($reportList));
    }

    public function getAction()
    {
        $this->checkPermission();

        /** @var $reportModel Webguys_Reportingapi_Model_Report */
        $reportModel = Mage::getSingleton('webguys_reportingapi/report');
        $reportInfo = $reportModel->getReport($this->getRequest()->getParam('id', false));

        if ($reportInfo === false) {
            $this->getResponse()->setHttpResponseCode(404);
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($reportInfo));
    }

    public function deleteAction()
    {
        $this->checkPermission();

        /** @var $reportModel Webguys_Reportingapi_Model_Report */
        $reportModel = Mage::getSingleton('webguys_reportingapi/report');
        $deleted = $reportModel->deleteReport($this->getRequest()->getParam('id', false));

        if ($deleted === false) {
            $this->getResponse()->setHttpResponseCode(404);
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($deleted));
    }
}