export const MIN_HEIGHT = 500;
export const MAX_HEIGHT = 900;

export default function (options) {
	if (!options.dragArea || !options.container) {
		return;
	}

	const dragArea = options.dragArea;
	const container = options.container;

	const minHeight = options.minHeight || MIN_HEIGHT;
	const maxHeight = options.maxHeight || MAX_HEIGHT;

	const onResize = options.onResize || function () {};

	var isResizing = false;
	var clicked = false;

	dragArea.addEventListener('mousedown', (e) => {
		isResizing = true;

		clicked = {
			height: container.clientHeight,
			clientY: e.clientY
		}

		const onMouseMove = (e) => {
			if (!isResizing) {
				return;
			}

			let currentHeight = Math.max(clicked.clientY - e.clientY + clicked.height, minHeight);
			if (currentHeight > minHeight && currentHeight < maxHeight) {
				onResize(currentHeight);
			}
		}

		const onMouseUp = (e) => {
			isResizing = false;
			clicked = false;

			document.removeEventListener('mousemove', onMouseMove);
			document.removeEventListener('mouseup', onMouseUp);
		}

		document.addEventListener('mousemove', onMouseMove);
		document.addEventListener('mouseup', onMouseUp);
	});

	dragArea.addEventListener('touchstart', (e) => {
		isResizing = true;

		e.stopPropagation();
		e.preventDefault();

		clicked = {
			height: container.clientHeight,
			clientY: e.touches[0].clientY
		}

		const onMouseMove = (e) => {
			if (!isResizing) {
				return;
			}

			let currentHeight = Math.max(clicked.clientY - e.touches[0].clientY + clicked.height, minHeight);
			if (currentHeight > minHeight && currentHeight < maxHeight) {
				onResize(currentHeight);
			}
		}

		const onMouseUp = (e) => {
			isResizing = false;
			clicked = false;

			document.removeEventListener('touchmove', onMouseMove);
			document.removeEventListener('touchend', onMouseUp);
		}

		document.addEventListener('touchmove', onMouseMove);
		document.addEventListener('touchend', onMouseUp);
	});
}
