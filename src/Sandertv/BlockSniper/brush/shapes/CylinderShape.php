<?php

namespace Sandertv\BlockSniper\brush\shapes;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Sandertv\BlockSniper\brush\BaseShape;
use Sandertv\BlockSniper\brush\BrushManager;

class CylinderShape extends BaseShape {
	
	public function __construct(Player $player, Level $level, int $radius = null, Position $center = null, bool $hollow = false) {
		parent::__construct($player, $level, $center);
		$this->radius = $radius;
		$this->height = BrushManager::get($player)->getHeight();
		$this->hollow = $hollow;
	}
	
	/**
	 * @return array
	 */
	public function getBlocksInside(): array {
		$radiusSquared = pow($this->radius, 2);
		$targetX = $this->center->x;
		$targetY = $this->center->y;
		$targetZ = $this->center->z;
		
		$minX = $targetX - $this->radius;
		$minZ = $targetZ - $this->radius;
		$minY = $targetY - $this->height;
		$maxX = $targetX + $this->radius;
		$maxZ = $targetZ + $this->radius;
		$maxY = $targetY + $this->height;
		
		$blocksInside = [];
		
		for($x = $minX; $x <= $maxX; $x++) {
			for($z = $minZ; $z <= $maxZ; $z++) {
				for($y = $minY; $y <= $maxY; $y++) {
					if(pow($targetX - $x, 2) + pow($targetZ - $z, 2) <= $radiusSquared) {
						if($this->hollow === true) {
							if($y !== $maxY && $y !== $minY && (pow($targetX - $x, 2) + pow($targetZ - $z, 2)) < $radiusSquared - 3 - $this->radius / 0.5) {
								continue;
							}
						}
						$blocksInside[] = $this->getLevel()->getBlock(new Vector3($x, (isset($gravityY) ? $gravityY : $y), $z));
						unset($gravityY);
					}
				}
			}
		}
		return $blocksInside;
	}
	
	public function getName(): string {
		return "Standing Cylinder";
	}
	
	public function getPermission(): string {
		return "blocksniper.shape.standingcylinder";
	}
	
	public function getApproximateProcessedBlocks(): int {
		$blockCount = round($this->radius * $this->radius * M_PI * $this->height);
		return $blockCount;
	}
}
