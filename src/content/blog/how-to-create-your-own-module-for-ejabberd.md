---
title: How to create your own module for ejabberd
date: 2017-08-20
description: Write a custom ejabberd module in Erlang that hooks into server events, and install it when it's too specific to share.
---

[Ejabberd](https://www.ejabberd.im/) is a chat server built upon [XMPP](https://xmpp.org/about/) standard. It is great, supports high availability as the same time is designed to hold huge amounts of traffic.

One of the coolest things about ejabberd is you can add modules to customize or extend its behaviour. Every piece of functionality is already a module on ejabberd so create a new one is not a big deal. The core is firing events when some actions are performed, like when an user send a stanza (user_send_packet hook) or an item is published on and *pubsub* channel (pubsub_publish_item hook). These hooks let us attach a listener and add some extra logic when it happens.

### Why I need to do it ?

In my case, the company which I work, needs to know when a user has sent a change in availability (status change on the user client). Previously, our user management system was using the rest module to query asking for all users connected and their resources updating the ones that have changed, but it was time consuming and that request was not returning all the information needed (show/status properties indeed).

So the next step was creating a erlang piece of code (module) that would be registered by ejabberd and attached to a [hook](https://www.ejabberd.im/Events%20and%20hooks) (user_send_packet and sm_remove_connection_hook). From now when a user is disconnected or a packet is send by him I can sniff inside and inform the user management component about the change produced.

```erlang
start(Host, _Opts) ->
    ?INFO_MSG("my custom module is starting", []),
    %% inet library is started to be able to do a POST request to the user management component
    inets:start(),
    %%Here is where hooks are attached to  a function for adding our logic
    ejabberd_hooks:add(user_send_packet, Host, ?MODULE, on_user_send_packet, 50),
    ejabberd_hooks:add(sm_remove_connection_hook, Host, ?MODULE, on_disconnect, 50),
    ?INFO_MSG("my module hooks attached", []),
    ok.

stop(Host) ->
    ?INFO_MSG("my module stopping", []),
    %%Unattached our functions when ejabberd stops
    ejabberd_hooks:delete(user_send_packet, Host, ?MODULE, on_user_send_packet, 50),
    ejabberd_hooks:delete(sm_remove_connection_hook, Host, ?MODULE, on_disconnect, 50),
    ok.
```

Now it is time to add our logic. In our case, our callback on_user_send_packet will listen only presence so thanks to pattern matching it easy to implement. In this example we only take action when the presence type is unavailable.

```erlang
on_user_send_packet(#xmlel{
                      name = <<"presence">>,
                      attrs = Attrs
                    } = Pkt,
                    _C2SState,
                    #jid{lresource = <<"">>} = From,
                    _To) ->
    Type = fxml:get_attr_s(<<"type">>, Attrs),
    if Type == <<"unavailable">> ->
      Jid = binary_to_list(jlib:jid_to_string(From)),
      BareJid = string:sub_string(Jid,1,string:str(Jid,"/")-1),
      send_availability(BareJid, "unavailable", "");
      true -> Pkt
    end,
    Pkt;
```

To see all cases that our callback listen you can check [this repository with all code for the module](https://github.com/pipo02mix/docker-ejabberd/blob/master/mod_cobrowser/src/mod_cobrowser.erl).

After explain what we can do, so there is some context around, lets go deep into the hole. Ejabberd offers third party module ([contrib moduiles](https://github.com/processone/ejabberd-contrib)) where you can find some modules made by external developers. The latest ejabberd versions have some useful commands that help you to install one of these modules.

> ejabberdctl modules_update_specs

to get a list of modules

> ejabberdctl module_install &lt;module&gt;

to install a specific module in your current ejabberd server.

Even it is worth to mention if you build you ejabberd server directly from source, you can set a environment variable to specify which module you want to load (EJABBERD_CONTRIB_MODULES=mod_rest).

But the problems comes when you have a custom module that is quite bound to your domain and you don’t want to share it. Then you need to install in a different way.

### Install and configure a custom module

First of all, you have to follow some module guidances in order to be able to compile and include the module in the ejabberd system. As an example, [our repo](https://github.com/pipo02mix/docker-ejabberd/tree/v16) has a module called “mod_cobrowser” with all structure ready to be accepted by ejabberd (other case the compile or start processes will not work).

In order to compile and attach the module, ejabberd looks in a path (can be configurable by environment variable) and compile all modules found there and extends the configuration taking the new properties from the module config file.
