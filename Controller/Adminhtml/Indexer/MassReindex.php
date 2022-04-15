<?php

/**
* Marc Watts
* Copyright (c) 2022 Marc Watts <marc@marcwatts.com.au>
*
* @author Marc Watts <marc@marcwatts.com.au>
* @copyright Copyright (c) Marc Watts (https://marcwatts.com.au/)
* @license Proprietary https://marcwatts.com.au/terms-and-conditions.html
* @package Marcwatts_Index
*/

namespace Marcwatts\Index\Controller\Adminhtml\Indexer;

class MassReindex extends \Magento\Backend\App\Action
{
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Indexer\IndexerRegistry $indexerRegistry
	) {
		$this->indexerRegistry = $indexerRegistry;
		parent::__construct($context);
	}

	protected function _isAllowed()
	{
		return true;
	}

	public function execute()
	{
		// initialise
		$indexerIds = $this->getRequest()->getParam('indexer_ids');

		// checks
		if (empty($indexerIds)) {
			$this->messageManager->addErrorMessage(__('Please select indexers that you wish to reindex'));
		} else {

			// run
			try {
				foreach ($indexerIds as $indexerId) {
					$this->indexerRegistry->get($indexerId)->reindexAll();
				}
				$this->messageManager->addSuccessMessage(__('Total of %1 index(es) have reindexed data', count($indexerIds)));
			} catch (LocalizedException $e) {
				$this->messageManager->addErrorMessage($e->getMessage());
			} catch (Exception $e) {
				$this->messageManager->addExceptionMessage($e, __('Cannot initialize the indexer process'));
			}
		}

		// redirect
		$this->_redirect('indexer/indexer/list');
	}
}
