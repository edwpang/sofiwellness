/* for jquery ajax 
*  
*   form - the form, note it uses form.action as url
*   event - the event in action bean, such as ajax  (in actinbean, public Resolution ajax())
*   container - the id of element where the update take place, e.g. <div id="replaceWithAjax"> 
*   e.g.
	$(function() {
		$('input[type=text]').keyup(function() {
		invoke($('form')[0], 'ajax', '#replaceWithAjax');
		});
	});

	the HTML:
	<stripes:form beanclass="com.example.web.actionbean.TestActionBean">
		<stripes:text name="text"/>

		<div id="replaceWithAjax">The text you type will replace this text.</div>

		<stripes:submit name="submit"/>
	</stripes:form>
*/

function invoke(form, event, container) 
{
	params = {};
	if (event != null) params = event + '&' + $(form).serialize();            
	
	$.post(form.action,
			params,
			function (xml) {
				$(container).html(xml);
			});
}


