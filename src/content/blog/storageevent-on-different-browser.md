---
title: StorageEvent on different browser
date: 2017-09-15
description: How to listen to StorageEvent inside a sourceless iframe when IE and Edge don't call the listeners set inside it.
---

This week, I have been working lately with local and session storage on our web client. Our product, a interaction widget, needs to synchronise behaviour between different tabs. Nowadays, we do not have web sockets on place, so we can not sync several client instances through server and we discard use long-polling or comet. The main reason is try to earn requests to server and boost the performance.

Then, we started to experience with session and local storage. It is a great API indeed (Web Storage), and all browsers support it. Mainly is used as key/value store, and for us it give us a way to shared information between different tabs (localStorage).

But our purpose is spread some actions that happen on the current tab, so the others can react to it. In order to achieve we use the StorageEvent. It is fired every time a value is created/changed/removed form the storage. It is good to mention, if a value is set but it is equal to the previous one then no event is triggered.

```js
window.addEventListener('storage', function(event) {
    // do something with my new value (event.newValue) for the
    // key (e.key)
});
...
localStorage.setItem('key', 'value');
```

This is great but our business case demands all code running under an iframe, so we add some kind of isolation (a requirement by some customers). Here it is when the problem appeared. The iframe we use has not source, because we inject all assets after the load from the main page, and that case had a flaw in some browsers.

It reveals us some browsers (IE and Edge) do not call listeners set under this iframe, even if you change the value inside that scope. It could have some sense due to the iframe has no origin if we read the specification.

> A StorageEvent is sent to a window when a storage area it has access to is changed within the context of another document.

But it does not mean you can not capture the event, if you register the listener in top window, it is called when inside the iframe a value is changed or removed.

```js
window.top.addEventListener('storage', function(event) {
    // do something with my new value (event.newValue) for the
    // key (e.key)
});
```

I hope this discovery can be useful for someone.
