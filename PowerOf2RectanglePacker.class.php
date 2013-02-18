<?php

/**
 * <b>PowerOf2RectanglePacker</b> implements a solution for the "diferent
 * rectangles in a rectangle" packing problem, with the additional restriction
 * of only powers of 2 are allowed for the dimensions.
 *
 * This is useful, for example, in packing textures for use with OpenGL ES.
 *
 * @author Marco Amado <mjamado@dreamsincode.com>
 * @since 2013-01-13 [last review: 2013-01-13]
 * @license Creative Commons 2.5 Portugal BY-NC-SA
 * @link http://www.dreamsincode.com/
 */
class PowerOf2RectanglePacker {

	/**
	 * This should be an array of associative arrays of the following format:
	 * {
	 *		id => "which can be anything you want, like an URL or a path",
	 *		width => [int],
	 *		height => [int]
	 * }
	 *
	 * It'll be expanded with two more properties, posX and posY, informing
	 * where in the texture they were positioned.
	 *
	 * @var array
	 */
	private $rectangles;
	/**
	 * The minimum power of 2 the width can be
	 *
	 * @var int
	 */
	private $widthStartingPowerOf2;
	/**
	 * The minimum power of 2 the height can be
	 *
	 * @var int
	 */
	private $heightStartingPowerOf2;
	/**
	 * The maximum power of 2 the width can be
	 *
	 * @var int
	 */
	private $widthEndingPowerOf2;
	/**
	 * The maximum power of 2 the height can be
	 *
	 * @var int
	 */
	private $heightEndingPowerOf2;
	/**
	 * The total area the rects occupy by themselves
	 *
	 * @var int
	 */
	private $totalRectArea;
	/**
	 * The best packing so far
	 *
	 * @var array
	 */
	private $bestPacking;
	/**
	 * The power of 2 width of the best packing so far
	 *
	 * @var int
	 */
	private $bestWidthPowerOf2;
	/**
	 * The power of 2 height of the best packing so far
	 *
	 * @var int
	 */
	private $bestHeightPowerOf2;

	/**
	 * Constructor. Nothing really interesting happens here...
	 *
	 * @param array $rectangles
	 */
	public function __construct($rectangles) {
		$this->rectangles = $rectangles;
	}

	/**
	 * Packs the rectangles into a big rectangle
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	public function pack() {
		if (!empty($this->rectangles) && is_array($this->rectangles)) {
			$this->sortRectangles();
			$this->initialMeasurements();

			require_once("BinaryTreeNode.class.php");
			$bestArea = PHP_INT_MAX;

			for ($widthPowerOf2 = $this->widthStartingPowerOf2; $widthPowerOf2 <= $this->widthEndingPowerOf2; $widthPowerOf2++) {
				for ($heightPowerOf2 = $this->heightStartingPowerOf2; $heightPowerOf2 <= $this->heightEndingPowerOf2; $heightPowerOf2++) {
					$realWidth = pow(2, $widthPowerOf2);
					$realHeight = pow(2, $heightPowerOf2);
					$realArea = $realWidth * $realHeight;
					$validPacking = true;

					if (($realArea >= $this->totalRectArea) && ($realArea < $bestArea)) {
						// create the main binary tree node
						$node = new BinaryTreeNode(0, 0, $realWidth, $realHeight);

						// iterate over the rectangles again, packing them
						foreach ($this->rectangles as &$rect) {
							$resNode = $node->insertRect($rect['width'], $rect['height']);

							if (is_null($resNode)) {
								// couldn't fit the rectangle anywhere
								// so, this packing is invalid
								$validPacking = false;
								break;
							}

							$rect['posX'] = $resNode->left;
							$rect['posY'] = $resNode->top;
						}

						if ($validPacking) {
							$bestArea = $realArea;
							$this->bestPacking = $this->rectangles;
							$this->bestWidthPowerOf2 = $widthPowerOf2;
							$this->bestHeightPowerOf2 = $heightPowerOf2;
						}
					}
				}
			}

			// return the complete rectangles information
			return $this->bestPacking;
		}
		else {
			throw new Exception("Bad rectangles array.", -1);
		}
	}

	/**
	 * Returns the dimensions of the best packing rectangle
	 *
	 * @return array
	 */
	public function getPackingDimensions() {
		return array(
			'width' => pow(2, $this->bestWidthPowerOf2),
			'height' => pow(2, $this->bestHeightPowerOf2)
		);
	}

	private function sortRectangles() {
		// sort the rectangles by descending area
		usort($this->rectangles, function($a, $b) {
			$aArea = $a['width'] * $a['height'];
			$bArea = $b['width'] * $b['height'];

			if ($aArea == $bArea) {
				return 0;
			}
			else {
				return ($aArea < $bArea) ? 1 : -1;
			}
		});
	}

	/**
	 * Initializes some variables for the packing cycles
	 */
	private function initialMeasurements() {
		// iterate over the rectangles, get the minimum width and height
		$maxWidth = 0;
		$maxHeight = 0;
		$totalWidth = 0;
		$totalHeight = 0;
		$this->totalRectArea = 0;
		foreach ($this->rectangles as $rect) {
			if ($rect['width'] > $maxWidth) {
				$maxWidth = $rect['width'];
			}

			if ($rect['height'] > $maxHeight) {
				$maxHeight = $rect['height'];
			}

			$totalWidth += $rect['width'];
			$totalHeight += $rect['height'];
			$this->totalRectArea += $rect['width'] * $rect['width'];
		}

		$this->widthStartingPowerOf2 = $this->getNextPowerOf2($maxWidth);
		$this->heightStartingPowerOf2 = $this->getNextPowerOf2($maxHeight);
		$this->widthEndingPowerOf2 = $this->getNextPowerOf2($totalWidth);
		$this->heightEndingPowerOf2 = $this->getNextPowerOf2($totalHeight);
	}

	/**
	 * Returns the next power of 2 of a given number.
	 * Eg.:	31 -> 5 (as 2^4 = 16);
	 *		158 -> 8 (as 2^7 = 128)
	 *
	 * @param type $num
	 * @return type
	 */
	private function getNextPowerOf2($num) {
		$log = log($num, 2);
		$y = floor($log);

		if ($y < $log) {
			return $y + 1;
		}
		else {
			return $y;
		}
	}
}
?>
