import init from './app/init'
import bxloader from './app/inits/bxloader';

import './global';
import './events';

// page on ready
function onReady()
{
	init();

	bxloader();
}

// composite data recieved
function onFrameDataReceived(json = {})
{
	if (!(json.dynamicBlocks || []).length)
	{
		return;
	}

	json.dynamicBlocks.forEach((block, index) =>
	{
		init([], document.querySelector(block.ID));
	})
}

$(window).ready(onReady);
if (window.frameCacheVars !== undefined)
{
	BX.addCustomEvent("onFrameDataReceived", (json) => onFrameDataReceived(json));
}
