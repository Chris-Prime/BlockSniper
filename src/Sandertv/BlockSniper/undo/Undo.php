<?php

namespace Sandertv\BlockSniper\undo;

use pocketmine\block\Block;

class Undo {
	
	private $undoBlocks;
	
	/**
	 * @param Block[] $undoBlocks
	 */
	public function __construct(array $undoBlocks) {
		$this->undoBlocks = $undoBlocks;
	}
	
	public function restore() {
		foreach($this->undoBlocks as $undoBlock) {
			$undoBlock->getLevel()->setBlock($undoBlock, $undoBlock, false, false);
		}
	}
	
	/**
	 * @return int
	 */
	public function getBlockCount(): int {
		return count($this->undoBlocks);
	}
}