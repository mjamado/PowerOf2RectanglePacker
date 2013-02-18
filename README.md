# PowerOf2RectanglePacker #

## A rectangle packer with dimensions that are powers of 2 ##

This packer is useful, for example, in packing textures for use in OpenGL ES,
in which the textures must have power of 2 widths and heights.

### Author and License ###

![Marco Amado's Gravatar](http://1.gravatar.com/avatar/1a11649fa31edc86ddbfa4466ebf560b?s=40&d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D40&r=G)
Marco Amado [mjamado@dreamsincode.com]

Creative Commons 2.5 Portugal BY-NC-SA

### Usage ###

In `index.php` there's an example of usage. However, the steps are pretty
simple:

```php
// create an array of rectangles to pack - don't worry about the ordering,
// they'll be sorted by descending area inside the packer
$rectangles = array(
	array(
		'id' => 'this can be whatever you want - more about it below',
		'width' => 100 // an integer
		'height' => 100 // another integer
	),
	// as many rectangles as you like
);

// prepare the packer
$rp = new PowerOf2RectanglePacker($rectangles);

// pack it!
$packing = $rp->pack();
// $packing now has the same array as declared before, but with two additional
// properties per item: posX and posY, that indicates where did the rectangle
// got positioned

// if you need to know the packing dimensions - which you normally do - they're
// right here:
$dimensions = $rp->getPackingDimensions();

// do stuff with it - like actually creating a texture from images, for example
```

Ok, about that `id`: that property isn't used inside the packer. At all. Heck,
you can even rename it to something else. It's just there because you'll need
it further down the road when you get the packing from the packer.

That's it.