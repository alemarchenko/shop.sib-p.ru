export default function (src) {
	var image = new Image();
	image.src = src;

	return image.complete;
}
