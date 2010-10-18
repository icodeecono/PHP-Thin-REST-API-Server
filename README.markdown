PHP Thin REST API Server
------------------------

The purpose of this project is to create an easy to use PHP shell for serving out RESTful web services.

The service works by responding to the following standard URI formats:

  - **(HTTP GET request) https://api.mysite.com/v1_0/myThing** - produces a listing of myThings, SHOULD respond with a "200 OK" status and a body of the collection of objects.
  - **(HTTP GET request)  https://api.mysite.com/v1_0/myThing/345** - returns a myThing object with ID of 345, SHOULD respond with a "200 OK" status and a body with the object.
  - **(HTTP POST request) https://api.mysite.com/v1_0/myThing** - creates a new myThing object, SHOULD respond with a "200 OK" status and a body of the new object.
  - **(HTTP PUT request) https://api.mysite.com/v1_0/myThing/345** - updates myThing object with ID of 345, SHOULD respond with a "200 OK" status and a body of the updated object.
  - **(HTTP DELETE request) https://api.mysite.com/v1_0/myThing/345** - deletes myThing object with ID of 245, SHOULD respond with a "204 No Content" status and SHOULD NOT return a body.

The "**/v1_0/**" part of the URL is the version of the API.  This is required and is used to future proof changes.

All the above suggestions are based off of accepted standards, however, with this library you have the ability to create your API as you wish.

To Use This Library
-------------------

**The htdocs folder** represents your webroot, so you need to setup your web server to point the https://api.mysite.com domin to the htdocs folder.  FYI... You may need to fiddle with the .htaccess file "RewriteBase /" command if things are not working correctly.

Next, you need to create your application wrapper classes to handle the requests.  This is done by extending the "**Com_Icodeecono_Api_ApiRoutableClassBase**" class found in the system folder.  If you take a look at that class, then you will see what methods are available to be overridden.  The methods map out to the URI formats listed above.  For instance, you might have the **MyThingAPIHandler** class extending **Com_Icodeecono_Api_ApiRoutableClassBase** which then overrides most, if not all the base class methods.

Your application class wrapper (e.g. MyThingAPIHandler) should do 2 things: 1) return (or not) the responders raw return data and 2) set the response status.  This is done by using the following functions:

  - $responder->setResponseStatus([Com_Icodeecono_Api_ApiResponseStatuses::STATUS_XXX])
  - $responder->setRawReturnData([PHP array of data to return]);

Once you have created your application wrapper classes, the last thing to do is connect it to the routing functions via the /application/routes.php file.

To do this, you must "require_once" your application wrapper class in the routes.php file.  Then you need to add $responder->addRoute("/myThing","MyThingAPIHandler"); line to the routes.php file.  This maps out anything /myThing to be directed to your MyThingAPIHandler class as laid out in the above URI format examples.

More Complex Routing
--------------------

For more complex routing, you can add routes in the following format...

$responder->addRoute("/myThing/:mything_id/otherThing","MyThingAPIHandler");

or even...

$responder->addRoute("/myThing/:mything_id/otherThing/:otherthing_id/thirdThing","MyThingAPIHandler");

everything in the path that starts with a ":" can be interpreted as an ID value.  Therefore, if the route matches the whole URI, then the number inserted for :mything_id and :otherthing_id will be made available to the MyThingAPIHandler methods via the "$passedParams" variable as indexed keys.  

So, with the above example, the following URI would match that route:

https://api.mysite.com/v1_0/myThing/897/otherThing/334/thirdThing

**NOTE:** There is a reserved keyword on ":id" because this is populated automatically to handle the GET, PUT, DELETE requests outlined above. (If in doubt, just dump out the $passedParams var to see what's going on in your class methods).

Response Formats
----------------

The library will try to automatically respond to some common formats such as JSON, XML, CSV and HTML.  The default format is JSON.  If you want to get one of the other formats in response, then you must add a "." and the format at the end of the request URI.  For example, https://api.mysite.com/v1_0/myThing/345.xml will return the myThing object with an ID of 345 in an XML format.  Other valid extensions are ".json", ".xml", ".csv" and ".html".   The HTML format is useful when you are debugging the web service.

Debugging
---------

One good way to debug your API is to use something like the Firebug extension for Firefox.  This way you can use the "Net" tab to see the incoming and outgoing HTTP requests and their corresponding status codes.

API Rest Client
---------------

There are a number of PHP API clients out there, but if you want to see an example of how do do a raw request using CURL and PHP, then check out the tests directory.  In fact, if you get stuck there might be something in the tests directory that might help (even though the tests are poorly written).

Thanks, please contribute...
----------------------------

Please let me know what you think.  If you have any improvements, go ahead and make them.  I welcome your suggestions, etc.  To contact me, please email me at icodeecono [at] bluecliff.net or @icodeecono.

----------------------------