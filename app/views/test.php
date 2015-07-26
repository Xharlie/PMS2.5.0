<html>
<head>
    <title>test page for object fbcontrol</title>
</head>
<script type="text/javascript"; charset="utf-8">
    function plugin0()
    {
        return document.getElementById('plugin0');
    }
    plugin = plugin0;
    function addEvent(obj, name, func)
    {
        if (obj.attachEvent) {
            obj.attachEvent("on"+name, func);
        } else {
            obj.addEventListener(name, func, false);
        }
    }

    function load()
    {
        addEvent(plugin(), 'test', function(){
            alert("Received a test event from the plugin.")
        });
    }
    function pluginLoaded() {
        alert("Plugin loaded!");
    }
    function ReadCard()
    {
        try{
            str=plugin().readIDCard();
            alert(str);
        }
        catch(err)
        {
            alert(err)
        }
    }
    function pluginValid()
    {
        if(plugin().valid){
            alert(plugin().echo("This plugin seems to be working!"));
        } else {
            alert("Plugin is not working :(");
        }
    }
</script>
<body onload="load()">
<object id="plugin0" type="application/x-hdinterface" width="300" height="300">
    <param name="onload" value="pluginLoaded" />
</object><br />

<a href="#" onclick="javascript:pluginValid()">Click me!</a> <br />
<a href="#" onclick="javascript:ReadCard()">Read</a> <br />
</body>
</html>
