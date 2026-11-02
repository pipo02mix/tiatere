---
title: Reporting javascript errors to your server
date: 2017-05-19
description: Two ways to send the JavaScript errors from your users' browsers to your own server, and how to get details for scripts from other domains.
---

When you have a web application there is always the desire to know everything that happens in the client side, specially if there are errors that could demean the user experience.

So ideally our solution is sent all errors produced in the browser to our servers. This is possible and there are some good tools that can help us a lot without code anything (like sentry.io), but if you want to have the control of your data then you need your own implementation.

There are like two main approaches to achieve it. First one is to use a global listener to register a callback and there send your error to your server. Second one is use *try/catch* mechanism to get the errors and send them to our machines.

#### Global event handler ‘onerror’

The easy way to catch all javascript errors(syntax errors and exceptions) is register a callback on the window object.

```js
window.onerror = function (message, file, lineNo, ColNo, error) {
    http.ajax({
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
           message: message,
           type: error && error.name ? error.name : '',
           file: file,
           stackTrace: error && error.stack ? error.stack : 'empty',
           url: window.location.href,
           timestamp: Math.ceil((new Date()).getTime()/1000),
           ua: navigator.userAgent,
           referrer: window.document.referrer,
           ln: lineNumber,
           lo: columnNo
        })
    });
}
```

So as the example illustrates the callback receive some arguments related with the error caught. We get the error message (or an event)firstly, the file name (URI), line number, column number and the error it self (where you can extract the stack is it exists).

The problem of this handler function, like always, is the browser compatibility. Although mostly all browser support it, the arguments passed are different. [In the specifications the only compulsory argument is the message but others are up to the vendor](https://html.spec.whatwg.org/multipage/webappapis.html#event-handler-attributes:handler-onerror).

#### What if I need to get all data cross browser

As second approach to get all error data is using *try/catch* statements, wrapping all your code blocks and redirecting the error information to your server.

This is hard and time consuming([although you use a wrapper to do the repetitive work](https://blog.sentry.io/2016/01/04/client-javascript-reporting-window-onerror.html#polyfilling-windowonerror-with-trycatch)) because you can not wrap all your code with one *try/catch*. Main reason is because this mechanism only catch the errors on the block defined [so callbacks would not work](https://jsfiddle.net/pipo02mix/rwyutdqw/).

#### Script with different domain than my website

When the source of your script is different than your website, then browser do not publish any information about the error. There is an error and it is captured by the listener but in the message just shows ‘Script error.’ and no line number or file name is exposed.

The reason is not let gather information about errors from third parties. This can be dodged thanks to CORS. If you add [cross origin attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/CORS_settings_attributes) on the script to load (with ‘anonymous’ or ‘use-credentials’) then your browser will send a preflight request to the server and if the server replies it with the correct CORS header (basically Access-Control-Allow-Origin: &lt;your-origin&gt;) then you can catch all the error information for that script.
