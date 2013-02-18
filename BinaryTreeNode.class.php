<?php

/**
 * <b>BinaryTreeNode</b> stores the information for each node of the binary
 * tree used to build the packing of PowerOf2RectanglePacker
 *
 * @author Marco Amado <mjamado@dreamsincode.com>
 * @since 2013-01-17 [last review: 2013-01-17]
 * @license Creative Commons 2.5 Portugal BY-NC-SA
 * @link http://www.dreamsincode.com/
 */
class BinaryTreeNode {

	/**
	 * The children of the current node - max 2 children
	 *
	 * @var array
	 */
	private $children = array();
	/**
	 * The left position of the current node
	 *
	 * @var int
	 */
	private $left;
	/**
	 * The top position of the current node
	 *
	 * @var int
	 */
	private $top;
	/**
	 * The width of the current node
	 *
	 * @var int
	 */
	private $width;
	/**
	 * The height of the current node
	 *
	 * @var int
	 */
	private $height;
	/**
	 * Whether the node is already occupied
	 *
	 * @var int
	 */
	private $occupied = false;

	/**
	 * A simple ctor, nothing really special to see here
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $w
	 * @param int $h
	 */
	public function __construct($x, $y, $w, $h) {
		$this->top = $y;
		$this->left = $x;
		$this->width = $w;
		$this->height = $h;
	}

	/**
	 * Inserts the passed rectangle into the packing space, going down the tree
	 * if needed.
	 *
	 * @param int $rectWidth
	 * @param int $rectHeight
	 * @return null|\BinaryTreeNode
	 */
	public function insertRect($rectWidth, $rectHeight) {
		if (!empty($this->children)) {
			// this is not a leaf, so let's try to insert the rectangle further
			// down the tree - we'll try the first children first
			$newNode = $this->children[0]->insertRect($rectWidth, $rectHeight);
			if (is_null($newNode)) {
				// the first children turned us down, let's try the second one
				$newNode = $this->children[1]->insertRect($rectWidth, $rectHeight);
			}

			// the first or the second, we'll just return them
			return $newNode;
		}
		else {
			// is it occupied?
			if ($this->occupied) {
				return null;
			}

			// does it fit?
			if (($this->width < $rectWidth) || ($this->height < $rectHeight)) {
				return null;
			}
			elseif (($this->width == $rectWidth) && ($this->height == $rectHeight)) {
				// just look at that, we fit perfectly!
				$this->occupied = true;
				return $this;
			}
			else {
				// ok, there's more than enough room - let's split
				// first, let's calculate the remaining space
				$deltaWidth = $this->width - $rectWidth;
				$deltaHeight = $this->height - $rectHeight;

				if ($deltaWidth > $deltaHeight) {
					$this->children[0] = new BinaryTreeNode($this->left, $this->top, $rectWidth, $this->height);
					$this->children[1] = new BinaryTreeNode($this->left + $rectWidth, $this->top, $deltaWidth, $this->height);
				}
				else {
					$this->children[0] = new BinaryTreeNode($this->left, $this->top, $this->width, $rectHeight);
					$this->children[1] = new BinaryTreeNode($this->left, $this->top + $rectHeight, $this->width, $deltaHeight);
				}

				// we've splitted, and we're sure it fits on the first one
				return $this->children[0]->insertRect($rectWidth, $rectHeight);
			}
		}
	}

	/**
	 * A simple getter for left and top
	 *
	 * @param string $name
	 *
	 * @return int
	 */
	public function __get($name) {
		if ($name == 'left') {
			return $this->left;
		}
		elseif ($name == 'top') {
			return $this->top;
		}
	}
}

?>
