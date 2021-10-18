export default function (context) {
	
	if (!window.BX)
		return;

	var BX = window.BX,
		defaultShowWait = BX.showWait,
		defaultCloseWait = BX.closeWait,
		lastWait = [];

	BX.showWait = function(node, msg)
	{
		node = BX(node) || document.body || document.documentElement;

		if (BX.findParent(node, {className: 'rs-megamart'}))
		{
			var container_id = node.id || Math.random();
			var obMsg = node.bxmsg =  node;

			BX.addClass(node, 'overlay is-loading');

			lastWait[lastWait.length] = obMsg;
			return obMsg;
		}
		else
		{
			defaultShowWait(node, msg);
		}
	};
	
	BX.closeWait = function(node, obMsg)
	{
		node = BX(node) || document.body || document.documentElement;

		if (BX.findParent(node, {className: 'rs-megamart'}))
		{
			if(node && !obMsg)
				obMsg = node.bxmsg;
			if(node && !obMsg && BX.hasClass(node, 'bx-core-waitwindow'))
				obMsg = node;
			if(node && !obMsg)
				obMsg = BX('wait_' + node.id);
			if(!obMsg)
				obMsg = lastWait.pop();

			if (obMsg && obMsg.parentNode)
			{
				for (var i=0,len=lastWait.length;i<len;i++)
				{
					if (obMsg == lastWait[i])
					{
						lastWait = BX.util.deleteFromArray(lastWait, i);
						break;
					}
				}
			}

			BX.removeClass(obMsg, 'overlay is-loading');

			if (node) node.bxmsg = null;
		}
		else
		{
			defaultCloseWait(node, obMsg);
		}
	};
}
