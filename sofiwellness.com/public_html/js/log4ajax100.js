
/**
 * Provide client side logging capabilities to AJAX applications.
 *
 * @author <a href="mailto:thespiegs@users.sourceforge.net">Eric Spiegelberg</a>
 * @see <a href="http://sourceforge.net/projects/log4ajax">Log4Ajax</a>
 */
 
function LOG()
{
  throw "Do not instantiate LOG";
}

LOG.transmitToServer = true;
LOG.consoleDivId = "logConsole";

LOG.debug = function(msg)
{
	LOG._log(msg, "debug");
}

LOG.info = function(msg)
{
	LOG._log(msg, "info");
}

LOG.warn = function(msg)
{
	LOG._log(msg, "warn");
}

LOG.error = function(msg)
{
	LOG._log(msg, "error");
}

LOG.fatal = function(msg)
{
	LOG._log(msg, "fatal");
}

LOG._log = function(msg, logLevel)
{
	LOG._logToConsole(msg, logLevel);

	if (LOG.transmitToServer)
	{
		LOG._logToServer(msg, logLevel);
	}
}

LOG._logToConsole = function(msg, logLevel)
{
	var consoleDiv = document.getElementById(LOG.consoleDivId);
	if (consoleDiv)
	{
		consoleDiv.innerHTML = "<span class='log_" + logLevel + "'>" + 
							   logLevel + "</span>: " + msg + "<br/>" + 
							   consoleDiv.innerHTML;
	}
	else
	{
		// If the consoleDiv is not available, you could create a 
		// new div or open a new window.
	}
}

LOG._logToServer = function(msg, logLevel)
{
	var data = logLevel.substring(0, 1) + msg;

	// Use request.js to make an AJAX transmission to the server
	Http.get({
		url: "/util/ajaxLogging",
		method: "POST",
		body: data,
		callback: LOG._requestCallBack
	});
}

LOG._requestCallBack = function()
{
	// Handle callback functionality here; if appropriate
}