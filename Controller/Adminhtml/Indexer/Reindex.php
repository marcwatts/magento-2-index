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

class Reindex extends \Magento\Backend\App\Action
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
		// initailise
		$indexerId = $this->getRequest()->getParam('id');
		$indexer = $this->indexerRegistry->get($indexerId);

		// checks
		if (!$indexer || !$indexer->getId()) {
			$this->messageManager->addErrorMessage(__('Cannot initialise the indexer process'));
		} else {

			// run
			try {
				$indexer->reindexAll();
				$this->messageManager->addSuccessMessage(__('%1 index was rebuilt', $indexer->getTitle()));
			} catch (LocalizedException $e) {
				$this->messageManager->addErrorMessage($e->getMessage());
			} catch (Exception $e) {
				$this->messageManager->addExceptionMessage($e, __('There was a problem with reindexing process'));
			}
		}

		// redirect
		$this->_redirect('indexer/indexer/list');
	}
}
